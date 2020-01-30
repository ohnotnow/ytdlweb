<?php

namespace App\Http\Livewire;

use App\Jobs\DownloadFile;
use Livewire\Component;

class UrlForm extends Component
{
    public $url;
    public $extractAudio = false;

    public function render()
    {
        return view('livewire.url-form');
    }

    public function queueDownload()
    {
        $this->validate([
            'url' => 'required|url',
        ]);

        DownloadFile::dispatch($this->url, $this->extractAudio);

        $this->url = null;
        $this->extractAudio = false;
    }
}
