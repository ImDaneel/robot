<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCleanReportsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('clean_reports', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('robot_id')->index();
			$table->datetime('clean_time');
            $table->decimal('area', 10, 2);
            $table->integer('duration');
            $table->decimal('percent', 5, 2)->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('clean_reports');
	}

}
