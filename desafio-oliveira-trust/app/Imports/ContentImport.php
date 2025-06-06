<?php

namespace App\Imports;

use App\Models\Content;
use Illuminate\Support\Collection;
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
        foreach ($rows as $row) {

            Content::create([
                'upload_id'  => $this->uploadId,
                'RptDt'      => $row['RptDt'],
                'TckrSymb'   => $row['TckrSymb'],
                'MktNm'      => $row['MktNm'],
                'SctyCtgyNm' => $row['SctyCtgyNm'],
                'ISIN'       => $row['ISIN'],
                'CrpnNm'     => $row['CrpnNm'],
            ]);
        }
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
