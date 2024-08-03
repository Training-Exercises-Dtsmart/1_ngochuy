<?php
/**
 * @Author: JustABusiness huysanti123456@gmail.com
 * @Date: 2024-08-03 14:03:54
 * @LastEditors: JustABusiness huysanti123456@gmail.com
 * @LastEditTime: 2024-08-03 14:04:46
 * @FilePath: rbac/AuthRule.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */


namespace app\rbac;

use yii\rbac\Rule;
use app\models\Post;

/**
 * Checks if authorID matches user passed via params
 */
class AuthorRule extends Rule
{
     public $name = 'isAuthor';

     /**
      * @param string|int $user the user ID.
      * @param Item $item the role or permission that this rule is associated with
      * @param array $params parameters passed to ManagerInterface::checkAccess().
      * @return bool a value indicating whether the rule permits the role or permission it is associated with.
      */
     public function execute($user, $item, $params)
     {
          return isset($params['post']) ? $params['post']->createdBy == $user : false;
     }
}