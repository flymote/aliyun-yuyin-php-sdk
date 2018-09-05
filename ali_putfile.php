<?php
set_time_limit(800);
define('ENABLE_HTTP_PROXY', false);
define('HTTP_PROXY_IP', '127.0.0.1');
define('HTTP_PROXY_PORT', '8888');

const AccessKeyId = '1111111111';
const AccessKeySecret = '22222222222222222';
const APPkey = 'nls-service-telephone8khz';

$to_url = "https://nlsapi.aliyun.com/transcriptions";
$get_url = "https://nlsapi.aliyun.com/transcriptions/";

$callback_url = "http://XXX.com/callback_ali.php";
$fileURL = "http://XXX.com/test2.mp3";

date_default_timezone_set('Asia/Shanghai');
header("Content-type: text/html; charset=utf-8");

interface ISigner
{
    public function getSignatureMethod();
    public function getSignatureVersion();
    public function signString($source, $accessSecret);
}
class ShaHmac1Signer implements ISigner
{
    public function signString($source, $accessSecret)
    {
        return    base64_encode(hash_hmac('sha1', $source, $accessSecret, true));
    }
    public function getSignatureMethod()
    {
        return "HMAC-SHA1";
    }
    public function getSignatureVersion()
    {
        return "1.0";
    }
}
class ShaHmac256Signer implements ISigner
{
    public function signString($source, $accessSecret)
    {
        return    base64_encode(hash_hmac('sha256', $source, $accessSecret, true));
    }
    public function getSignatureMethod()
    {
        return "HMAC-SHA256";
    }
    public function getSignatureVersion()
    {
        return "1.0";
    }
}
function encode_body($body){
    return base64_encode(md5($body,true));
}
function signature ($source){
    $signer = new ShaHmac1Signer();
    return $signer->signString($source, AccessKeySecret);
}

include_once 'aliyun/HttpHelper.php';

function sendFilePost($fileURL, $tourl,$callback_url) {
    $result = "";
    $request = new HttpHelper();
    $realUrl = $tourl;
    $method = "POST";
    $accept = "application/json";
    $content_type = "application/json";
    $Data = json_encode(array("app_key"=>APPkey,"oss_link"=>$fileURL,"auto_split"=>false,"enable_callback"=>true,"callback_url"=>$callback_url));
    $length = strlen($Data);
    $date = date("D, d M Y H:m:s e",time());
    // 1.对body做MD5+BASE64加密
    $bodyMd5 = encode_body($Data);
    $stringToSign = $method."\n".$accept."\n".$bodyMd5."\n".$content_type."\n".$date ;
    // 2.计算 HMAC-SHA1
    $sig = new ShaHmac1Signer();
    $signature = $sig->signString($stringToSign, AccessKeySecret);
    // 3.得到 authorization header
    $authHeader = "Dataplus ".AccessKeyId.":".$signature;
    // 打开和URL之间的连接
    $headers["accept"] = $accept;
    $headers["content-type"] = $content_type;
    $headers["date"] = $date;
    $headers["Authorization"] = $authHeader;
    $headers["Content-Length"] = $length;
    $response = $request->curl($realUrl,$method,$Data,$headers);
    return $response;
}

function getFilePost($id, $tourl) {
	$result = "";
	$request = new HttpHelper();
	$realUrl = $tourl.$id;
	$method = "GET";
	$accept = "application/json";
	$content_type = "application/json";
	$date = date("D, d M Y H:m:s e",time());
	// 1.POST方式对body做MD5+BASE64加密,GET方式为空
	$bodyMd5 = "";
	$stringToSign = $method."\n".$accept."\n".$bodyMd5."\n".$content_type."\n".$date ;
	// 2.计算 HMAC-SHA1
	$sig = new ShaHmac1Signer();
	$signature = $sig->signString($stringToSign, AccessKeySecret);
	// 3.得到 authorization header
	$authHeader = "Dataplus ".AccessKeyId.":".$signature;
	// 打开和URL之间的连接
	$headers["accept"] = $accept;
	$headers["content-type"] = $content_type;
	$headers["date"] = $date;
	$headers["Authorization"] = $authHeader;
	$response = $request->curl($realUrl,$method,NULL,$headers);
	return $response;
}

    	echo 'start:';
        $text = sendFilePost($fileURL,$to_url,$callback_url);
	     if ($text->getStatus() == 200){
	            $text = json_decode($text->getBody());
	            $text = $text->{'id'};
	            echo "OK !!";
	            sleep(4);
	            $text = getFilePost($text, $get_url);
	            echo "result:<pre>"; var_dump($text);echo "</pre>";
	     }else{
	     	echo "FAIL !!<pre>"; var_dump($text);echo "</pre>";
	     }; 

// include 'Shoudian_db.php';

// $result = $svcall->query("SELECT * FROM `sv_mix` where svmixtime <  DATE_SUB(NOW(), INTERVAL 5 MINUTE) ORDER BY svmixtime DESC LIMIT 1000 ");
// while (($row = $result->fetch_array(MYSQLI_ASSOC))!==false) {
// 	if (!$row)
//         die("nothing found to do !");
//     else{
//     	$date = strtotime($row['svmixtime']);
//     	$day = date("Ymd",$date);
//     	$month = date("m",$date);
//     	$day1 = date("Y-m-d",$date);
//     	$file = $path.$day1.'/'.$row['svmixfilename'];
//     	$table = 'sv_callout_detail'.$day;
//     	$resultin = $svcall->query("SELECT svcalloutfromip FROM `sv_callout_detail` PARTITION($table) where ( svcalluqid = '$row[svmixuqid]' AND svcalloutcalled ='$row[svmixcalled]' ) limit 1");
//     	$rowin = $resultin->fetch_array(MYSQLI_ASSOC);
//     	echo 'start:';
//         if (!file_exists($file))
//             continue;
//         echo 'fileOK ';
//         $result1 = $mysqli->query("select id from readvoice$month where filename='$row[svmixfilename]' limit 1");
//         $row1 = $result1->fetch_array(MYSQLI_NUM);
//         if ($row1)
//             continue;
//         echo 'read ';
//         $text = sendAsrPost(file_get_contents($file), 'wav', 8000,$to_url);
//         if ($text->getStatus() == 200){
//             $text = json_decode($text->getBody());
//             $text = $text->{'result'};
//         }
//         else continue;
//         $sql ="insert into readvoice$month (callto,starttime,callfrom,filename,text,calloutcalled,IP,engine) values ('$row[svmixcalled]','$row[svmixtime]','$row[svmixcaller]','$row[svmixfilename]','$text','$row[svmixaccount]','$rowin[svcalloutfromip]',3)";
//         $mysqli->query($sql);
//         echo 'insertOK<br/>';
//     }
// }

?>
