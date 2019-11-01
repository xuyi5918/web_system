<?php

class Results
{
    private $result;
    public function __construct($paramsArr)
    {

        if(isset($paramsArr['return_code']) && $paramsArr['return_code'] == 'error' && $paramsArr['err_code'] >= 5000)
        {
            $error = array(
                'method'=>isset($paramsArr['method']) ? $paramsArr['method'] : '',
                'message'=>$paramsArr['message']
            );
            app()->trace()->logs('rpc_request_error', $error); # 记录RPC错误
            error(errorMsg(500, "RPC Server Error ({$paramsArr['err_code']})!"));
        }

        $this->result = $paramsArr;
    }

    /**
     * @return mixed
     */
    public function result($key = NULL)
    {
        if(empty($key)) {
            return $this->result;
        }

        return ! empty($this->result[$key]) ? $this->result[$key] : array();
    }


    /**
     * @param null $filed
     * @return array
     */
    public function get_array($filed = NULL)
    {
        if(is_array($this->result) || empty($filed)) {
            return $this->result;
        }

        $result = array(
            $filed => $this->result
        );

        return $result;
    }

    /**
     * @return object
     */
    public function get_object()
    {
        return (object) $this->result;
    }
}
