<?php

/**
 * Class Users_comic_reply_info_model shell auto create
 * @author xuyi
 * @date 2019-09-26 02:21:06
 */
class Users_comic_reply_info_model extends Driver_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = $this->getTable();
    }


    /**
    * getUsersComicReplyInfoListByReplyIdArr
    * @param $replyIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-26 02:21:06
    * @return array
    */
    public function getUsersComicReplyInfoListByReplyIdArr($replyIdArr, $fields, $dbMaster = FALSE)
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
    * getUsersComicReplyInfoRowByReplyId
    * @param $replyId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-26 02:21:06
    * @return array
    */
    public function getUsersComicReplyInfoRowByReplyId($replyId, $fields, $dbMaster = FALSE)
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
    * getUsersComicReplyInfoListByCommentIdArr
    * @param $commentIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-26 02:21:06
    * @return array
    */
    public function getUsersComicReplyInfoListByCommentIdArr($commentIdArr, $fields, $dbMaster = FALSE)
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
    * getUsersComicReplyInfoRowByCommentId
    * @param $commentId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-26 02:21:06
    * @return array
    */
    public function getUsersComicReplyInfoRowByCommentId($commentId, $fields, $dbMaster = FALSE)
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
    * getUsersComicReplyInfoListByUsersIdArr
    * @param $usersIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-26 02:21:06
    * @return array
    */
    public function getUsersComicReplyInfoListByUsersIdArr($usersIdArr, $fields, $dbMaster = FALSE)
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
    * getUsersComicReplyInfoRowByUsersId
    * @param $usersId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-26 02:21:06
    * @return array
    */
    public function getUsersComicReplyInfoRowByUsersId($usersId, $fields, $dbMaster = FALSE)
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
    * getUsersComicReplyInfoListByReplyParentIdArr
    * @param $replyParentIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-26 02:21:06
    * @return array
    */
    public function getUsersComicReplyInfoListByReplyParentIdArr($replyParentIdArr, $fields, $dbMaster = FALSE)
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
    * getUsersComicReplyInfoRowByReplyParentId
    * @param $replyParentId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-26 02:21:06
    * @return array
    */
    public function getUsersComicReplyInfoRowByReplyParentId($replyParentId, $fields, $dbMaster = FALSE)
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
     * saveUsersComicReplyInfo
     * @param $replyId
     * @param $saveData
     * @author shell auto create
     * @date 2019-09-26 02:21:06
     * @return array
     */
    public function saveUsersComicReplyInfo($replyId, $saveData)
    {
        $replyId  = intval($replyId);
        $saveData = !is_array($saveData) ? array() : $saveData;

        if(empty($saveData)) {
            return array();
        }

        # 参数整理
        $replyId = empty($saveData['reply_id']) ? 0 : $saveData['reply_id'];
        $commentId = empty($saveData['comment_id']) ? 0 : $saveData['comment_id'];
        $replayStatus = empty($saveData['replay_status']) ? 0 : $saveData['replay_status'];
        $usersId = empty($saveData['users_id']) ? 0 : $saveData['users_id'];
        $replyParentId = empty($saveData['reply_parent_id']) ? 0 : $saveData['reply_parent_id'];
        $likeNum = empty($saveData['like_num']) ? 0 : $saveData['like_num'];
        $createTime = empty($saveData['create_time']) ? 0 : $saveData['create_time'];
        $updateTime = empty($saveData['update_time']) ? 0 : $saveData['update_time'];

        $default = 'users';
        $resultRow = $this->getUsersComicReplyInfoRowByReplyId($replyId, $fields = 'reply_id');
        if(empty($resultRow))
        {
            $insert = array(
                'comment_id' => $commentId,
                'replay_status' => $replayStatus,
                'users_id' => $usersId,
                'reply_parent_id' => $replyParentId,
                'like_num' => $likeNum,
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

            if(! empty($commentId)) {
                $update['comment_id'] = $commentId;
            }

            if(! empty($replayStatus)) {
                $update['replay_status'] = $replayStatus;
            }

            if(! empty($usersId)) {
                $update['users_id'] = $usersId;
            }

            if(! empty($replyParentId)) {
                $update['reply_parent_id'] = $replyParentId;
            }

            if(! empty($likeNum)) {
                $update['like_num'] = $likeNum;
            }

            if(! empty($createTime)) {
                $update['create_time'] = $createTime;
            }

            if(! empty($updateTime)) {
                $update['update_time'] = $updateTime;
            }

            $this->db_master($default)->where(array('reply_id' => $replyId))->update($update, $this->table);
            $affectedRows = $this->db_master($default)->affected_rows();
            if(empty($affectedRows)) {
                return errorMsg(500, 'update error~');
            }
        }


        return successMsg('ok~');
    }


    /**
     * editUsersComicReplyInfoByReplayStatus
     * @param $replyId
     * @param $status
     * @author shell auto create
     * @date 2019-09-26 02:21:06
     * @return array
     */
    public function editUsersComicReplyInfoByReplayStatus($replyId, $status)
    {
        $replyId  = intval($replyId);
        $status = in_array($status, array(1, 2, 3)) ? 0 : $status;

        if(empty($replyId) || empty($status)) {
            return errorMsg(404, 'edit databases params is empty');
        }

        $whereArr = array(
            'reply_id' => $replyId
        );

        $update = array('replay_status'=>$status);

        $default = 'users';
        $this->db_master($default)->where($whereArr)->update($this->table, $update);
        $num = $this->db_master($default)->affected_rows();

        if(empty($num)) {
            return errorMsg(500, 'edit error~');
        }

        return successMsg('ok~');
    }


}
