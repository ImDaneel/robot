<?php

class DatabaseSeeder extends Seeder
{
    protected $tables = [
        'robot_user',
        'users',
        'robots',
    ];

    protected $seeders = [
        'RobotTableSeeder',
        'UserTableSeeder',
    ];

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

        $this->cleanDatabase();

        foreach ($this->seeders as $seedClass) {
            $this->call($seedClass);
        }
	}

    public function cleanDatabase()
    {
        //DB::statement('SET FOREIGN_KEY_CHECKS=0');
        foreach ($this->tables as $table) {
            DB::table($table)->truncate();
        }
        //DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
