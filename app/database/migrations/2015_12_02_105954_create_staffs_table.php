<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staffs', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name')->index();
            $table->string('password');
            $table->string('remember_token')->nullable();
            $table->boolean('is_banned')->default(false)->index();
            $table->string('real_name')->nullable()->index();
            $table->string('email')->nullable()->index();
            $table->string('image_url')->nullable();
            $table->softDeletes(); 
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
        Schema::drop('staffs');
    }

}
