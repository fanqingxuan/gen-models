<?php

/**
 * Generate by generate-model-tool
 * @name UsersDao
 * @desc UsersDao类, 主要用来访问数据库
 * @author Json
 * @see http://github.com/fanqingxuan/gen-model
 */
class UsersDao extends Base {

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