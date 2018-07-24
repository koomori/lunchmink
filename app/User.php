<?php

namespace App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function orders()
    {
      return $this->hasMany(Order::class)->orderBy('updated_at', 'desc');
    }

    /* update account topping choices */
    public function setDefaultToppings($toppings)
    {
        $notNeeded = array_search('A', $toppings);
        unset($toppings[$notNeeded]);

        /*name of food options with underscores */
        $columnNames = FoodOption::toppingsColumnNameArray();
       // dd($toppings);

        /* get the names of the choosen id toppings
        set them as choosen in the database and
        uncheck the others
        */

        $choosenToppings = FoodOption::whereIn('id', $toppings)->get();
        $choosenToppingNames = array();

        foreach($choosenToppings as $topping)
        {
          $choosenOptionName = $topping['option_name'];
          $choosenOptionName = str_replace (' ','_',$choosenOptionName);
          $choosenToppingNames[] = $choosenOptionName;
           DB::table('users')
            ->where('id', '=', Auth::user()->id)
            ->update(["$choosenOptionName" => 1]);
        }

        $notChoosen = array_diff($columnNames, $choosenToppingNames);

        foreach($notChoosen as $remove)
        {
          DB::table('users')
            ->where('id', '=', Auth::user()->id)
            ->update(["$remove" => 0]);
        }
    }

    /* retrieve user account topping choices */
    public function userToppingSelections($userid)
    {
      $toppingSelections = FoodOption::toppingsColumnNameArray();

       $userToppingChoices = array();

     /* get combine topping info with user information
        on if the user has selected it  -- only needed for
        sandwich toppings checkboxes -- otherwise
        foodoption selections have a default
        radio button option
     */

      foreach($toppingSelections as $selection)
      {

        /* get user topping preferance for that topping */
        $toppingChoiceValue = DB::table("users")
        ->select($selection)
        ->where("id", "=", "$userid")->get();

        /* add the topping preferance values to an array */
        $toppingUserChoice = array();
        foreach($toppingChoiceValue as $key => $value)
        {
          $toppingUserChoice = $toppingChoiceValue[$key];
        }

        /* get info for each topping from the food options database
        where the topping names don't have an underscore */

        $selection = str_replace("_", " ", $selection);

        $toppingChoiceInfo = DB::table('food_options')
          ->select('option_description','option_name','type')
          ->where("option_name", "=", "$selection")
          ->get();
        $toppingOptionInfo = array();
        foreach($toppingChoiceInfo as $key => $value)
        {
            $toppingOptionInfo = $toppingChoiceInfo[$key];
        }
        /* combine info together into one array */
        $toppingInfo = array_merge((array)$toppingUserChoice, (array) $toppingOptionInfo);
        $userToppingChoices[] = $toppingInfo;
      }

      return $userToppingChoices;
    }

    public function addCustomName($addName, $request)
    {
        if($this->custom_names)
        {
            $currentCustomNames = explode(',', $this->custom_names);
            array_push($currentCustomNames, $addName);
            $currentCustomNames = implode(',', $currentCustomNames);

            $this->custom_names = $currentCustomNames;

        } else {
            $this->custom_names = $request->addCustomName;
            $this->primary_custom_name = $request->addCustomName;
        }
    }

    public static function prepareCustomNames()
    {
      $customNames = Auth::user()->custom_names;

      if(strlen($customNames) > 0) {
        $customNames = explode(',', $customNames);
        asort($customNames);
      } else {
         $customNames = array();
      }

    /* $defaultName = Auth::user()->primary_custom_name;
      if(strlen($defaultName) > 0)
      {
        array_unshift($customNames, $defaultName);
      } */
      return $customNames;
    }

    public function deleteCustomName($nameToDelete)
    {
        $this->custom_names;

        if($this->primary_custom_name == $nameToDelete)
        {
          $this->primary_custom_name = NULL;
        }

        $currentCustomNames = explode(',', $this->custom_names);

        foreach($currentCustomNames as $key => $value)
        {
          if ($value == $nameToDelete)
          {
            unset($currentCustomNames[$key]);
          }
        }

        if(count($currentCustomNames) == 0)
        {
          $currentCustomNames = "";
        } elseif (count($currentCustomNames) == 1 ) {
         $currentCustomNames = implode($currentCustomNames);
        } elseif(count($currentCustomNames) > 1)
        {
          $currentCustomNames = implode(',', $currentCustomNames);

        }
        $this->custom_names = $currentCustomNames;
    }
}
