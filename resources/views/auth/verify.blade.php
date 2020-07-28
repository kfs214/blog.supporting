@extends('common')
@section('title', 'メール認証')

@section('content')
  <div class="content">
    <div class="row">
      <h2>メール認証</h2>
      @if (session('resent'))
        <p>
            {{ __('A fresh verification link has been sent to your email address.') }}
        </p>
      @endif

      <p>
        {!! __('Before proceeding, please check your email for a verification link.') !!}
        {{ __('If you did not receive the email,') }}
      </p>
      <form method="POST" action="{{ route('verification.resend') }}">
        @csrf
        <button type="submit">{{ __('click here to request another.') }}</button>
      </form>
    </div>
    <div class="row">
      <p>または</p>
      <button type="button" onClick="location.href='{{ route('discard') }}'">データを破棄して戻る</button>
    </div>
  </div>

@endsection
