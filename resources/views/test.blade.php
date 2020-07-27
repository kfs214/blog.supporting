@extends('common')
@section('title', 'テスト')


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
      <input type="submit" value="送信">
    </div>
  </form>
</div>

@isset($posts)
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

@endsection
