<?php

error_reporting(E_ALL);

function loadClass($class) {

    if(file_exists("lib/$class.php")) {
        require_once "lib/$class.php";
    }
    if(file_exists("models/$class.php")) {
        require_once "models/$class.php";
    }
}
spl_autoload_register('loadClass', true);

$usersObj = new TblUsersDao();

print_r($usersObj->fields(['name'])->findAll([1]));
