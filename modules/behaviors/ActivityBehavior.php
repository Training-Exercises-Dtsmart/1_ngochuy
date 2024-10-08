<?php
/**
 * @Author: RobertPham0327 s3926681@rmit.edu.vn
 * @Date: 2024-07-19 11:14:19
 * @LastEditors: RobertPham0327 s3926681@rmit.edu.vn
 * @LastEditTime: 2024-07-19 11:15:09
 * @FilePath: modules/behaviors/ActivityBehavior.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */


namespace app\modules\behaviors;

use app\modules\v1\workspaces\models\WorkspaceActivity;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\helpers\Json;
use yii\helpers\VarDumper;

class ActivityBehavior extends Behavior
{
     /**
      * @var \Closure
      */
     public $workspace_id = null;
     /**
      * @var string
      */
     public $contentIdAttribute = 'id';
     /**
      * @var string
      */
     public $tableName = null;

     public $events = ['create', 'update', 'delete'];

     public $eventMap = [];

     /**
      * @var \Closure
      */
     public $data = null;

     /**
      * @var \Closure
      */
     public $parentIdentity = null;

     public function events()
     {
          return [
               WorkspaceActivity::EVENT_AFTER_INSERT => 'afterInsert',
               WorkspaceActivity::EVENT_AFTER_UPDATE => 'afterUpdate',
               WorkspaceActivity::EVENT_BEFORE_DELETE => 'beforeDelete',
          ];
     }

     public function appendAction($action)
     {
          $mapAction = $this->eventMap[$action] ?? $action;
          $workspaceActivity = new WorkspaceActivity();
          $workspaceActivity->workspace_id = $this->workspace_id instanceof \Closure ? call_user_func($this->workspace_id) : null;
          $workspaceActivity->table_name = $this->processTableName($this->tableName ?? $this->owner->tableName());
          $workspaceActivity->content_id = $this->owner->{$this->contentIdAttribute};
          $workspaceActivity->action = is_callable($mapAction) ? call_user_func($mapAction) : $mapAction;
          $workspaceActivity->data = $this->data ? Json::encode(call_user_func($this->data)) : Json::encode($this->owner->toArray());
          $saved = $workspaceActivity->save();

          if (!$saved) {
               \Yii::error("WorkspaceActivity was not saved. Errors: ". VarDumper::dumpAsString($workspaceActivity->errors));
               throw new Exception("Invalid Arguments ". implode('<br>', $workspaceActivity->getFirstErrors()));
          }
     }

     private function processTableName($tableName)
     {
          return preg_replace('(^{{%?|}}$)', '', $tableName);
     }

     public function afterInsert()
     {
          if (in_array(WorkspaceActivity::ACTION_INSERT, $this->events))
               $this->appendAction(WorkspaceActivity::ACTION_INSERT);

     }

     public function afterUpdate()
     {
          if (in_array(WorkspaceActivity::ACTION_UPDATE, $this->events))
               $this->appendAction(WorkspaceActivity::ACTION_UPDATE);
     }

     public function beforeDelete()
     {
          if (in_array(WorkspaceActivity::ACTION_DELETE, $this->events))
               $this->appendAction(WorkspaceActivity::ACTION_DELETE);
     }
}