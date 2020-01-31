<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class DownloadedFile extends Model
{
    protected $guarded = [];

    public function getDownloadLink()
    {
        return asset('downloads/' . basename($this->filename));
    }

    public function removeFromDisk()
    {
        File::delete($this->filename);
    }

    public function markAsGubbed(string $message)
    {
        $this->update([
            'is_gubbed' => true,
            'error_message' => $message
        ]);
    }

    public function markAsComplete(\YoutubeDl\Entity\Video $video, bool $audioExtracted)
    {
        $this->update([
            'is_complete' => true,
            'title' => $video->getTitle() . ($audioExtracted ? ' (MP3)' : ''),
            'filename' => $video->getFile()->getPathname(),
        ]);
    }

    public function updateProgress(array $progress)
    {
        $this->update([
            'percent' => $progress['percentage'],
            'size' => $progress['size'],
            'speed' => $progress['speed'] ?? null,
            'eta' => $progress['eta'] ?? null,
        ]);
    }
}
