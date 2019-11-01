<?php
/**
 * Created by PhpStorm.
 * author: 零上一度
 * date: 2019/6/13 14:42
 */
class lock
{
    /**
     * @param $uri
     * @param $lockTime
     * @param $extSign
     * @author 零上一度
     * @date 2019/6/13
     * @return array
     */
    public function exec($uri, $lockTime, $extSign)
    {
        $uri = trim($uri);
        $lockTime = intval($lockTime);
        $extSign = trim($extSign);

        if(empty($uri) || empty($lockTime)) {
            return errorMsg('lock params is empty!');
        }

        $lockSign = serialize(array('uri'=>$uri, 'lock_time'=>$lockTime, 'ext_sign'=>$extSign));
        $lockSign = md5($lockSign);

        $lock = $this->getLock($lockSign, $lockTime);

        if($lock === TRUE) {
            error(errorMsg('Request url lock status'));
        }

        return successMsg('Request url not lock');
    }

    /**
     * @param $sign
     * @param $lockTime
     * @author 零上一度
     * @date 2019/6/13
     * @return bool
     */
    public function getLock($sign, $lockTime)
    {
        $sign = trim($sign);
        $lockTime = intval($lockTime);
        if(empty($sign) || empty($lockTime)) {
            return FALSE;
        }

        $redis = app()->cache('redis');
        $createTime = $redis->server()->get($sign);

        if(time() - $createTime > $lockTime)
        {
            $redis->server()->del($sign);
            $redis->server()->setnx($sign, time());
            $redis->server()->expire($sign, $lockTime);
            return FALSE;
        }

        return TRUE;
    }
}
