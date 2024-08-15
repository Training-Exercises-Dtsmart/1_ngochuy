<?php
/**
 * @Author: JustABusiness huysanti123456@gmail.com
 * @Date: 2024-08-06 14:29:21
 * @LastEditors: JustABusiness huysanti123456@gmail.com
 * @LastEditTime: 2024-08-06 17:14:43
 * @FilePath: index.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */



//if(true) {
//     function foo(): mixed {
//          return [2.5];
//     }
//}
//
//var_dump(foo());


//function sum(...$numbers): int|float {
//     $sum = 0;
//     foreach ($numbers as $number) {
//          $sum += $number;
//     }
//     
//     return $sum;
//}
//
//$a = 10;
//echo sum($a, 50);


// variable scope


//$x = 1;
//
//$sum = function (int|float ...$number) use ($x): int|float
//{
//     echo $x;
//     return array_sum($number);
//};
//
//echo $sum(1, 2, 3, 4);
//function someThing()
//{
//     sleep(2);
//     return 10;
//}
//
//echo getValue(). '<br />';
//echo getValue(). '<br />';
//echo getValue(). '<br />';


//$array = [1, 2, 4, 5];
//
//function foo($element) {
//     return $element * 2;
//}
//
////$array2 = array_map(function($element) {
////     return $element * 2;
////     
////}, $array);
//
//$array2 = array_map('foo', $array);
//print_r($array2);


//$array = [1, 2, 3, 4];
//
//$array2 =  array_map(function($number) {
//     return $number * $number;
//}, $array);
//
//echo '<pre>';
//print_r($array2);
//echo '</pre>';

echo time();
echo '<br />';
echo date('m/d/Y g:ia');

//echo date_default_timezone_get()\;
$date =  date('m/d/Y g:ia', strtotime('last of of august 2024'));
date_parse($date);