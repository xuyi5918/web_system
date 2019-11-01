<?php
/**
 * Created by PhpStorm.
 * author: xuyi
 * date: 2019/8/7 13:28
 */
class RPC_Server{

    public $config = array(
        'directory' => '',
    );

    private $app;

    private $group;
    private $class;

    public $methodArr = array();
    public $paramsArr = array();

    public function __construct()
    {

    }

    /**
     * set config item
     * @param $item
     * @param $value
     * @author xuyi
     * @date 2019/8/10
     */
    public function configItem($item, $value)
    {
        $value = trim($value);
        $item = ! in_array($item, $this->config) ? '' : $item;

        if(! empty($item)) {
            $this->config[$item] = trim($value);
            return TRUE;
        }

        return FALSE;
    }

    /**
     * 定位server代码文件位置
     * @param $group
     * @param $class
     * @author xuyi
     * @date 2019/8/10
     */
    public function router($group, $class)
    {
        $group = trim($group);
        $class = trim($class);

        if(users_exists('class/'.$group, $class) === FALSE) {
            error(errorMsg(404, 'RPC Server does not exist'), FALSE);
        }

        $this->app = app()::classes($class, $group);
    }

    /**
     * 获取请求内容
     * @param $request
     * @author xuyi
     * @date 2019/8/10
     */
    public function request($request)
    {
        $request = trim($request);
        $requestArr = empty($request) ? array() : unserialize(base64_decode($request));

        if(empty($requestArr)) {
            return $this;
        }
        # 获取参数内容
        $this->group = empty($requestArr['group']) ? '' : $requestArr['group'];
        $this->class = empty($requestArr['class']) ? '' : $requestArr['class'];
        $this->paramsArr = empty($requestArr['params_arr']) ? array() : $requestArr['params_arr'];
        $this->methodArr = empty($requestArr['method_arr']) ? array() : $requestArr['method_arr'];

        return $this;
    }

    /**
     * 执行服务代码
     * @author xuyi
     * @date 2019/8/10
     * @return mixed
     */
    public function exec()
    {
        # 加载路由
        $this->router($this->group,$this->class);

        # 支持客户端连贯操作
        foreach ($this->methodArr as $item=>$method)
        {

            $paramsArr = empty($this->paramsArr[$method]) ? array() : $this->paramsArr[$method];
            $this->app = call_user_func_array(array($this->app, $method), array_values($paramsArr));
        }

        return $this->app;
    }
}
