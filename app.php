<?php
/**
 * Created by PhpStorm.
 * author: xuyi
 * date: 2019/8/26 11:01
 */

function displayMsg($content, $colour='',$bold=true ,$type='')
{

    $bold === TRUE ? ';1' : '';
    switch($colour)
    {
        case 'red':
        case '红':
            $strStart ="\033[31;4{$bold}0m";
            break;
        case 'green':
        case '绿':
            $strStart ="\033[32;4{$bold}0m";
            break;
        case 'yellow':
        case '黄':
            $strStart ="\033[33;4{$bold}0m";
            break;
        case 'blue':
        case '蓝':
            $strStart ="\033[34;4{$bold}0m";
            break;
        case 'violet':
        case '紫':
            $strStart ="\033[35;4{$bold}0m";
            break;
        default :
            $strStart ="\033[{$bold}0m";
    }
    if($type == 'center')
    {
        $len = (strlen($content)+mb_strlen($content))/4;
        for($i = $len;$i < 50;$i++){
            $content = "=$content=";
        }
    }
    $content = $strStart.$content."\033[0m";
    return $content;
}


class app
{

    public function service($sign, $host = '', $dir)
    {
        $host = empty($host) ?  'localhost:8080' : $host;
        $dir  = empty($dir) ? './' : $dir;

        shell_exec("php -S {$host} -t {$dir}");
    }

    /**
     * create model
     * @param $object
     * @param $table
     * @author xuyi
     * @date 2019/8/31
     */
    public function create_model($object, $table)
    {
        $object= trim($object);
        if(empty($object)) {
            echo 'object err~';
            return;
        }

        if(empty($table)) {
            echo 'table err';
            return;
        }

        echo "\n\nstart create files\n-------------------\n";
        echo displayMsg("tools author by xuyi", 'red')."\n-------------------\n";
        $shell = shell_exec("php -f index.php scaffold scaffold/create_object model {$object} {$table}");
        if(!empty($shell))
        {
            echo "\ncreate file\t:[" . displayMsg($shell, 'red').']';
            echo "\ncreate status\t:{$table} table model files ".displayMsg('ok~', 'green');
            echo "\n".displayMsg('-------------------------------');
        }else{
            echo "\ncreate {$table} table model files ".displayMsg('fail');
        }

        echo displayMsg("\ncreate model files complete!!!");
    }
}

unset($argv[0]);

$methods = empty($argv[1]) ? '' : $argv[1];

unset($argv[1]);

$paramArr = $argv;
$app = new app();
echo call_user_func_array(array($app, $methods), $paramArr);
