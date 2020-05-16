<?php

/**
 * Generate by generate-model-tool
 * @name TblUsersDao
 * @desc TblUsersDao类, 主要用来访问数据库
 * @author Json
 * @see http://github.com/fanqingxuan/gen-model
 */
class TblUsersDao extends Base {

   /**
    * table primary key
    */
   protected $primary_key = 'user_id';

   /**
    * @return string
    */
   public static function tableName()
   {
        return 'tbl_users';
   }

}