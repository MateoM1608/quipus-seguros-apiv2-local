<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;

// Controllers
use App\Http\Controllers\DataUpload\FileUploadManagerController;

class FileUploadManagerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $connetion;
    public $id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($connetion, $id)
    {
        $this->connection = $connetion;
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $request = new Request([
            'connection' => $this->connection,
            'id' => $this->id
        ]);

        $uploadManager = new FileUploadManagerController;
        $uploadManager->manager($request);
    }
}
