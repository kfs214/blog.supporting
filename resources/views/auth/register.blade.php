@extends('common')
@section('title', '新規登録')

@section('content')
  <div class="content">
    <h2>{{ __('Register') }}</h2>

    <form method="POST">
      @csrf
      <div class="row">
        <h3><label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label></h3>
        <input id="email" type="text" name="email" value="{{ old('email') }}" required autocomplete="email" {{ $errors->has('email') ? 'autofocus' : ''}}>
        @error('email')
            <br><span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
      </div>

      <div class="row">
        <h3><label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label></h3>
        <input id="password" type="password" name="password" required autocomplete="new-password" {{ $errors->has('password') ? 'autofocus' : ''}}>
        @error('password')
            <br><span class="invalid-feedback">
                {{ $message }}
            </span>
        @enderror
      </div>

      <div class="row">
        <h3><label for="password-confirm">{{ __('Confirm Password') }}</label></h3>
        <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password" {{ $errors->has('password_confirm') ? 'autofocus' : ''}}>
      </div>

      <div class="row">
        <button type="submit" class="btn btn-primary">
            {{ __('Register') }}
        </button>
      </div>
    </div>
  </div>
@endsection
