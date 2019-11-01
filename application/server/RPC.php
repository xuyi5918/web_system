<?php

/**
 * RPC Server Controller
 * Class Index
 */
class RPC extends Core_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * RPC RUN SERVER
     * @author xuyi
     * @date 2019/8/19
     */
    public function start_run()
    {
        if(self::isPost())
        {
            $this->load->library('CI_Server', array(), 'RPC');
            $request = self::post('request');

            $result = $this->RPC->request($request)->exec();
            $result = isset($result['return_code']) ? $result : successMsg('RPC Request success!', $result);
            $this->displayJson($result);
        }

        error(errorMsg(404));
    }
}
