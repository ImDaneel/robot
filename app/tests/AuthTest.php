<?php

use Robot\Utils\Sms;

class AuthTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->seed();
    }

    public function testLoginByRobotSn()
    {
        $this->post('login', ['robot_sn' => '00000001'])
            ->seeJson([
                'code' => 'success',
            ]);
    }

    public function testLoginByPassword()
    {
        $this->post('login', ['phone' => '13912345678', 'password' => md5('1234')])
            ->seeJson([
                'code' => 'success',
            ]);
    }

    public function testLoginByPasswordWithErrorPassword()
    {
        $this->post('login', ['phone' => '13912345678', 'password' => md5('errorpassword')])
            ->seeJson([
                'code' => 'error',
                'message' => ['errors' => 'Wrong username or password.'],
            ]);
    }

    public function testLoginByVerifyCode()
    {
        $phone = '13912345678';
        $verifyCode = $this->generateVerifyCode($phone);

        $this->post('login', ['phone' => $phone, 'verify_code' => $verifyCode])
            ->seeJson([
                'code' => 'success',
            ]);
    }

    public function testLoginByVerifyCodeWithErrorVerifyCode()
    {
        $phone = '13912345678';
        $verifyCode = $this->generateVerifyCode($phone);

        $this->post('login', ['phone' => $phone, 'verify_code' => '0999'])
            ->seeJson([
                'code' => 'error',
                'message' => ['errors' => 'verify code error'],
            ]);
    }

    public function testLoginByVerifyCodeWhileNotExist()
    {
        $phone = '18601234567';
        $verifyCode = $this->generateVerifyCode($phone);

        $this->post('login', ['phone' => $phone, 'verify_code' => $verifyCode])
            ->seeJson([
                'code' => 'success',
            ])
            ->seeInDatabase('users', [
                'is_admin' => false,
                'phone' => '18601234567',
            ]);
    }

    public function testRegister()
    {
        $this->post('register', ['app_type' => 'ios', 'phone' => '18601234567', 'password' => md5('123456')])
            ->seeJson([
                'code' => 'success',
            ])
            ->seeInDatabase('users', [
                'is_admin' => false,
                'app_type' => 'ios',
                'phone' => '18601234567',
                'password' => md5('123456'),
            ]);
    }

    public function testLogout()
    {
        $this->get('logout')
            ->seeJson([
                'code' => 'success',
            ]);
    }

    public function testInitialize()
    {
        $this->post('initialize', ['app_type' => 'android', 'robot_sn' => '00000002'])
            ->seeJson([
                'code' => 'success',
            ])
            ->seeInDatabase('robots', [
                'id' => 2,
                'sn' => '00000002',
                'admin_id' => 3,
            ])
            ->seeInDatabase('users', [
                'id' => 3,
                'is_admin' => true,
                'app_type' => 'android',
                'phone' => null,
            ])
            ->seeInDatabase('robot_user', [
                'robot_id' => 2,
                'user_id' => 3,
            ]);
    }

/*
    public function testSendVerifyCode()
    {
        $mock = \Mockery::mock('Robot\Utils\Sms');
        $mock->shouldReceive('send')
            ->once()
            ->andReturn('{"statusCode":"000000"}');

        $this->app->instance('Sms', $mock);

        $this->get('send-verify-code', ['phone' => '13912345678'])
            ->seeJson([
                'code' => 'success',
            ])
            ->seeInDatabase('verify_codes', [
                'phone' => '13912345678',
            ]);
    }
*/

    public function testAuthenticate()
    {
        $phone = '18601234567';
        $verifyCode = $this->generateVerifyCode($phone);

        $this->post('auth', ['phone' => $phone, 'verify_code' => $verifyCode, 'robot_sn' => '00000001'])
            ->seeJson([
                'code' => 'success',
            ])
            ->seeInDatabase('auth_requests', [
                'phone' => $phone,
                'robot_sn' => '00000001',
            ]);

    }

    protected function generateVerifyCode($phone)
    {
        $rand = rand(1000, 9999);

        DB::table('verify_codes')->insert([
            'phone' => $phone,
            'code' => $rand,
            'created_at' => time(),
        ]);

        return $rand;
    }
}
