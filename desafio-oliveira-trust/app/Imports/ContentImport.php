<?php

namespace App\Imports;

use App\Models\Content;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ContentImport implements ToCollection, WithChunkReading, WithHeadingRow, WithCustomCsvSettings
{
    private int $uploadId;
    public int $rows = 0;

    public function __construct(int $uploadId)
    {
        $this->uploadId = $uploadId;
    }

    public function collection(Collection $rows)
    {
        $data = [];

        foreach ($rows as $row) {
            $this->rows++;

            $data[] = [
                'upload_id'    => $this->uploadId,
                'RptDt'        => $row['RptDt'] ?? null,
                'TckrSymb'     => $row['TckrSymb'] ?? null,
                'MktNm'        => $row['MktNm'] ?? null,
                'SctyCtgyNm'   => $row['SctyCtgyNm'] ?? null,
                'ISIN'         => $row['ISIN'] ?? null,
                'CrpnNm'       => $row['CrpnNm'] ?? null,
                'created_at'   => now(),
                'updated_at'   => now(),
            ];
        }

        Content::insert($data);
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ';',
            'enclosure' => '"',
            'escape_character' => '\\',
            'input_encoding' => 'UTF-8',
        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
