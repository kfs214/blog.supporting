@extends('common')
@section('title', '共有先メールアドレスの管理')


@section('content')
<h2>@yield('title')</h2>

<ul>
  <a href="#make-group"><li>グループの追加</li></a>
  <a href="#manage-emails"><li>メールアドレスの管理</li></a>
  <a href="add-emails"><li>メールアドレスの追加</li></a>
</ul>
<a href="{{route('settings.frequency')}}">投稿設定を管理する</a>

<h3 id="make-group">グループの追加</h3>
<div class="content">
  <p>グループを追加することで、グループごとに異なる設定で投稿を共有できます</p>
  @if($user->plan == 'free')
    <a href="route('settings.plan')">有料プランに申し込んでこの機能を利用する</a>
  @else
    <form method="POST">
      @csrf
      <input type="text" name="add">
      <button>グループを追加する</button>
  @endif
</div>

<h3 id="manage-emails">メールアドレスの管理</h3>
@forelse($user->emails()->get(['id', 'email']) as $email)
  <div class="content item">
    <form method="POST">
      @csrf
      <input type="hidden" value="{{$email->email}}">
      <div class="row">
        <button type="submit" name="update" value="{{$email->id}}">更新</button>
        <button type="submit" name="delete" value="{{$email->id}}">削除</button>
      </div>

      <div class="row">
        {{$email->email}}
        @foreach($user->groups as $group)
          <label><input type="checkbox" name="belongs" value="{{$group}}"{{$email->groups->contains($group) ? ' checked' : ''}}>{{$group}}</label>
        @endforeach
      </div>
    </form>
  </div>

@empty
  <div class="content">
    <p>まだ共有先のメールアドレスが登録されていません。</p>
  </div>

@endforelse

<h3 id="add-emails">メールアドレスの追加</h3>
<div class="content">
  <form method="post">
    @csrf
    @for($i = 0; $i < 4; $i++)
      <div class="row">
        <label>メールアドレス<input type="email" name="emails[]"></label>
        <select name="groups[]">
          @forelse($user->groups as $group)
            <option value="{{$group}}">{{$group}}</option>
          @empty
            <option value="default">default</option>
          @endforelse
        </select>
      </div>
    @endfor
    <button>追加する</button>
  </form>
</div>

@endsection
