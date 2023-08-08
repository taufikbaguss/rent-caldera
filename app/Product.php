<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillabe = ['slug','photo','name','description','stock','price','category_id','user_id','weight','deposit_harga'];

    function user(){
    	return $this->belongsTo('App\User');
    }
}
