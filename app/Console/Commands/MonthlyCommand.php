<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;

class MonthlyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monthly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset posted_count every month.';

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
      $user = new User;
      $user->whereIn('plan', ['free', 'standard'])->update([
        'posted_count' => 0,
        'updated_at' => now(),
      ]);
    }
}
