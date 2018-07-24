<div class="container">
  <header id="header-guide" class="layout">
     <div class="welcome-tagline">
      @if (Auth::guest())
          <div id="header-name" class="user-not-signed-in">
           Welcome, Guest
          </div>
      @else
           <div id="header-name" class="user-signed-in">
            Hello, {{ Auth::user()->name }}
           </div>
      @endif
            <div id="header-message" class="tagline">
            @yield('tagline message')
            </div>
      </div>
  </header>
</div>

