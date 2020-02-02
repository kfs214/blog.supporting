@if(isset($contents))
  <textarea name="code" cols="100" rows="57">
    @foreach($contents as $content)
<div class="eyecatch"><a href="#{{sprintf('%03d', $loop->iteration)}}"></a></div>
      @if($loop->last)

        <!--more-->


      @endif
    @endforeach
    @foreach($contents as $content)
      @if($loop->first)
<div id="001" class="items first">
      @else
<div id="{{sprintf('%03d', $loop->iteration)}}" class="items">
      @endif
      <p>
      @foreach($content as $row)
        @if(!$loop->last)
  {{$row}}
        @else
</p></div><div class="date">
  <p>{{$year . $row}}</p>
</div>
        @endif
      @endforeach
    @endforeach
  </textarea>
@endif

<form method="post" action="/">
  {{csrf_field()}}
  <textarea name="contents" cols="100" rows="57"></textarea></br>
  <input type="text" placeholder="年" name="year"><input type="submit" value="送信">
</form>
