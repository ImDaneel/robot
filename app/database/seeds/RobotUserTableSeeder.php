<?php

class RobotUserTableSeeder extends Seeder
{
    public function run()
    {
        $robot_users = [
            [
                'id' => 1,
                'robot_id' => 1,
                'user_id' => 1,
            ],
            [
                'id' => 2,
                'robot_id' => 1,
                'user_id' => 2,
            ],
        ];

        foreach($robot_users as $r) {
            Robot::create($r);
        }
    }
}
