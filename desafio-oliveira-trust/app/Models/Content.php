<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Content extends Model
{
    protected $table = 'contents';

    protected $fillable = [
        'upload_id',
        'RptDt',
        'TckrSymb',
        'MktNm',
        'SctyCtgyNm',
        'ISIN',
        'CrpnNm',
    ];

    protected $casts = [
        'RptDt' => 'datetime',
    ];

    public function upload(): BelongsTo
    {
        return $this->belongsTo(Upload::class);
    }
}
