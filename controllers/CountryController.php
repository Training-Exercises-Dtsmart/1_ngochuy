<?php
/**
 * @Author: JustABusiness huysanti123456@gmail.com
 * @Date: 2024-07-26 15:46:08
 * @LastEditors: JustABusiness huysanti123456@gmail.com
 * @LastEditTime: 2024-07-26 15:52:48
 * @FilePath: controllers/CountryController.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */

namespace app\controllers;

use app\controllers\Controller;
use app\models\Contact;
use yii\data\Pagination;

class CountryController extends Controller
{
     public function actionIndex()
     {
          $query = Contact::find();

          $pagination = new Pagination([
               'defaultPageSize' => 5,
               'totalCount' => $query->count(),
          ]);

          $countries = $query->orderBy('name')
               ->offset($pagination->offset)
               ->limit($pagination->limit)
               ->all();
          
          return $this->render('index', [
               'countries' => $countries,
               'pagination' => $pagination,
          ]);
     }
}