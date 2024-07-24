<?php
/**
 * @Author: RobertPham0327 s3926681@rmit.edu.vn
 * @Date: 2024-07-19 10:34:26
 * @LastEditors: JustABusiness huysanti123456@gmail.com
 * @LastEditTime: 2024-07-24 15:59:31
 * @FilePath: modules/shops/forms/CommentForm.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */


namespace app\modules\shops\form;

use app\modules\shops\models\Comment;

class CommentForm extends Comment
{
     public function rules()
     {
          return array_merge(
               parent::rules(),
               [
                    [['comment', 'product_id'],'required'],
               ]
          );
     }
}