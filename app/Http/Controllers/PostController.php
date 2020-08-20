<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use GuzzleHttp\Client;
use Carbon\Carbon;



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
        $source = $request->source;
        $posts = $this->getPosts($source, $request->date);

      }else{
        $data = $request->validate([
          'source' => 'required|active_url',
        ]);
        $source = $data['source'];

        $posts = $this->getPosts($data['source']);

      }

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

    public function getPosts($source, $date = null, $sharing = false){
      $note_domain = 'https://note.com';

      if(preg_match('#' . preg_quote($note_domain) . '/[^/]+#', $source, $matches)){
        $url = $matches[0];

        preg_match('#[^/]+$#', $url, $matches);

        $note_account = $matches[0];

        $sourceUrl = $note_domain . '/api/v2/creators/' . $note_account . '/contents?kind=note';

        $json = file_get_contents($sourceUrl);

        $json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');

        $json = json_decode($json, true);
      //  dd($json);
        foreach($json['data']['contents'] as $key => $content){
          if($date){
            $publishAt = new Carbon($content['publishAt']);
            $publishAt = $publishAt->toDateString();

            if($date != $publishAt){
              continue;
            }
          }

          $data[$key]['link'] = $note_domain . '/' . $note_account . '/n/' . $content['key'];
          $data[$key]['title']['rendered'] = $content['name'];
          $data[$key]['excerpt']['rendered'] = $content['body'];
        }

        return $data;

      }else{
        $client = new Client();

        $sourceUrl = preg_replace('#$[/\s]#', '', $source) . '/wp-json/wp/v2/posts?per_page=10&_fields=title,link';

        if($sharing){
          $sourceUrl .= ',excerpt';
        }

        if($date){
          $sourceUrl .= "&after=$date" . "T00:00:00&before=$date" . 'T23:59:59';
        }

        $responseData = $client->request("GET", $sourceUrl);

        return json_decode($responseData->getBody()->getContents(), true);
      }


    }
}
