<?php

/**
 * Generate by generate-model-tool
 * @name TblUsersModel
 * @desc TblUsersModel类, 主要用来访问数据库
 * @author Json
 * @see http://github.com/fanqingxuan/gen-model
 */
class TblUsersModel extends Base {

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