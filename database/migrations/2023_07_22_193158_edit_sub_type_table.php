<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class EditSubTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscription_types', function (Blueprint $table) {
            $table->string('duration')->after('name');
        });
        DB::table('subscription_types')->insert([
            'name' => 'Monthly',
            'duration' => '30',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('subscription_types')->insert([
            'name' => 'Weekly',
            'duration' => '7',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('subscription_types')->insert([
            'name' => 'Yearly',
            'name' => '360',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
