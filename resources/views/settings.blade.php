@extends('common')
@section('title', '設定')


@section('content')
<div class="content">
  <h2>@yield('title')</h2>
  <h3>投稿設定を追加</h3>
  <div class="content item">
    @if($user->urls->count() && $user->accounts->count())
      <form method="POST">
        @csrf
          <div class="row">
            <select name="url">
              @foreach($user->urls as $url)
                <option value="{{$url}}">{{$url}}</option>
              @endforeach
            </select>
            <button type="submit" name="create" value="true">追加</button>
          </div>

          <div class="row">
            <input type="number" name="number" placeholder="100">
            <select name="unit">
              <option value="days">日</option>
              <option value="weeks">週間</option>
              <option value="months">ヶ月</option>
              <option value="years">年</option>
            </select>
            前の投稿を送信
          </div>

          <div class="row">
            @foreach($user->accounts as $account)
              <label><input type="checkbox" name="enabled" value="{{$account->id}}">{{$account->type}}:{{$account->account}}</label>
            @endforeach
          </div>
        </form>

    @else
      @if($user->urls->count())
        <a href="{{route('settings.accounts')}}" target="_blank">
          <span class="invalid-feedback">
              <strong>先に共有先のアカウントを設定してください。</strong>
          </span>
        </a>
      @else
        <a href="{{route('settings.url')}}" target="_blank">
          <span class="invalid-feedback">
              <strong>先にURLを設定してください。</strong>
          </span>
        </a>
      @endunless
    @endif
  </div>

  <h3>投稿設定を編集</h3>
  @forelse($user->frequencies as $frequency)
    <form method="POST">
      @csrf
      <div class="content item">
        <div class="row">
          <button type="submit" name="update" value="{{$frequency->id}}">更新</button>
          <button type="submit" name="delete" value="{{$frequency->id}}">削除</button>
        </div>

        <div class="row">
          {{$frequency->url}}
        </div>

        <div class="row">
          <input type="number" name="number" value="{{$frequency->number}}">
          <select name="unit">
            <option value="days" {{$frequency->unit == 'days' ? 'selected' : ''}}>日</option>
            <option value="weeks" {{$frequency->unit == 'weeks' ? 'selected' : ''}}>週間</option>
            <option value="months" {{$frequency->unit == 'months' ? 'selected' : ''}}>ヶ月</option>
            <option value="years" {{$frequency->unit == 'years' ? 'selected' : ''}}>年</option>
          </select>
          前の投稿を送信
        </div>

        <div class="row">
          @foreach($user->accounts as $account)
            <label><input type="checkbox" name="enabled" value="{{$account->id}}" {{$frequency->account_ids->contains($account->id) ? 'checked' : ''}}>{{$account->type}}:{{$account->account}}</label>
          @endforeach
        </div>
      </form>

      <div class="row">
        動作確認　
        <form method="POST" action="{{route('test')}}" target="_blank">
          @csrf
          <input type="hidden" value="{{$frequency->url}}">
          <input type="number" name="year" required placeholder="1980">年
          <input type="number" name="month" required placeholder="2">月
          <input type="number" name="day" required placeholder="14">日
          <label>に共有される投稿を<button>確認</button></label>
        </form>
      </div>
    </div>
  @empty
    <div class="row">
      <p>まだ登録されていないようです</p>
    </div>
  @endforelse
</div>
{{-- ここに新規追加の枠。URl一覧を減らしたりもできる --}}
@endsection
