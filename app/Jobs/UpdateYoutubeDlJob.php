<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class UpdateYoutubeDlJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
		// put another copy of this on the queue first so that if the process fails it will
		// still be tried again later
		UpdateYoutubeDlJob::dispatch()->delay(now()->addWeek());

		// and now try and update youtube-dl
    	$process = new Process('/usr/local/bin/youtube-dl -U');
		$process->run();
		if (!$process->isSuccessful()) {
		    throw new ProcessFailedException($process);
		}
		\Log::info($process->getOutput());
    }
}
