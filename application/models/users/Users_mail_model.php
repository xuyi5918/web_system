<?php

/**
 * Class Users_mail_model shell auto create
 * @author xuyi
 * @date 2019-09-06 09:50:12
 */
class Users_mail_model extends Driver_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = $this->getTable();
    }


    /**
    * getUsersMailListByMailIdArr
    * @param $mailIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author
    * @date 2019-09-06 09:50:12
    * @return array
    */
    public function getUsersMailListByMailIdArr($mailIdArr, $fields, $dbMaster = FALSE)
    {
        $mailIdArr = array_map('intval',$mailIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($mailIdArr) || empty($fields)) {
            return array();
        }

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'mail_id' => $mailIdArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($mailIdArr))->get()->result_array('mail_id');
    }

    /**
    * getUsersMailRowByMailId
    * @param $mailId
    * @param $fields
    * @param bool $dbMaster
    * @author
    * @date 2019-09-06 09:50:12
    * @return array
    */
    public function getUsersMailRowByMailId($mailId, $fields, $dbMaster = FALSE)
    {
        $mailId = intval($mailId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($mailId) || empty($fields)) {
            return array();
        }

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'mail_id' => $mailId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }

    /**
    * getUsersMailListBySignArr
    * @param $signArr
    * @param $fields
    * @param bool $dbMaster
    * @author
    * @date 2019-09-06 09:50:12
    * @return array
    */
    public function getUsersMailListBySignArr($signArr, $fields, $dbMaster = FALSE)
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
    * getUsersMailRowBySign
    * @param $sign
    * @param $fields
    * @param bool $dbMaster
    * @author
    * @date 2019-09-06 09:50:12
    * @return array
    */
    public function getUsersMailRowBySign($sign, $fields, $dbMaster = FALSE)
    {
        $sign = trim($sign);
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
    * getUsersMailListByUsersIdArr
    * @param $usersIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author
    * @date 2019-09-06 09:50:12
    * @return array
    */
    public function getUsersMailListByUsersIdArr($usersIdArr, $fields, $dbMaster = FALSE)
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
    * getUsersMailRowByUsersId
    * @param $usersId
    * @param $fields
    * @param bool $dbMaster
    * @author
    * @date 2019-09-06 09:50:12
    * @return array
    */
    public function getUsersMailRowByUsersId($usersId, $fields, $dbMaster = FALSE)
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
     * saveUsersMail
     * @param $mailId
     * @param $saveData
     * @author
     * @date 2019-09-06 09:50:12
     * @return array
     */
    public function saveUsersMail($mailId, $saveData)
    {
        $mailId  = intval($mailId);
        $saveData = ! is_array($saveData) ? array() : $saveData;

        if(empty($saveData)) {
            return errorMsg(404, 'save data array is empty~');
        }

        # 参数整理
        $mail = empty($saveData['mail']) ? '' : $saveData['mail'];
        $sign = empty($saveData['sign']) ? '' : $saveData['sign'];
        $usersId = empty($saveData['users_id']) ? 0 : $saveData['users_id'];
        $createTime = empty($saveData['create_time']) ? 0 : $saveData['create_time'];

        $default = 'users';
        $usersRow = $this->getUsersMailRowByMailId($mailId, $fields = 'mail_id');
        if(empty($usersRow))
        {
            $insert = array(
                'mail' => $mail,
                'sign' => $sign,
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

            if(! empty($mail)) {
                $update['mail'] = $mail;
            }

            if(! empty($sign)) {
                $update['sign'] = $sign;
            }

            if(! empty($usersId)) {
                $update['users_id'] = $usersId;
            }

            $this->db_master($default)->where(array('mail_id' => $mailId))->update($this->table, $update);
            $affectedRows = $this->db_master($default)->affected_rows();
            if(empty($affectedRows)) {
                return errorMsg(500, 'update error~');
            }
        }


        return successMsg('ok~');
    }
}
