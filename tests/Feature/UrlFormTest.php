<?php

namespace Tests\Feature;

use App\Http\Livewire\UrlForm;
use App\Jobs\DownloadFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Bus;
use Livewire\Livewire;
use Tests\TestCase;

class UrlFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function when_the_form_is_submitted_we_dispatch_an_event_and_clear_the_existing_form_data()
    {
        Bus::fake();
        Livewire::test(UrlForm::class)
            ->set('url', 'http://www.example.com')
            ->set('extractAudio', true)
            ->call('queueDownload')
            ->assertSet('url', null)
            ->assertSet('extractAudio', false);

        Bus::assertDispatched(DownloadFile::class);
    }
}
