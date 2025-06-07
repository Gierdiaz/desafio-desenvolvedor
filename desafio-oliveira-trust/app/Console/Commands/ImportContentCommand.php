<?php

namespace App\Console\Commands;

use App\Imports\ContentImport;
use App\Models\Upload;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;

class ImportContentCommand extends Command
{
    protected $signature = 'content:import {filename}';

    protected $description = 'Importa dados do CSV para a tabela contents';

    public function handle(): void
    {
        $filename = $this->argument('filename');
        $path     = storage_path("app/{$filename}");

        if (!File::exists($path)) {
            $this->error("Arquivo CSV não encontrado: {$path}");

            return;
        }

        ini_set('memory_limit', '2G');
        ini_set('max_execution_time', 0);

        $this->info("Iniciando importação do arquivo: {$filename}");

        try {
            $upload = Upload::firstOrCreate(['file' => basename($filename)]);

            Excel::import(new ContentImport($upload->id), $path);

            $this->info('Importação concluída com sucesso!');
        } catch (\Exception $e) {
            $this->error('Erro durante a importação: ' . $e->getMessage());
        }
    }
}
