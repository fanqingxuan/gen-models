<?php

/**
 * Generate by generate-model-tool
 * @name Migrations
 * @desc Migrations类, 主要用来访问数据库
 * @author Json
 * @see http://github.com/fanqingxuan/gen-model
 */
class Migrations extends Base {

   /**
    * table primary key
    */
   protected $primary_key = 'id';

   /**
    * @return string
    */
   public static function tableName()
   {
        return 'migrations';
   }

}