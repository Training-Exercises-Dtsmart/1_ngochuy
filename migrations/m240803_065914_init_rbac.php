<?php

use yii\db\Migration;

/**
 * Class m240803_065914_init_rbac
 */
class m240803_065914_init_rbac extends Migration
{
//    /**
//     * {@inheritdoc}
//     */
//    public function safeUp()
//    {
//
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function safeDown()
//    {
//        echo "m240803_065914_init_rbac cannot be reverted.\n";
//
//        return false;
//    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
         $auth = Yii::$app->authManager;

         // add "createPost" permission
         $createPost = $auth->createPermission('createPost');
         $createPost->description = 'Create a post';
         $auth->add($createPost);

         // add "updatePost" permission
         $updatePost = $auth->createPermission('updatePost');
         $updatePost->description = 'Update post';
         $auth->add($updatePost);

         // add "author" role and give this role the "createPost" permission
         $author = $auth->createRole('author');
         $auth->add($author);
         $auth->addChild($author, $createPost);

         // add "admin" role and give this role the "updatePost" permission
         // as well as the permissions of the "author" role
         $admin = $auth->createRole('admin');
         $auth->add($admin);
         $auth->addChild($admin, $updatePost);
         $auth->addChild($admin, $author);

         // Assign roles to users. 1 and 2 are IDs returned by IdentityInterface::getId()
         // usually implemented in your User model.
         $auth->assign($author, 2);
         $auth->assign($admin, 1);
    }

    public function down()
    {
         $auth = Yii::$app->authManager;

         $auth->removeAll();
    }

}
