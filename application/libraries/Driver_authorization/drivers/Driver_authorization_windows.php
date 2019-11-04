<?php
/**
 * Created by PhpStorm.
 * author: xuyi
 * date: 2019/10/31 20:23
 */
class Driver_authorization_windows extends CI_Driver
{
    public $validate = array();
    public $authorization = FALSE;

    const WINDOWS_SIGNAL    = 'db76ee9b41e111a6f38b78b6fc7d403d';
    const WINDOWS_VERSION   = 1.20; # 可用的最小版本
    const REQUEST_MAX_TIME  = 5;


    /**
     * @param array $validate
     * @author xuyi
     * @date 2019/11/1
     */
    public function validate($validate = array())
    {
        $requestTime = '';
        isset($_SERVER['HTTP_X_REQUEST_TIME']) && $requestTime = $_SERVER['HTTP_X_REQUEST_TIME'];

        $validate['request_time'] = empty($validate['request_time']) ?  $requestTime : $validate['request_time'];
        $this->validate = $validate;
    }

    /**
     * 检测版本是否可用
     * @param $ver
     * @author xuyi
     * @date 2019/11/1
     */
    public function version($ver)
    {
       return $this->authorization = self::WINDOWS_VERSION <= floatval($ver);
    }

    /**
     * signal
     * @param $validate
     * @param $signal
     * @author xuyi
     * @date 2019/11/1
     * @return bool
     */
    public function signal($validate, $signal)
    {
        ksort($validate);
        $requestTime = empty($validate['request_time']) ? 0 : intval($validate['request_time']);

        $httpBuildQuery = http_build_query($validate);
        return $this->authorization = strtoupper(md5($httpBuildQuery . self::WINDOWS_SIGNAL)) == $signal
            && time() - $requestTime <= self::REQUEST_MAX_TIME; // 防止重播请求
    }

    /**
     * 执行验证
     * @author xuyi
     * @date 2019/10/31
     * @return mixed
     */
    public function exec()
    {
        $signal = empty($this->validate['signal']) ? '' : $this->validate['signal'];
        $version = empty($this->validate['version']) ? '' : $this->validate['version'];
        unset($this->validate['signal']);

        $this->signal($this->validate, $signal); # 签名验证
        if(! $this->authorization) {
            return errorMsg(500, '签名验证失败, 请重试！');
        }

        $this->version($version); # 版本可用验证
        if(! $this->authorization) {
            return errorMsg(100, '当前版本已经不在支持, 请升级最新版本！');
        }

        $this->authorization = TRUE;

        return successMsg('authorization success');
    }
}
