<?php

namespace App\Imports;

use App\Models\Content;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Concerns\{ToCollection, WithChunkReading, WithCustomCsvSettings, WithHeadingRow};
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

// Serve para evitar que o Laravel Excel altere o cabeçalho quando eu uso a interface WithHeadingRow
HeadingRowFormatter::default('none');

class ContentImport implements ToCollection, WithChunkReading, WithCustomCsvSettings, WithHeadingRow
{
    private $uploadId;

    public function __construct($uploadId)
    {
        $this->uploadId = $uploadId;
    }

    public function collection(Collection $rows)
    {
        $data = [];

        foreach ($rows as $row) {
            if (
                isset($row['RptDt']) &&
                isset($row['TckrSymb']) &&
                isset($row['MktNm']) &&
                isset($row['SctyCtgyNm']) &&
                isset($row['ISIN']) &&
                isset($row['CrpnNm'])
            ) {
                $data[] = [
                    'upload_id'  => $this->uploadId,
                    'RptDt'      => $row['RptDt'] ?? null,
                    'TckrSymb'   => $row['TckrSymb'],
                    'MktNm'      => $row['MktNm'],
                    'SctyCtgyNm' => $row['SctyCtgyNm'],
                    'ISIN'       => $row['ISIN'],
                    'CrpnNm'     => $row['CrpnNm'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        Content::insert($data);

        Cache::flush();
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ';',
        ];
    }
}
