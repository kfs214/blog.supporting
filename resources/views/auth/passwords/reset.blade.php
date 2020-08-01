@extends('common')
@section('title', 'パスワードを再設定')

@section('content')
  <div class="content">
    <h2>@yield('title')</h2>
    <form method="POST" action="{{ route('password.update') }}">
      @csrf
      <input type="hidden" name="token" value="{{ $token }}">

      <div class="row">
        <h3><label for="email">{{ __('E-Mail Address') }}</label></h3>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" {{ $errors->any() ? $errors->has('email') ? 'autofocus' : '' : 'autofocus' }}>
        @error('email')
          <br><span class="invalid-feedback">
              <strong>{{ $message }}</strong>
          </span>
        @enderror
      </div>

      <div class="row">
        <h3><label for="password">{{ __('Password') }}</label></h3>
        <input id="password" type="password" name="password" required autocomplete="new-password" {{ $errors->has('password') ? 'autofocus' : ''}}>
        @error('password')
          <br><span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
          </span>
        @enderror
      </div>

      <div class="row">
        <h3><label for="password-confirm">{{ __('Confirm Password') }}</label></h3>
        <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password">
      </div>

      <div class="row">
          <button type="submit">
              {{ __('Reset Password') }}
          </button>
      </div>
    </form>
@endsection
