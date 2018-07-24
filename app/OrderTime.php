<?php

namespace App;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Carbon\Carbon;
class OrderTime extends Model
{
    //
  protected static $firstOrderTime = '9:00 AM';
  protected static $lastOrderTime = '9:30 PM';

   public function cartOrder()
  {
    $this->belongsTo(CartOrder::Class);
  }

  public static function getLastOrderTime() {
    return self::$lastOrderTime;
  }

   /* check if the store is closed or there is not enough time to take orders */
  public static function checkIfStoreIsOpenForOrders()
  {

        $currentTime = new Carbon(); 
        $lastOrderTimeFullFormat = Carbon::parse(self::$lastOrderTime);

         /* check if there is a more than a 1/2 an hour 
        before closing time to place an order */
        //$checkEnoughTime = Carbon::parse('12:02');
        $checkEnoughTime = $currentTime->addMinutes(30);
        if($checkEnoughTime->gt($lastOrderTimeFullFormat))
        {
            session()->put('closed', TRUE);
           
        } else {
            session()->put('closed', FALSE);
        } 

        //dd(session()->all());
  }

/* returns an array of all possible order times */

  public static function getDailyOrderTimes()
  {

    /* Set Pickup and Delivery Orders to have
    15 minute time distance between ordering group times */ 
    
      $orderTime = Carbon::parse(self::$firstOrderTime);
      $orderTimeArray = array();

      do {
          $orderOnlyTime  = $orderTime->format('g:i A');
          $orderTimeArray[] = $orderOnlyTime;
          $orderTime->addMinutes(15);

      } while($orderOnlyTime != self::$lastOrderTime );

      return $orderTimeArray;
  }

  public static function deleteOrderTime($orderId)
  {
    $time = self::where('cart_order_id','=',$orderId)->get();
    if(!$time->isEmpty())
    {
        self::where('cart_order_id','=',$orderId)->delete();
    }
  }

  public static function availableOrderTimes($method, $orderId, Request $request)
  {
    /* first remove any expired times from the session */  
    if ($request->session()->exists('availableOrderTimes')) {
      //session()->pull('availableOrderTimes');
      $request->session()->forget('availableOrderTimes');
      $request->session()->save();
    }

    $cartOrder = CartOrder::find($orderId);

    /**** example information only *****/
    //
    $baseOrderTime = Carbon::parse('12:02');
    //$nowTime = new Carbon();
    //$saveThis = $nowTime;
    //$saveThis->toTimeString();

    $baseOrderTime = new Carbon();
    $minute = $baseOrderTime->minute;
    $second = $baseOrderTime->second;
    $base =  $baseOrderTime->copy()->subMinutes($minute);
    $base->subSeconds($second);
    $nextPossibleTime;
    /* change current time into an increment of 15 minutes */
    switch(TRUE)
    {
        case ($minute <=15 && $minute >= 0):
            $nextPossibleTime = $base->addMinutes(30);
            break;
        case ($minute <= 30 && $minute > 15 ):
            $nextPossibleTime = $base->addMinutes(45);
            break;
        case ($minute <= 45 && $minute > 30 ):
            $nextPossibleTime = $base->addhour();
            break;
        case ($minute < 60 && $minute > 45 ):
            $nextPossibleTime = $base->addHour();
            $nextPossibleTime = $base->addMinutes(15);
            break;
    }

    /* get used pickup or delivery times from the order_times table
       and get the number of each for each time */ 
    $orderTimes = DB::table('order_times')
                     ->select(DB::raw( 'order_time, count(*) as times_count'))
                     ->where('method','=',$method)
                     ->groupBy('order_time')
                     ->orderBy('order_time')
                     ->get();

    $orderTimesCounted = clone($orderTimes);

    /* Get the list of all possible order times */
    $dailyOrderTimes = self::getDailyOrderTimes();

    /* Just get the times that have orders set */
    $orderTimesFormatted = array();
    $times = $orderTimesCounted->pluck('order_time');

    
    /*format the used times to compare them with the possible times*/
    foreach($times as $time)
    {
      $time = Carbon::parse($time);
      $orderTimesFormatted[] = $time->format('g:i A');
    }

    /* pickup orders can have 15 per time, delivery orders can have 5 per 15 minute period */

    if($method == 'pickup')
    {
        $maxNumberOrders = 15;

    } else {
        $maxNumberOrders = 5;
    }

    foreach($dailyOrderTimes as $dailyOrderTime)
    {
        $dailyOrderTimeStamp = Carbon::parse($dailyOrderTime);
        
        /* check possible times that are greater than the current time */

        if ($dailyOrderTimeStamp->gte($nextPossibleTime))
        {
           $dailyOrderTimeFormatted = $dailyOrderTimeStamp->format('g:i A');

          /* If time hasn't been used yet, it is available, no need to check
          for how many times it has been used - check the times that are */
          if(in_array($dailyOrderTime, $orderTimesFormatted))
          {
              foreach($orderTimesCounted as $orderTimesInUse)
              {
                $orderTimeStamp  = Carbon::parse($orderTimesInUse->order_time);
                $orderTimeFormat =  $orderTimeStamp->format('g:i A');

                /* check used time for max pickup orders as 15 and = or greater than the base order time */
                if($dailyOrderTime == $orderTimeFormat && $orderTimesInUse->times_count <= ($maxNumberOrders - 1) && $orderTimeStamp->gte($base))
                {
                  session()->push('availableOrderTimes', $orderTimeFormat);
                }
              }
          } else {
              session()->push('availableOrderTimes', $dailyOrderTimeFormatted);
          }
        }
    } //foreach daily order time

   // dd(session()->get('availableOrderTimes'));
  }
}
