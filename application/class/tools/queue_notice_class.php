<?php
/**
 * Created by PhpStorm.
 * author: xuyi
 * date: 2019/9/25 19:17
 */

class queue_notice_class
{
    const QUEUE_NOTICE  = 'notice:queue:%s';
    public $load        = NULL;
    public $signal      = NULL;
    public $queueList   = array();

    public function __construct()
    {
        $this->load = app();

        $this->load->cache('redis', 'redis');
    }

    /**
     * setSignal
     * @param $signal
     * @author xuyi
     * @date 2019/9/29
     * @return $this
     */
    public function setSignal($signal)
    {
        $signal = trim($signal);
        if(! empty($signal)) {
            $this->signal = $this->load->cache->key(self::QUEUE_NOTICE, array($signal));
        }

        return $this;
    }

    /**
     * addQueue
     * @param array $queueList
     * @author xuyi
     * @date 2019/9/29
     * @return $this
     */
    public function addQueue(array $queueList = array())
    {
        if(empty($queueList)) {
            return $this;
        }

        if(count($queueList) < count($queueList, 1))
        {
            foreach ($queueList as $item=>$queueRow)
            {
                array_unshift($this->queueList, $queueRow);
            }
        }else{
            array_unshift($this->queueList, $queueList);
        }

        return $this;
    }

    /**
     * setQueue
     * @author xuyi
     * @date 2019/9/25
     * @throws Exception
     */
    public function setQueue()
    {
        if(empty($this->signal)) {
            throw new Exception('queue signal not set~', 1);
        }

        if(empty($this->queueList)) {
            throw new Exception('queue task process list not set~', 1);
        }

        foreach ($this->queueList as $item => $queueRow)
        {
            if(empty($queueRow)) {
                continue;
            }

           $result = $this->load->cache->lpush($this->signal, json_encode($queueRow));
        }

        return $result;
    }
}
