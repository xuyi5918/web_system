<?php
/**
 * Created by PhpStorm.
 * User: njj
 * Date: 2017/10/11
 * Time: 12:13
 */
class Invoker
{
    private static $uri = NULL;
    private static $request = array();

    public function __construct()
    {

    }

    /**
     * 生成请求参数
     *
     * @param string $uri
     * @param $method
     * @param $paramsArr
     */
    public function bindRequestParamsArr($uri = '', $group, $class, $method, $paramsArr)
    {

//        $request = array(
//            'group' => 'recommend',
//            'class' => 'recommend_info_class',
//            'params_arr' => array(
//                'getRecommendPageInfo' => array('pc', 'nav')
//            ),
//            'method_arr' => array(
//                'getRecommendPageInfo'
//            )
//
//        );
//
//        $request = base64_encode(serialize($request));


        $uri   = trim($uri);
        $group = trim($group);
        $class = trim($class);


        if(! empty($uri) && ! empty($method))
        {
            self::$uri = $uri;
            self::$request['group'] = $group;
            self::$request['class'] = $class;

            self::$request['params_arr'][$method] = $paramsArr;
            self::$request['method_arr'][] = $method;
            return FALSE;
        }

        return TRUE;
    }

    /**
     * 运行远程方法
     *
     * @param $type
     *
     * @return mixed
     */
    public function run($type, $paramArr)
    {
        $params = array('request' => base64_encode(serialize(self::$request)));
        $headers = array(
            'X-REQUESTED-API' => 'rpc'
        );

        $startTime = microtime(3);
        $request = http(self::$uri, $params, 'POST', $headers);

        $endTime = microtime(3);
        app()->trace()->logs('RPC RUN TIME : ', ($endTime - $startTime)); # 记录RPC错误


        self::$uri = NULL; # 重置URL
        self::$request = array(); # 重置参数

        $results = new Results($request);
        return call_user_func_array(array(&$results, $type), $paramArr);
    }
}
