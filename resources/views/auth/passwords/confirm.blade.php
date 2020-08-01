@extends('common')
@section('title', __('Confirm Password'))

@section('content')
  <div class="content">
    <h2>@yield('title')</h2>
    <p>{{ __('Please confirm your password before continuing.') }}</p>

    <form method="POST" action="{{ route('password.confirm') }}">
      @csrf

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
        <button type="submit">
            {{ __('Confirm Password') }}
        </button>

        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}">
                {{ __('Forgot Your Password?') }}
            </a>
        @endif
      </div>
    </form>
  </div>
@endsection
