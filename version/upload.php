<?php

require __DIR__.'/../bootstrap/autoload.php';

$app = require_once __DIR__.'/../bootstrap/start.php';
$app->register(new Illuminate\Database\DatabaseServiceProvider($app));
$app->boot();

require_once 'Archive/Tar.php';

if (! isset($argv[1])) {
    echo "usage: sudo php -f upload.php [tarFile]\n";
    return;
}

$tmpPath = 'temp/';
if (! file_exists($tmpPath)) {
    mkdir($tmpPath, 0755);
}
$tar = new Archive_Tar($argv[1]);

$tar->extract($tmpPath);

$xml = simplexml_load_file($tmpPath.'download.xml');
$basePath = $xml->getName();
$ftp_root = '/var/daneel/version/';

foreach ($xml->package as $xmlElement) {
    $attr = $xmlElement->attributes();
    $path = $basePath . '/' . $attr['name'] . '/';
    $file = $attr['name'] . '_' . $attr['version'] . '.tar.bz2';

    if (! file_exists($ftp_root.$path)) {
        mkdir($ftp_root.$path, 0755, true);
    }
    copy($tmpPath.$file, $ftp_root.$path.$file);
    $attr['remotepath'] = $path.$file;
}

$ver = (string) $xml['version'];
$versionFile = 'robot/version_' . $ver . '.xml';
$xml->asXML($ftp_root.$versionFile);

$data = [
    'type' => 'robot',
    'number' => $ver,
    'file_path' => $versionFile,
];

$version = Version::where($data)->first();
if ($version == null) {
    Version::create($data);
} else {
    $version->touch();
}

system('rm -rf ' . $tmpPath);
