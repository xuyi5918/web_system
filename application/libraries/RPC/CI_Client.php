<?php
include __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'Invoker.php';
include __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'Results.php';

class CI_Client
{
    private $uri = NULL;
    private $type = FALSE;

    public function __construct($uri = '')
    {

        $this->uri = $uri;
    }

    /**
     *
     * @param $method
     * @param $paramsArr
     */
    public function __call($method, $paramsArr)
    {
        $request = $this->sendRequest($this->uri, $method, $paramsArr)->run($this->type);
//        $request->result()->;
    }

    /**
     *
     * @param $type
     * @return $this
     */
    public function requestType($type)
    {
        $type = empty($type) ? FALSE : TRUE;

        $this->type = $type;
        return $this;
    }

    /**
     *
     * @param $uri
     * @param $method
     * @param $paramArr
     * @return Invoker
     */
    public function sendRequest($uri, $method, $paramArr)
    {
        $uri = trim($uri);
        $method = trim($method);
        $paramArr = ! empty($paramArr) && is_array($paramArr) ? $paramArr : array();

        return new Invoker($uri, $method, $paramArr);
    }


}
