<?php

use Ramsey\Uuid\Uuid;
use Spatie\Browsershot\Browsershot;

require_once __DIR__.'/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/..');
$dotenv->load();

$currentIp = $_SERVER['REMOTE_ADDR'];
$whitelist = explode(',', getenv('ALLOWED_IPS'));

if (! in_array($currentIp, $whitelist)) {
    die('Access not allowed');
}

if ($url = $_REQUEST['url'] ?? false) {
    $file = 'pdf/'.Uuid::uuid4().'.pdf';
    $browser = Browsershot::url($url);
    if ($path = getenv('NODE_PATH')) {
        $browser->setIncludePath($path);
    }
    
    try {
        $browser
            ->noSandbox()
            ->waitUntilNetworkIdle($_REQUEST['strict'] ?? 1)
            ->windowSize(1200, 900)
            ->showBrowserHeaderAndFooter()
            ->delay($_REQUEST['delay'] ?? 3000)
            ->margins(12, 5, 12, 5)
            ->pages($_REQUEST['pages'] ?? '')
            ->format($_REQUEST['size'] ?? 'A4')
            ->save('./'.$file);

        if (file_exists(__DIR__.'/'.$file)) {
            header('Location:/'.$file);
            die();
        }
    } catch (\Exception $e) {}

    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    die('500 Internal Server Error');
}

die('no URL provided');
