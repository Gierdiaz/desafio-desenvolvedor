<?php

namespace App\Imports;

use App\Models\Content;
use Maatwebsite\Excel\Concerns\{OnEachRow, WithChunkReading, WithCustomCsvSettings, WithHeadingRow};
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Row;

HeadingRowFormatter::default('none');

class ContentImport implements OnEachRow, WithChunkReading, WithCustomCsvSettings, WithHeadingRow
{
    private $uploadId;

    public function __construct($uploadId)
    {
        $this->uploadId = $uploadId;
    }

    public function onRow(Row $row)
    {
        $data = $row->toArray();

        if (
            isset($data['RptDt']) &&
            isset($data['TckrSymb']) &&
            isset($data['MktNm']) &&
            isset($data['SctyCtgyNm']) &&
            isset($data['ISIN']) &&
            isset($data['CrpnNm'])
        ) {
            Content::create([
                'upload_id'  => $this->uploadId,
                'RptDt'      => $data['RptDt'] ?? null,
                'TckrSymb'   => $data['TckrSymb'],
                'MktNm'      => $data['MktNm'],
                'SctyCtgyNm' => $data['SctyCtgyNm'],
                'ISIN'       => $data['ISIN'],
                'CrpnNm'     => $data['CrpnNm'],
                'created_at' => now(),
                'updated_at' => now(),
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
