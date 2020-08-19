<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

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
                  break;

                case 'months':
                  $date->subMonths($frequency_setting->number);
                  break;

                case 'weeks':
                  $date->subWeeks($frequency_setting->number);
                  break;

                case 'days':
                  $date->subDays($frequency_setting->number);
                  break;
              }

              $posts = new PostController;
              $posts = $posts->getPosts($frequency_setting->url, $date->toDateString());

              dd($posts);
          }
        }


    }
}
