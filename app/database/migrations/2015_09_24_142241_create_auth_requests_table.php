<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthRequestsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auth_requests', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('phone')->index();
            $table->string('robot_sn')->index();
            $table->string('token')->index();
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('auth_requests');
    }

}
