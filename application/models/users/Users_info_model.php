<?php

/**
 * Class Users_info_model shell auto create
 * @author xuyi
 * @date 2019-09-06 09:57:28
 */
class Users_info_model extends Driver_Model
{
    public function __construct()
    {
        parent::__construct();
    }


    /**
    * getUsersInfoListByIdArr
    * @param $idArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-06 09:57:28
    * @return array
    */
    public function getUsersInfoListByIdArr($idArr, $fields, $dbMaster = FALSE)
    {
        $idArr = array_map('intval',$idArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($idArr) || empty($fields)) {
            return array();
        }

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'id' => $idArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($idArr))->get()->result_array('id');
    }

    /**
    * getUsersInfoRowById
    * @param $id
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-06 09:57:28
    * @return array
    */
    public function getUsersInfoRowById($id, $fields, $dbMaster = FALSE)
    {
        $id = intval($id);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($id) || empty($fields)) {
            return array();
        }

        $this->table = $this->getTable($id);

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'id' => $id
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }

    /**
    * getUsersInfoListByUsersIdArr
    * @param $usersIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-06 09:57:28
    * @return array
    */
    public function getUsersInfoListByUsersIdArr($usersIdArr, $fields, $dbMaster = FALSE)
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
    * getUsersInfoRowByUsersId
    * @param $usersId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-06 09:57:28
    * @return array
    */
    public function getUsersInfoRowByUsersId($usersId, $fields, $dbMaster = FALSE)
    {
        $usersId = intval($usersId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($usersId) || empty($fields)) {
            return array();
        }

        $this->table = $this->getTable($usersId);

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'users_id' => $usersId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }



    /**
     * saveUsersInfo
     * @param $id
     * @param $saveData
     * @author shell auto create
     * @date 2019-09-06 09:57:28
     * @return array
     */
    public function saveUsersInfo($usersId, $saveData)
    {
        $usersId  = intval($usersId);
        $saveData = !is_array($saveData) ? array() : $saveData;

        if(empty($saveData)) {
            return array();
        }

        # 参数整理
        $usersPassword = empty($saveData['users_password']) ? '' : $saveData['users_password'];
        $usersStatus = empty($saveData['users_status']) ? 0 : $saveData['users_status'];
        $virtualCoin = empty($saveData['virtual_coin']) ? 0 : $saveData['virtual_coin'];
        $recentlyActive = empty($saveData['recently_active']) ? 0 : $saveData['recently_active'];
        $createTime = empty($saveData['create_time']) ? 0 : $saveData['create_time'];
        $updateTime = empty($saveData['update_time']) ? 0 : $saveData['update_time'];

        $default = 'users';
        $usersRow = $this->getUsersInfoRowByUsersId($usersId, $fields = 'id');
        $id = empty($usersRow['id']) ? 0 : intval($usersRow['id']);

        $this->table = $this->getTable($usersId);


        if(empty($usersRow))
        {
            $insert = array(
                'users_id' => $usersId,
                'users_password' => $usersPassword,
                'users_status' => $usersStatus,
                'virtual_coin' => $virtualCoin,
                'recently_active' => $recentlyActive,
                'create_time' => $createTime,
                'update_time' => $updateTime,
            );

            $this->db_master($default)->insert($this->table, $insert);
            $id = $this->db_master($default)->insert_id();
            if(empty($id)) {
                return errorMsg(500, 'insert db error~');
            }

        }else
        {
            $update = array();

            if(! empty($usersId)) {
                $update['users_id'] = $usersId;
            }

            if(! empty($usersPassword)) {
                $update['users_password'] = $usersPassword;
            }

            if(! empty($usersStatus)) {
                $update['users_status'] = $usersStatus;
            }

            if(! empty($virtualCoin)) {
                $update['virtual_coin'] = $virtualCoin;
            }

            if(! empty($recentlyActive)) {
                $update['recently_active'] = $recentlyActive;
            }

            if(! empty($createTime)) {
                $update['create_time'] = $createTime;
            }

            if(! empty($updateTime)) {
                $update['update_time'] = $updateTime;
            }

            $this->db_master($default)->where(array('id' => $id))->update($this->table, $update);
            $affectedRows = $this->db_master($default)->affected_rows();
            if(empty($affectedRows)) {
                return errorMsg(500, 'update error~');
            }
        }


        return successMsg('ok~');
    }


    /**
     * editUsersInfoByUsersStatus
     * @param $id
     * @param $status
     * @author shell auto create
     * @date 2019-09-06 09:57:28
     * @return array
     */
    public function editUsersInfoByUsersStatus($id, $status)
    {
        $id  = intval($id);
        $status = in_array($status, array(1, 2, 3)) ? 0 : $status;

        if(empty($id) || empty($status)) {
            return errorMsg(404, 'edit databases params is empty');
        }

        $whereArr = array(
            'id' => $id
        );

        $update = array('users_status'=>$status);

        $default = 'users';
        $this->db_master($default)->where($whereArr)->update($this->table, $update);
        $num = $this->db_master($default)->affected_rows();

        if(empty($num)) {
            return errorMsg(500, 'edit error~');
        }

        return successMsg('ok~');
    }

    /**
     * decrUsersInfoVCoinByUsersId
     * @param $usersId
     * @param $vCoin
     * @author xuyi
     * @date 2019/10/10
     */
    public function decrUsersInfoVCoinByUsersId($usersId, $vCoin)
    {
        $usersId = intval($usersId);
        $vCoin   = intval($vCoin);
        $vCoin   = max($vCoin, 0);

        if(empty($usersId) || empty($vCoin)) {
            return errorMsg(404, '');
        }


        $this->table = $this->getTable($usersId);

        $default = 'users';
        $updateFields = 'virtual_coin';

        $this->db_master($default)->where('users_id', $usersId)->where("{$updateFields} >=", $vCoin)
            ->set($updateFields, "{$updateFields} - {$vCoin}", FALSE)->update($this->table);
        $num = $this->db_master($default)->affected_rows();

        if(empty($num)) {
            return errorMsg(500, 'decr users vcoin error ~');
        }

        return successMsg('decr users vcoin ok ~');
    }
}
