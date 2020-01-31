<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\UpdateYoutubeDlJob;

class UpdateYoutubeDl extends Command
{
    protected $signature = 'ytdl:update-youtube-dl';

    protected $description = 'Queue update youtube-dl to the latest version';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        UpdateYoutubeDlJob::dispatch();
    }
}
