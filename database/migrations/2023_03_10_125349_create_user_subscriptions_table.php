<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()
                ->references('id')->on('users')->nullOnDelete();
            $table->foreignId('subscription_id')
                ->references('id')->on('club_subscriptions')->cascadeOnDelete();
            $table->date('start_at')->nullable();
            $table->double('price')->nullable();
            $table->date('end_at')->nullable();
            $table->enum("status", ["pending", "approved", "denied"])->default("pending");
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
        Schema::dropIfExists('user_subscriptions');
    }
}
