<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\User;

class Order extends Model
{
    //
  public function orderfoods()
  {
    return $this->hasMany(OrderFood::class, 'food_id','cart_order_id');
  }

  public function userPickupOrders()
  {
  	$user = Auth::user();
  	$orders = self::where
  	('user_id','=', $user->id)
  	->whereNotNull('pickup_time')
  	->get();


  }

}
