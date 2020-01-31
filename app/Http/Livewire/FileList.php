<?php

namespace App\Http\Livewire;

use App\DownloadedFile;
use Livewire\Component;

class FileList extends Component
{
    public $files;

    public function render()
    {
        $this->files = DownloadedFile::latest()->get();
        return view('livewire.file-list');
    }

    public function removeFile($fileId)
    {
        $file = DownloadedFile::findOrFail($fileId);
        $file->removeFromDisk();
        $file->delete();
    }
}
