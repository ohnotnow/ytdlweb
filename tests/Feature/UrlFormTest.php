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
        $form = Livewire::test(UrlForm::class);
        $form->url = 'http://www.example.com';
        $form->extractAudio = true;

        $form->queueDownload();

        $this->assertNull($form->url);
        $this->assertFalse($form->extractAudio);
        Bus::assertDispatched(DownloadFile::class);
    }
}
