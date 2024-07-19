<?php
/**
 * @Author: RobertPham0327 s3926681@rmit.edu.vn
 * @Date: 2024-07-19 12:06:04
 * @LastEditors: RobertPham0327 s3926681@rmit.edu.vn
 * @LastEditTime: 2024-07-19 12:06:31
 * @FilePath: core/HttpRequest.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */

namespace app\core;

use GuzzleHttp\Client;

trait HttpRequest
{
     public $timeout = '5.0';

     protected function get($url, $query = [], $headers = [])
     {
          return $this->request('GET', $url, [
               'headers' => $headers,
               'query' => $query,
          ]);
     }

     protected function post($url, $params = [], $headers = [])
     {
          return $this->request('POST', $url, [
               'headers' => $headers,
               'form_params' => $params,
          ]);
     }

     protected function postJson($url, $params = [], $headers = [])
     {
          return $this->request('POST', $url, [
               'headers' => $headers,
               'json' => $params,
          ]);
     }

     public function request($method, $url, $options = [])
     {
          $client = $this->getClient($this->getBaseOptions());
          $response = $client->{$method}($url, $options);
          return $this->unwrapResponse($response);
     }

     protected function unwrapResponse($response)
     {
          $contents = $response->getBody()->getContents();
          $res = json_decode($contents, true);
          if ($res === null) {
               return $contents;
          }
          return $res;
     }

     protected function getClient($options = [])
     {
          return new Client($options);
     }

     protected function getBaseOptions()
     {
          return [
               'base_uri' => $this->getBaseUrl(),
               'timeout' => $this->getTimeout(),
               'verify' => false,
          ];
     }

     protected function getBaseUrl()
     {
          return '';
     }

     protected function getTimeout()
     {
          return $this->timeout;
     }

     protected function setTimeout($time)
     {
          $this->timeout = $time;
          return $this;
     }
}

