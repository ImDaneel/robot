<?php

class UserTableSeeder extends Seeder {

    public function run()
    {
        $users = [
            [
                'id' => 1,
                'is_admin' => true,
                'app_type' => 'ios',
                'created_at' => '2015-10-09 15:19:16',
                'updated_at' => '2015-10-09 15:19:16',
            ],
            [
                'id' => 2,
                'is_admin' => false,
                'app_type' => 'ios',
                'phone' => '13912345678',
                'password' => md5('1234'),
                'created_at' => '2015-10-10 11:02:18',
                'updated_at' => '2015-11-17 20:10:19',
            ]
        ];

        foreach($users as $user) {
            User::create($user);
            Robot::find(1)->users()->attach($user['id']);
        }
    }

}
