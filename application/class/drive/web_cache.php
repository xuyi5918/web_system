<?php
class WEB_cache
{
    private $cache = NULL;
    public $debrisTemp = '';
    private $ttl = NULL;
    private $cacheType = 'memcache';

    public $result = array();

    public function __construct($param = array())
    {
        $this->ttl = empty($param['ttl']) ? CACHE_OUT_TIME_THIRTY_SECO : $param['ttl'];
        $this->cacheType = empty($param['cache_type']) ? $this->cacheType : $param['cache_type'];

        if(! empty($param)) {
            $this->cache = app()->cache();
        }
    }

    /**
     *
     * @param array $paramArr
     * @return array
     */
    private function attribute(array $paramArr = array())
    {
        $class = isset($paramArr['class']) && ! empty($paramArr['class']) ? $paramArr['class'] : '';
        $function = isset($paramArr['function']) && ! empty($paramArr['function']) ? $paramArr['function'] : '';

        if(empty($class) || empty($function)) {
            return $paramArr;
        }

        $merge = array(
            'class'     => $class,
            'function'  => $function
        );

        return array_merge($paramArr, $merge);
    }

    /**
     *
     * @param $id
     * @param array $paramArr
     */
    public function benginCache($paramArr = array())
    {
        $uri = app()->urlPath()->uriString;
        $this->debrisTemp = 'tpl('.$this->ttl.'):' . $uri .' param:'. md5(serialize($paramArr));
        $debrisHtml = $this->cache->gets($this->debrisTemp, $this->cacheType);

        if(! empty($debrisHtml))
        {
            echo $debrisHtml;
            return FALSE;
        }

        ob_start();
        $attribute = array_filter($this->attribute($paramArr));
        $classes = app()->classes($attribute['class'], $attribute['dir']);

        unset($attribute['class'], $attribute['dir']);
        $this->result = call_user_func_array(array(&$classes, $attribute['function']), $attribute);
        return TRUE;
    }

    /**
     *  结束时候设置缓存
     */
    public function endCache()
    {
        $debrisHtml = ob_get_clean();
        $this->cache->set($this->debrisTemp, $debrisHtml, $this->ttl, $this->cacheType);
        echo $debrisHtml;
    }

}
