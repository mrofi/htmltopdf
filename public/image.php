<?php

use Ramsey\Uuid\Uuid;
use Spatie\Browsershot\Browsershot;

require_once __DIR__.'/../vendor/autoload.php';

class Browser extends Browsershot
{
    public function base64Screenshot(): string
    {
        $command = $this->createScreenshotCommand();
        $encoded_image = $this->callBrowser($command);

        return $encoded_image;
    }
}

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/..');
$dotenv->load();

$currentIp = $_SERVER['REMOTE_ADDR'];
$whitelist = explode(',', getenv('ALLOWED_IPS'));

if (! in_array($currentIp, $whitelist)) {
    die('Access not allowed');
}

if ($url = $_REQUEST['url'] ?? false) {
    $file = Uuid::uuid4().'.png';
    $browser = Browser::url($url);
    if ($path = getenv('NODE_PATH')) {
        $browser->setIncludePath($path);
    }

    try {
        $browser
            ->noSandbox()
            ->waitUntilNetworkIdle($_REQUEST['strict'] ?? 1)
            ->windowSize(800, 800)
            ->hideBrowserHeaderAndFooter();

        if ($_REQUEST['base64'] ?? false) {
            die('data:image/png;base64,'.$browser->base64Screenshot());
        }

        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$file");
        header("Content-Type: image/png");
        header("Content-Transfer-Encoding: binary");
        echo $browser->screenshot();
        die();
    } catch (\Exception $e) {}

    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    die('500 Internal Server Error');
}

die('no URL provided');
