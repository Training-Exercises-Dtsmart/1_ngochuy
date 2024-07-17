<?php
/**
 * @Author: RobertPham0327 s3926681@rmit.edu.vn
 * @Date: 2024-07-17 10:11:33
 * @LastEditors: RobertPham0327 s3926681@rmit.edu.vn
 * @LastEditTime: 2024-07-17 10:11:33
 * @FilePath: commands/DemoController.php
 */
namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;

class DemoController extends Controller
{
     public function actionIndex($message = 'Demo Controller')
     {
          echo $message. '\n';
          return ExitCode::OK;
     }
 }