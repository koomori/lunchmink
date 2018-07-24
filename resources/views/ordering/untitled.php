




  $totalSalesTax = round($runningTotal * $salesTax, 2);
      dd($totalSalesTax);
      //$totalSalesTax = bcadd($totalSalesTax, 0, 2);
      //$totalLocalTax = round($runningTotal * $localTax, 2);
      //dd($runningTotal * $localTax);
      //$totalLocalTax = bcadd($totalLocalTax, 0, 2);
      //$total = round(($runningTotal + $totalLocalTax + $totalSalesTax), 2);
      //$total = bcadd($total, 0, 2);

          <?php 
    ?>
  <div class="brand-label-text align-right" style="border-bottom: 1px solid green">Sub Total: $
    <span id="orderSubTotal" >{{ $runningTotal }}</span>
  </div>
  <div class="brand-label-text align-right">Sales Tax: 
    $<span id="salesTax" >{{ $totalSalesTax }}</span>
  </div>
  <div class="brand-label-text align-right">Local Tax: 
    $<span id="localTax" >{{ $totalLocalTax }}</span>
  </div>
  <div class="brand-label-text align-right">Order Total: 
    $<span id="orderTotal" >{{ $total }}</span>
  </div>
</section>
  </div>
  <div class="centered upper-border-background" >
   <form id="credit-card-entry-form" method="post" action="/placeorder"> 
         {{ csrf_field() }}
   <div id="credit-card-entry" class="brand-label-text" >
   <label for="credit-card">Enter Credit Card Number:</label>
   <input style="margin-bottom: 10px;" id="credit-card" type="text" name="credit_card" placeholder="Ex: 8765456765432100" maxlength="19" >
     @if ($errors)
           @include('partials.errors')


   </div>
     <a class="button-custom-gradient-small rounded" href="/vieworder">Make Changes</a>
     <button class="button-custom-gradient-small rounded">Place Order</button>
    </form>
  </div>
  </div>
</div>