<?php
/**
 * @Author: RobertPham0327 s3926681@rmit.edu.vn
 * @Date: 2024-07-19 10:33:09
 * @LastEditors: JustABusiness huysanti123456@gmail.com
 * @LastEditTime: 2024-07-24 15:59:31
 * @FilePath: modules/shops/resources/CommentResource.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */


namespace app\modules\shops\resources;

use app\modules\shops\models\Comment;

class CommentResource extends Comment
{
     public function fields()
     {
          return ['id', 'post_id', 'user_id', 'content', 'created_at'];
     }
}