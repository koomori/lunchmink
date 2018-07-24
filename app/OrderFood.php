<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderFood extends Model
{
    //
  public function order()
  {
    return $this->belongsTo(Order::Class,'cart_order_id','cart_order_id');
  }
}
