<?php

/**
 * Class Users_platform_model shell auto create
 * @author xuyi
 * @date 2019-09-21 09:34:05
 */
class Users_platform_model extends Core_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = $this->getTable();
    }


    /**
    * getUsersPlatformListByPlatformIdArr
    * @param $platformIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-21 09:34:05
    * @return array
    */
    public function getUsersPlatformListByPlatformIdArr($platformIdArr, $fields, $dbMaster = FALSE)
    {
        $platformIdArr = array_map('intval',$platformIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($platformIdArr) || empty($fields)) {
            return array();
        }

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'platform_id' => $platformIdArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($platformIdArr))->get()->result_array('platform_id');
    }

    /**
    * getUsersPlatformRowByPlatformId
    * @param $platformId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-21 09:34:05
    * @return array
    */
    public function getUsersPlatformRowByPlatformId($platformId, $fields, $dbMaster = FALSE)
    {
        $platformId = intval($platformId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($platformId) || empty($fields)) {
            return array();
        }

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'platform_id' => $platformId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }

    /**
    * getUsersPlatformListBySignArr
    * @param $signArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-21 09:34:05
    * @return array
    */
    public function getUsersPlatformListBySignArr($signArr, $fields, $dbMaster = FALSE)
    {
        $signArr = array_map('intval',$signArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($signArr) || empty($fields)) {
            return array();
        }

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'sign' => $signArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($signArr))->get()->result_array('sign');
    }

    /**
    * getUsersPlatformRowBySign
    * @param $sign
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-21 09:34:05
    * @return array
    */
    public function getUsersPlatformRowBySign($sign, $fields, $dbMaster = FALSE)
    {
        $sign = intval($sign);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($sign) || empty($fields)) {
            return array();
        }

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'sign' => $sign
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }

    /**
    * getUsersPlatformListByUsersIdArr
    * @param $usersIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-21 09:34:05
    * @return array
    */
    public function getUsersPlatformListByUsersIdArr($usersIdArr, $fields, $dbMaster = FALSE)
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
    * getUsersPlatformRowByUsersId
    * @param $usersId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-21 09:34:05
    * @return array
    */
    public function getUsersPlatformRowByUsersId($usersId, $fields, $dbMaster = FALSE)
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
     * saveUsersPlatform
     * @param $platformId
     * @param $saveData
     * @author shell auto create
     * @date 2019-09-21 09:34:05
     * @return array
     */
    public function saveUsersPlatform($platformId, $saveData)
    {
        $platformId  = intval($platformId);
        $saveData = !is_array($saveData) ? array() : $saveData;

        if(empty($saveData)) {
            return array();
        }

        # 参数整理
        $platformSign = empty($saveData['platform_sign']) ? '' : $saveData['platform_sign'];
        $platform = empty($saveData['platform']) ? 0 : $saveData['platform'];
        $sign = empty($saveData['sign']) ? '' : $saveData['sign'];
        $usersId = empty($saveData['users_id']) ? 0 : $saveData['users_id'];
        $createTime = empty($saveData['create_time']) ? 0 : $saveData['create_time'];

        $default = 'users';
        $resultRow = $this->getUsersPlatformRowByPlatformId($platformId, $fields = 'platform_id');
        if(empty($resultRow))
        {
            $insert = array(
                'platform_sign' => $platformSign,
                'platform' => $platform,
                'sign' => $sign,
                'users_id' => $usersId,
                'create_time' => $createTime,
            );

            $this->db_master($default)->insert($insert, $this->table);
            $id = $this->db_master($default)->insert_id();
            if(empty($id)) {
                return errorMsg(500, 'insert db error~');
            }

        }else
        {
            $update = array();

            if(! empty($platformSign)) {
                $update['platform_sign'] = $platformSign;
            }

            if(! empty($platform)) {
                $update['platform'] = $platform;
            }

            if(! empty($sign)) {
                $update['sign'] = $sign;
            }

            if(! empty($usersId)) {
                $update['users_id'] = $usersId;
            }

            $this->db_master($default)->where(array('platform_id' => $platformId))->update($update, $this->table);
            $affectedRows = $this->db_master($default)->affected_rows();
            if(empty($affectedRows)) {
                return errorMsg(500, 'update error~');
            }
        }


        return successMsg('ok~');
    }
}
