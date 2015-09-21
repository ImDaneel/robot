<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function(Blueprint $table)
        {
            $table->increments('id');
            $table->boolean('is_admin')->default(false);
            $table->string('app_type');
            $table->string('phone')->nullable()->index();
            $table->string('password')->nullable();
            $table->string('remember_token')->nullable();
            $table->string('nick_name')->nullable()->index();
            $table->string('avatar')->nullable();
            $table->string('standby_phone')->nullable();
            $table->timestamps();
            $table->softDeletes(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }

}
