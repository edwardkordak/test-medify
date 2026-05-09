<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'category_id',
        'foto',
        'harga_beli',
        'laba',
        'supplier',
        'jenis',
    ];

    public function category()
    {
        return $this->belongsTo(
            Category::class,
            'category_id'
        );
    }

    use SoftDeletes;
}
