<?php
header('Content-type: text/css');

$_ENV['BASE_URL'] = getenv('BASE_URL');

if (!isset($_ENV['BASE_URL'])) {
    throw new Exception('ENV "BASE_URL" missing.');
}


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
    $url = str_replace(' ', '+', $url);
    if (strpos($url, 'fonts.googleapis.com') !== false || strpos($url, 'fonts.gstatic.com') !== false) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        $content = str_replace('https://', $_ENV['BASE_URL'].'/?url=https://', $result);
        
        file_put_contents($filepath, $content);
    } else {
        die('invalid: '.$url);
    }
}
