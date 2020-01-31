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
}
