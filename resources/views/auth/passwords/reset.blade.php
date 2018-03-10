@extends('layouts.guest-auth')

@section('content')
    <div class="heading">
        @lang('Change Password')
    </div>
    <form method="POST" action="{{ route('password.request') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group">
            <label for="email">E-Mail Address</label>
            <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" required autofocus>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input id="password" type="password" class="form-control" name="password" required>
        </div>

        <div class="form-group">
            <label for="password-confirm">Confirm Password</label>
            <input id="password-confirm" type="password" class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" name="password_confirmation" required>
        </div>

        <div class="btnHolder">
            <button type="submit" class="btnGrad">
                @lang('Reset Password')
            </button>
        </div>
    </form>
</div>
@endsection
