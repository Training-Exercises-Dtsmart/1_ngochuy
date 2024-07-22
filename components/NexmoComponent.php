<?php
/**
 * @Author: RobertPham0327 s3926681@rmit.edu.vn
 * @Date: 2024-07-19 17:06:10
 * @LastEditors: JustAbusiness huysanti123456@gmail.com
 * @LastEditTime: 2024-07-22 09:10:35
 * @FilePath: components/NexmoComponent.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */


namespace app\components;

use Vonage\Client;
use Vonage\Client\Credentials\Basic;
use yii\base\Component;

class NexmoComponent extends Component
{
     public $apiKey;
     public $apiSecret;
     public $fromNumber;

     private $client;

     public function init()
     {
          parent::init();
          $credentials = new Basic($this->apiKey, $this->apiSecret);
          $this->client = new Client($credentials);
     }

     public function sendSms($to, $message)
     {
          $response = $this->client->sms()->send(
               new \Vonage\SMS\Message\SMS($to, $this->fromNumber, $message)
          );

          return $response;
          
     }
     
}