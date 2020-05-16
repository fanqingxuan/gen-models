<?php

/**
 * Generate by generate-model-tool
 * @name PasswordResetsDao
 * @desc PasswordResetsDao类, 主要用来访问数据库
 * @author Json
 * @see http://github.com/fanqingxuan/gen-model
 */
class PasswordResetsDao extends Base {

   /**
    * table primary key
    */
   protected $primary_key = 'id';

   /**
    * @return string
    */
   public static function tableName()
   {
        return 'password_resets';
   }

}