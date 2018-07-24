<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use App\FoodOption;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use Session;

class UserController extends Controller
{
    //
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index()
  {
    $allFoodOptions = FoodOption::all();
    $foodOptions = FoodOption::all()->sortBy('option_name')->groupBy('type');
    $foodOptions = $foodOptions->toArray();
    $userid = Auth::user()->id;
    $account = true;
    $user = new User;
    $userToppingChoices = array();
    $userToppingChoices = $user->userToppingSelections($userid);
    $userNonToppingChoices = User::select('sandwich','soup','beverage','side','sweetener','dressing','cracker','cheese')
        ->where('id', '=', $userid) ->get();

    $customNames = User::prepareCustomNames();
    

    /*foreach ($foodOptions as $type => $selection) {
         foreach($selection as $key =>$value);
         dd($value);
      }*/
    return view('partials.accountmain', compact('userToppingChoices','allFoodOptions','userNonToppingChoices','customNames','account','foodOptions'));
  }

  public function delete(Request $request)
  {
      $user = Auth::user();

      if($request->delivery_location_street_address)
      {
          $user->delivery_location_street_address = null;
          $user->delivery_location_instructions = null;
          $user->save();

          return response()->json(['message' => 'location deleted']);
      }

      if($request->deleteCustomName )
      {
        $nameToDelete = $request->deleteCustomName;
        $user->deleteCustomName($nameToDelete);
        $user->save();

        return response()->json(['message' => 'name deleted']);
      }

    return back();
  }

/*
  update user all information coming from the account page
*/
  public function update(Request $request)
  {
    $user = Auth::user();
    $checkValues = FoodOption::foodOptionIdValues();
    $customNameString = $user->custom_names;
    //this is the check value on the form to give a value with an empty submission
    $checkValues['topping'] .= ',A';
 
    $this->validate($request, [
        'name' => 'sometimes|required|min:2|max:60',
        'email' => 'sometimes|email|unique:users|required|min:3|max:60',
        'delivery_location_instructions' => 'sometimes|max:255|nullable',
        'delivery_location_street_address' => 'sometimes|required|min:1|max:191]',
        'addCustomName' => 'sometimes|max:60|min:1',
        'newCustomName' => 'sometimes|max:60|min:1',
        'keepPrimaryName' => 'sometimes|boolean',
        'topping' => 'sometimes|array|in:'.$checkValues['topping'],
        'sweetener' => 'sometimes|in:'.$checkValues['sweetener'],
        'dressing' => 'sometimes|in:'.$checkValues['dressing'],
        'cracker' => 'sometimes|in:'.$checkValues['cracker'],
        'cheese' => 'sometimes|in:'.$checkValues['cheese'],
        'side' => 'sometimes|in:'.$checkValues['side']
        ]);


    if ($request->topping && count($request->topping) >= 1)
    {
        $toppings = (array) $request->topping;
        $user->setDefaultToppings($toppings);
        $user->save();
        return response()->json(['message' => 'new toppings saved']);
    }

 

   if($request->sweetener) {
      $requestedSweetener = FoodOption::select('option_name')
      ->where('id','=',"$request->sweetener")
      ->get();
      $user->sweetener = $requestedSweetener[0]->option_name;
      $user->save();
      return response()->json(['message' => 'sweetener updated']);
   }

     if($request->dressing) {
        $requestedDressing = FoodOption::select('option_name')
        ->where('id','=',"$request->dressing")
        ->get();
        $user->dressing = $requestedDressing[0]->option_name;
        $user->save();
        response()->json(['message' => 'side updated']);
     }

     if($request->cracker) {
        $requestedCracker = FoodOption::select('option_name')
        ->where('id','=',"$request->cracker")
        ->get();
        $user->cracker = $requestedCracker[0]->option_name;
        $user->save();
        return response()->json(['message' => 'cracker updated']);
     }

     if($request->cheese) {
        $requestedCheese = FoodOption::select('option_name')
        ->where('id','=',"$request->cheese")
        ->get();

        $user->cheese = $requestedCheese[0]->option_name;
        $user->save();
        return response()->json(['message' => 'cheese updated']);
     }

     if($request->side) {
        $requestedSide = FoodOption::select('option_name')
        ->where('id','=',"$request->side")
        ->get();

        $user->side = $requestedSide[0]->option_name;
        $user->save();

        return response()->json(['message' => 'side updated']);
     }

     if($request->makePrimaryCustomName)
     {
      $addName = $user->primary_custom_name;
      $user->primary_custom_name = $request->makePrimaryCustomName;
      $user->save();
      return response()->json(['message' => 'default sucessfully changed']); 
    }

     if($request->addCustomName)
     {
        $addName = $request->addCustomName;
        $user->addCustomName($addName, $request);
        $user->save();
        return back();
     }

    if($request->newCustomName && $request->previousCustomName)
    {

      $previousCustomName = $request->previousCustomName;
      $newCustomName =  $request->newCustomName;
      $currentCustomNames = User::prepareCustomNames();

      foreach($currentCustomNames as $key => $value)
      {
        if ($previousCustomName == $value)
        {
          $currentCustomNames[$key] = $newCustomName;

          if( $previousCustomName == $user->primary_custom_name )
          {
            $user->primary_custom_name = $newCustomName;
          }
        }
          $user->save(); 
      }

      $currentCustomNames = implode(',', $currentCustomNames);
      $user->custom_names = $currentCustomNames;
      $user->save(); 

      return response()->json(['message' => 'new name saved', 'newName' => $newCustomName]);
    }

    if($request->name) {
      $user->name = $request->name;
      $user->save();
     return response()->json(['message' => 'name sucessfully changed']);  
    }

    if($request->email) {
      $user->email = $request->email;
      $user->save();
      return response()->json(['message' => 'email sucessfully changed']);  
    }

    $user->save();

    //return back();
  }

   /**
     * Show the profile for the given user.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function show(Request $request, $id)
    {

    }
}
