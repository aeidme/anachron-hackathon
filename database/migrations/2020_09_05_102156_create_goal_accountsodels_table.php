<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoalAccountsodelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goal_accounts', function (Blueprint $table) {
            $table->increments('goal_acc_id')->unique();
            $table->integer('user_id');
            $table->string('goal_name');
            $table->float('target_amount');
            $table->float('current_amount');
            $table->date('deadline');
            $table->string('importance');
            $table->float('chances');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goal_accounts');
    }
}
