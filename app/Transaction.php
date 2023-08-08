<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
   protected $fillabe = ['code','name','file','lama_pinjam_id','address','portal_code','ekspedisi','user_id','product_id'];

   function user(){
   	return $this->belongsTo('App\User');
   }

   function product(){
   	return $this->belongsTo('App\Product');
   }

   function pinjam(){
        return $this->belongsTo('App\Lama_pinjam', 'lama_pinjam_id');
    }

   protected $casts = [
   		'ekspedisi' => 'array',
   	];
}
