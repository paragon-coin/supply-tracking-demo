<div class="heading">
    <a href="{{ route('login') }}" {{ Route::is('login') ? 'class=active' : '' }} >Sign In</a>
    <div class="divider">or</div>
    <a href="{{ route('register') }}" {{ Route::is('register') ? 'class=active' : '' }} >Sign Up</a>
</div>