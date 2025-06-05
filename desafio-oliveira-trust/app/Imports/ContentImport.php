<?php
namespace App\Imports;

use App\Models\Content;
use Maatwebsite\Excel\Concerns\ToModel;

class ContentImport implements ToModel
{

    private int $uploadId;

    public function __construct(int $uploadId)
    {
        $this->uploadId = $uploadId;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Content([
            'upload_id'     => $this->uploadId,
            'RptDt'         => $row[0] ?? null,
            'TckrSymb'      => $row[1] ?? null,
            'MktNm'         => $row[2] ?? null,
            'SctyCtgyNm'    => $row[3] ?? null,
            'ISIN'          => $row[4] ?? null,
            'CrpnNm'        => $row[5] ?? null,
        ]);
    }
}
