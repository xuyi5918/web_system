<?php

/**
 * Class Users_fav_comic_model shell auto create
 * @author xuyi
 * @date 2019-09-23 11:33:08
 */
class Users_fav_comic_model extends Core_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = $this->getTable();
    }


    /**
    * getUsersFavComicListByFavIdArr
    * @param $favIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-23 11:33:08
    * @return array
    */
    public function getUsersFavComicListByFavIdArr($favIdArr, $fields, $dbMaster = FALSE)
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
    * getUsersFavComicRowByFavId
    * @param $favId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-23 11:33:08
    * @return array
    */
    public function getUsersFavComicRowByFavId($favId, $fields, $dbMaster = FALSE)
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
    * getUsersFavComicListByUsersIdArr
    * @param $usersIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-23 11:33:08
    * @return array
    */
    public function getUsersFavComicListByUsersIdArr($usersIdArr, $fields, $dbMaster = FALSE)
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
    * getUsersFavComicRowByUsersId
    * @param $usersId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-23 11:33:08
    * @return array
    */
    public function getUsersFavComicRowByUsersId($usersId, $fields, $dbMaster = FALSE)
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
     * getUsersFavComicRowByComicId
     * @param $comicId
     * @param $usersId
     * @param $fields
     * @param bool $dbMaster
     * @author xuyi
     * @date 2019/9/23
     * @return array
     */
    public function getUsersFavComicRowByComicId($comicId, $usersId, $fields, $dbMaster = FALSE)
    {
        $usersId = intval($usersId);
        $fields  = trim($fields);
        $comicId = intval($comicId);

        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($usersId) || empty($fields) || empty($comicId)) {
            return array();
        }

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'users_id' => $usersId,
            'comic_id' => $comicId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }


    /**
     * saveUsersFavComic
     * @param $favId
     * @param $saveData
     * @author shell auto create
     * @date 2019-09-23 11:33:08
     * @return array
     */
    public function saveUsersFavComic($favId, $saveData)
    {
        $favId  = intval($favId);
        $saveData = !is_array($saveData) ? array() : $saveData;

        if(empty($saveData)) {
            return errorMsg(500, 'insert data is empty~');
        }

        # 参数整理
        $comicId = empty($saveData['comic_id']) ? 0 : $saveData['comic_id'];
        $usersId = empty($saveData['users_id']) ? 0 : $saveData['users_id'];
        $favStatus = empty($saveData['fav_status']) ? 0 : $saveData['fav_status'];
        $createTime = empty($saveData['create_time']) ? 0 : $saveData['create_time'];

        $default = 'users';
        $resultRow = $this->getUsersFavComicRowByFavId($favId, $fields = 'fav_id');
        if(empty($resultRow))
        {
            $insert = array(
                'comic_id' => $comicId,
                'users_id' => $usersId,
                'fav_status' => $favStatus,
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

            if(! empty($comicId)) {
                $update['comic_id'] = $comicId;
            }

            if(! empty($usersId)) {
                $update['users_id'] = $usersId;
            }

            if(! empty($favStatus)) {
                $update['fav_status'] = $favStatus;
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
     * editUsersFavComicByFavStatus
     * @param $favId
     * @param $status
     * @author shell auto create
     * @date 2019-09-23 11:33:08
     * @return array
     */
    public function editUsersFavComicByFavStatus($favId, $status)
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

    /**
     * getUsersFavComicListByPage
     * @param $usersId
     * @param $fields
     * @param $rowsNum
     * @param $pageNum
     * @author xuyi
     * @date 2019/9/24
     * @return array
     */
    public function getUsersFavComicListByPage($usersId, $fields, $rowsNum, $pageNum, $dbMaster = FALSE)
    {
        $usersId = intval($usersId);
        $fields  = trim($fields);

        $rowsNum = intval($rowsNum);
        $rowsNum = min(50, $rowsNum);
        $pageNum = intval($pageNum);
        $pageNum = max(1, $pageNum);

        $offset = ($pageNum - 1) * $rowsNum;

        $resultPage = array(
            'data'       => array(),
            'rows_total' => 0,
            'page_total' => 0,
            'rows_num'   => $rowsNum,
            'page_num'   => $pageNum,
        );

        if(empty($usersId) || empty($fields) || empty($rowsNum)) {
            return $resultPage;
        }


        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'users_id'      => $usersId,
            'fav_status'    => NORMAL
        );

        $resultTotal = $db->select('*')->from($this->table)->where($whereArr)->count_all_results();

        if(empty($resultTotal)) {
            return $resultPage;
        }

        $resultList = $db->select($fields)->from($this->table)->where($whereArr)->limit($rowsNum, $offset)
            ->order_by('fav_id DESC')->get()->result_array();

        return array(
            'data'       => $resultList,
            'rows_total' => $resultTotal,
            'page_total' => ceil($resultTotal / $rowsNum),
            'rows_num'   => $rowsNum,
            'page_num'   => $pageNum,
        );
    }
}
