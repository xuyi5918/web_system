<?php

/**
 * Class Users_book_comment_info_model shell auto create
 * @author xuyi
 * @date 2019-09-25 03:05:59
 */
class Users_book_comment_info_model extends Driver_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = $this->getTable();
    }


    /**
    * getUsersBookCommentInfoListByCommentIdArr
    * @param $commentIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-25 03:05:59
    * @return array
    */
    public function getUsersBookCommentInfoListByCommentIdArr($commentIdArr, $fields, $dbMaster = FALSE)
    {
        $commentIdArr = array_map('intval',$commentIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($commentIdArr) || empty($fields)) {
            return array();
        }

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'comment_id' => $commentIdArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($commentIdArr))->get()->result_array('comment_id');
    }

    /**
    * getUsersBookCommentInfoRowByCommentId
    * @param $commentId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-25 03:05:59
    * @return array
    */
    public function getUsersBookCommentInfoRowByCommentId($commentId, $fields, $dbMaster = FALSE)
    {
        $commentId = intval($commentId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($commentId) || empty($fields)) {
            return array();
        }

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'comment_id' => $commentId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }

    /**
    * getUsersBookCommentInfoListByBookIdArr
    * @param $bookIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-25 03:05:59
    * @return array
    */
    public function getUsersBookCommentInfoListByBookIdArr($bookIdArr, $fields, $dbMaster = FALSE)
    {
        $bookIdArr = array_map('intval',$bookIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($bookIdArr) || empty($fields)) {
            return array();
        }

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'book_id' => $bookIdArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($bookIdArr))->get()->result_array('book_id');
    }

    /**
    * getUsersBookCommentInfoRowByBookId
    * @param $bookId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-25 03:05:59
    * @return array
    */
    public function getUsersBookCommentInfoRowByBookId($bookId, $fields, $dbMaster = FALSE)
    {
        $bookId = intval($bookId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($bookId) || empty($fields)) {
            return array();
        }

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'book_id' => $bookId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }

    /**
     * getUsersBookCommentListByPage
     * @param $bookId
     * @param $fields
     * @param $rowsNum
     * @param $pageNum
     * @author xuyi
     * @date 2019/9/25
     * @return array
     */
    public function getUsersBookCommentListByPage($bookId, $fields, $rowsNum, $pageNum, $dbMaster)
    {
        $bookId    = intval($bookId);
        $fields    = trim($fields);

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

        if(empty($bookId) || empty($fields) || empty($rowsNum)) {
            return $resultPage;
        }

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'book_id'           => $bookId,
            'comment_status'    => NORMAL
        );

        $resultTotal = $db->select('*')->from($this->table)->where($whereArr)->count_all_results();

        if(empty($resultTotal)) {
            return $resultPage;
        }

        $resultList = $db->select($fields)->from($this->table)->where($whereArr)->limit($rowsNum, $offset)
            ->order_by('comment_id DESC')->get()->result_array();

        return array(
            'data'       => $resultList,
            'rows_total' => $resultTotal,
            'page_total' => ceil($resultTotal / $rowsNum),
            'rows_num'   => $rowsNum,
            'page_num'   => $pageNum,
        );
    }


    /**
     * saveUsersBookCommentInfo
     * @param $commentId
     * @param $saveData
     * @author shell auto create
     * @date 2019-09-25 03:05:59
     * @return array
     */
    public function saveUsersBookCommentInfo($commentId, $saveData)
    {
        $commentId  = intval($commentId);
        $saveData = !is_array($saveData) ? array() : $saveData;

        if(empty($saveData)) {
            return array();
        }

        # 参数整理
        $bookId = empty($saveData['book_id']) ? 0 : $saveData['book_id'];
        $replayNum = empty($saveData['replay_num']) ? 0 : $saveData['replay_num'];
        $likeNum = empty($saveData['like_num']) ? 0 : $saveData['like_num'];
        $commentStatus = empty($saveData['comment_status']) ? 0 : $saveData['comment_status'];
        $createTime = empty($saveData['create_time']) ? 0 : $saveData['create_time'];
        $updateTime = empty($saveData['update_time']) ? 0 : $saveData['update_time'];

        $default = 'users';
        $resultRow = $this->getUsersBookCommentInfoRowByCommentId($commentId, $fields = 'comment_id');
        if(empty($resultRow))
        {
            $insert = array(
                'book_id' => $bookId,
                'replay_num' => $replayNum,
                'like_num' => $likeNum,
                'comment_status' => $commentStatus,
                'create_time' => $createTime,
                'update_time' => $updateTime,
            );

            $this->db_master($default)->insert($insert, $this->table);
            $commentId = $this->db_master($default)->insert_id();
            if(empty($commentId)) {
                return errorMsg(500, 'insert db error~');
            }

        }else
        {
            $update = array();

            if(! empty($bookId)) {
                $update['book_id'] = $bookId;
            }

            if(! empty($replayNum)) {
                $update['replay_num'] = $replayNum;
            }

            if(! empty($likeNum)) {
                $update['like_num'] = $likeNum;
            }

            if(! empty($commentStatus)) {
                $update['comment_status'] = $commentStatus;
            }

            if(! empty($createTime)) {
                $update['create_time'] = $createTime;
            }

            if(! empty($updateTime)) {
                $update['update_time'] = $updateTime;
            }

            $this->db_master($default)->where(array('comment_id' => $commentId))->update($update, $this->table);
            $affectedRows = $this->db_master($default)->affected_rows();
            if(empty($affectedRows)) {
                return errorMsg(500, 'update error~');
            }
        }

        $resultRow = array(
            'comment_id' => $commentId
        );

        return successMsg('ok~', $resultRow);
    }


    /**
     * editUsersBookCommentInfoByCommentStatus
     * @param $commentId
     * @param $status
     * @author shell auto create
     * @date 2019-09-25 03:05:59
     * @return array
     */
    public function editUsersBookCommentInfoByCommentStatus($commentId, $status)
    {
        $commentId  = intval($commentId);
        $status = in_array($status, array(1, 2, 3)) ? 0 : $status;

        if(empty($commentId) || empty($status)) {
            return errorMsg(404, 'edit databases params is empty');
        }

        $whereArr = array(
            'comment_id' => $commentId
        );

        $update = array(
            'comment_status'    => $status,
            'update_time'       => time()
        );

        $default = 'users';
        $this->db_master($default)->where($whereArr)->update($this->table, $update);
        $num = $this->db_master($default)->affected_rows();

        if(empty($num)) {
            return errorMsg(500, 'edit error~');
        }

        return successMsg('ok~');
    }


}
