@extends('common')
@section('title', '投稿設定を管理する')


@section('content')
  <h2>@yield('title')</h2>
  <h3>その他の設定</h3>
  <div class="content">
    <ul>
      <a href="{{route('settings.url')}}"><li>投稿を取得するURLを管理する</li></a>
      <a href="{{route('settings.account')}}"><li>投稿を共有するアカウントとメールアドレスを管理する</li></a>
    </ul>
  </div>

  <h3>投稿設定を追加</h3>
  @if($user->urls->count() && $user->accounts->count())
    <div class="content item">
      <form method="POST">
        @csrf
          <div class="row">
            <select name="url_id">
              @foreach($user->urls as $url)
                <option value="{{$url->id}}" {{old('url_id') == $url->id ? 'selected' : ''}}>{{$url->url}}</option>
              @endforeach
            </select>
            <button type="submit" name="create" value="true">追加</button>

          </div>

          @error('url_id')
            <div class="row">
                <span class="invalid-feedback">
                  {{$message}}
                </span>
            </div>
          @enderror

          <div class="row">
            <input type="number" name="number" placeholder="100" required value="{{old('number')}}">
            <select name="unit">
              <option value="years" {{old('unit') == 'years' ? 'selected' : ''}}>年</option>
              <option value="months" {{old('unit') == 'months' ? 'selected' : ''}}>ヶ月</option>
              <option value="weeks" {{old('unit') == 'weeks' ? 'selected' : ''}}>週間</option>
              <option value="days" {{old('unit') == 'days' ? 'selected' : ''}}>日</option>
            </select>
            前の投稿を送信
          </div>

          @error('number')
            <div class="row">
              <span class="invalid-feedback">
                {{$message}}
              </span>
            </div>
          @enderror

          @error('unit')
            <div class="row">
              <span class="invalid-feedback">
                {{$message}}
              </span>
            </div>
          @enderror

          <div class="row">
            @foreach($user->accounts as $account)
              <label><input type="checkbox" name="enabled[]" value="{{$account->id}}">{{$account->type}}/{{$account->account}}</label>
            @endforeach
          </div>

          @error('enabled')
            <div class="row">
              <span class="invalid-feedback">
                {{$message}}
              </span>
            </div>
          @enderror
        </form>
      </div>
    @else
      <div class="content">
        @if(!$user->urls->count())
          <div class="row">
            <a href="{{route('settings.url')}}">
              <span class="invalid-feedback">
                  <strong>先にURLを設定してください。</strong>
              </span>
            </a>
          </div>
        @endif
        @if(!$user->accounts->count())
          <div class="row">
            <a href="{{route('settings.account')}}">
              <span class="invalid-feedback">
                  <strong>先に共有先のアカウントを設定してください。</strong>
              </span>
            </a>
          </div>
        @endif
      </div>
    @endif

  <h3>投稿設定を編集</h3>
  @forelse($user->frequencies as $key => $frequency)
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
          <input type="number" name="numbers[{{$key}}]" value="{{$frequency->number}}" {{$errors->has("enabled_accounts.$key") ? 'autofocus' : ''}}>
          <select name="unit">
            <option value="years" {{$frequency->unit == 'years' ? 'selected' : ''}}>年</option>
            <option value="months" {{$frequency->unit == 'months' ? 'selected' : ''}}>ヶ月</option>
            <option value="weeks" {{$frequency->unit == 'weeks' ? 'selected' : ''}}>週間</option>
            <option value="days" {{$frequency->unit == 'days' ? 'selected' : ''}}>日</option>
          </select>
          前の投稿を送信
        </div>

        <div class="row">
          <input type="hidden" name="enabled_accounts[{{$key}}]" value="">
          @foreach($user->accounts as $account)
            <label><input type="checkbox" name="enabled_accounts[{{$key}}][]" value="{{$account->id}}" {{$frequency->account_ids->contains($account->id) ? 'checked' : ''}}>{{$account->type}}:{{$account->account}}</label>
          @endforeach
        </div>

        @error("enabled_accounts.$key")
          <div class="row">
            <span class="invalid-feedback">
              {{$message}}
            </span>
          </div>
        @enderror

      </form>

      <div class="row">
        動作確認（先に設定を更新してください）
        <form method="POST" target="_blank">
          @csrf
          <input type="hidden" name="frequency_id" value="{{$frequency->id}}">
          <input type="number" name="year" required placeholder="1980" {{$errors->has('date') ? 'autofocus' : ''}} value="{{old('year', \Carbon\Carbon::now()->year)}}">年
          <input type="number" name="month" required placeholder="2" value="{{old('month', \Carbon\Carbon::now()->month)}}">月
          <input type="number" name="day" required placeholder="14" value="{{old('day', \Carbon\Carbon::now()->day)}}">日
          <label>に共有される投稿を<button name="source" value="{{$frequency->url}}">確認</button></label>
        </form>
      </div>

      @error("date")
        <div class="row">
          <span class="invalid-feedback">
            {{$message}}
          </span>
        </div>
      @enderror

    </div>
  @empty
    <div class="row">
      <p>まだ登録されていないようです</p>
    </div>
  @endforelse

@endsection
