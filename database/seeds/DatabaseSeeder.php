<?php

use Illuminate\Database\Seeder;
use App\Frequency;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //自分のアカウントを作成、パスワードは'password'
        factory(App\User::class)->create([
          'posted_count' => 10, 'plan' => 'standard',
          'email' => '1@te.st',
        ]);

        factory(App\User::class)->create([
          'posted_count' => 10, 'plan' => 'free',
          'email' => '2@te.st',
        ]);

        factory(App\User::class)->create([
          'posted_count' => 10, 'plan' => 'standard',
          'email' => '3@te.st',
        ]);

        //自分以外に10人のユーザーを作成
        factory(App\User::class, 10)->create();

        //メルマガ送信先100
        factory(App\Email::class, 100)->create();

        //取得するURL10
        factory(App\Url::class, 10)->create();

        //SNSアカウントを10件ずつ作成
        factory(App\Account::class, 10)->create([
          'type' => 'facebook',
        ]);

        factory(App\Account::class, 10)->create([
          'type' => 'twitter',
        ]);

        //メール配信
        factory(App\Account::class)->create([
          'user_id' => 1,
          'type' => 'email', 'account' => 'a'
        ]);

        factory(App\Account::class)->create([
          'user_id' => 1,
          'type' => 'email', 'account' => 'b'
        ]);

        factory(App\Account::class)->create([
          'user_id' => 2,
          'type' => 'email', 'account' => 'a'
        ]);

        factory(App\Account::class)->create([
          'user_id' => 2,
          'type' => 'email', 'account' => 'b'
        ]);

        //投稿設定10件、user_id 1番さんだけ
        factory(App\Frequency::class, 10)->create();

        // account_frequency3件、user_id 1番さんだけ
        $frequency = Frequency::find(1);

        for($i = 1; $i <= 3; $i++){
          $frequency->accounts()->attach($i);
        }
    }
}
