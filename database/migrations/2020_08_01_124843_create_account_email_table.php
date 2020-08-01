<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountEmailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_email', function (Blueprint $table) {
            $table->bigInteger('account_id')->unsigned();
            $table->bigInteger('email_id')->unsigned();
            $table->primary(['account_id', 'email_id']);

            $table->foreign('account_id')
              ->references('id')
              ->on('accounts')
              ->onDelete('cascade');

            $table->foreign('email_id')
              ->references('id')
              ->on('emails')
              ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_email');
    }
}
