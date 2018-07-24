$(document).ready( function() {

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

/**** update user delivery location and instructions ****/
  if($('#change-delivery-instructions').length)
  {
    $('#change-delivery-instructions').on('click', function(){
      $('#delivery-location-form').show();
    });
  }
  if($('#delivery_location_instructions').length)
  {
    $('#delivery_location_instructions').on('click', function(){
      $(this).val("");
    });
  }

  if($('#set-delivery-address').length)
  {
    $('#set-delivery-address').on('click', function(event){
        setDeliveryAddress(event);
    }); 

  } //setting delivery address on googlemap page

  if($('#pac-input').length)
  {
    $('#pac-input').on('click', function()
    {
     
      $(this).val('');
      if($('#acceptable-delivery-location').is(':visible'))
      {  
          $('#acceptable-delivery-location').text("");
      }

    });
  }

  function setDeliveryAddress(event)
  {
    event.preventDefault();

      $deliveryLocation = $('#delivery-location-input').val();
      $deliveryInstructions = $('#delivery_location_instructions').val();
      $addressPurpose = $('input[name="addressPurpose"]').val();

     $.ajax({
      url:'/setdeliverylocation',
      data:{
        delivery_location_street_address: $deliveryLocation,
        delivery_location_instructions: $deliveryInstructions,
        addressPurpose: $addressPurpose
      },
      error: function(jqXhr){
          if( jqXhr.status === 422 ) {
            //process validation errors here.
            $errors = jqXhr.responseJSON; //this will get the errors response data.
            //show them somewhere in the markup
            //e.g
            errorsHtml = '<div class="alert alert-danger"><ul>';

            $.each( $errors, function( key, value ) {
                errorsHtml += '<li>' + value[0] + '</li>'; //showing only the first error.
            });
            errorsHtml += '</ul></div>';
            $('#error-delivery-location').show();
            $('#error-delivery-location').html(errorsHtml);
          }
        },
      dataType: 'json',
       success: function(data) {
        $message = data.message;
        $id = data.cartOrderId;
        $address =data.address;
        $instructions = data.instructions; 
        
        switch($addressPurpose)
        {
          case 'changeAccountAddress':
            window.location.href = '/account';
            break;
          case 'changeDeliveryLocationAddress':
            window.location.href= '/vieworder';
            break;
          case 'changeInitalDeliveryLocationAddress':
            window.location.href= '/settimeforcustomdeliverylocation';
            break;
          case 'setAccountAndDeliveryLocationAddress':
            window.location.href='/keepusersdeliveryaddress';
            break;
        }
       },
    type: 'POST'
  });
}
/************ make sure dressing option
doesn't stay checked if user isn't getting a salad *************/
if( $('#vegetable_salad').length )
{
  checkDressings();
}

/************* show overlay for starting online order ****************/
if ( $( "#overlay" ).length )
{
  $( "#overlay" ).show();
}
/************ update order quantity in checkout cart *********************/
if( $('.selectQuantity').length)
{
  $('.selectQuantity').change(function ()
  {
      $newQuantity = $(this).val();
      $cartOrderFoodId = $(this).prev().val();

 $.ajax({
  url:'/changeQuantity',
  data:{
    food_quantity: $newQuantity,
    cart_order_food_id : $cartOrderFoodId
  },
  error: function(){
        $('#error-'+$cartOrderFoodId).text("The Quantity was unable to be updated");
    },
  dataType: 'json',
   success: function(data) {

    /* update the subtotals and total
     when there is a successful
     response*/

    var $discount  = data.discountId;
    var $price = data.foodPrice;
    var $salesTax = data.salesTax;
    var $localTax = data.localTax;
    if($discount != 0)
    {
      $subtotal = Math.round(($newQuantity - 1) * parseFloat($price)*100)/100;
    } else {
      $subtotal = Math.round($newQuantity * parseFloat($price)*100)/100;
    }
    $subtotal = Number($subtotal).toFixed(2);
    $('#subtotal-'+$cartOrderFoodId).text($subtotal);
    updateCartItemsTotals($salesTax, $localTax, $discount, $price);
  },
    type: 'POST'
  });
});

}

/********* update order custom food names in checkout *****************/

$('.custom-food-name').change( function(){
   var $customFoodName = $(this).val();
   var $cartOrderFoodId = $(this).next().val();
   
   $.ajax({
    url:'/changeCustomFoodName',
    data: {
      custom_name: $customFoodName,
      cart_order_food_id: $cartOrderFoodId
    },
    error: function(xhr, status, error){
    },
    dataType: 'json',
    success: function(data){
      $('#custom-name-'+$cartOrderFoodId).text("Custom Name : " +data.newName);
    },
    type: 'POST'
   });

});

/********************* in checkout update totals 
for discount usage & quantity changes
**********************/

function updateCartItemsTotals($salesTax, $localTax, $discount, $discountAmount)
{
  $newCost = 0;
  $newRunningTotal = 0;
  $method = $('#timeReservedUntil').attr('data-method');

  $('.subtotal').each( function(i,obj){
    $text = $(this).text();
    $newRunningTotal += Math.round(parseFloat($text)*100)/100;
  });

  //Format the numbers to always show two decimal places 
  $newRunningTotal = Math.round($newRunningTotal*100)/100;
  $newSalesTax = Math.round($newRunningTotal * $salesTax*100)/100;
  $newLocalTax = Math.round($newRunningTotal * $localTax*100)/100;
  $newCost = Math.round(($newRunningTotal + $newSalesTax + $newLocalTax)*100)/100;
  $newRunningTotal = Number($newRunningTotal).toFixed(2);
  $newSalesTax = Number($newSalesTax).toFixed(2);
  $newLocalTax = Number($newLocalTax).toFixed(2);
  $newCost = Number($newCost).toFixed(2);
  
  $('#orderSubTotal').text($newRunningTotal);
  $('#salesTax').text($newSalesTax);
  $('#localTax').text($newLocalTax);
  $('#orderTotal').text($newCost);

  if($method == "pickup")
  {
    if($newCost <= 75 && $newCost > 0)
    {
      showReviewAndPay();
    } else {
      hideReviewAndPay($method, $newCost);
    }
  }

  if( $method == "delivery")
  {
    if($newCost >= 10 && $newCost <= 75)
    {
      showReviewAndPay();
    } else {
      hideReviewAndPay($method, $newCost);
    }
  }
}

function showReviewAndPay()
{
    $('#deliveryPriceWarning').hide();
    if($('#reviewAndPay').length == 0)
    {  
      $('#allowReviewOrder').append('<a id="reviewAndPay" class="button-custom-gradient-small rounded" href="/revieworder">Review &amp; Pay</a>');
    } else {
      if($('#reviewAndPay').hasClass('ordertoolarge'))
      {
        $('#reviewAndPay').removeClass('ordertoolarge');
      }
      $('#reviewAndPay').show();
    }
}

function hideReviewAndPay($method, $newCost)
{
    $message = "";
    if($newCost > 75)
    {
      $message = "An pickup or delivery order must be under $75.";
    }

    if($method == 'delivery' && $newCost < 10)
    {
      $message = "Your Order Must Be at Least $10 for a Delivery Order";
    }

    if($('#deliveryPriceWarning').length == 0)
    {
      $('#allowReviewOrder').prepend('<div id="deliveryPriceWarning" class="brand-label-text">'+ $message + '</div>');
    } else {
      $('#deliveryPriceWarning').text($message);
      $('#deliveryPriceWarning').show();
    }

    if($('#reviewAndPay').length)
    { 
      $('#reviewAndPay').hide();
    } 
}

/* ******* apply coffee or sandwich discount ****** */
$('.button-custom-gradient-small.applyDiscount').on('click', function(event){
  applyDiscount(event);
});

function applyDiscount(event){
    event.preventDefault();
    event.stopPropagation();

    var $targetElement = $(event.target);
    var $cartOrderFoodId = $targetElement.prevAll('input[name=cart_order_food_id]').val();
    var $discountId = $targetElement.prevAll('input[name=discount_id]').val();

    $.ajax({
    url:'/useDiscount',
    data: {
      cart_order_food_id: $cartOrderFoodId,
      discount_id : $discountId
    },
    error: function(xhr, status, error){
      
    },
    dataType: 'json',
    success: function(data){
      $discount = data.discount;
      $discountAmount = data.discountAmount;
   
      $salesTax = data.salesTax;
      $localTax = data.localTax;
      
      $quantity = data.quantity;
      $discountCurrentlyUsed = data.discountCurrentlyUsed;

      $targetElement.off('click', applyDiscount );
      $targetElement.removeClass('button-custom-gradient-small').addClass('button-custom-gradient-disabled');

      $('.applyDiscount.button-custom-gradient-small').addClass('hideOtherDiscounts');
      
      $subTotal = Math.round((($quantity * $discountAmount) - $discountAmount)*100)/100;
      $subTotal = Number($subTotal).toFixed(2);
      $('#subtotal-' + $cartOrderFoodId).text($subTotal);
      if($('#discount-price-show-'+$cartOrderFoodId).length > 0)
      {
        $(this).attr('id','#discount-price-' + $cartOrderFoodId);
      }

      $('#discount-price-' + $cartOrderFoodId).text("- Discount: $" + $discountAmount);
      $('#discount-price-' + $cartOrderFoodId).css('display', 'inline');
      $('#discount-price-'+ $cartOrderFoodId).css('background-color', 'pink');

      updateCartItemsTotals($salesTax, $localTax, $discount, $discountAmount);
      $targetElement.text("Discount Applied");
       //show the remove button once the discount has been added 
      $targetElement.next().removeClass('discountNotApplied');
   
    },
    type: 'POST'
   });

}

/****** remove a discount from an item in cart *********/
$(".button-custom-gradient-small.removeDiscount").on('click', function(event) {
    event.preventDefault();
    event.stopPropagation();
    var $discountId = $(this).prevAll("input[name=discount_id]").val();
    var $cartOrderFoodId = $(this).prevAll("input[name=cart_order_food_id]").val();
    $(this).addClass('discountNotApplied');
    $.ajax({
      url:'/removeDiscount',
      data:{
        cart_order_food_id : $cartOrderFoodId,
        discount_id : $discountId
      },
       error: function(){
        $('#error-'+$cartOrderFoodId).text("The Discount was unable to be applied");
        $('#error-'+$cartOrderFoodId).show();
    },
    dataType: 'json',
    success: function(data){

      $price = data.price;
      $quantity = data.quantity;
      $salesTax = data.salesTax;
      $localTax = data.localTax;
      $discountId = data.discount;
      $discount = 0;
      $discountCurrentlyUsed = data.discountCurrentlyUsed;
      $subtotal = Math.round($quantity * $price *100)/100;
      $subtotal = Number($subtotal).toFixed(2);
      $('#subtotal-' + $cartOrderFoodId ).text($subtotal);

      if($('#discount-price-show-' + $cartOrderFoodId).length)
      {
        $('#discount-price-show-' + $cartOrderFoodId).attr('id', 'discount-price-' + $cartOrderFoodId);
      }

      $('.applyDiscount.button-custom-gradient-small').each(function(){
          $(this).removeClass('hideOtherDiscounts').addClass('showOtherDiscounts');
      });

      $('#discount-price-' + $cartOrderFoodId).text("");
      $('#discount-price-' + $cartOrderFoodId).css("display", "none");
      $('.applyDiscount').removeClass('button-custom-gradient-disabled');
      $('.applyDiscount').addClass('button-custom-gradient-small');

      $('.applyDiscount').each( function(i,obj){
       
        $discount_id = $(this).prevAll('input[name=discount_id]').val();
        if($discount_id == 1)
        {
          $messageText = "Coffee";
        } else {
          $messageText = "Sandwich";
        }
        $(this).text("Apply One Free " + $messageText + " Discount");

      });

      updateCartItemsTotals($salesTax, $localTax, $discount, $price);
      $('.applyDiscount').on('click', applyDiscount);
    },
    type: 'POST'
    });
 
});

 /* ***** controls revelation of add custom name at the bottom of the account custom name form ***** */
 $("#addCustomName").on('click', function(event) {
    event.preventDefault();
    var elClicked = $(this);
    $messageText = elClicked.attr('data-text');
    elClicked.parent().parent().prev().fadeToggle();
    elClicked.toggleClass('close-change');
    elClicked.toggleClass('show button-custom-gradient-small');

   if (elClicked.hasClass('show'))
    {
      elClicked.text($messageText);
    } else {
       elClicked.text('Close');
       elClicked.css('background', );
    }
  });
 
/* ****** control toggling open sections of the screen to reveal hidden forms ******* */

  $(".form-toggle a").click(function(){
    var elClicked = $(this);
    $messageText = elClicked.attr('data-text');
    toggleButtonFormatting(elClicked, $messageText);
    $(this).parent().parent().next().fadeToggle();
  });

/*** controls the changing of the clicked button into a 'close' button ***/
  function toggleButtonFormatting (elClicked, $messageText) {

    if (elClicked.hasClass('show'))
    {
      elClicked.addClass('close-change');
      elClicked.removeClass('show button-custom-gradient-small');
      elClicked.text('Close');
      elClicked.attr('aria-expanded', 'true');

    } else {
      elClicked.addClass('show button-custom-gradient-small');
      elClicked.removeClass('close-change');
      elClicked.text($messageText);
      elClicked.attr('aria-expanded', 'false');
    }
  }

/****** delete a custom name on the account page ******/

$('.delete-custom-name').on('click', function(event){
  event.preventDefault();

  $parentLi = $(this).parents(".custom-name-li");
  $counter = $parentLi.find('span.custom-name').attr('data-index');
  $nameToDelete = $parentLi.find('input[name="deleteCustomName"]').val();
  $error = $parentLi.find('.error-customname');
  $error.text("");

  /* the ajax method for deleting that custom
    name from the database */
    $errorMessage="We are unable to use that name";
     $.ajax({
        url:'/deleteuserinfo',
        data:{
          deleteCustomName:  $nameToDelete
        },
        dataType: 'json',
        error: function(jqXhr){
          showAjaxErrorMessage(jqXhr, $error, $errorMessage);
      },
      type : 'POST',
      success: function(data){
         $message = data.message;
        //remove that element from the dom
        $parentLi.remove();
        
        //reindex the li that were beneath the element that were removed.
        $('.custom-name-li').each( function(index, element){
          if(index >= $counter)
          {
            $(this).find("span.custom-name").attr('data-index',index);
          }
        });
      }
    });  
});

/*** change custom name on the account page ****/
  $(".change-custom-name").click( function(){

      elClicked = $(this);
      $messageText = elClicked.attr('data-text');
      elClicked.parent().prev('.custom-name-form-container').fadeToggle("fast");
      
      toggleButtonFormatting(elClicked, $messageText);
  });

  $("#order-name-form").submit(function( event ) {

    event.preventDefault();

    $('#orderNameError').text('');
    $newOrderName = $('#newOrderName').val();

    $.ajax({
      url: '/changeOrderName',
      data: {
        order_name: $newOrderName
      },
      error: function(){
          $('#orderNameError').text('Order Name unable to be set');
      },
      dataType: 'json',
      success: function(data) {
        $('#currentOrderName').text($newOrderName);
      },
      type: 'POST'
    });
  });
  /* controls the button on the item page and in the account */

  $('#chooseAllToppings').on('click', function(event) {
    event.preventDefault();
    var toppingChoices = $('input[name="topping[]"]');

    if($(this).text() == "Choose All Toppings")
    {
      toppingChoices.prop("checked", true);
      $('#chooseAllToppings').text('Deselect All Toppings');
    } else {
      toppingChoices.prop("checked", false);
      $('#chooseAllToppings').text('Choose All Toppings');
    } 
  });

  /********** control showing salad dressing options on food item page and on the account page *********/

$('input[name=side]').click ( function() {
  checkDressings();
});

  function checkDressings()
  {
     if ($('#vegetable_salad').is(':checked'))
        {
          $('div#dressing-options').fadeIn("slow");
        }else {
          $('div#dressing-options').fadeOut("slow");
          $('.dressing-for-salad').attr('checked', false);
        }
  }

  /********* timer for the order reservation time *********/

  if ($('#timeReservedUntil').length)
  {
    //var orderHeldUntil =  $('#holdTime').attr('data-time');
    // Set the date we're counting down to
    //var countDownDate = new Date("Jan 5, 2018 15:37:25").getTime();
    //var countDownDate = new Date(orderHeldUntil);
    //var countDownTime = countDownDate.getTime(); 
    //Hold time is in seconds and getTime() gives date in milisecons;
    var countDownTime = (parseInt($('#holdTime').attr('data-time')))*1000;

    // Update the count down every 1 second
    var orderTimeCountDown = setInterval(function() {

      // Get todays date and time
      var now = new Date().getTime();
  
      // Find the distance between now an the count down time
      var distance = countDownTime - now;

      // Time calculations minutes and seconds
      var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      var seconds = Math.floor((distance % (1000 * 60)) / 1000);

      // Display the result in the timeReservedUntil id
      $('#timeReservedUntil').text(minutes + " minutes " + seconds + " seconds ");

      // After Count Down has ended the User will have to reset their time 
      if (distance < 0) {
        clearInterval(orderTimeCountDown);
         $('#resetOrderTime').hide();
        $method = $('#timeReservedUntil').attr('data-method');
       
        if($method == 'delivery')
        {
          $messageLink = "<a class='button-custom-gradient-small rounded' href='/pickupordeliverymethod' >Recheck available pickup and delivery times.</a>";
        } else {
          $messageLink = "<a class='button-custom-gradient-small rounded' href='/pickuporder' >Recheck available times.</a>";
        }
         $('#timeReservedUntil').html("Available Time No longer Held. " + $messageLink); 
         $('#timeMessage').hide();
         $('#giveOrderAnotherTime').show(); 
         $('#order-time-location').hide();

          $.ajax({
            url:'/removeTimeReservation',
            data:{
              },
               error: function(){
            },
            dataType: 'json',
            success: function(data){
              $removed = data.time;
            },
          type: 'POST'
        });
      } 
    }, 1000);

  } //end timer 
/* *********** ajax and form management of user name field ******** */
  $('#name-input').on('click', function() {
    $(this).val("");
    $('#error-username').empty();
  });

  $('#change-name').on('click', function() {
    $('#name-input').val("");
    $('#error-username').empty();
  });

  $('#change-username').on('click', function(event){
    event.preventDefault();
    $username = $('input[name="name"]').val();
    $error = $('#error-username');
    $errorMessage = "We are unable to use that name";
    $.ajax({
        url:'/user',
        data:{
           name : $username
        },
        dataType: 'json',
        error: function(jqXhr){
          showAjaxErrorMessage(jqXhr, $error, $errorMessage); 
      },
      type : 'POST',
      success: function(data){
         $message = data.message;
         $('#user-name').text($username);
         $('#error-username').show();
         $('#error-username').text($message);
      }
      });
    });

  /* *********** ajax and form management of email field on account form ******** */
  $('#email-input').on('click', function() {
    $(this).val("");
    $('#error-email').empty();
  });

  $('#change-email').on('click', function() {
    $('#email-input').val("");
    $('#error-email').empty();
  });

  $('#change-useremail').on('click', function(event){
    event.preventDefault();
    $useremail = $('input[name="email"]').val();
    $error = $('#error-email');
    $errorMessage = "We are unable to use that email";
    $.ajax({
        url:'/user',
        data:{
           email :  $useremail 
        },
        dataType: 'json',
        error: function(jqXhr){
        showAjaxErrorMessage(jqXhr, $error, $errorMessage); 
      },
      type : 'POST',
      success: function(data){
         $message = data.message;
         $('#user-email').text($useremail);
         $('#error-email').show();
         $('#error-email').text($message);
      }
      });
    });

  /* *********** ajax and account form management of Custom Names ******** */
  $('.default-name').on('click', function(event){
    event.preventDefault();

    $defaultNameButton = $(this);
    
    $parentLi = $defaultNameButton.parents(".custom-name-li");
    $counter = $parentLi.find('span.custom-name').attr('data-index');

    $inputDefault =  $defaultNameButton .prev($('input[name="makePrimaryCustomName"]'));
    $inputDefaultValue = $inputDefault.val();
    $error = $parentLi.find('.error-customname'); 
    $error.text("");
    $error.hide();
    $errorMessage = "We are unable to use that name";

    $.ajax({
        url:'/user',
        data:{
           makePrimaryCustomName :  $inputDefaultValue
        },
        dataType: 'json',
        error: function(jqXhr){
          showAjaxErrorMessage(jqXhr, $error, $errorMessage); 
      },
      type : 'POST',
      success: function(data){
        $message = data.message;
        // remove default text where ever it was before     
        $('.custom-name .default').text("");
        // add default text to the new default
        $showDefaultEl = $parentLi.find('.custom-name span:first')
        $showDefaultEl.addClass('default');
        $showDefaultEl.text("Default:");
        $parentLi.find('.defaultNameForm').addClass('hideDefaultNameForm');
        $counter = parseInt($counter);

        // remove default from the other elements and make sure the 'Make Default Name' button is showing 
        $('.custom-name').each(function(index) {
            if(index !== $counter)
            {   
                $checkNameElement = $(this).children('span:first');
                $checkNameElement.removeClass('default');
                $(this).next('.defaultNameForm').removeClass('hideDefaultNameForm');
            }
        });        
         //.removeClass('hideDefaultNameForm')
      }
      });
    });


/* ******** change custom name on user account page ******** */
  // 'document' on needs to be used for previously unknown elements
  $(document).on('click','.save-changed-name-button', function(event){
      event.preventDefault();
      $parentLi = $(this).parents(".custom-name-li");
      $counter = $parentLi.find('span.custom-name').attr('data-index');
      $newName = $parentLi.find('input[name="newCustomName"]').val();
      $previousCustomName = $parentLi.find('input[name="previousCustomName"]').val();
      $error = $parentLi.find('.error-custom-name-change');
      $errorMessage = "We are unable to use that name";

      $.ajax({
          url:'/user',
          data:{
             newCustomName : $newName,
             previousCustomName : $previousCustomName
          },
          dataType: 'json',
          error: function(jqXhr){
            showAjaxErrorMessage(jqXhr, $error, $errorMessage);
        },
        type : 'POST',
        success: function(data){
          $message = data.message;
          $newName = data.newName;
          $parentLi.find('.display-custom-name').text($newName);
          //make sure the make defaut value button showing up when it should
       }

    });

  });

 /* remove the address on the account page */ 
$('#delete-address').on('click', function(event){
  event.preventDefault();

  $deliveryLocationAddress = $('#delivery_location_input').val();
  $error = $('#error-delete-address');
  $error.text("");
  $errorMessage = 'We are unable to remove the location';

  $.ajax({
        url:'/deleteuserinfo',
        data:{
          delivery_location_street_address : $deliveryLocationAddress
        },
        dataType: 'json',
        error: function(jqXhr){
          showAjaxErrorMessage(jqXhr, $error, $errorMessage);
      },
      type : 'POST',
      success: function(data){
        $('#location-name').text("No Location Selected");
        $('#location-instructions').text("No Instructions");
     }
    });
  });

/* the one method for saving the food option changes for the checkbox and radio button forms */
  $('.save-account-food-option').on('click', function(event){
    event.preventDefault();
    $foodOptionId = $(this).attr('id');
    $foodType = $foodOptionId.replace('account-','');
    
    $error = $('.error-food-selected-'+$foodType);
    $error.hide();
   
    if($foodType  == "topping")
    {
      $selections = [];
      $selections.push('A');
      $('input[name="topping[]"]:checked').each(function(index, value) 
      {
          $selections.push($(this).val()); 
      });
     
     $errorMessage = "unable to change food option";

     $.ajax({
        url:'/user',
        data:{
         topping : $selections
        },
        dataType: 'json',
        error: function(jqXhr){
          showAjaxErrorMessage(jqXhr, $error, $errorMessage);
      },
      type : 'POST',
      success: function(data){
        $message = data.message;
        // flash a message that the data has been saved
        showAndFade($message, $foodType);
     }
    });
     //end if topping selected 
    } else if($foodType == "side"){
        $sideChoice = $('input[name="side"]:checked').val();
        $dressingChoice = null;

        if($sideChoice == "31")
        {
          $dressingChoice = $('input[name="dressing"]:checked').val();
                 $.ajax({
              url:'/user',
              data:{
               side: $sideChoice,
               dressing:  $dressingChoice
              },
              dataType: 'json',
              error: function(jqXhr){
                showAjaxErrorMessage(jqXhr, $error, $errorMessage);
            },
            type : 'POST',
            success: function(data){
              $message = data.message;
              // flash a message that the data has been saved
              showAndFade($message, $foodType);
           }
          });

        } else {
            $error = $('.error-food-selected-'+$foodType);
            $error.hide();
            $errorMessage = "unable to update side";

            $.ajax({
              url:'/user',
              data:{
               side: $sideChoice,
              },
              dataType: 'json',
              error: function(jqXhr){
                showAjaxErrorMessage(jqXhr, $error, $errorMessage);
            },
            type : 'POST',
            success: function(data){
              $message = data.message;
              // flash a message that the data has been saved
              showAndFade($message, $foodType);
           }
          });
        }
    } else if ($foodType == "cheese")
    {
            $cheeseChoice = $sideChoice = $('input[name="cheese"]:checked').val();
            $error = $('.error-food-selected-'+$foodType);
            $error.hide();
            $errorMessage = "unable to update cheese";

            $.ajax({
              url:'/user',
              data:{
               cheese: $cheeseChoice,
              },
              dataType: 'json',
              error: function(jqXhr){
                showAjaxErrorMessage(jqXhr, $error, $errorMessage);
            },
            type : 'POST',
            success: function(data){
              $message = data.message;
              // flash a message that the data has been saved
              showAndFade($message, $foodType);
           }
          });
      //end cheese
    } else if ($foodType == "sweetener")
    {
            $sweetenerChoice = $('input[name="sweetener"]:checked').val();
            $error = $('.error-food-selected-'+$foodType);
            $error.hide();
            $errorMessage = "unable to update sweetener";

            $.ajax({
              url:'/user',
              data:{
               sweetener: $sweetenerChoice,
              },
              dataType: 'json',
              error: function(jqXhr){
                showAjaxErrorMessage(jqXhr, $error, $errorMessage);
            },
            type : 'POST',
            success: function(data){
              $message = data.message;
              // flash a message that the data has been saved
              showAndFade($message, $foodType);
           }
          });

    }else if ($foodType == "cracker")
    {
            $crackerChoice = $('input[name="cracker"]:checked').val();
            $error = $('.error-food-selected-'+$foodType);
            $error.hide();
            $errorMessage = "unable to update cracker";

            $.ajax({
              url:'/user',
              data:{
               cracker: $crackerChoice,
              },
              dataType: 'json',
              error: function(jqXhr){
                showAjaxErrorMessage(jqXhr, $error, $errorMessage);
            },
            type : 'POST',
            success: function(data){
              $message = data.message;
              // flash a message that the data has been saved          
              showAndFade($message, $foodType);
           }
          });
      }

  });

  function showAjaxErrorMessage(jqXhr, $error, $errorMessage)
  { 
    if( jqXhr.status === 422 ) {
      $errors = jqXhr.responseJSON; //this will get the errors response data.
      //show error on page
      errorsHtml = '<div class="alert alert-danger"><ul>';

      $.each( $errors, function( key, value ) {
          errorsHtml += '<li>' + value[0] + '</li>'; //showing only the first error.
      });
      errorsHtml += '</ul></div>';
      //$( '#form-errors' ).html( errorsHtml ); //appending to a <div id="form-errors"></div> inside form
      
      $error.show();
      $error.html(errorsHtml);
    } else {
      
      $error.show();
      $error.html('<div class="alert alert-danger">'+ $errorMessage + '</div>');
    }  
  }
event
  function showAndFade($message, $type)
  {
     $flashElement = $('.flash-message-' + $type);
     $flashElement.text($message);
     $flashElement.show().delay(500).fadeOut(1500);
  }

$(".header").click(function (event) {
      event.preventDefault();
      $header = $(this);
      //getting the next element
      $content = $header.next();
      //open up the content needed - toggle the slide- if visible, slide up, if not slidedown.
      $content.slideToggle(800, function () {
          //execute this after slideToggle is done
          //change text of header based on visibility of content div
          $header.text(function () {
              //change text based on condition
              //return $content.is(":visible") ? "Collapse" : "Expand";
          });
      });
  });

});
