<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use GuzzleHttp\Client;



class PostController extends Controller
{
    public function testForm(Request $request){
      if(session('date')){
        $request->merge([
          'date' => session('date'),
          'source' => session('source'),
        ]);

        return $this->test($request);
      }

      return view('test');

    }

    public function test(Request $request){
      if($request->date){
        $date = $request->date;
        $source = $request->source;

      }else{
        $data = $request->validate([
          'source' => 'required|active_url',
        ]);
        $source = $data['source'];

      }

      $client = new Client();

      $sourceUrl = preg_replace('#$[/\s]#', '', $source) . '/wp-json/wp/v2/posts?_fields=title,link&per_page=10';

      if(isset($date)){
        // $sourceUrl .= "&after=$date&before=$date";
        $sourceUrl .= "&after=$date" . "T00:00:00&before=$date" . 'T23:59:59';
      }

      $responseData = $client->request("GET", $sourceUrl);

      $posts = json_decode($responseData->getBody()->getContents(), true);

      $user = Auth::user();

      if($user->urls->pluck('url')->contains($source)){
        $new = false;
      }else{
        $new = true;
      }

      if(count($posts)){
        return view('test', compact('posts', 'source', 'new'));
      }else{
        return redirect(route('test'), 303)->with('status', '投稿が取得できませんでした');
      }
    }
}
