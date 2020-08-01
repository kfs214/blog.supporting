@extends('common')
@section('title', '共有先アカウントの管理')


@section('content')
  <h2>@yield('title')</h2>
  <h3>その他の設定</h3>
  <div class="content">
    <ul>
      <a href="{{route('settings.frequency')}}"><li>投稿設定を管理する</li></a>
      <a href="{{route('settings.email')}}"><li>投稿を共有するメールアドレスを管理する</li></a>
    </ul>
  </div>

  <h3>共有先アカウントの管理</h3>
  @forelse($user->accounts as $account)
    <form method="POST">
      @csrf
      <div class="content item">
          <button type="submit" name="delete" value="{{$account->id}}">削除</button>
          {{$account->type}}/{{$account->account}}
      </div>
    </form>

  @empty
    <div class="row">
      <p>まだ登録されていないようです</p>
    </div>
  @endforelse
</div>
{{-- ここに新規追加の枠。 --}}
@endsection
