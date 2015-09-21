<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRobotsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('robots', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('sn')->index();
            $table->string('name')->nullable()->index();
            $table->integer('admin_id')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('admin_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('robots');
    }

}
