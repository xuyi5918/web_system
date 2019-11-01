<?php
class MODEL_cache
{
    private $model = NULL;
    private $ttl = NULL;
    private $cache = NULL;

    /**
     *
     * MODEL_cache constructor.
     *
     * @param array $param
     */
    public function __construct($param = array())
    {
        $this->model = is_object($param['model']) ? $param['model'] : NULL;
        $this->ttl   = isset($param['ttl']) ? intval($param['ttl']) : CACHE_OUT_TIME_ONE_HOUR;

        if(empty($this->cache) || ! is_object($this->cache))
        {
            app()->cache('file', 'file');
            $this->cache = app()->cache;
        }
    }



    /**
     *
     * @param $method
     * @param $param
     * @return mixed
     */
    public function __call($method, $paramArr)
    {
        $method = trim($method);
        $paramArr = empty($paramArr) ? array() : $paramArr;

        if(empty($method) && empty($paramArr)) {
            error(errorMsg(500, 'Model Cache Server Call Error (1001)!'));
        }

        if(method_exists($this->model, $method) !== TRUE) {
            error(errorMsg(500, 'Model Cache Server Call Error (1002)!'));
        }

        $keys = md5('model('.get_class($this->model).') function: '. $method . '  param: '.serialize($paramArr));

        if(CACHE_SWITCH != TRUE) {
            return call_user_func_array(array($this->model, $method), $paramArr);
        }

        $resultArr = $this->cache->get($keys);

        if(empty($resultArr))
        {
            $resultArr = call_user_func_array(array($this->model, $method), $paramArr);
            $this->cache->save($keys, $resultArr, $this->ttl);
        }

        return $resultArr;
    }

}
