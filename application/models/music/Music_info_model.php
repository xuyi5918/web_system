<?php

/**
 * Class Music_info_model shell auto create
 * @author xuyi
 * @date 2019-09-27 11:43:24
 */
class Music_info_model extends Driver_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = $this->getTable();
    }


    /**
    * getMusicInfoListByMusicIdArr
    * @param $musicIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-27 11:43:24
    * @return array
    */
    public function getMusicInfoListByMusicIdArr($musicIdArr, $fields, $dbMaster = FALSE)
    {
        $musicIdArr = array_map('intval',$musicIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($musicIdArr) || empty($fields)) {
            return array();
        }

        $default = 'music';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'music_id' => $musicIdArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($musicIdArr))->get()->result_array('music_id');
    }

    /**
    * getMusicInfoRowByMusicId
    * @param $musicId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-27 11:43:24
    * @return array
    */
    public function getMusicInfoRowByMusicId($musicId, $fields, $dbMaster = FALSE)
    {
        $musicId  = intval($musicId);
        $fields   = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($musicId) || empty($fields)) {
            return array();
        }

        $default = 'music';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'music_id' => $musicId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }

    /**
    * getMusicInfoListByUsersIdArr
    * @param $usersIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-27 11:43:24
    * @return array
    */
    public function getMusicInfoListByUsersIdArr($usersIdArr, $fields, $dbMaster = FALSE)
    {
        $usersIdArr = array_map('intval',$usersIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($usersIdArr) || empty($fields)) {
            return array();
        }

        $default = 'music';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'users_id' => $usersIdArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($usersIdArr))->get()->result_array('users_id');
    }

    /**
    * getMusicInfoRowByUsersId
    * @param $usersId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-27 11:43:24
    * @return array
    */
    public function getMusicInfoRowByUsersId($usersId, $fields, $dbMaster = FALSE)
    {
        $usersId = intval($usersId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($usersId) || empty($fields)) {
            return array();
        }

        $default = 'music';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'users_id' => $usersId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }



    /**
     * saveMusicInfo
     * @param $musicId
     * @param $saveData
     * @author shell auto create
     * @date 2019-09-27 11:43:24
     * @return array
     */
    public function saveMusicInfo($musicId, $saveData)
    {
        $musicId  = intval($musicId);
        $saveData = !is_array($saveData) ? array() : $saveData;

        if(empty($saveData)) {
            return array();
        }

        # 参数整理
        $musicId = empty($saveData['music_id']) ? 0 : $saveData['music_id'];
        $musicName = empty($saveData['music_name']) ? '' : $saveData['music_name'];
        $musicStatus = empty($saveData['music_status']) ? 0 : $saveData['music_status'];
        $areaId = empty($saveData['area_id']) ? 0 : $saveData['area_id'];
        $usersId = empty($saveData['users_id']) ? 0 : $saveData['users_id'];
        $freePlatform = empty($saveData['free_platform']) ? 0 : $saveData['free_platform'];
        $paymentType = empty($saveData['payment_type']) ? 0 : $saveData['payment_type'];
        $createTime = empty($saveData['create_time']) ? 0 : $saveData['create_time'];
        $updateTime = empty($saveData['update_time']) ? 0 : $saveData['update_time'];

        $default = 'music';
        $resultRow = $this->getMusicInfoRowByMusicId($musicId, $fields = 'music_id');
        if(empty($resultRow))
        {
            $insert = array(
                'music_name' => $musicName,
                'music_status' => $musicStatus,
                'area_id' => $areaId,
                'users_id' => $usersId,
                'free_platform' => $freePlatform,
                'payment_type' => $paymentType,
                'create_time' => $createTime,
                'update_time' => $updateTime,
            );

            $this->db_master($default)->insert($insert, $this->table);
            $id = $this->db_master($default)->insert_id();
            if(empty($id)) {
                return errorMsg(500, 'insert db error~');
            }

        }else
        {
            $update = array();

            if(! empty($musicName)) {
                $update['music_name'] = $musicName;
            }

            if(! empty($musicStatus)) {
                $update['music_status'] = $musicStatus;
            }

            if(! empty($areaId)) {
                $update['area_id'] = $areaId;
            }

            if(! empty($usersId)) {
                $update['users_id'] = $usersId;
            }

            if(! empty($freePlatform)) {
                $update['free_platform'] = $freePlatform;
            }

            if(! empty($paymentType)) {
                $update['payment_type'] = $paymentType;
            }

            if(! empty($createTime)) {
                $update['create_time'] = $createTime;
            }

            if(! empty($updateTime)) {
                $update['update_time'] = $updateTime;
            }

            $this->db_master($default)->where(array('music_id' => $musicId))->update($update, $this->table);
            $affectedRows = $this->db_master($default)->affected_rows();
            if(empty($affectedRows)) {
                return errorMsg(500, 'update error~');
            }
        }


        return successMsg('ok~');
    }


    /**
     * editMusicInfoByMusicStatus
     * @param $musicId
     * @param $status
     * @author shell auto create
     * @date 2019-09-27 11:43:24
     * @return array
     */
    public function editMusicInfoByMusicStatus($musicId, $status)
    {
        $musicId  = intval($musicId);
        $status = in_array($status, array(1, 2, 3)) ? 0 : $status;

        if(empty($musicId) || empty($status)) {
            return errorMsg(404, 'edit databases params is empty');
        }

        $whereArr = array(
            'music_id' => $musicId
        );

        $update = array('music_status'=>$status);

        $default = 'music';
        $this->db_master($default)->where($whereArr)->update($this->table, $update);
        $num = $this->db_master($default)->affected_rows();

        if(empty($num)) {
            return errorMsg(500, 'edit error~');
        }

        return successMsg('ok~');
    }


}
