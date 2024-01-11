<?php
header('Content-Type: application/json; charset=utf-8');

function curl_csdn($url, $header = [
    "accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
    "Accept-Encoding: gzip, deflate, br",
    "Accept-Language: zh-CN,zh;q=0.9",
    "Connection: keep-alive",
    "User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 10_3_1 like Mac OS X) AppleWebKit/603.1.30 (KHTML, like Gecko) Version/10.0 Mobile/14E304 Safari/602.1"
], $cookie = null, $refer = 'https://www.baidu.com') {
    $ip = rand(0, 255) . '.' . rand(0, 255) . '.' . rand(0, 255) . '.' . rand(0, 255);
    $header[] = "CLIENT-IP:" . $ip;
    $header[] = "X-FORWARDED-FOR:" . $ip;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_COOKIE, $cookie);
    curl_setopt($ch, CURLOPT_REFERER,  $refer);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

function csdn() {
    // 执行 cURL 请求
    $jsonRes = json_decode(curl_csdn('https://blog.csdn.net/phoenix/web/blog/hotRank?&pageSize=100', null, null), true);

    $tempArr = [];
    foreach ($jsonRes['data'] as $k => $v) {
      array_push($tempArr, [
        'index' => $k +1,
        'title' => $v['articleTitle'],
        'url' => $v['articleDetailUrl'],
      ]);
    }
    return [
        'success' => true,
        'title' => 'CSDN',
        'subtitle' => '热榜',
        'update_time' => date('Y-m-d H:i:s', time()),
        'data' => $tempArr
    ];
}

if (basename(__FILE__) === basename($_SERVER['PHP_SELF'])) {
	$_res = csdn();
	$json = json_encode($_res, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
	$json = str_replace('\/', '/', $json);
	echo $json;
}
?>
