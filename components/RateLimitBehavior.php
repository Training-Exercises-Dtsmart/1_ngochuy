<?php
/**
 * @Author: JustABusiness huysanti123456@gmail.com
 * @Date: 2024-07-31 15:21:22
 * @LastEditors: JustABusiness huysanti123456@gmail.com
 * @LastEditTime: 2024-07-31 15:48:44
 * @FilePath: components/RateLimitBehavior.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */


namespace app\components;

use Yii;
use yii\filters\RateLimiter;

class RateLimitBehavior extends  RateLimiter
{
     public function getRateLimit($request, $action)
     {
          $user = Yii::$app->user->identity;

          if ($user && $user->isAdmin()) {
               return [1000, 60]; // 1000 requests per minute for admin users
          }

          if ($user && $user->isGuest()) {
               return [50, 60]; // 50 requests per minute for guest users
          }

          return [100, 600]; // 100 requests per 10 minutes for regular users
     }

     public function loadAllowance($request, $action)
     {
          $userId = Yii::$app->user->id;
          $allowance = Yii::$app->cache->get("allowance_{$userId}");
          $timestamp = Yii::$app->cache->get("allowance_timestamp_{$userId}");

          return [$allowance ?? 100, $timestamp ?? time()];
     }

     public function saveAllowance($request, $action, $allowance, $timestamp)
     {
          $userId = Yii::$app->user->id;
          Yii::$app->cache->set("allowance_{$userId}", $allowance, 3600);
          Yii::$app->cache->set("allowance_timestamp_{$userId}", $timestamp, 3600);
     }
}