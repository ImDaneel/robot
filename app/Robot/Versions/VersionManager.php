<?php namespace Robot\Versions;

use App;

abstract class VersionManager
{
    public static function make($data)
    {
        App::make('Robot\Validators\VersionValidator')->validate($data);
        $class = __namespace__ . '\\' . ucfirst($data['type']) . 'Version';
        return new $class;
    }

    abstract public function pushLatest();
}
