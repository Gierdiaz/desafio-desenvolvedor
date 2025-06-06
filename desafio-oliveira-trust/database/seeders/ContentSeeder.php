<?php

namespace Database\Seeders;

use App\Imports\ContentImport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;

class ContentSeeder extends Seeder
{
    public function run(): void
    {

        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '2G');

        $file = storage_path('app/InstrumentsConsolidatedFile_20250605_1.csv');

        if (!File::exists($file)) {
            $this->command->error('Arquivo CSV não encontrado: ' . $file);

            return;
        }

        try {
            $uploadId = \App\Models\Upload::firstOrCreate(
                ['file_name' => basename($file)]
            );

            Excel::import(new ContentImport($uploadId->id), $file);

            $this->command->info('CSV importado com sucesso usando ContentImport!');
        } catch (\Exception $e) {
            $this->command->error('Erro ao importar: ' . $e->getMessage());
        }
    }
}
