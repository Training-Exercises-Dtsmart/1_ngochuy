<?php

namespace app\controllers;

use modules\models\HttpStatus;
use Yii;

class CalculateController extends Controller
{

    public function actionTotal()
    {
        $a = Yii::$app->request->post('a');
        $b = Yii::$app->request->post('b');

        if (!is_numeric($a) or !is_numeric($b)) {
            return $this->json(false, ["now" => date('d/m/y')], 'a or b is not a valid number', HttpStatus::BAD_REQUEST );
        }

        $result = $a + $b;
        return $this->json(true, ["now" => date('d/m/y'), 'result' => $result], 'Succesed');
    }

    public function actionDivide()
    {
        $a = Yii::$app->request->post('a');
        $b = Yii::$app->request->post('b');

        //bad request, number invalid => status code = HttpStatus::BAD_REQUEST
        if (!is_numeric($a) or !is_numeric($b)) {
            return $this->json(false, ["now" => date('d/m/Y')], 'a or b is not a valid number', HttpStatus::BAD_REQUEST);
        }
        if ($b == 0) {
            return $this->json(false, ["now" => date('d/m/Y')], "Number b can't be zero", HttpStatus::BAD_REQUEST);
        }

        $result = $a / $b;
        return $this->json(true, ["now" => date('d/m/y'), 'result' => $result], 'Successed');
    }

    public function actionAverage()
    {
        $numbers = Yii::$app->request->post('numbers');
        $arrayNumber = explode(',', $numbers);

        //bad request, number invalid => status code = HttpStatus::BAD_REQUEST
        foreach ($arrayNumber as $number) {
            if (!is_numeric($number)) {
                return $this->json(false, ["now" => date('d/m/Y')], 'Number is not a number', HttpStatus::BAD_REQUEST);
            }
        }

        $result = round(array_sum($arrayNumber) / count($arrayNumber), 2);
        return $this->json(true, ["now" => date('d/m/y'), 'result' => $result], 'Successed');
    }
}