<?php namespace Robot\Versions;

use Version;
use Robot;
use Config;
use Robot\Utils\PushService;

class RobotVersion extends VersionManager
{
    public function pushLatest()
    {
        try {
            $version = Version::getLatest('robot');
        } catch (\Exception $e) {
            return 'failed';
        }

        $content = [
            'ftp_host' => Config::get('ftp.host'),
            'ftp_username' => Config::get('ftp.username'),
            'ftp_password' => Config::get('ftp.password'),
            'version_file' => $version->file_path,
        ];

        $robots = Robot::all();
        foreach ($robots as $robot) {
            $ret = PushService::push('Version', $robot->sn, $content, false);
        }

        return 'success';
    }
}
