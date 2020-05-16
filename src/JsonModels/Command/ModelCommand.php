<?php
namespace JsonModels\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Style\SymfonyStyle;

class ModelCommand extends Command
{

    private $model_tempalte = <<<EOF
<?php

/**
 * Generate by generate-model-tool
 * @name {{Model}}
 * @desc {{Model}}类, 主要用来访问数据库
 * @author {{Author}}
 * @see http://github.com/fanqingxuan/gen-model
 */
class {{Model}} extends Base {

   /**
    * table primary key
    */
   protected \$primary_key = '{{primaryKey}}';

   /**
    * @return string
    */
   public static function tableName()
   {
        return '{{tableName}}';
   }

}
EOF;


    protected function configure()
    {
        $this
            // 命令的名称 （"php console_command" 后面的部分）
            ->setName('model')
            // 运行 "php console_command list" 时的简短描述
            ->setDescription('Create new model')
            // 运行命令时使用 "--help" 选项时的完整命令描述
            ->setHelp('This command allow you to create models...')
            // 配置一个参数
            ->addArgument('database', InputArgument::REQUIRED, 'Database name?')
            ->addArgument('path', InputArgument::REQUIRED, 'Generate models to the destination')
            ->addOption('host','-H',InputOption::VALUE_REQUIRED, 'Database host can be either a host name or an IP address','127.0.0.1')
            ->addOption('user','-u',InputOption::VALUE_REQUIRED, 'The MySQL user name','root')
            ->addOption('password','-p',InputOption::VALUE_OPTIONAL, 'The MySQL password','root')
            ->addOption('port', 'P',InputOption::VALUE_OPTIONAL, 'Specifies port number to attempt to connect to the MySQL server','3306')
            ->addOption("ignore-prefix",'',InputOption::VALUE_REQUIRED,'Ignore table prefix for model class')
            ->addOption("suffix",'',InputOption::VALUE_NONE,'Choice suffix for model,if you want generate model as UserDao or UserModel format')
            ->addOption("author",'',InputOption::VALUE_REQUIRED,'Author who generate the model class','Json')
            ->addOption("force",'f',InputOption::VALUE_NONE,"Override model class if exists,Otherwise skip generate");
    }

    //Converts 'table_name' to 'TableName'
    private function classify(string $word)
    {
        return str_replace([' ', '_', '-'], '', ucwords($word, ' _-'));
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $host = $input->getOption('host');
        $user = $input->getOption('user');
        $pwd = $input->getOption('password');
        $dbname = $input->getArgument('database');
        $port = $input->getOption('port');
        $io = new SymfonyStyle($input, $output);
        //连接数据库
        $mysqli = new \mysqli($host, $user, $pwd,$dbname, $port);
        if ($mysqli->connect_error) {
            $io->write('Database connect error:');
            $io->error($mysqli->connect_error);
            exit;
        }
        //校验路径
        $model_path = $input->getArgument('path');
        if(!is_dir($model_path) || !is_writeable($model_path)) {
            $io->error('The path "'.$model_path.'" is not readable.');
            exit;
        }
        $suffix = '';
        if($input->getOption('suffix')) {
            $suffix = $io->choice("Choice suffix for model",['','Dao','Model'],0);
        }

        //查询数据库
        $result = $mysqli->query("SHOW TABLES");
        $successCount = 0;

        //按表循环，查询主键，生成model

        //进度条开始
        $progressBar = $io->createProgressBar($result->num_rows);
        $progressBar->setFormat(' %current%/%max% [%bar%] %percent:3s%%'."\n");

        while($row=$result->fetch_array(MYSQLI_NUM))
        {

            $tableName = $row[0];
            $r = $mysqli->query("show columns from ".$tableName);
            $primaryKey = 'id';
            while($item = $r->fetch_array(MYSQLI_ASSOC ))
            {
                if($item['Key'] == 'PRI')
                {
                    $primaryKey = $item['Field'];
                    break;
                }
            }

            $table = $tableName;

            $ignore_prefix = $input->getOption("ignore-prefix");
            if($ignore_prefix && strpos($tableName,$ignore_prefix) !== false) {
                $table = substr($tableName,strpos($tableName,$ignore_prefix)+strlen($ignore_prefix));
            }
            $model = $this->classify($table).$suffix;

            $replaceDict = [
                "{{Author}}"    =>  $input->getOption('author'),
                "{{Model}}"     =>  $model,
                "{{primaryKey}}"=>  $primaryKey,
                "{{tableName}}" =>  $table,
            ];

            $content = str_replace(array_keys($replaceDict),array_values($replaceDict),$this->model_tempalte);
            //强制替换生成model,或者模型不存在
            if($input->getOption("force") || !file_exists($model_path.'/'.$model.'.php')) {
                $charNum = file_put_contents($model_path.'/'.$model.'.php',$content);
                if($charNum) {
                    $io->writeln("Model class ".$model."Model generate successful in file $model.php");
                    $successCount++;
                } else {
                    $io->writeln("Model class ".$model."Model generate failed in file $model.php");
                }
            }
            $progressBar->advance();
        }
        $progressBar->finish();
        $result->free();//释放内存
        $mysqli->close();//关闭连接
        $io->success("Model create finished. Total generate ".$successCount." model(s)");
        return 0;
    }
}
