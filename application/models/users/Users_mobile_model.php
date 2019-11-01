<?php

/**
 * Class Users_mobile_model shell auto create
 * @author xuyi
 * @date 2019-09-21 09:39:29
 */
class Users_mobile_model extends Core_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = $this->getTable();
    }


    /**
    * getUsersMobileListByMobileIdArr
    * @param $mobileIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-21 09:39:29
    * @return array
    */
    public function getUsersMobileListByMobileIdArr($mobileIdArr, $fields, $dbMaster = FALSE)
    {
        $mobileIdArr = array_map('intval',$mobileIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($mobileIdArr) || empty($fields)) {
            return array();
        }

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'mobile_id' => $mobileIdArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($mobileIdArr))->get()->result_array('mobile_id');
    }

    /**
    * getUsersMobileRowByMobileId
    * @param $mobileId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-21 09:39:29
    * @return array
    */
    public function getUsersMobileRowByMobileId($mobileId, $fields, $dbMaster = FALSE)
    {
        $mobileId = intval($mobileId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($mobileId) || empty($fields)) {
            return array();
        }

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'mobile_id' => $mobileId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }

    /**
    * getUsersMobileListByMobileArr
    * @param $mobileArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-21 09:39:29
    * @return array
    */
    public function getUsersMobileListByMobileArr($mobileArr, $fields, $dbMaster = FALSE)
    {
        $mobileArr = array_map('intval',$mobileArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($mobileArr) || empty($fields)) {
            return array();
        }

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'mobile' => $mobileArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($mobileArr))->get()->result_array('mobile');
    }

    /**
    * getUsersMobileRowByMobile
    * @param $mobile
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-21 09:39:29
    * @return array
    */
    public function getUsersMobileRowByMobile($mobile, $fields, $dbMaster = FALSE)
    {
        $mobile = trim($mobile);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($mobile) || empty($fields)) {
            return array();
        }

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'mobile' => $mobile
        );


        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }

    /**
    * getUsersMobileListByUsersIdArr
    * @param $usersIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-21 09:39:29
    * @return array
    */
    public function getUsersMobileListByUsersIdArr($usersIdArr, $fields, $dbMaster = FALSE)
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
    * getUsersMobileRowByUsersId
    * @param $usersId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-21 09:39:29
    * @return array
    */
    public function getUsersMobileRowByUsersId($usersId, $fields, $dbMaster = FALSE)
    {
        $usersId = intval($usersId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($usersId) || empty($fields)) {
            return array();
        }

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'users_id' => $usersId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }



    /**
     * saveUsersMobile
     * @param $mobileId
     * @param $saveData
     * @author shell auto create
     * @date 2019-09-21 09:39:29
     * @return array
     */
    public function saveUsersMobile($mobileId, $saveData)
    {
        $mobileId  = intval($mobileId);
        $saveData = !is_array($saveData) ? array() : $saveData;

        if(empty($saveData)) {
            return array();
        }

        # 参数整理
        $mobile = empty($saveData['mobile']) ? '' : $saveData['mobile'];
        $usersId = empty($saveData['users_id']) ? 0 : $saveData['users_id'];
        $createTime = empty($saveData['create_time']) ? 0 : $saveData['create_time'];

        $default = 'users';
        $resultRow = $this->getUsersMobileRowByMobileId($mobileId, $fields = 'mobile_id');
        if(empty($resultRow))
        {
            $insert = array(
                'mobile' => $mobile,
                'users_id' => $usersId,
                'create_time' => $createTime,
            );

            $this->db_master($default)->insert($this->table, $insert);
            $id = $this->db_master($default)->insert_id();
            if(empty($id)) {
                return errorMsg(500, 'insert db error~');
            }

        }else
        {
            $update = array();

            if(! empty($mobile)) {
                $update['mobile'] = $mobile;
            }

            if(! empty($usersId)) {
                $update['users_id'] = $usersId;
            }


            $this->db_master($default)->where(array('mobile_id' => $mobileId))->update($this->table, $update);
            $affectedRows = $this->db_master($default)->affected_rows();
            if(empty($affectedRows)) {
                return errorMsg(500, 'update error~');
            }
        }


        return successMsg('ok~');
    }


}
