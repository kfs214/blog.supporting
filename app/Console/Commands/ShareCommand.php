<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Abraham\TwitterOAuth\TwitterOAuth;

use App\Http\Controllers\PostController;

use App\Frequency;
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
                $text = $frequency_setting->number . $unit . "前の投稿\n" .
                  config('hajizome.note') . "\n\n" .
                  $post['title']['rendered'] . "\n" .
                  $post['excerpt']['rendered'] . "\n";

                $text = preg_replace('/<.*?>/', '', $text);

                // 文字数無限シェア

                // twitterシェア
                $content_len = config('twitter.post_len') - config('twitter.url_len');

                if(mb_strlen($text) > $content_len){
                  $text = mb_substr($text, 0, $content_len - 1) . '…';
                }

                $text .= $post['link'];

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
              }

              $user = User::find($frequency_setting->user_id);
              $user->posted_count++;
              $user->save();
          }
        }


    }
}
