<?php

/**
 * Generate by generate-model-tool
 * @name Posts
 * @desc Posts类, 主要用来访问数据库
 * @author Json
 * @see http://github.com/fanqingxuan/gen-model
 */
class Posts extends Base {

   /**
    * table primary key
    */
   protected $primary_key = 'id';

   /**
    * @return string
    */
   public static function tableName()
   {
        return 'posts';
   }

}