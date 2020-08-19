<?php

use Illuminate\Database\Seeder;
use App\Account;
use App\Email;
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
          'posted_count' => 10, 'plan' => 'ultimate',
          'email' => '1@te.st',
        ]);

        factory(App\User::class)->create([
          'posted_count' => 1, 'plan' => 'free',
          'email' => '2@te.st',
        ]);

        factory(App\User::class)->create([
          'posted_count' => 3, 'plan' => 'standard',
          'email' => '3@te.st',
        ]);

        //その他に10人のユーザーを作成
        factory(App\User::class, 10)->create();

        //メルマガ送信先100
        factory(App\Email::class, 100)->create();

        //kfs214.netをサンプルURLに追加
        factory(App\Url::class)->create([
          'user_id' => 2,
          'url' => 'https://kfs214.net',
        ]);

        //取得するURL10
        factory(App\Url::class, 10)->create();

        //メール配信
        factory(App\Account::class)->create([
          'user_id' => 2,
          'type' => 'email', 'account' => 'a'
        ]);

        factory(App\Account::class)->create([
          'user_id' => 2,
          'type' => 'email', 'account' => 'b'
        ]);

        factory(App\Account::class)->create([
          'user_id' => 3,
          'type' => 'email', 'account' => 'a'
        ]);

        factory(App\Account::class)->create([
          'user_id' => 3,
          'type' => 'email', 'account' => 'b'
        ]);

        //SNSアカウントを10件ずつ作成
        factory(App\Account::class, 10)->create([
          'type' => 'facebook',
        ]);

        factory(App\Account::class, 10)->create([
          'type' => 'twitter',
        ]);

        //投稿設定10件、user_id 2番さんだけ
        factory(App\Frequency::class, 10)->create();

        // account_frequency3件、user_id 2番さんだけ
        $account = Account::find(2);

        for($i = 1; $i <= 3; $i++){
          $account->frequencies()->attach($i);
        }

        // account_email3件、user_id 2番さんだけ
        $emails = Email::where('user_id', 2);

        foreach($emails as $key => $email){
          $email->accounts()->attach($key % 2 + 1);
        }

        $emails->first()->accounts()->sync([1, 2]);
    }
}
