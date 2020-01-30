<?php

namespace App\Jobs;

use App\DownloadedFile;
use YoutubeDl\YoutubeDl;
use Illuminate\Bus\Queueable;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use YoutubeDl\Exception\NotFoundException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use YoutubeDl\Exception\CopyrightException;
use YoutubeDl\Exception\PrivateVideoException;

class DownloadFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $url;
    public $shouldExtractAudio;

    public function __construct(string $url, bool $shouldExtractAudio = false)
    {
        $this->url = $url;
        $this->shouldExtractAudio = $shouldExtractAudio;
    }

    public function handle()
    {
        $options = [
            'continue' => true,
            'format' => 'bestvideo',
        ];
        if ($this->shouldExtractAudio) {
            $options = [
                'extract-audio' => true,
                'audio-format' => 'mp3',
                'audio-quality' => 0, // best
                'output' => '%(title)s.%(ext)s',
            ];
        }

        $dl = new YoutubeDl($options);
        $downloadDir = storage_path('app/downloads');
        app(Filesystem::class)->ensureDirectoryExists($downloadDir, $mode = 0755, $recursive = true);
        $dl->setDownloadPath($downloadDir);
        $dl->setBinPath('/usr/local/bin/youtube-dl');

        $dbFile = DownloadedFile::create([
            'url' => $this->url,
        ]);

        $dl->onProgress(function ($progress) use ($dbFile) {
            $dbFile->update([
                'percent' => $progress['percentage'],
                'size' => $progress['size'],
                'speed' => $progress['speed'] ?? null,
                'eta' => $progress['eta'] ?? null,
            ]);
        });

        try {
            $video = $dl->download($dbFile->url);
            $dbFile->update([
                'is_complete' => true,
                'title' => $video->getTitle(),
                'filename' => $video->getFile()->getPathname(),
            ]);
        } catch (NotFoundException $e) {
            $dbFile->update([
                'is_gubbed' => true,
                'error_message' => 'Video not found (404)'
            ]);
        } catch (PrivateVideoException $e) {
            $dbFile->update([
                'is_gubbed' => true,
                'error_message' => 'Video is marked as private'
            ]);
        } catch (CopyrightException $e) {
            $dbFile->update([
                'is_gubbed' => true,
                'error_message' => 'Video removed for copyright reasons'
            ]);
        } catch (\Exception $e) {
            $dbFile->update([
                'is_gubbed' => true,
                'error_message' => $e->getMessage(),
            ]);
        }
    }
}
