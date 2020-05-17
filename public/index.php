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
    
    try {
        $browser
            ->noSandbox()
            ->waitUntilNetworkIdle($_REQUEST['strict'] ?? 1)
            ->windowSize(1200, 900)
            ->showBrowserHeaderAndFooter()
            ->delay($_REQUEST['delay'] ?? 5000)
            ->margins(0, 0, 10, 10)
            ->pages($_REQUEST['pages'] ?? '')
            ->format($_REQUEST['size'] ?? 'A4')
            ->setOption('preferCSSPageSize', true);

            header("Cache-Control: public");
            header("Content-Description: File Transfer");
            header("Content-Disposition: attachment; filename=$file");
            header("Content-Type: application/pdf");
            header("Content-Transfer-Encoding: binary");
            echo $browser->pdf();
            die();
    } catch (\Exception $e) {}

    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    die('500 Internal Server Error');
}

die('no URL provided');
