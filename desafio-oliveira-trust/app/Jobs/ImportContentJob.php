<?php

namespace App\Jobs;

use App\Imports\ContentImport;
use App\Models\Upload;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Maatwebsite\Excel\Facades\Excel;

class ImportContentJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $upload;

    public function __construct(Upload $upload)
    {
        $this->upload = $upload;
    }

    public function handle()
    {
        $this->upload->update(['status' => 'processing']);

        $filePath = storage_path('app/public/' . $this->upload->file_path);

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if (isset($lines[0]) && str_starts_with($lines[0], 'Status do Arquivo')) {
            unset($lines[0]);
            $cleanedFile = implode("\n", $lines);

            file_put_contents($filePath, $cleanedFile);
        }
        Excel::import(new ContentImport($this->upload->id), $filePath);

        $this->upload->update(['status' => 'completed']);
    }

}
