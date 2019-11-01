<?php

/**
 * Class Users_order_trace_log_model shell auto create
 * @author xuyi
 * @date 2019-10-02 04:06:05
 */
class Users_order_trace_log_model extends Core_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = $this->getTable();
    }


    /**
    * getUsersOrderTraceLogListByLogIdArr
    * @param $logIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-10-02 04:06:05
    * @return array
    */
    public function getUsersOrderTraceLogListByLogIdArr($logIdArr, $fields, $dbMaster = FALSE)
    {
        $logIdArr = array_map('intval',$logIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($logIdArr) || empty($fields)) {
            return array();
        }

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'log_id' => $logIdArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($logIdArr))->get()->result_array('log_id');
    }

    /**
    * getUsersOrderTraceLogRowByLogId
    * @param $logId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-10-02 04:06:05
    * @return array
    */
    public function getUsersOrderTraceLogRowByLogId($logId, $fields, $dbMaster = FALSE)
    {
        $logId = intval($logId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($logId) || empty($fields)) {
            return array();
        }

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'log_id' => $logId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }

    /**
    * getUsersOrderTraceLogListByOrderIdArr
    * @param $orderIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-10-02 04:06:05
    * @return array
    */
    public function getUsersOrderTraceLogListByOrderIdArr($orderIdArr, $fields, $dbMaster = FALSE)
    {
        $orderIdArr = array_map('intval',$orderIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($orderIdArr) || empty($fields)) {
            return array();
        }

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'order_id' => $orderIdArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($orderIdArr))->get()->result_array('order_id');
    }

    /**
    * getUsersOrderTraceLogRowByOrderId
    * @param $orderId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-10-02 04:06:05
    * @return array
    */
    public function getUsersOrderTraceLogRowByOrderId($orderId, $fields, $dbMaster = FALSE)
    {
        $orderId = intval($orderId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($orderId) || empty($fields)) {
            return array();
        }

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'order_id' => $orderId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }



    /**
     * saveUsersOrderTraceLog
     * @param $logId
     * @param $saveData
     * @author shell auto create
     * @date 2019-10-02 04:06:05
     * @return array
     */
    public function saveUsersOrderTraceLog($orderId, $saveData)
    {
        $orderId  = intval($orderId);
        $saveData = !is_array($saveData) ? array() : $saveData;

        if(empty($saveData)) {
            return array();
        }

        # 参数整理
        $traceOrderSn = empty($saveData['trace_order_sn']) ? '' : $saveData['trace_order_sn'];
        $payType = empty($saveData['pay_type']) ? 0 : $saveData['pay_type'];
        $createTime = empty($saveData['create_time']) ? 0 : $saveData['create_time'];

        $default = 'users';
        $resultRow = $this->getUsersOrderTraceLogRowByOrderId($orderId, $fields = 'log_id');
        $logId = empty($resultRow['log_id']) ? 0 : intval($resultRow['log_id']);

        if(empty($resultRow))
        {
            $insert = array(
                'order_id'       => $orderId,
                'trace_order_sn' => $traceOrderSn,
                'pay_type'       => $payType,
                'create_time'    => $createTime,
            );

            $this->db_master($default)->insert($this->table, $insert);
            $id = $this->db_master($default)->insert_id();
            if(empty($id)) {
                return errorMsg(500, 'insert db error~');
            }

        }else
        {
            $update = array();

            if(! empty($orderId)) {
                $update['order_id'] = $orderId;
            }

            if(! empty($traceOrderSn)) {
                $update['trace_order_sn'] = $traceOrderSn;
            }

            if(! empty($payType)) {
                $update['pay_type'] = $payType;
            }

            if(! empty($createTime)) {
                $update['create_time'] = $createTime;
            }

            $this->db_master($default)->where(array('log_id' => $logId))->update($this->table, $update);
            $affectedRows = $this->db_master($default)->affected_rows();
            if(empty($affectedRows)) {
                return errorMsg(500, 'update error~');
            }
        }


        return successMsg('ok~');
    }
}
