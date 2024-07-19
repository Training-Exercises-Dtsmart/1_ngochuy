<?php
/**
 * @Author: RobertPham0327 s3926681@rmit.edu.vn
 * @Date: 2024-07-19 10:34:26
 * @LastEditors: RobertPham0327 s3926681@rmit.edu.vn
 * @LastEditTime: 2024-07-19 11:04:17
 * @FilePath: modules/models/form/CommentForm.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */


namespace app\modules\models\form;

use app\modules\models\Comment;

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