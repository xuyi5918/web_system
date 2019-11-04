<?php

/**
 * Class Users_order_info_model shell auto create
 * @author xuyi
 * @date 2019-09-27 11:17:18
 */
class Users_order_info_model extends Driver_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = $this->getTable();
    }


    /**
    * getUsersOrderInfoListByOrderIdArr
    * @param $orderIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-27 11:17:18
    * @return array
    */
    public function getUsersOrderInfoListByOrderIdArr($orderIdArr, $fields, $dbMaster = FALSE)
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
    * getUsersOrderInfoRowByOrderId
    * @param $orderId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-27 11:17:18
    * @return array
    */
    public function getUsersOrderInfoRowByOrderId($orderId, $fields, $dbMaster = FALSE)
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
    * getUsersOrderInfoListByOrderSnArr
    * @param $orderSnArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-27 11:17:18
    * @return array
    */
    public function getUsersOrderInfoListByOrderSnArr($orderSnArr, $fields, $dbMaster = FALSE)
    {
        $orderSnArr = array_map('intval',$orderSnArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($orderSnArr) || empty($fields)) {
            return array();
        }

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'order_sn' => $orderSnArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($orderSnArr))->get()->result_array('order_sn');
    }

    /**
    * getUsersOrderInfoRowByOrderSn
    * @param $orderSn
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-27 11:17:18
    * @return array
    */
    public function getUsersOrderInfoRowByOrderSn($orderSn, $fields, $dbMaster = FALSE)
    {
        $orderSn = trim($orderSn);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($orderSn) || empty($fields)) {
            return array();
        }

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'order_sn' => $orderSn
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }

    /**
    * getUsersOrderInfoListByUsersIdArr
    * @param $usersIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-27 11:17:18
    * @return array
    */
    public function getUsersOrderInfoListByUsersIdArr($usersIdArr, $fields, $dbMaster = FALSE)
    {
        $usersIdArr = array_map('intval',$usersIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($usersIdArr) || empty($fields)) {
            return array();
        }

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'users_id' => $usersIdArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($usersIdArr))->get()->result_array('users_id');
    }

    /**
    * getUsersOrderInfoRowByUsersId
    * @param $usersId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-27 11:17:18
    * @return array
    */
    public function getUsersOrderInfoRowByUsersId($usersId, $payStatus, $fields, $dbMaster = FALSE)
    {
        $usersId = intval($usersId);
        $fields  = trim($fields);
        $payStatus = intval($payStatus);

        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($usersId) || empty($fields)) {
            return array();
        }

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'users_id' => $usersId,
            'pay_status' => $payStatus
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->order_by('order_id DESC')->limit(1)->get()->row_array();
    }

    /**
     * getUsersOrderInfoRowByProductId
     * @param $productId
     * @param $usersId
     * @param $productType
     * @param $fields
     * @param bool $dbMaster
     * @author xuyi
     * @date 2019/10/3
     * @return array
     */
    public function getUsersOrderInfoRowByProductId($productId, $usersId, $productType,$fields, $dbMaster = FALSE)
    {
        $usersId = intval($usersId);
        $fields  = trim($fields);
        $productId = intval($productId);
        $productType = intval($productType);

        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($usersId) || empty($fields) || empty($productId) || empty($productType)) {
            return array();
        }

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'users_id'      => $usersId,
            'product_type'  => $productType,
            'product_id'    => $productId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->order_by('order_id DESC')->limit(1)->get()->row_array();
    }
    /**
     *
     * @param $objectId
     * @param $usersId
     * @param $productType
     * @param $fields
     * @param bool $dbMaster
     * @author xuyi
     * @date 2019/10/3
     * @return mixed
     */
    public function getUsersOrderInfoListByObjectId($objectId, $usersId, $productType, $fields, $dbMaster = FALSE)
    {
        $objectId = intval($objectId);
        $usersId = intval($usersId);
        $productType = intval($productType);
        $fields = trim($fields);


        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'users_id'      => $usersId,
            'object_id'     => $objectId,
            'product_type'  => $productType,
            'pay_status'    => 2
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->order_by('order_id DESC')->get()->result_array();
    }

    /**
     * saveUsersOrderInfo
     * @param $orderId
     * @param $saveData
     * @author shell auto create
     * @date 2019-09-27 11:17:18
     * @return array
     */
    public function saveUsersOrderInfo($orderId, $saveData)
    {
        $orderId  = intval($orderId);
        $saveData = !is_array($saveData) ? array() : $saveData;

        if(empty($saveData)) {
            return array();
        }

        # 参数整理
        $orderSn = empty($saveData['order_sn']) ? '' : $saveData['order_sn'];
        $usersId = empty($saveData['users_id']) ? 0 : $saveData['users_id'];
        $productId = empty($saveData['product_id']) ? 0 : $saveData['product_id'];
        $objectId = empty($saveData['object_id']) ? 0 : $saveData['object_id'];
        $price = empty($saveData['price']) ? 0 : $saveData['price'];
        $productTotal = empty($saveData['product_total']) ? 0 : $saveData['product_total'];
        $productType = empty($saveData['product_type']) ? 0 : $saveData['product_type'];
        $payType = empty($saveData['pay_type']) ? 0 : $saveData['pay_type'];
        $payStatus = empty($saveData['pay_status']) ? 0 : $saveData['pay_status'];
        $createTime = empty($saveData['create_time']) ? 0 : $saveData['create_time'];
        $updateTime = empty($saveData['update_time']) ? 0 : $saveData['update_time'];

        $default   = 'users';
        $resultRow = $this->getUsersOrderInfoRowByOrderId($orderId, $fields = 'order_id');
        if(empty($resultRow))
        {
            $insert = array(
                'order_sn'      => $orderSn,
                'users_id'      => $usersId,
                'object_id'     => $objectId,
                'product_id'    => $productId,
                'price'         => $price,
                'product_total' => $productTotal,
                'product_type'  => $productType,
                'pay_type'      => $payType,
                'pay_status'    => $payStatus,
                'create_time'   => $createTime,
                'update_time'   => $updateTime,
            );

            $this->db_master($default)->insert($this->table, $insert);
            $id = $this->db_master($default)->insert_id();
            if(empty($id)) {
                return errorMsg(500, 'insert db error~');
            }

        }else
        {
            $update = array();

            if(! empty($orderSn)) {
                $update['order_sn'] = $orderSn;
            }

            if(! empty($usersId)) {
                $update['users_id'] = $usersId;
            }

            if(! empty($objectId)) {
                $update['object_id'] = $objectId;
            }

            if(! empty($productId)) {
                $update['product_id'] = $productId;
            }

            if(! empty($productTotal)) {
                $update['product_total'] = $productTotal;
            }

            if(! empty($price)) {
                $update['price'] = $price;
            }

            if(! empty($productType)) {
                $update['product_type'] = $productType;
            }

            if(! empty($payType)) {
                $update['pay_type'] = $payType;
            }

            if(! empty($payStatus)) {
                $update['pay_status'] = $payStatus;
            }

            if(! empty($createTime)) {
                $update['create_time'] = $createTime;
            }

            if(! empty($updateTime)) {
                $update['update_time'] = $updateTime;
            }

            $this->db_master($default)->where(array('order_id' => $orderId))->update($this->table, $update);
            $affectedRows = $this->db_master($default)->affected_rows();
            if(empty($affectedRows)) {
                return errorMsg(500, 'update error~');
            }
        }


        return successMsg('ok~');
    }


    /**
     * editUsersOrderInfoByPayStatus
     * @param $orderId
     * @param $status
     * @author shell auto create
     * @date 2019-09-27 11:17:18
     * @return array
     */
    public function editUsersOrderInfoByPayStatus($orderId, $status)
    {
        $orderId  = intval($orderId);
        $status   = ! in_array($status, array(1, 2)) ? 0 : $status;

        if(empty($orderId) || empty($status)) {
            return errorMsg(404, 'edit databases params is empty');
        }

        $whereArr = array(
            'order_id' => $orderId
        );

        $update = array('pay_status'=>$status);

        $default = 'users';
        $this->db_master($default)->where($whereArr)->update($this->table, $update);
        $num = $this->db_master($default)->affected_rows();

        if(empty($num)) {
            return errorMsg(500, 'edit error~');
        }

        return successMsg('ok~');
    }

    /**
     * 查询出订单列表
     * @param $usersId
     * @param $type
     * @param $rowsNum
     * @param $pageNum
     * @param $fields
     * @param bool $dbMaster
     * @author xuyi
     * @date 2019/10/12
     * @return array
     */
    public function getUsersOrderInfoListByPage($usersId, $type, $rowsNum, $pageNum, $fields, $dbMaster = FALSE)
    {
        $usersId = intval($usersId);
        $rowsNum = intval($rowsNum);
        $pageNum = intval($pageNum);
        $fields  = trim($fields);

        $offset  = ($pageNum - 1) * $rowsNum;

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $resultPage = array(
            'data'       => array(),
            'rows_total' => 0,
            'page_total' => 0,
            'rows_num'   => $rowsNum,
            'page_num'   => $pageNum,
        );

        if(empty($usersId) || empty($fields)) {
            return $resultPage;
        }


        $whereArr = array(
            'users_id'      => $usersId,
            'product_type'  => $type,
            'pay_status'    => 2
        );

        $resultTotal = $db->select('*')->from($this->table)->where($whereArr)->count_all_results();

        if(empty($resultTotal)) {
            return $resultPage;
        }

        $resultList = $db->select($fields)->from($this->table)->where($whereArr)->limit($rowsNum, $offset)
            ->order_by('order_id DESC')->get()->result_array();

        return array(
            'data'       => $resultList,
            'rows_total' => $resultTotal,
            'page_total' => ceil($resultTotal / $rowsNum),
            'rows_num'   => $rowsNum,
            'page_num'   => $pageNum,
        );
    }

}
