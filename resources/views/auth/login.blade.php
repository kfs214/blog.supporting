@extends('common')
@section('title', 'ログイン')

@section('content')
  <h2>{{ __('Login') }}</h2>

  <div class="row">
    <a href="{{route('register')}}">新規登録はこちらから</a>
  </div>

  <form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="row">
      <h3><label for="email">{{ __('E-Mail Address') }}</label></h3>
      <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
      @error('email')
          <br><span class="invalid-feedback">
              {{ $message }}
          </span>
      @enderror
    </div>

    <div class="row">
      <h3><label for="password">{{ __('Password') }}</label></h3>
      <input id="password" type="password" name="password" required autocomplete="current-password">
      @error('password')
          <br><span class="invalid-feedback">
              {{ $message }}
          </span>
      @enderror
    </div>

    <div class="row">
      <h3><label for="remember"></label></h3>
        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            {{ __('Remember Me') }}
    </div>

    <button type="submit">
        {{ __('Login') }}
    </button>

    @if (Route::has('password.request'))
        <a href="{{ route('password.request') }}">
            {{ __('Forgot Your Password?') }}
        </a>
    @endif
  </form>
</div>
@endsection
