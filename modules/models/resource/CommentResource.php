<?php
/**
 * @Author: RobertPham0327 s3926681@rmit.edu.vn
 * @Date: 2024-07-19 10:33:09
 * @LastEditors: RobertPham0327 s3926681@rmit.edu.vn
 * @LastEditTime: 2024-07-19 10:35:01
 * @FilePath: modules/models/resource/CommentResource.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */


namespace app\modules\models\resource;

use app\modules\models\Comment;

class CommentResource extends Comment
{
     public function fields()
     {
          return ['id', 'post_id', 'user_id', 'content', 'created_at'];
     }
}