<?php

/**
 * CI class Object
 * @return CI_Controller
 */
function &app()
{
    return get_instance();
}

/**
 * @param $validate
 * @return bool
 */
function validate($validate)
{
    if(isset($validate['return_code']) and $validate['return_code'] === 'Success') {
        return TRUE;
    }

    return FALSE;
}
/**
 * error logs
 * @param $level
 * @param $message
 * @param $filename
 * @param $line
 * @param $content
 */
function error_logs($level, $message, $filename, $line, $content)
{
    var_dump($level, $message, $filename, $line);
}

/**
 * get rpc function object
 *
 * @param null $model
 * @param $sign
 * @return Yar_Client
 */
function rpc($model = NULL, $sign)
{
    $model  = is_string($model) ? trim($model) : '';
    $sign   = is_string($sign) ? trim($sign) : '';

    $CI = app();
    if(empty($model)) {
        return $CI->classes($name = 'MODEL_exception', $group = 'exception');
    }

    # 调用RPC 。
    $configArr = config('config','RPC_SERVER_GROUP');



    $http = isset($configArr['http']) ? $configArr['http'] : '';
    if(empty($http)) {
        error(errorMsg(500, 'RPC Config Params Error !'));
    }

    $paramArr = explode('/', $model);
    $group = reset($paramArr);
    $class = end($paramArr);


    $requestArr = http_build_query(array('sign'=>$sign), '', '&');
    $http = $http . '?' . $requestArr;

    $CI->trace()->logs('rpc_uri_list', $http); # 记录发起的 RPC 请求 URL

    ##########################################PHP RPC################################################

    # RPC 方式是否是PHP脚本 发送HTTP请求执行
    if(in_array(config('config','RPC_TYPE'), array('PHP')))
    {
        $name = md5($class);
        app()->load->library('CI_Client', array('uri'=>$http), $name);
        try{
            app()->$name->uri($http, $group, $class);
            return app()->$name;
        } catch (Exception $e) {
            log_message(1, $e->getMessage());
            error(errorMsg(500, $e->getMessage()));
        }
    }


    ######################################### Yar RPC ##############################################

    $Exception = http($http, array());

    if(isset($Exception['return_code']) && $Exception['return_code'] == 'error')
    {
        $logMessage = 'err_code: ' . $Exception['err_code'] . ' message: ' . $Exception['message'];
        log_message(1, $logMessage);
        return $CI->classes($name = 'MODEL_exception', $group = 'exception');
    }

    try{
        return new Yar_Client($http);
    }catch (Yar_Client_Protocol_Exception $e) {
        log_message(1, $e->getMessage());
        error(errorMsg(500, $e->getMessage()));
    }

    return $CI->classes($name = 'MODEL_exception', $group = 'exception');
}

/**
 * get or set config value
 *
 * @param null $object
 * @param null $key
 * $param File $configType File Db
 * @return array
 */
function config($object, $key = '', $value = NULL, $configType = 'File')
{
    $object = trim($object);
    $key    = trim($key);
    $configType = in_array($configType, array('File', 'Db')) ? trim($configType) : 'File';

    $CI = app();
    if(empty($object) && empty($key)) {
       return NULL;
    }


    $configValue = NULL;

    switch ($configType)
    {
        case 'File':
            $CI->config->load($object, TRUE, TRUE);
            if($value === NULL) {

                $config = $CI->config->item($object);
                $configValue = empty($config[$key]) ? NULL : $config[$key];

            }else{
                $configValue = $CI->config->set_item($key, $value);
            }
            break;
        case 'Db':
            $CI->model('configure', 'configure');
            $configValue = $CI->Configure->Cache(CACHE_OUT_TIME_ONE_HOUR)->$key($object, $value); # 数据库配置默认进入缓存
            break;
    }

    return $configValue;
}

/**
 *
 * @param $url
 * @param $paramArr
 * @param bool|FALSE $info
 * @return mixed|null
 */
