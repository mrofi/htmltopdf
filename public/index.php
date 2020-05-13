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
    $file = Uuid::uuid4().'.pdf';
    $browser = Browsershot::url($url);
    if ($path = getenv('NODE_PATH')) {
        $browser->setIncludePath($path);
    }
    $browser
        ->noSandbox()
        ->waitUntilNetworkIdle()
        ->windowSize(1200, 900)
        ->showBrowserHeaderAndFooter()
        ->delay($_REQUEST['delay'] ?? 3000)
        ->margins(12, 5, 12, 5)
        ->pages($_REQUEST['pages'] ?? '')
        ->format($_REQUEST['size'] ?? 'A4')
        ->save($file);
    
    header('Location:/'.$file);
}

die('no URL provided');
