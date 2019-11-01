<?php

class Core_Model extends  CI_Model
{

    public $table = '';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @author 零上一度
     * @date 2019/6/5
     * @return string
     */
    public function getTable($number = 0)
    {
        $number = intval($number);

        $className = get_class($this);

        if(empty($className)) {
            error(errorMsg(500, 'model class name is empty!'));
        }

        $tableName = str_replace('_model','', $className);

        $tableName = empty($number) ? $tableName : sprintf("{$tableName}_%s", ceil($number / 1000000));

        return strtolower($tableName);
    }

    /**
     * 设置数据模型缓存
     *
     * @param null $cacheType
     * @param null $ttl
     * @return mixed
     */
    public function cache($ttl = NULL, $cacheType = NULL)
    {
        $ttl = is_numeric($ttl) ? intval($ttl) : $ttl;
        $cacheType = trim($cacheType);
        $param = array(
            'model'      => $this,
            'cache_type' => empty($cacheType) ? config('database', 'cache_type') : $cacheType,
            'ttl'        => empty($ttl) ? config('database', 'ttl') : $ttl,
            'remove'     => $ttl === TRUE ? TRUE : FALSE
        );
        return app()->classes('MODEL_cache', 'drive', $param);
    }
}
