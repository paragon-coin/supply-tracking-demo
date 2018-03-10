@lang('Please verify your email')

<a href="{{ route('auth.verify', $token) }}">@lang('Verify your email')</a>