function http($url, $paramArr, $requestType = 'GET', $headers = array(), $info = FALSE)
{
    if(empty($url)) {
        return NULL;
    }

    $headers['User-Agent'] = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.9; rv:33.0) Gecko/20100101 Firefox/33.0';

    $headerArr = array();
    foreach( $headers as $n => $v ) {
        $headerArr[] = $n .':' . $v;
    }




    $paramArr = http_build_query($paramArr, '', '&');
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_HTTPHEADER , $headerArr);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, TRUE);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $requestType);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $paramArr);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_HEADER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    $result = curl_exec($curl);  //$result 获取页面信息

    curl_close($curl);

    $resultArr = json_decode($result, TRUE);
    if($resultArr === NULL && in_array(config('config','RPC_TYPE'), array('PHP'))) {
       app()->trace()->logs('rpc_server_error', $result); # 记录RPC错误
    }

    return $info ? curl_getinfo($curl) : $resultArr;
}

/**
 * set browser cache
 *
 * @param null $ttl
 */
function BrowserCache($ttl = NULL)
{

    $ttl = intval($ttl);
    $ttl = empty($ttl) ? config('webcache', 'browser_cache_time') : $ttl;


    $modifiedTime = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : '';
    if($modifiedTime && (strtotime($modifiedTime) + $ttl > time())) {
        header('HTTP/1.1 304');
        exit();
    }

    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Expires: " . gmdate("D, d M Y H:i:s", time() + $ttl) . " GMT");
    header("Cache-Control: max-age={$ttl}");
}

/**
 * get view ioc function
 *
 * @return mixed
 */
function view()
{
    return app()->view();
}


/**
 * get themes paths
 *
 * @param $dir
 */
function themes($dir = NULL)
{
    $dir = trim($dir);
    empty($dir) && exit('themes is dir null !');
    $platform = config_item('platform');
    $theme = $platform. $dir . DIRECTORY_SEPARATOR;
    return  $theme;
}

/**
 * 支持跨域操作
 * @author xuyi
 * @date 2019/9/30
 */
function isAllowOrigin($isStatus = TRUE)
{
    if($isStatus == TRUE)
    {
        $method = implode(',', array('POST', 'GET'));

        config(''); # 读取Origin配置

        header("Access-Control-Allow-Origin:http://www.ipensoft.com");
        header('Access-Control-Allow-Methods:'.$method);
        header('Access-Control-Allow-Headers:Origin, X-Requested-With, Content-Type');
    }

    return $isStatus;
}

/**
 * web error view
 * @param $paramsArr
 */
function error($paramsArr, $tipPage = TRUE)
{
    if(empty($paramsArr))
    {
        $paramsArr['return_code'] = 'error';
        $paramsArr['err_code'] = 404;
    }

    if(isset($paramsArr['return_code']) && $paramsArr['return_code'] == 'error' && ! empty($paramsArr['err_code']))
    {
        $error = config('error', $paramsArr['err_code']);


        $message = isset($paramsArr['message']) && ! empty($paramsArr['message']) ? $paramsArr['message'] : $error['message'];
        $header = isset($error['header']) ? $error['header'] : 'HTTP/1.1 200 OK';
        header($header);


        $logArr = array(
            'level'   => isset($paramsArr['level']) ? $paramsArr['level'] : '',
            'module'  => isset($paramsArr['module']) ? $paramsArr['module'] : '',
            'message' => isset($paramsArr['info']) ? $paramsArr['info'] : ''
        );
        $logArrStatus = array_filter($logArr);

        if(! empty($logArrStatus)) {
            logWrite($logArr);
        }


        if($tipPage === TRUE) {
            app()->displayTpl(array('message' => $message), CACHE_OUT_TIME_ONE_MINU, $error['tpl']);
            exit();
        }

        app()->displayJson($paramsArr);
        exit();
    }
}

/**
 * @param $logArr
 */
function logWrite($logArr)
{
    if(empty($logArr)) {
        return FALSE;
    }

    $logMsg  = "[time : ".date('Y-m-d H:i:s', time())."]\n";
    $logMsg .= "[level: {$logArr['level']}]\n";
    $logMsg .= "[module: {$logArr['module']}]\n";
    $logMsg .= "[message: {$logArr['message']}]\n";

    $filename = APPPATH .DIRECTORY_SEPARATOR.'logs'.DIRECTORY_SEPARATOR .$logArr['module'] .'txt';
    file_put_contents($filename, $logMsg);
}

/**
 * create page links
 *
 * @param $uri
 * @param $totalRows
 * @param $totalPage
 * @return mixed
 */
