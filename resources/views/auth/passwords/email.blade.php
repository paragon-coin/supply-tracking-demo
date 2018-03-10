@extends('layouts.guest-auth')

@section('content')
    <div class="heading">
        @lang('Reset Password')
    </div>
    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="form-group">
            <label for="email">Email</label>
            <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                   name="email" value="{{ old('email') }}" required autofocus>
        </div>

        <div class="btnHolder">
            <button type="submit" class="btnGrad">
                @lang('Send Password Reset Link')
            </button>
        </div>
    </form>
@endsection
