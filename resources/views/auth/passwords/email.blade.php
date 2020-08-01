@extends('common')
@section('title', __('Reset Password'))

@section('content')
  <div class="content">
    <h2>@yield('title')</h2>
    @if (session('status'))
      {{ session('status') }}
    @endif

    <form method="POST" action="{{ route('password.email') }}">
      @csrf

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
        <button type="submit">
            {{ __('Send Password Reset Link') }}
        </button>
      </div>
    </form>
  </div>
@endsection
