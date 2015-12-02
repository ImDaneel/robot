<?php

class RobotTableSeeder extends Seeder
{
    public function run()
    {
        $robots = [
            [
                'id' => 1,
                'sn' => '00000001',
                'admin_id' => 1,
                'created_at' => '2015-10-09 15:19:16',
                'updated_at' => '2015-10-09 15:19:16',
            ],
        ];

        foreach($robots as $robot) {
            Robot::create($robot);
        }
    }
}
