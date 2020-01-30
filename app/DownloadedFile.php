<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DownloadedFile extends Model
{
    protected $guarded = [];

    public function getDownloadLink()
    {
        return asset('downloads/' . basename($this->filename));
    }
}
