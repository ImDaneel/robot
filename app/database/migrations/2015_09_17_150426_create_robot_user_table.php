<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRobotUserTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Creates the robot_user (Many-to-Many relation) table
        Schema::create('robot_user', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('robot_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->foreign('robot_id')->references('id')->on('robots')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('robot_user');
    }

}
