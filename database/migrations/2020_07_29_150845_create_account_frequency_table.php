<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountFrequencyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_frequency', function (Blueprint $table) {
            $table->bigInteger('account_id')->unsigned();
            $table->bigInteger('frequency_id')->unsigned();
            $table->primary(['account_id', 'frequency_id']);

            $table->foreign('account_id')
              ->references('id')
              ->on('accounts')
              ->onDelete('cascade');

            $table->foreign('frequency_id')
              ->references('id')
              ->on('frequencies')
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
        Schema::dropIfExists('account_frequency');
    }
}
