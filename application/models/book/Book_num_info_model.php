<?php

/**
 * Class Book_num_info_model shell auto create
 * @author xuyi
 * @date 2019-09-15 05:56:08
 */
class Book_num_info_model extends Core_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = $this->getTable();
    }


    /**
    * getBookNumInfoListByNumIdArr
    * @param $numIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-15 05:56:08
    * @return array
    */
    public function getBookNumInfoListByNumIdArr($numIdArr, $fields, $dbMaster = FALSE)
    {
        $numIdArr = array_map('intval',$numIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($numIdArr) || empty($fields)) {
            return array();
        }

        $default = 'book';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'num_id' => $numIdArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($numIdArr))->get()->result_array('num_id');
    }

    /**
    * getBookNumInfoRowByNumId
    * @param $numId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-15 05:56:08
    * @return array
    */
    public function getBookNumInfoRowByNumId($numId, $fields, $dbMaster = FALSE)
    {
        $numId = intval($numId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($numId) || empty($fields)) {
            return array();
        }

        $default = 'book';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'num_id' => $numId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }

    /**
    * getBookNumInfoListByBookIdArr
    * @param $bookIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-15 05:56:08
    * @return array
    */
    public function getBookNumInfoListByBookIdArr($bookIdArr, $fields, $dbMaster = FALSE)
    {
        $bookIdArr = array_map('intval',$bookIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($bookIdArr) || empty($fields)) {
            return array();
        }

        $default = 'book';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'book_id' => $bookIdArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($bookIdArr))->get()->result_array('book_id');
    }

    /**
    * getBookNumInfoRowByBookId
    * @param $bookId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-15 05:56:08
    * @return array
    */
    public function getBookNumInfoRowByBookId($bookId, $fields, $dbMaster = FALSE)
    {
        $bookId = intval($bookId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($bookId) || empty($fields)) {
            return array();
        }

        $default = 'book';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'book_id' => $bookId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }



    /**
     * saveBookNumInfo
     * @param $numId
     * @param $saveData
     * @author shell auto create
     * @date 2019-09-15 05:56:08
     * @return array
     */
    public function saveBookNumInfo($numId, $saveData)
    {
        $numId  = intval($numId);
        $saveData = is_array($saveData) ? array() : $saveData;

        if(empty($saveData)) {
            return array();
        }

        # 参数整理
        $bookId = empty($saveData['book_id']) ? 0 : $saveData['book_id'];
        $favNum = empty($saveData['fav_num']) ? 0 : $saveData['fav_num'];
        $commentNum = empty($saveData['comment_num']) ? 0 : $saveData['comment_num'];
        $pvNum = empty($saveData['pv_num']) ? 0 : $saveData['pv_num'];
        $createTime = empty($saveData['create_time']) ? 0 : $saveData['create_time'];
        $updateTime = empty($saveData['update_time']) ? 0 : $saveData['update_time'];

        $default = 'book';
        $resultRow = $this->getBookNumInfoRowByNumId($numId, $fields = 'num_id');
        if(empty($resultRow))
        {
            $insert = array(
                'book_id' => $bookId,
                'fav_num' => $favNum,
                'comment_num' => $commentNum,
                'pv_num' => $pvNum,
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

            if(! empty($bookId)) {
                $update['book_id'] = $bookId;
            }

            if(! empty($favNum)) {
                $update['fav_num'] = $favNum;
            }

            if(! empty($commentNum)) {
                $update['comment_num'] = $commentNum;
            }

            if(! empty($pvNum)) {
                $update['pv_num'] = $pvNum;
            }

            if(! empty($createTime)) {
                $update['create_time'] = $createTime;
            }

            if(! empty($updateTime)) {
                $update['update_time'] = $updateTime;
            }

            $this->db_master($default)->where(array('num_id' => $numId))->update($update, $this->table);
            $affectedRows = $this->db_master($default)->affected_rows();
            if(empty($affectedRows)) {
                return errorMsg(500, 'update error~');
            }
        }


        return successMsg('ok~');
    }
}
