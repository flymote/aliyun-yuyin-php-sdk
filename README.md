# 阿里云语音识别 PHP SDK （1.0版SDK）

在阿里云上的语音识别竟然没有PHP的SDK，而通过他们官方的所谓PHP的openSDK根本也没办法使用，于是，我基于他们的JAVA SDK改写成了PHP SDK，绝对可用！！
里面有些类是挪用了阿里云的官方php OpenSDK中core的代码
记得修改代码把里面的AccessKeyId和AccessKeySecret修改成自己所申请的哦！！

ali_get.php :::
阿里云语音识别 PHP SDK，基于其JAVA SDK改写，RESTful API

 **短语音识别REST**  

API支持以POST方式整段上传长度不多于一分钟的语音文件。识别结果将以JSON格式在请求响应中一次性返回，开发者需保证在识别结果返回前连接不被中断。官方文档：
https://help.aliyun.com/document_detail/52787.html


ali_putfile.php :::
阿里云语音识别 PHP SDK

 **录音文件识别**  

以提交录音文件的形式进行语音的识别，可用mp3/wav格式，本文件同时提供了提交录音文件和查询识别结果两个功能

callback_ali.php :::
阿里云语音识别 PHP SDK

 **录音文件识别 回调**  

实现录音文件语音识别的回调
