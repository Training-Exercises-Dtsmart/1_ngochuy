<?php
/**
 * @Author: JustABusiness huysanti123456@gmail.com
 * @Date: 2024-07-24 12:04:29
 * @LastEditors: JustABusiness huysanti123456@gmail.com
 * @LastEditTime: 2024-07-24 12:06:06
 * @FilePath: interface/block/BlockCache.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */

namespace app\interfaces\block;

interface BlockCache
{
     const BLOCK_CACHE_PREFIX = 'block_cache';
     public function getCacheKey();
}

