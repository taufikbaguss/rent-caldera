<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductsLamaPinjam extends Model
{
    protected $table = 'products_lama_pinjam';
    protected $fillable = ['product_id', 'lama_pinjam_id', 'harga'];
    public $timestamps = FALSE;
}
