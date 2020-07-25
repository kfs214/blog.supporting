@extends('common')
@section('title', '下書きを加工')

@section('content')
@if(isset($code))
  <h2>加工された下書き</h2>
  <div class="content">
    <textarea name="code">{{trim($code)}}</textarea>
  </div>
@endif

<div class="content">
  <form method="post">
    @csrf
    <div class="row">
      <h2>下書きを加工</h2>
      <textarea name="contents" placeholder="下書き" required>{{session('contents')}}</textarea>
    </div>
    <div class="row">
      <input type="text" placeholder="平成7年" name="year" value="{{session('year')}}">
    </div>
    <div class="row">
      <input type="submit" value="送信">
    </div>
  </form>
</div>

<div class="content">
  <h2>使い方</h2>
  <p>
    1行目：ショートカット<br>
    2行目：空白行<br>
    3行目〜：本文<br>
    最終行：日付<br>
    <br>
    空白行1行で&lt;p>タグ<br>
    空白行2行で次のコンテンツ<br>
    <br>
    #5 -> &lt;h5>タグ<br>
    #6 -> &lt;h6>タグ<br>
  </p>
  <img src="{{ asset('/links/eyecatch-example.png') }}" alt="ショートカットとは"　title="ショートカットとは">
  <img src="{{ asset('/links/date-example.png') }}" alt="日付とは"　title="日付とは">
</div>
@endsection
