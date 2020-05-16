<?php

/**
 * Generate by generate-model-tool
 * @name Users
 * @desc Users类, 主要用来访问数据库
 * @author Json
 * @see http://github.com/fanqingxuan/gen-model
 */
class Users extends Base {

   /**
    * table primary key
    */
   protected $primary_key = 'user_id';

   /**
    * @return string
    */
   public static function tableName()
   {
        return 'users';
   }

}