      <div class="form-stripe-desat d-flex flex-column flex-wrap">
        <div class="user-info">
          <h3 class="brand-label-text">Custom Names for Items</h3>
          <ul class="show-dot">
            <li class="brand-label-text-small">Custom Names are used to label food item packaging</li>
            <li class="brand-label-text-small">No more eating Robin's sandwich because yours is labeled with your name</li>
          </ul>  
           @if(count($customNames) > 0)
              <ul class="list-group" id="custom-name-list">
              <?php  $counter = 0; ?>
            @foreach($customNames as $customName)
              <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap custom-name-li" >
                  <span class="custom-name" data-index="{{$counter}}">
                    @if($customName == Auth::user()->primary_custom_name) 
                      <span class="brand-label-text default">Default:</span>
                    @else
                      <span class="brand-label-text"></span>
                    @endif  
                      <span class="display-custom-name  brand-label-text-small">{{ $customName }}</span>
                      <div class="error-customname" ></div>
                  </span>
                  <div class="button-group defaultNameForm @if($customName == Auth::user()->primary_custom_name)hideDefaultNameForm @endif">
                        <form class="inline" action="/user" method="POST">
                          {{ csrf_field() }}
                        <input type="hidden" name="makePrimaryCustomName" value ='{{ $customName }}'>
                        <button class="rounded default-name button-custom-gradient-small" >Make Default Name</button>
                        </form>
                  </div>
                 <div class="custom-name-form-container">
                  <form  method="post" action="/user" class="custom-name-form bright-background d-flex flex-column flex-wrap">
                    {{ csrf_field() }}
                    <label class="custom-name-input"> New Name:
                      <input class="extra-margin-bottom" type="text" name="newCustomName" value ='{{ $customName }}'>
                      <input  type="hidden" name="previousCustomName" value ='{{ $customName }}'>
                    </label>
                    <div class="error-custom-name-change"></div>
                    <button class="button-custom-gradient-small rounded max-btn-width save-changed-name-button"  >Save New Name</button>
                  </form>
                  </div>
                  <div class="button-group">
                        <a class="change-custom-name rounded show button-custom-gradient-small" data-text="Change Name">Change Name</a>
                        <form class="inline" action="/deleteuserinfo" method="POST">
                          {{ csrf_field() }}
                        <input type="hidden" name="deleteCustomName" value ='{{ $customName }}'>
                        <button class="delete-custom-name rounded button-custom-gradient-small">Delete</button>
                        </form>
                  </div>
              </li>
                  <?php ++$counter; ?>
            @endforeach
              </ul>
           @else
           <p class="brand-label-text" >No Custom Names</p>
          @endif

        </div>
        </div><!-- form stripe -->
        <div class="display-user-form ">
          <div class="form-stripe-color d-flex flex-column flex-wrap" >
            <form class="bright-background" method="POST" action="/user">
                {{ csrf_field() }}
              <label for="add-name-input" >Enter Name to give an order food:
              <input type="text" id="add-name-input" class="add-name" name="addCustomName" placeholder="Ex: Sally from Accounting" ></label>
              <div id="error-add-name">
              @if ($errors->has('addCustomName'))
                  @include('partials.errors')
              @endif
              </div>
              <button id="add-name-button" class="button-custom-gradient-small rounded">Add Name</button>
            </form>
          </div>
        </div>
        <div class="form-stripe-desat d-flex justify-content-between align-items-center flex-wrap">
          <div class="toggle" >
            <a class="button-custom-gradient-small rounded show" id="addCustomName" data-text="Add Custom Name" href="#" aria-expanded="false">
              Add Custom Name
            </a>
          </div>
      </div>