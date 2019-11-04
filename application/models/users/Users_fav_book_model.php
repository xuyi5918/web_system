<?php

/**
 * Class Users_fav_book_model shell auto create
 * @author xuyi
 * @date 2019-09-24 10:05:05
 */
class Users_fav_book_model extends Driver_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = $this->getTable();
    }


    /**
    * getUsersFavBookListByFavIdArr
    * @param $favIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-24 10:05:05
    * @return array
    */
    public function getUsersFavBookListByFavIdArr($favIdArr, $fields, $dbMaster = FALSE)
    {
        $favIdArr = array_map('intval',$favIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($favIdArr) || empty($fields)) {
            return array();
        }

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'fav_id' => $favIdArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($favIdArr))->get()->result_array('fav_id');
    }

    /**
    * getUsersFavBookRowByFavId
    * @param $favId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-24 10:05:05
    * @return array
    */
    public function getUsersFavBookRowByFavId($favId, $fields, $dbMaster = FALSE)
    {
        $favId = intval($favId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($favId) || empty($fields)) {
            return array();
        }

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'fav_id' => $favId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }

    /**
    * getUsersFavBookListByUsersIdArr
    * @param $usersIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-24 10:05:05
    * @return array
    */
    public function getUsersFavBookListByUsersIdArr($usersIdArr, $fields, $dbMaster = FALSE)
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
    * getUsersFavBookRowByUsersId
    * @param $usersId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-24 10:05:05
    * @return array
    */
    public function getUsersFavBookRowByUsersId($usersId, $fields, $dbMaster = FALSE)
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
     * getUsersFavBookRowByBookId
     * @param $bookId
     * @param $usersId
     * @param $fields
     * @param bool $dbMaster
     * @author xuyi
     * @date 2019/9/24
     * @return array
     */
    public function getUsersFavBookRowByBookId($bookId, $usersId, $fields, $dbMaster = FALSE)
    {
        $usersId = intval($usersId);
        $bookId = intval($bookId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($usersId) || empty($fields) || empty($bookId)) {
            return array();
        }

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'users_id' => $usersId,
            'book_id'  => $bookId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }

    /**
     * saveUsersFavBook
     * @param $favId
     * @param $saveData
     * @author shell auto create
     * @date 2019-09-24 10:05:05
     * @return array
     */
    public function saveUsersFavBook($favId, $saveData)
    {
        $favId  = intval($favId);
        $saveData = !is_array($saveData) ? array() : $saveData;

        if(empty($saveData)) {
            return array();
        }

        # 参数整理
        $bookId = empty($saveData['book_id']) ? 0 : $saveData['book_id'];
        $usersId = empty($saveData['users_id']) ? 0 : $saveData['users_id'];
        $favStatus = empty($saveData['fav_status']) ? 0 : $saveData['fav_status'];
        $updateTime = empty($saveData['update_time']) ? 0 : $saveData['update_time'];
        $createTime = empty($saveData['create_time']) ? 0 : $saveData['create_time'];

        $default = 'users';
        $resultRow = $this->getUsersFavBookRowByFavId($favId, $fields = 'fav_id');
        if(empty($resultRow))
        {
            $insert = array(
                'book_id' => $bookId,
                'users_id' => $usersId,
                'fav_status' => $favStatus,
                'update_time' => $updateTime,
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

            if(! empty($bookId)) {
                $update['book_id'] = $bookId;
            }

            if(! empty($usersId)) {
                $update['users_id'] = $usersId;
            }

            if(! empty($favStatus)) {
                $update['fav_status'] = $favStatus;
            }

            if(! empty($updateTime)) {
                $update['update_time'] = $updateTime;
            }

            if(! empty($createTime)) {
                $update['create_time'] = $createTime;
            }

            $this->db_master($default)->where(array('fav_id' => $favId))->update($update, $this->table);
            $affectedRows = $this->db_master($default)->affected_rows();
            if(empty($affectedRows)) {
                return errorMsg(500, 'update error~');
            }
        }


        return successMsg('ok~');
    }


    /**
     * editUsersFavBookByFavStatus
     * @param $favId
     * @param $status
     * @author shell auto create
     * @date 2019-09-24 10:05:05
     * @return array
     */
    public function editUsersFavBookByFavStatus($favId, $status)
    {
        $favId  = intval($favId);
        $status = in_array($status, array(1, 2, 3)) ? 0 : $status;

        if(empty($favId) || empty($status)) {
            return errorMsg(404, 'edit databases params is empty');
        }

        $whereArr = array(
            'fav_id' => $favId
        );

        $update = array('fav_status'=>$status);

        $default = 'users';
        $this->db_master($default)->where($whereArr)->update($this->table, $update);
        $num = $this->db_master($default)->affected_rows();

        if(empty($num)) {
            return errorMsg(500, 'edit error~');
        }

        return successMsg('ok~');
    }


}