function createPageLinks($uri, $totalRows, $totalPage)
{
    $load = app();

    $load->load->library('pagination');
    $pageinationConf = array(
        'base_url'      =>  $uri,
        'total_rows'    =>  $totalRows,
        'per_page'      =>  $totalPage
    );
    $load->pagination->initialize($pageinationConf);

    return $load->pagination->create_links();
}

/**
 * @param $array
 * @param $keys
 * @return array
 */
function arrayColumnValues($array, $keys)
{
    $array = ! empty($array) && is_array($array) ? $array : array();
    $keys  = ! empty($keys) ? array() : $keys;

    if(!  empty($keys) && is_string($keys)) {
        return array_column($array, $keys);
    }

    $result = array();
    if(! empty($keys) && is_array($keys))
    {
        foreach ($array as $item=>$value)
        {
            foreach ($keys as $k=>$val)
            {
                $findVal = isset($value[$val]) ? $value[$val] : NULL;
                if(empty($findVal)) {
                    continue;
                }

                $result[] = $findVal;
            }
        }
    }

    return $result;
}

/**
 *
 * @param int $code
 * @param string $message
 * @return array
 */
function errorMsg($code = 0, $message = '' ,$info = '')
{
    $infoArr = ! empty($info) && ! is_array($info) ? explode('|', $info) : '';

    $default = array(
        'return_code'   => 'error',
        'err_code'      => $code,
        'message'       => $message,
        'info'          => empty($infoArr) ? $info : $infoArr
    );
    return array_filter($default);
}

/**
 *
 * @param string $message
 * @param array $param
 * @return array
 */
function successMsg($message = '', $params = array())
{
    $default = array(
        'return_code'   => 'Success',
        'message'       => $message,
        'result'        => empty($params) ? array() : $params
    );

    return $default;
}

/**
 * @param $path
 * @author xuyi
 * @date 2019/7/9
 * @return
 */
function import($path)
{
    $path = trim($path);
    if(empty($path)) {
        return;
    }


    $uri = VIEWPATH . themes(config('config', 'theme_views_path'));
    include $uri .DIRECTORY_SEPARATOR . $path;
    return;
}

/**
 * @param $path
 * @author xuyi
 * @date 2019/9/21
 * @return string
 */
function staticUrl($path, $isCDN = TRUE)
{
    $path = trim($path);
    if(empty($path)) {
        return '';
    }

    if($isCDN === TRUE) {
        return STATIC_URL . $path;
    }

    $uri = '/application/views/' . themes(config('config', 'theme_views_path'));
    return  $uri .DIRECTORY_SEPARATOR. 'resources'.DIRECTORY_SEPARATOR.$path;
}

/**
 *  创建订单号
 *  @param userId
 *  @param $
 */
function createOrderNo()
{
    $app = app();

    $nextUniqueId = $app->classes('next_unique_id_class', 'tools');
    $nextOrderNoId = $nextUniqueId->getOrderNextUniqueNoId();

    if(empty($nextOrderNoId))
    {
        $uniqueId = explode('.', uniqid('', true));
        $nextOrderNoId = end($uniqueId);
    }

    $date    = date('Ymdhis', time());
    $orderNo = $date . str_pad($nextOrderNoId, 8, 0, STR_PAD_LEFT);
    return $orderNo;
}

/**
 * 下划线字符串转换驼峰
 * @param $toHump
 * @author xuyi
 * @date 2019/9/21
 * @return string
 */
function toHump($toHump)
{
    return implode('', array_map('ucfirst', explode('_', $toHump)));
}

function dump($paramArr)
{
    echo '<pre>';
    var_dump($paramArr);
}

/**
 * 付费状态
 * @param $isPayment
 * @param $freePlatform
 * @param $curPlatform
 * @author xuyi
 * @date 2019/10/27
 * @return bool
 */
function isPayment($isPayment, $freePlatform, $curPlatform)
{
    $isPayment    = intval($isPayment);
    $freePlatform = intval($freePlatform);
    $curPlatform  = trim($curPlatform);

    $platform = array(
        'pc'        => array(1), # PC web平台免费
        'android'   => array(3, 5), # 5: mobile
        'ios'       => array(4, 5),
        'h5'        => array(2),
        'windows'   => array(6, 8), # 8: desktop
        'mac'       => array(7, 8)
    );

    if($isPayment == 3) {
        return FALSE;
    }

    return ! in_array($freePlatform, isset($platform[$curPlatform]) ? $platform[$curPlatform] : array());
}
