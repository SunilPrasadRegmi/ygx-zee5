<?php
//=============================================================================//
// THIS SCRIPT IS FOR EDUCATION PURPOSE ONLY. Don't Sell this Script.
// Join Community https://t.me/ygxworld, https://t.me/ygx_chat
//=============================================================================//

$jsonFile = 'data.json';
$jsonData = file_get_contents($jsonFile);
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$requestUri = $_SERVER['REQUEST_URI'];
$scriptUrl = $protocol . $host . str_replace('playlist.php','index.php', $requestUri);
$data = json_decode($jsonData, true);

  function getUserAgent() {
    $chromeVersions = range(120, 131);
    $edgeVersions = range(120, 131);
    $safariVersions = ['16.6', '17.0', '17.1', '17.2', '17.3', '17.4', '17.5', '17.6', '18.0'];
    $webkitVersions = range(605, 620);
    
    $userAgents = [
        // Chrome
        function() use ($chromeVersions) {
            $version = $chromeVersions[array_rand($chromeVersions)];
            $patch = rand(0, 9999);
            $subpatch = rand(100, 999);
            return "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/{$version}.0.{$patch}.{$subpatch} Safari/537.36";
        },
        // Edge
        function() use ($edgeVersions) {
            $version = $edgeVersions[array_rand($edgeVersions)];
            $patch = rand(0, 9999);
            $subpatch = rand(100, 999);
            return "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/{$version}.0.{$patch}.{$subpatch} Safari/537.36 Edg/{$version}.0.{$patch}.{$subpatch}";
        },
        // Safari
        function() use ($safariVersions, $webkitVersions) {
            $safariVer = $safariVersions[array_rand($safariVersions)];
            $webkitVer = $webkitVersions[array_rand($webkitVersions)];
            $subver = rand(1, 9);
            return "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/{$webkitVer}.{$subver}.15 (KHTML, like Gecko) Version/{$safariVer} Safari/{$webkitVer}.{$subver}.15";
        }
    ];
    
    $selectedUA = $userAgents[array_rand($userAgents)];
    return $selectedUA();
}

header('Content-Type: audio/x-mpegurl');
echo "#EXTM3U\n";
echo "#https://github.com/yuvraj824/zee5\n\n";

$userAgent = getUserAgent();

foreach ($data['data'] as $channel) {
    $id = $channel['id'] ?? '';
    if ($id === '' || isset($seenIds[$id])) continue;
    $seenIds[$id] = true;
    $slug      = $channel['slug'] ?? '';
    $country   = $channel['country'] ?? '';
    $chno      = $channel['chno'] ?? '';
    $language  = $channel['language'] ?? '';
    $name      = $channel['name'] ?? '';
    $chanName  = $channel['channel_name'] ?? '';
    $logo      = $channel['logo'] ?? '';
    $genre     = $channel['genre'] ?? '';
    $streamUrl = $scriptUrl . '?id=' . $id;
    
    
    echo "#EXTINF:-1 tvg-id=\"$id\" tvg-country=\"$country\" tvg-chno=\"$chno\" tvg-language=\"$language\" tvg-name=\"$name\" tvg-logo=\"$logo\" group-title=\"$genre\", $chanName\n";
    echo "#KODIPROP:inputstream=inputstream.adaptive\n";
    echo "#KODIPROP:inputstream.adaptive.manifest_type=HLS\n";
    echo "#KODIPROP:inputstream.adaptive.manifest_headers=User-Agent=".urlencode($userAgent)."\n";
    echo "#KODIPROP:inputstream.adaptive.stream_headers=User-Agent=".urlencode($userAgent)."\n";
    echo "#EXTVLCOPT:http-user-agent=$userAgent\n";
    echo "$streamUrl&%7CUser-Agent=$userAgent\n\n";
}
exit;
//@yuvraj824
