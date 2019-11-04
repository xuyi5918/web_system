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

        $this->authorization();
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
            $stream = self::post('request', '', 'trim');

            $this->load->driver('Driver_xmlrpc');
            $this->driver_xmlrpc->driver('server')->initialize($initialize = array());
            $result = $this->driver_xmlrpc->request_stream($stream)->exec();

            $result = isset($result['return_code']) ? $result : successMsg('RPC Request success!', $result);
            $this->displayJson($result);
        }

        error(errorMsg(404));
    }
}
