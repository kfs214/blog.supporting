<html>
<!-- code name: HAjizome/ #D9A62E -->
{{-- h2 font-size: 1.5em; h3 font-size: 1.17em; --}}

<head>
  <title>@yield('title')|{{ config('app.name') }}</title>
  <link href="{{ asset('/links/common.css') }}" rel="stylesheet">
  <link href="{{ asset('/links/hajizome.css') }}" rel="stylesheet">
  <link rel="icon" href="{{ asset('/links/favicon.ico') }}">
  <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
</head>

<body>
  <div class="header theme flex-container">
    <a class="title" href="{{ route('tags') }}">{{ config('app.name') }}</a>
    <ul class="flex-container">
      <a href="{{ route('tags') }}"><li>下書きを加工</li></a>
      <a href="{{ route('test') }}"><li>テスト</li></a>
    </ul>
  </div>

  <div class="container">
    @yield('content')
  </div>

  <div class="footer">
      <div class="container">
        <p>
          {{ __('Please use this application at your own risk.') }}<br>
          {{ __('Send feedback:') }}<a href="https://kfs214.net/articles/425#006" target="_blank">kfs214</a>
        </p>
      </div>
  </div>

  @if(session('status'))
    <script>
        alert('{{session('status')}}');
    </script>
  @endif

  @if($errors->any())
    <script>
        alert('入力に誤りがあります');
    </script>
  @endif

</body>
</html>
