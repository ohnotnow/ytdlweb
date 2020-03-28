<?php

namespace Tests\Feature;

use App\DownloadedFile;
use App\Http\Livewire\FileList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class FileListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function we_get_the_latest_downloaded_files()
    {
        $files = factory(DownloadedFile::class, 3)->create();
        $fileList = Livewire::test(FileList::class);

        $this->assertEquals(3, count($fileList->files['id']));
        $this->assertEquals(DownloadedFile::class, $fileList->files['class']);
    }

    /** @test */
    public function we_can_remove_a_downloaded_file()
    {
        File::put('/tmp/test.txt', '');
        $file = factory(DownloadedFile::class)->create(['filename' => '/tmp/test.txt']);
        Livewire::test(FileList::class)
            ->call('removeFile', $file->id);

        $this->assertFalse(file_exists('/tmp/test.txt'));
        $this->assertDatabaseMissing('downloaded_files', ['id' => $file->id]);
    }
}
