<nav class="navbar navbar-inverse navbar-toggleable-sm" role="navigation">    
    <a class="navbar-brand" id="logo-container" href="/"><img width="100px" height="69px" src="{{ asset('images/logo.svg') }}" alt="a white mink with one paw and its chin placed on a large, tasty sandwich"><span class="logo-text"> Lunch Mink</span></a>
    <div class="navbar-toggler navbar-toggler-right header" data-target="#exCollapsingNavbar"  aria-controls="#exCollapsingNavbar" aria-expanded="false" aria-label="Toggle Navigation">
    &#9776;
    </div>
    <div class="collapse navbar-collapse" id="exCollapsingNavbar" >
        <ul class="navbar-nav nav-fill">
        <!-- Restaurant Info Links -->
            <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="/ourmenu">Lunch Menu</a></li>
            <?php //Session::flush(); ?>
            @if(!Session::has('order_started') && Session::get('closed') == FALSE)
            <li class="nav-item"><a class="nav-link" href="/onlineorder">Order Online</a></li>
            @endif
            <li class="nav-item"><a class="nav-link" href="/deliverylocation">Delivery Area</a></li>
        <!-- Authentication Links -->
            @if (Auth::guest())
                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Create Account</a></li>
            @else
            <li class="nav-item"><a class="nav-link" href="/account">Account</a></li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('logout') }}"
                 onclick = "event.preventDefault();
                 document.getElementById('logout-form').submit();" >
                    Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </li>
            @endif
            <?php //session()->forget('order_started');
                   // dd(session()->forget('order_started'));
             ?>
            @if(Session::has('order_started'))

            <li class="nav-item">
                <a class="nav-link" href="/vieworder">
                    View Order
                </a>
            </li>
            @endif

        </ul>
    </div><!-- end collapsing nav -->
</nav>




