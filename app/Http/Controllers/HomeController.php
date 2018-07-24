<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\OrderTime;
use App\Discount;
use App\User;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Show the application dashboard.
    * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $discounts = Discount::all();
        $discount = new Discount;
        $neededPurchases = $discount->getNeededPurchases();

        if(Auth::user() && !session()->has('number_of_coffee_discounts'))
        {
            $discount->addUserDiscountInfoToSession();
        }

        OrderTime::checkIfStoreIsOpenForOrders();
       
        return view('partials.index', compact('discounts','neededPurchases'));
    }
}
