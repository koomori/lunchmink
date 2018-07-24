<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected static $salesTax = .0688;
    protected static $localTax = .01;

    public function getSalesTax()
    {
      return self::$salesTax;
    }

    public function getLocalTax()
    {
      return self::$localTax;
    }
}
