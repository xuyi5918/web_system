<?php
/**
 * Created by PhpStorm.
 * User: njj
 * Date: 2017/8/11
 * Time: 9:36
 */
class MODEL_exception
{
    public function __call($name, $arguments)
    {
        $messgaeLog = 'Model Not Exists Function Name :'.$name.'(); Param : '.serialize($arguments);
        log_message(1, $messgaeLog);

        # 显示500
        $error = array('method'=>$name, 'message'=>'RPC model function Not !');
        app()->trace()->logs('rpc_request_error', $error); # 记录RPC错误
        error(errorMsg(500, 'RPC Server Call Error !'));

    }
}
