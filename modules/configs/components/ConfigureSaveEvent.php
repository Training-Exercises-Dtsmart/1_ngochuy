<?php
/**
 * @Author: JustABusiness huysanti123456@gmail.com
 * @Date: 2024-07-24 14:05:07
 * @LastEditors: JustABusiness huysanti123456@gmail.com
 * @LastEditTime: 2024-07-24 14:11:46
 * @FilePath: modules/configs/components/ConfigureSaveEvent.php
 */

/**
 * được sử dụng để tạo và quản lý các sự kiện tùy chỉnh trong quá trình lưu cấu hình của module
 */

class ConfigureSaveEvent extends Event
{
     /**
      * @var bool Whether to continue handling saving configuration
      */
     public $isValid = true;
     public $configurable = null;
     public $configurableModel = null;
}