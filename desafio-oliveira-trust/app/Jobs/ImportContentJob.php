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

    public $upload;

    public function __construct(Upload $upload)
    {
        $this->upload = $upload;
    }

    public function handle()
    {
        $this->upload->update(['status' => 'processing']);

        $filePath = storage_path('app/public/' . $this->upload->file_path);

        if (!file_exists($filePath)) {
            $this->upload->update(['status' => 'error']);

            return;
        }

        $lines    = fopen($filePath, 'r');
        $tempPath = $filePath . '.tmp';
        $temp     = fopen($tempPath, 'w');

        $firstLine = fgets($lines);

        if (!str_starts_with(trim($firstLine), 'Status do Arquivo')) {
            fwrite($temp, $firstLine);
        }

        while (($line = fgets($lines)) !== false) {
            fwrite($temp, $line);
        }

        fclose($lines);
        fclose($temp);
        rename($tempPath, $filePath);

        Excel::import(new ContentImport($this->upload->id), $filePath);

        $this->upload->update(['status' => 'completed']);
    }
}
