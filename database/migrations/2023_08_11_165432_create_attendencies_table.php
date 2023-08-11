<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->references('id')->on('users')->cascadeOnDelete();
            $table->foreignId("club_sub")->references("id")->on("club_subscriptions")->cascadeOnDelete();
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
        Schema::dropIfExists('attendencies');
    }
}
