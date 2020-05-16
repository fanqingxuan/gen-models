### Gen Models

### background

这个项目的目的是自动生成model类。一般的开源框架都有自己的orm，比如laravel，yii等，并且他们都有自己的命令行，所以不需要自己生成。但是也有一些高性能框架没有封装db层，比如yaf，phpslim，这个时候需要我们手动需创建model，或者dao去操作数据库。

我们一般创建一个model基类，封装基本的增删改查方法，然后实体model去继承基类，像下面这样

```php
class Base {
    public function __construct() {
        
    }
    
    public function find() {
        
    }
    
    public function findAll() {
        
    }
    
    public function update() {
        
    }
    
    public function query() {
        
    }
    
    public function insert() {
        
    }
}

class User extends Base {
    
}

class Post extends Base {
    
}
```

这个时候我们发现，如果数据库中表比较多，我们就需要手动创建类似的代码，容易产出错误，另外每个表都有自己的主键，都有自己的表名称，需要在实体model中去声明的。

```php
<?php
class User {
    public $primaryKey = 'user_id';
    
    public static function tableName() {
        return "tbl_users";
    }
} 
```

上面基本上是自己封装model的设计思路

### Project goal

我们希望手动写更少的代码，减少错误，自动根据表生成model

### Usage

- 安装

```php
composer require fanqingxuan/gen-models
```

- 配置入口文件

创建入口文件，或者将vender/fanqingxuan/gen-models目录下的index.php直接copy下来

```php
require 'vendor/autoload.php';

use Symfony\Component\Console\Application;
use JsonModels\Command\ModelCommand;
$application = new Application();

$name = "A tool for generate models written by Json<fanqingxuan@163.com>";
$application->setName($name);

$application->add(new ModelCommand());

$application->run();

```

- 使用

```shell
$php index.php model database path //将连接的数据库中的所有表，一个表一个模型生成到对应目录,默认host是localhost,db user是root,password是root，port是3306

$php index.php model -h //查看命令帮助

$php index.php model database path -uroot123 //连接的时候db user使用root123

$php index.php model database path -uroot123 -pa12345 -H192.168.56.55 -P3308 //连接host是192.168.56.55,user是root123 pasword是a12345,端口是3308的库

$php index.php model database path --ignore-prefix  tbl_  //创建的model类名忽略表前缀

$php index.php model database path -f //若path中model文件已经存在，进行覆盖，不存在则创建

$php index.php model database path --suffix //为model类文件添加Dao或者Model后缀

```



- model生成规则

1. tbl_user_address表生成文件名是TblUserAddress.php,类名是TblUserAddress的驼峰类
2. tbl_user_address表如果命令行使用了--ignore-prefix,则生成文件名UserAddress.php,类名是UserAddress的驼峰类
3. tbl_user_address如果使用了--suffix，并且选择了Model，没有使用--ignore-prefix命令，生成文件名是TblUserAddressModel.php的TblUserAddressModel类
4. tbl_user_address如果使用了--suffix，并且选择了Dao，使用--ignore-prefix命令，生成文件名是UserAddressDao.php的UserAddressDao类

- 生成的实例demo,包含primary_key属性，和tableName静态属性

```php
<?php

/**
 * Generate by generate-model-tool
 * @name UsersModel
 * @desc UsersModel类, 主要用来访问数据库
 * @author Json
 * @see http://github.com/fanqingxuan/gen-models
 */
class UsersModel extends BaseModel {

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
```

### 其它

如果您电脑没有安装composer，可以使用gen-models，然后执行如下命令都可以

```php
$ git@github.com:fanqingxuan/gen-models.git  //gen-models在这个仓库的根目录，可以拿下来直接用
```

```
$php gen-models model database path
$./gen-models model database path
```

