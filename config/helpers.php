<?php
/**
 * @Author: RobertPham0327 s3926681@rmit.edu.vn
 * @Date: 2024-07-22 14:38:06
 * @LastEditors: RobertPham0327 s3926681@rmit.edu.vn
 * @LastEditTime: 2024-07-22 14:41:25
 * @FilePath: config/helpers.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */


/**
 * Debugging helper function
 * @param mixed $arr The variable to be printed
 * @param int $type 0: print_r, 1: var_dump
 */
function debug ($arr, $type = 0)
{
     if ($type) echo '<pre>'. print_r($arr, true) .'</pre>';
     else {
          echo '<pre>';
          print_r($arr);
          echo '</pre>';
     }
}     

