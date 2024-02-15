<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kabupaten extends Model
{
    use HasFactory;
    protected $table = 'kabupaten';

    protected $guarded = [];

    public function prov()
    {
        return $this->belongsTo(Provinsi::class, 'provinsi_id', 'id');
    }
}
