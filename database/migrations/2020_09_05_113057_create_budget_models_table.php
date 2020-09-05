<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBudgetModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget', function (Blueprint $table) {
            $table->increments('budget_id')->unique();
            $table->integer('user_id');
            $table->float('food');
            $table->float('clothes');
            $table->float('housing');
            $table->float('transport');
            $table->float('goals');
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
        Schema::dropIfExists('budget');
    }
}
