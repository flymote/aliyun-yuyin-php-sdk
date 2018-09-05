# 阿里云语音识别 PHP SDK （1.0版SDK）


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
