<?php
/**
 * @Author: JustAbusiness huysanti123456@gmail.com
 * @Date: 2024-07-22 11:27:26
 * @LastEditors: JustAbusiness huysanti123456@gmail.com
 * @LastEditTime: 2024-07-22 11:31:02
 * @FilePath: components/filesystem/LocalFileSystem.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */

namespace app\components\filesystem;

use Yii;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use trntv\filekit\filesystem\FilesystemBuilderInterface;

class LocalFileSystem implements FileSystemBuilderInterface
{
     public $path;
     
     public function build()
     {
          $adapter = new Local(Yii::getAlias($this->path));
          return new FileSystem($adapter);
     }
}