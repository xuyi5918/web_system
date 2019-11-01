<?php
include __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'Invoker.php';
include __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'Results.php';

class RPC_Client
{
    private $uri = NULL;
    private $type = FALSE;

    private $invoker = NULL;
    private $group = NULL;
    private $class = NULL;

    public function __construct($uri)
    {
        $this->uri = isset($uri['uri']) ? $uri['uri'] : '';
    }

    /**
     * @param string $uri
     * @param $group
     * @param $class
     * @author xuyi
     * @date 2019/8/13
     */
    public function uri($uri = '', $group, $class)
    {
        $this->group = $group;
        $this->class = $class;
        $this->uri = ! empty($uri) ? $uri : '';
    }

    /**
     * 调用远程内容
     *
     * @param $method
     * @param $paramsArr
     */
    public function __call($method, $paramsArr)
    {
        $method = trim($method);
        $this->type = TRUE;
        if($this->type != TRUE) {
            return $this->sendRequest($this->uri, $method, $paramsArr)->run('get_default');
        }

        if(in_array($method, array('get_string', 'get_array', 'get_object', 'result'))) {
            $this->type = FALSE;
            return $this->sendRequest()->run($method, $paramsArr);
        }

        $this->sendRequest($this->uri, $method, $paramsArr);
        return $this;
    }

    /**
     * 请求类型 TRUE: 可以进行连贯操作调用远程代码 FALSE: 单一调用
     * @param $type
     * @return $this
     */
    public function requestType($type = FALSE)
    {
        $type = empty($type) ? FALSE : TRUE;

        $this->type = $type;
        return $this;
    }

    /**
     * 发送请求参数
     *
     * @param $uri
     * @param $method
     * @param $paramArr
     * @return Invoker
     */
    public function sendRequest($uri = NULL, $method = NULL, $paramArr = array())
    {
        $uri = trim($uri);
        $method = trim($method);
        $paramArr = ! empty($paramArr) && is_array($paramArr) ? $paramArr : array();


        if(empty($this->invoker)) {
            $this->invoker = new Invoker();
        }

        $this->invoker->bindRequestParamsArr($uri, $this->group, $this->class, $method, $paramArr);

        return $this->invoker;
    }


}
