@extends('common')
@section('title', '投稿取得元のURLを管理')


@section('content')
<h2>@yield('title')</h2>
<h3>その他の設定</h3>
<div class="content">
  <ul>
    <a href="{{route('settings.frequency')}}"><li>投稿設定を管理する</li></a>
    <a href="{{route('settings.account')}}"><li>投稿を共有するアカウントとメールアドレスを管理する</li></a>
  </ul>
</div>

<h3 id="manage-emails">投稿取得元のURLを管理</h3>
@forelse($user->urls()->get(['id', 'url']) as $url)
  <div class="content item">
    <form method="POST">
      @csrf
      <div class="row">
        <button type="submit" name="delete" value="{{$url->id}}">削除</button>
        {{$url->url}}
      </div>
    </form>
  </div>

@empty
  <div class="content">
    <p>まだ投稿取得元のURLが登録されていません。</p>
  </div>

@endforelse

<h3 id="add-emails">投稿取得元のURLを追加</h3>
<div class="content">
  <a href="{{route('test')}}" title="取得テスト">投稿取得元のURLをテストして追加</a>
</div>

@endsection
