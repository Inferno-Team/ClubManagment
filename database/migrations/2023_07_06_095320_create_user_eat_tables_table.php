<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserEatTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_eat_tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references("id")->on('users')->cascadeOnDelete();
            $table->foreignId('table_id')->references("id")->on('eat_tables')->cascadeOnDelete();
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
        Schema::dropIfExists('user_eat_tables');
    }
}
