<?php
header('Content-type: text/css');
require_once('vendor/autoload.php');
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


if (!isset($_GET['url'])) {
    die('nope...');
}

$url = $_GET['url'];

if (!is_dir('./cache')) {
    mkdir('./cache');
}

$url = str_replace('http://', 'https://', $url);
$url = base64_encode($url);

getFile($url);

function getFile($url)
{
    $filename = md5($url) . '.cache';
    if (!file_exists('./cache/' . $filename)) {
        cacheFile($url, './cache/' . $filename);
    }


    echo file_get_contents('./cache/' . $filename);
}

function cacheFile($url, $filepath) {
    $url = base64_decode($url);
    $url = str_replace('http://', 'https://', $url);
    if (strpos($url, 'fonts.googleapis.com') !== false || strpos($url, 'fonts.gstatic.com') !== false) {
        $content = file_get_contents($url);
        $content = str_replace('https://', $_ENV['BASE_URL'].'/?url=https://', $content);
        file_put_contents($filepath, $content);
    } else {
        die('invalid: '.$url);
    }
}