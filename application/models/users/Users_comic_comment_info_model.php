<?php

/**
 * Class Users_comic_comment_info_model shell auto create
 * @author xuyi
 * @date 2019-09-26 02:20:38
 */
class Users_comic_comment_info_model extends Driver_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = $this->getTable();
    }


    /**
    * getUsersComicCommentInfoListByCommentIdArr
    * @param $commentIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-26 02:20:38
    * @return array
    */
    public function getUsersComicCommentInfoListByCommentIdArr($commentIdArr, $fields, $dbMaster = FALSE)
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
    * getUsersComicCommentInfoRowByCommentId
    * @param $commentId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-26 02:20:38
    * @return array
    */
    public function getUsersComicCommentInfoRowByCommentId($commentId, $fields, $dbMaster = FALSE)
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
    * getUsersComicCommentInfoListByComicIdArr
    * @param $comicIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-26 02:20:38
    * @return array
    */
    public function getUsersComicCommentInfoListByComicIdArr($comicIdArr, $fields, $dbMaster = FALSE)
    {
        $comicIdArr = array_map('intval',$comicIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($comicIdArr) || empty($fields)) {
            return array();
        }

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'comic_id' => $comicIdArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($comicIdArr))->get()->result_array('comic_id');
    }

    /**
    * getUsersComicCommentInfoRowByComicId
    * @param $comicId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-26 02:20:38
    * @return array
    */
    public function getUsersComicCommentInfoRowByComicId($comicId, $fields, $dbMaster = FALSE)
    {
        $comicId = intval($comicId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($comicId) || empty($fields)) {
            return array();
        }

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'comic_id' => $comicId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }

    /**
    * getUsersComicCommentInfoListByUsersIdArr
    * @param $usersIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-26 02:20:38
    * @return array
    */
    public function getUsersComicCommentInfoListByUsersIdArr($usersIdArr, $fields, $dbMaster = FALSE)
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
    * getUsersComicCommentInfoRowByUsersId
    * @param $usersId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-26 02:20:38
    * @return array
    */
    public function getUsersComicCommentInfoRowByUsersId($usersId, $fields, $dbMaster = FALSE)
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
     * saveUsersComicCommentInfo
     * @param $commentId
     * @param $saveData
     * @author shell auto create
     * @date 2019-09-26 02:20:38
     * @return array
     */
    public function saveUsersComicCommentInfo($commentId, $saveData)
    {
        $commentId  = intval($commentId);
        $saveData = !is_array($saveData) ? array() : $saveData;

        if(empty($saveData)) {
            return array();
        }

        # 参数整理
        $commentId = empty($saveData['comment_id']) ? 0 : $saveData['comment_id'];
        $comicId = empty($saveData['comic_id']) ? 0 : $saveData['comic_id'];
        $replyNum = empty($saveData['reply_num']) ? 0 : $saveData['reply_num'];
        $usersId = empty($saveData['users_id']) ? 0 : $saveData['users_id'];
        $likeNum = empty($saveData['like_num']) ? 0 : $saveData['like_num'];
        $commentStatus = empty($saveData['comment_status']) ? 0 : $saveData['comment_status'];
        $newReplyIdArr = empty($saveData['new_reply_id_arr']) ? '' : $saveData['new_reply_id_arr'];
        $createTime = empty($saveData['create_time']) ? 0 : $saveData['create_time'];
        $updateTime = empty($saveData['update_time']) ? 0 : $saveData['update_time'];

        $default = 'users';
        $resultRow = $this->getUsersComicCommentInfoRowByCommentId($commentId, $fields = 'comment_id');
        if(empty($resultRow))
        {
            $insert = array(
                'comic_id' => $comicId,
                'reply_num' => $replyNum,
                'users_id' => $usersId,
                'like_num' => $likeNum,
                'comment_status' => $commentStatus,
                'new_reply_id_arr' => $newReplyIdArr,
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

            if(! empty($comicId)) {
                $update['comic_id'] = $comicId;
            }

            if(! empty($replyNum)) {
                $update['reply_num'] = $replyNum;
            }

            if(! empty($usersId)) {
                $update['users_id'] = $usersId;
            }

            if(! empty($likeNum)) {
                $update['like_num'] = $likeNum;
            }

            if(! empty($commentStatus)) {
                $update['comment_status'] = $commentStatus;
            }

            if(! empty($newReplyIdArr)) {
                $update['new_reply_id_arr'] = $newReplyIdArr;
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


        return successMsg('ok~');
    }


    /**
     * editUsersComicCommentInfoByCommentStatus
     * @param $commentId
     * @param $status
     * @author shell auto create
     * @date 2019-09-26 02:20:38
     * @return array
     */
    public function editUsersComicCommentInfoByCommentStatus($commentId, $status)
    {
        $commentId  = intval($commentId);
        $status = in_array($status, array(1, 2, 3)) ? 0 : $status;

        if(empty($commentId) || empty($status)) {
            return errorMsg(404, 'edit databases params is empty');
        }

        $whereArr = array(
            'comment_id' => $commentId
        );

        $update = array('comment_status'=>$status);

        $default = 'users';
        $this->db_master($default)->where($whereArr)->update($this->table, $update);
        $num = $this->db_master($default)->affected_rows();

        if(empty($num)) {
            return errorMsg(500, 'edit error~');
        }

        return successMsg('ok~');
    }


}
