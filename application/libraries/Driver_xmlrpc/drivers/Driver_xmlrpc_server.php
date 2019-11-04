<?php
/**
 * RPC 服务端驱动代码逻辑
 * author: xuyi
 * date: 2019/11/3 11:39
 */
class Driver_xmlrpc_server extends CI_Driver
{
    public $requestArr = array();
    public $load       = null;

    public function __construct()
    {
        $this->load = app();
    }

    /**
     * stream
     * @param $stream
     * @author xuyi
     * @date 2019/11/3
     * @return bool
     */
    public function stream($stream)
    {
        $stream = trim($stream);

        if(! empty($stream))
        {
            $this->requestArr = unserialize(base64_decode($stream));
        }

        return TRUE;
    }

    /**
     * 执行服务代码
     * @author xuyi
     * @date 2019/8/10
     * @return mixed
     */
    public function exec()
    {

        $directory = isset($this->requestArr['group']) ? $this->requestArr['group'] : '';
        $file      = isset($this->requestArr['class']) ? $this->requestArr['class'] : '';

        # 加载路由
        if(users_exists('class/'.$directory, $file) === FALSE) {
            return errorMsg(5000, 'RPC Server does not exist');
        }

        # 加载执行文件
        $classes = $this->load->classes($file, $directory);


        # 支持客户端连贯操作
        $paramsArr = isset($this->requestArr['params_arr']) ? $this->requestArr['params_arr'] : array();
        $methodArr = isset($this->requestArr['method_arr']) ? $this->requestArr['method_arr'] : array();
        foreach ($methodArr as $method)
        {
            $paramsRow = empty($paramsArr[$method]) ? array() : $paramsArr[$method];
            if(! method_exists($classes, $method)) {
                return errorMsg(5005, "RPC Server method {$method} does not exist");
            }

            $classes = call_user_func_array(array($classes, $method), array_values($paramsRow));
        }

        return $classes;
    }
}
