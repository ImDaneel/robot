<?php

class AuthTest extends TestCase
{

    public function testLoginByRobotSn()
    {
        $response = $this->call('POST', 'login', ['robot_sn'=>'00000001']);

        $this->assertTrue($response->isOk());
    }

}
