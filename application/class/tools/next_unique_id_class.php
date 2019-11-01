<?php
/**
 * Created by PhpStorm.
 * author: xuyi
 * date: 2019/9/22 11:48
 */
class next_unique_id_class
{
    public $load = NULL;

    const USERS_NEXT_UNIQUE_ID_SIGNAL_KEY = 'users:next_unique_id:%s';

    public function __construct()
    {
        $this->load = app();
    }

    /**
     * 訂單號唯一ID生產
     * @author xuyi
     * @date 2019/10/1
     * @return int
     */
    public function getOrderNextUniqueNoId()
    {
        return $this->getNextUniqueId('order_no_id', 30);
    }

    /**
     * 获取用户自增ID
     * @author xuyi
     * @date 2019/9/22
     * @return int
     */
    public function getUsersNextUsersId()
    {
        return $this->getNextUniqueId('users_id', 0);
    }

    /**
     * create unique id
     * @param $signal
     * @author xuyi
     * @date 2019/9/22
     * @return int
     */
    private function getNextUniqueId($signal, $expire)
    {
        $signal = trim($signal);
        $expire = intval($expire);

        if(empty($signal)) {
            return 0;
        }

        $cache = $this->load->cache('redis', 'redis');
        $signalRedisKey = $cache->key(self::USERS_NEXT_UNIQUE_ID_SIGNAL_KEY, array($signal));

        $nextId = $cache->incr($signalRedisKey, 1);

        if(! empty($expire) && $nextId <= 1) {
            $cache->expire($signalRedisKey, $expire);
        }

        return $nextId;
    }
}
