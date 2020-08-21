<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Abraham\TwitterOAuth\TwitterOAuth;

use App\Http\Controllers\PostController;

use App\Frequency;
use App\Url;
use App\User;

class ShareCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'share';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Search posts and share them';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users_id = User::where('plan', 'ultimate')
          ->orWhere(function($query){
            $query->where('plan', 'standard')->where('posted_count', '<', 4);
          })->orWhere(function($query){
            $query->where('plan', 'free')->where('posted_count', 0);
          })->pluck('id');

        if(config('app.env') != 'production'){
          $users_id = [1];
        }

        //その日更新された記事
        foreach($users_id as $user_id){
          $urls = Url::where('user_id', $user_id)->get(['url'])->pluck('url');

          foreach($urls as $url){
            $posts = new PostController;
            $date = Carbon::yesterday();
            $posts = $posts->getPosts($url, $date->toDateString(), true);

            foreach($posts as $post){
              $body = config('hajizome.note') . "\n\n" .
                $post['title']['rendered'] . "\n" .
                $post['excerpt']['rendered'];

              $body = preg_replace('/<.*?>/', '', $body);

              $this->shareLine($body . "\n" . $post['link']);
            }
          }

          $user = User::find($user_id);
          $user->posted_count++;
          $user->save();
        }

        // 設定に基づく共有
        $frequency_settings = Frequency::whereIn('user_id', $users_id)->get();

        if($frequency_settings->count()){
          foreach($frequency_settings as $frequency_setting){
              $date = Carbon::today();

              switch($frequency_setting->unit){
                case 'years':
                  $date->subYears($frequency_setting->number);
                  $unit = '年';
                  break;

                case 'months':
                  $date->subMonths($frequency_setting->number);
                  $unit = 'ヶ月';
                  break;

                case 'weeks':
                  $date->subWeeks($frequency_setting->number);
                  $unit = '週間';
                  break;

                case 'days':
                  $date->subDays($frequency_setting->number);
                  $unit = '日';
                  break;
              }

              $posts = new PostController;
              $posts = $posts->getPosts($frequency_setting->url, $date->toDateString(), true);

              foreach($posts as $post){
                $body = $frequency_setting->number . $unit . "前の投稿\n" .
                  config('hajizome.note') . "\n\n" .
                  $post['title']['rendered'] . "\n" .
                  $post['excerpt']['rendered'];

                $body = preg_replace('/<.*?>/', '', $body);

                // 文字数無限シェア
                $this->shareLine($body . "\n" . $post['link']);

                // twitterシェア
                $this->shareTwitter($body, $post['link']);

              }

              $user = User::find($frequency_setting->user_id);
              $user->posted_count++;
              $user->save();
          }
        }


    }


    // twitterシェア
    public function shareTwitter($body, $link){
      $content_len = config('twitter.post_len') - config('twitter.url_len');

      if(mb_strlen($body) > $content_len){
        $body = mb_substr($body, 0, $content_len - 1) . '…';
      }

      $text = "$body\n$link";

      if(config('app.env') == 'production'){
        $connect = new TwitterOAuth(config('twitter.ck'), config('twitter.cs'), config('twitter.at'), config('twitter.as'));

        $result = $connect->post(
          'statuses/update',
          ['status' => $text]
        );

        if($connect->getLastHttpCode() == 200){
          echo "tweeted\n";
        }else{
          echo "tweet failed\n";
        }
      }else{
        echo "Twitter ==============================\n" . $text . "\n";
      }
    }


    // LINE at シェア
    public function shareLine($text){
      // HTTPヘッダを設定
      $channelToken = config('line.access_token');
      $headers = [
      	'Authorization: Bearer ' . $channelToken,
      	'Content-Type: application/json; charset=utf-8',
      ];

      // POSTデータを設定してJSONにエンコード
      $post = [
      	'messages' => [
      		[
      			'type' => 'text',
      			'text' => $text,
      		],
      	],
      ];

      if(config('app.env') != 'production'){
        $post['to'] = config('line.user_id');
      }

      if(config('app.env') != 'production'){
        echo "LINE ==================================\n";
        var_dump($post);
        // return 0;
      }

      $post = json_encode($post);

      // HTTPリクエストを設定
      $ch = curl_init('https://api.line.me/v2/bot/message/push');
      $options = [
      	CURLOPT_CUSTOMREQUEST => 'POST',
      	CURLOPT_HTTPHEADER => $headers,
      	CURLOPT_RETURNTRANSFER => true,
      	CURLOPT_BINARYTRANSFER => true,
      	CURLOPT_HEADER => true,
      	CURLOPT_POSTFIELDS => $post,
      ];
      curl_setopt_array($ch, $options);

      // 実行
      $result = curl_exec($ch);

      // エラーチェック
      $errno = curl_errno($ch);
      if ($errno) {
        echo 'error! :' . $errorno . "\n";
      	return;
      }

      // HTTPステータスを取得
      $info = curl_getinfo($ch);
      $httpStatus = $info['http_code'];

      $responseHeaderSize = $info['header_size'];
      $body = substr($result, $responseHeaderSize);

      // 200 だったら OK
      echo 'line:' . $httpStatus . ' ' . $body;
    }
}
