<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class PostController extends Controller
{
    public function testForm(){
      return view('test');
    }

    public function test(Request $request){
      $data = $request->validate([
        'source' => 'required|active_url',
      ]);

      $client = new Client();

      $sourceUrl = preg_replace('#$[/\s]#', '', $data['source']) . '/wp-json/wp/v2/posts?_fields=title,link&per_page=10';

      $responseData = $client->request("GET", $sourceUrl);

      $posts = json_decode($responseData->getBody()->getContents(), true);

      if(count($posts)){
        return view('test', compact('posts'));
      }else{
        return view('test')->with('status', '投稿が取得できませんでした');
      }
    }
}
