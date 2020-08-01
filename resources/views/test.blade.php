@extends('common')
@section('title', '取得テストと取得元の管理')


@section('content')
<div class="content">
  <form method="post">
    @csrf
    <div class="row">
      <h2>投稿の取得をテスト</h2>
      <label>ソースURL
        <input type="url" name="source" value="{{old('source', session('source', 'https://'))}}" required></label>
        @error('source')
          <span class="invalid-feedback">{{$message}}</span>
        @enderror
    </div>
    <div class="row">
      <button>取得テスト</button>
    </div>
  </form>
</div>

@isset($posts)
  @auth
    <div class="content">
      <form method="POST" action="{{route('settings.url')}}">
        @csrf
        <button name="add" value="{{$source}}">このサイトを取得元に追加する</button>
      </form>
    </div>
  @endauth

  <div class="content">
    <h2>取得された投稿</h2>
    @foreach($posts as $post)
      @if($loop->index == 10)
         <p>ほか{{$loop->remaining + 1}}件</p>
         @break
      @endif
      <a href="{{$post['link']}}" target="_blank" title="記事を開く">{{$post['title']['rendered']}}</a><br>
    @endforeach
  </div>
@endisset

<a href="{{route('settings.frequency')}}">投稿設定を管理する</a>

@endsection
