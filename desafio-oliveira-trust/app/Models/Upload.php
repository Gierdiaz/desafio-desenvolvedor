<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Upload extends Model
{
    protected $table = 'uploads';

    protected $fillable = [
        'file_name',
        'file_path',
        'reference_date',
    ];

    protected $casts = [
        'reference_date' => 'datetime'
    ];

    public function contents(): HasMany
    {
        return $this->hasMany(Content::class);
    }
}
