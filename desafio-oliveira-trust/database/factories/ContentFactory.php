<?php

namespace Database\Factories;

use App\Models\Content;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Content>
 */
class ContentFactory extends Factory
{
    protected $model = Content::class;

    public function definition(): array
    {
        return [
            'upload_id'  => \App\Models\Upload::factory(),
            'RptDt'      => now()->toDateString(),
            'TckrSymb'   => 'AAPL',
            'MktNm'      => 'NASDAQ',
            'SctyCtgyNm' => 'Stock',
            'ISIN'       => 'US0378331005',
            'CrpnNm'     => 'Apple Inc.',
        ];
    }
}
