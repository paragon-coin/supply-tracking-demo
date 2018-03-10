@extends('layouts.guest-auth')

@section('content')
    @include('auth._partials.tabs')
    <form method="POST" action="">
        @csrf
        <div class="form-group">
            <label for="email">Email</label>
            <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                   name="email" value="{{ old('email') }}" required autofocus>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                   name="password" required>
        </div>

        <div class="checkbox">
            <label>
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
            </label>
        </div>

        <div class="btnHolder">
            <button type="submit" class="btnGrad">
                Login
            </button>

            <a href="{{ route('password.request') }}">
                @lang('Forgot Your Password?')
            </a>
        </div>
    </form>
@endsection
