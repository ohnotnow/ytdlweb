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
        $downloader = $this->createDownloader();

        $dbFile = $this->createModel();

        $downloader->onProgress(function ($progress) use ($dbFile) {
            $dbFile->updateProgress($progress);
        });

        try {
            $video = $downloader->download($dbFile->url);
            $dbFile->markAsComplete($video, $this->shouldExtractAudio);
        } catch (NotFoundException $e) {
            $dbFile->markAsGubbed('Video not found (404)');
        } catch (PrivateVideoException $e) {
            $dbFile->markAsGubbed('Video is marked as private');
        } catch (CopyrightException $e) {
            $dbFile->markAsGubbed('Video removed for copyright reasons');
        } catch (\Exception $e) {
            $dbFile->markAsGubbed($e->getMessage());
        }
    }

    protected function getOptions()
    {
        if ($this->shouldExtractAudio) {
            return [
                'extract-audio' => true,
                'audio-format' => 'mp3',
                'audio-quality' => 0, // best
                'output' => '%(title)s.%(ext)s',
            ];
        }

        return [
            'continue' => true,
            'format' => 'bestvideo',
        ];
    }

    protected function createModel()
    {
        return DownloadedFile::create([
            'url' => $this->url,
        ]);
    }

    protected function createDownloader()
    {
        $downloader = new YoutubeDl($this->getOptions());
        $downloadDir = storage_path('app/downloads');
        app(Filesystem::class)->ensureDirectoryExists($downloadDir, $mode = 0755, $recursive = true);
        $downloader->setDownloadPath($downloadDir);
        $downloader->setBinPath('/usr/local/bin/youtube-dl');
        if (config('app.debug')) {
            $downloader->debug(function ($type, $buffer) {
                if (\Symfony\Component\Process\Process::ERR === $type) {
                    info('ERR > ' . $buffer);
                } else {
                    info('OUT > ' . $buffer);
                }
            });
        }
        return $downloader;
    }
}
