<?php

/**
 * Class Users_book_reply_info_model shell auto create
 * @author xuyi
 * @date 2019-09-25 03:04:48
 */
class Users_book_reply_info_model extends Driver_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = $this->getTable();
    }


    /**
    * getUsersBookReplyInfoListByReplyIdArr
    * @param $replyIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-25 03:04:48
    * @return array
    */
    public function getUsersBookReplyInfoListByReplyIdArr($replyIdArr, $fields, $dbMaster = FALSE)
    {
        $replyIdArr = array_map('intval',$replyIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($replyIdArr) || empty($fields)) {
            return array();
        }

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'reply_id' => $replyIdArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($replyIdArr))->get()->result_array('reply_id');
    }

    /**
    * getUsersBookReplyInfoRowByReplyId
    * @param $replyId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-25 03:04:48
    * @return array
    */
    public function getUsersBookReplyInfoRowByReplyId($replyId, $fields, $dbMaster = FALSE)
    {
        $replyId = intval($replyId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($replyId) || empty($fields)) {
            return array();
        }

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'reply_id' => $replyId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }

    /**
    * getUsersBookReplyInfoListByReplyParentIdArr
    * @param $replyParentIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-25 03:04:48
    * @return array
    */
    public function getUsersBookReplyInfoListByReplyParentIdArr($replyParentIdArr, $fields, $dbMaster = FALSE)
    {
        $replyParentIdArr = array_map('intval',$replyParentIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($replyParentIdArr) || empty($fields)) {
            return array();
        }

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'reply_parent_id' => $replyParentIdArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($replyParentIdArr))->get()->result_array('reply_parent_id');
    }

    /**
    * getUsersBookReplyInfoRowByReplyParentId
    * @param $replyParentId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-25 03:04:48
    * @return array
    */
    public function getUsersBookReplyInfoRowByReplyParentId($replyParentId, $fields, $dbMaster = FALSE)
    {
        $replyParentId = intval($replyParentId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($replyParentId) || empty($fields)) {
            return array();
        }

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'reply_parent_id' => $replyParentId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }

    /**
     * getUsersBookReplyListByPage
     * @param $commentId
     * @param $fields
     * @param $rowsNum
     * @param $pageNum
     * @author xuyi
     * @date 2019/9/25
     * @return array
     */
    public function getUsersBookReplyListByPage($commentId, $fields, $rowsNum, $pageNum, $dbMaster = FALSE)
    {
        $commentId = intval($commentId);
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

        if(empty($commentId) || empty($fields) || empty($rowsNum)) {
            return $resultPage;
        }

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'comment_id'    => $commentId,
            'fav_status'    => NORMAL
        );

        $resultTotal = $db->select('*')->from($this->table)->where($whereArr)->count_all_results();

        if(empty($resultTotal)) {
            return $resultPage;
        }

        $resultList = $db->select($fields)->from($this->table)->where($whereArr)->limit($rowsNum, $offset)
            ->order_by('reply_id DESC')->get()->result_array();

        return array(
            'data'       => $resultList,
            'rows_total' => $resultTotal,
            'page_total' => ceil($resultTotal / $rowsNum),
            'rows_num'   => $rowsNum,
            'page_num'   => $pageNum,
        );
    }


    /**
     * saveUsersBookReplyInfo
     * @param $replyId
     * @param $saveData
     * @author shell auto create
     * @date 2019-09-25 03:04:48
     * @return array
     */
    public function saveUsersBookReplyInfo($replyId, $saveData)
    {
        $replyId  = intval($replyId);
        $saveData = !is_array($saveData) ? array() : $saveData;

        if(empty($saveData)) {
            return array();
        }

        # 参数整理
        $commentId = empty($saveData['comment_id']) ? 0 : $saveData['comment_id'];
        $replyStatus = empty($saveData['reply_status']) ? 0 : $saveData['reply_status'];
        $replyParentId = empty($saveData['reply_parent_id']) ? 0 : $saveData['reply_parent_id'];
        $likeNum = empty($saveData['like_num']) ? 0 : $saveData['like_num'];
        $createTime = empty($saveData['create_time']) ? 0 : $saveData['create_time'];
        $updateTime = empty($saveData['update_time']) ? 0 : $saveData['update_time'];

        $default   = 'users';
        $resultRow = $this->getUsersBookReplyInfoRowByReplyId($replyId, $fields = 'reply_id');
        if(empty($resultRow))
        {
            $insert = array(
                'comment_id' => $commentId,
                'reply_status' => $replyStatus,
                'reply_parent_id' => $replyParentId,
                'like_num' => $likeNum,
                'create_time' => $createTime,
                'update_time' => $updateTime,
            );

            $this->db_master($default)->insert($this->table, $insert);
            $replyId  = $this->db_master($default)->insert_id();
            if(empty($replyId)) {
                return errorMsg(500, 'insert db error~');
            }

        }else
        {
            $update = array();

            if(! empty($commentId)) {
                $update['comment_id'] = $commentId;
            }

            if(! empty($replyStatus)) {
                $update['reply_status'] = $replyStatus;
            }

            if(! empty($replyParentId)) {
                $update['reply_parent_id'] = $replyParentId;
            }

            if(! empty($likeNum)) {
                $update['like_num'] = $likeNum;
            }

            if(! empty($updateTime)) {
                $update['update_time'] = $updateTime;
            }

            $this->db_master($default)->where(array('reply_id' => $replyId))->update($this->table,$update);
            $affectedRows = $this->db_master($default)->affected_rows();
            if(empty($affectedRows)) {
                return errorMsg(500, 'update error~');
            }
        }


        $resultRow = array(
            'comment_id' => $commentId,
            'reply_id'   => $replyId
        );

        return successMsg('ok~', $resultRow);
    }


    /**
     * editUsersBookReplyInfoByReplayStatus
     * @param $replyId
     * @param $status
     * @author shell auto create
     * @date 2019-09-25 03:04:48
     * @return array
     */
    public function editUsersBookReplyInfoByReplyStatus($replyId, $status)
    {
        $replyId  = intval($replyId);
        $status = in_array($status, array(1, 2, 3)) ? 0 : $status;

        if(empty($replyId) || empty($status)) {
            return errorMsg(404, 'edit databases params is empty');
        }

        $whereArr = array(
            'reply_id' => $replyId
        );

        $update = array('reply_status'=>$status);

        $default = 'users';
        $this->db_master($default)->where($whereArr)->update($this->table, $update);
        $num = $this->db_master($default)->affected_rows();

        if(empty($num)) {
            return errorMsg(500, 'edit error~');
        }

        return successMsg('ok~');
    }

}
