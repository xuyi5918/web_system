<?php
Class CI_Rpc
{
    public $accessKey;
    public $secretKey;
    public $rand_str;
    public $authUrl = 'http://rpc.ipensoft.com/public/index';

    /**
     * 获取RPC执行权限
     *
     * @param $accessKey
     * @param $secretKey
     */
    private function auth($accessKey,$secretKey)
    {
        if(! empty($accessKey) && ! empty($secretKey))
        {
            $this->accessKey = $accessKey;
            $this->secretKey = $secretKey;
            $param = array(
                'access' => $this->accessKey,
                'secret' => $this->secretKey
            );
            $result = $this->post($this->authUrl, $param);

            $row = json_decode($result, TRUE);

            if($row['return_code'] == 'error')
            {
                exit($row['message']);
            }

            return $row;
        }
    }

    /**
     * 获取 sign 字符串
     *
     * @param null $randStr
     * @param $access
     * @return mixed
     */
    public function getSign($randStr = NULL, $access)
    {
        $uri = 'http://rpc.ipensoft.com/public/index/index/rand_auth_str';
        $row = $this->post(
            $uri,
            array(
                'rand_str'=>$randStr,
                'access'=>$access
            )
        );

        return json_decode($row, TRUE);
    }

    /**
     * 调用最终 RPC 中的 model 模型
     * @param $name
     * @param $arguments
     * @return Yar_Client
     */
    public function __call($name, $arguments)
    {
        $signStr = isset($_COOKIE['sign']) ? $_COOKIE['sign'] : '';

//        if(empty($signStr))
//        {
//            $default = config('rpc', config('rpc', 'type'));
//            $result  = $this->auth($default['key'], $default['secret']);
//
//            $sign = $this->getSign($result['rand_str'], $default['key']);
//
//
//            if($sign['return_code'] == 'error') {
//                error(errorMsg(500, 'RPC Server Sign Str Error !'));
//            }
//
//            $signStr = $sign['sign'];
//            setcookie('sign', $signStr, $sign['fail_time']);
//        }


        $model   = reset($arguments);
        return rpc($model, $signStr);
    }

    /**
     * POST
     * @param $http
     * @param array $param
     * @return mixed
     */
    public function post($http,$param = array())
    {
        if(! empty($http) && ! empty($param) && is_array($param))
        {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL,$http);
            curl_setopt($curl, CURLOPT_POST,TRUE);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_POSTFIELDS,$param);
            curl_setopt($curl, CURLOPT_HEADER, FALSE);
            $result = curl_exec($curl);  //$result 获取页面信息
            curl_close($curl);
            return $result;
        }
    }
}
