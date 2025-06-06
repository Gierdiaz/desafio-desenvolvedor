<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class ContentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = [
            'RptDt'      => $this->RptDt,
            'TckrSymb'   => $this->TckrSymb,
            'MktNm'      => $this->MktNm,
            'SctyCtgyNm' => $this->SctyCtgyNm,
            'ISIN'       => $this->ISIN,
            'CrpnNm'     => $this->CrpnNm,
        ];

        $hasFilters = $request->filled('TckrSymb') || $request->filled('RptDt');

        // Só adiciona HATEOAS se não houver filtros
        if (!$hasFilters) {
            $data['_links'] = [
                'self' => url("/api/contents?TckrSymb={$this->TckrSymb}&RptDt=" . Str::substr($this->RptDt, 0, 10)),
            ];
        }

        return $data;
    }
}
