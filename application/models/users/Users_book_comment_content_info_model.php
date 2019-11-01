<?php

/**
 * Class Users_book_comment_content_info_model shell auto create
 * @author xuyi
 * @date 2019-09-25 03:05:05
 */
class Users_book_comment_content_info_model extends Core_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = $this->getTable();
    }


    /**
    * getUsersBookCommentContentInfoListByContentIdArr
    * @param $contentIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-25 03:05:05
    * @return array
    */
    public function getUsersBookCommentContentInfoListByContentIdArr($contentIdArr, $fields, $dbMaster = FALSE)
    {
        $contentIdArr = array_map('intval',$contentIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($contentIdArr) || empty($fields)) {
            return array();
        }

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'content_id' => $contentIdArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($contentIdArr))->get()->result_array('content_id');
    }

    /**
    * getUsersBookCommentContentInfoRowByContentId
    * @param $contentId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-25 03:05:05
    * @return array
    */
    public function getUsersBookCommentContentInfoRowByContentId($contentId, $fields, $dbMaster = FALSE)
    {
        $contentId = intval($contentId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($contentId) || empty($fields)) {
            return array();
        }

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'content_id' => $contentId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }

    /**
    * getUsersBookCommentContentInfoListByCommentIdArr
    * @param $commentIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-25 03:05:05
    * @return array
    */
    public function getUsersBookCommentContentInfoListByCommentIdArr($commentIdArr, $fields, $dbMaster = FALSE)
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
    * getUsersBookCommentContentInfoRowByCommentId
    * @param $commentId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-25 03:05:05
    * @return array
    */
    public function getUsersBookCommentContentInfoRowByCommentId($commentId, $fields, $dbMaster = FALSE)
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
     * saveUsersBookCommentContentInfo
     * @param $contentId
     * @param $saveData
     * @author shell auto create
     * @date 2019-09-25 03:05:05
     * @return array
     */
    public function saveUsersBookCommentContentInfo($commentId, $saveData)
    {
        $commentId  = intval($commentId);
        $saveData   = !is_array($saveData) ? array() : $saveData;

        if(empty($saveData)) {
            return errorMsg(404, 'insert param is empty~');
        }

        # 参数整理
        $content    = empty($saveData['content']) ? '' : $saveData['content'];
        $createTime = empty($saveData['create_time']) ? 0 : $saveData['create_time'];
        $updateTime = empty($saveData['update_time']) ? 0 : $saveData['update_time'];

        $default   = 'users';
        $resultRow = $this->getUsersBookCommentContentInfoRowByCommentId($commentId, $fields = 'content_id');
        $contentId = empty($resultRow['content_id']) ? 0 : intval($resultRow['content_id']);

        if(empty($resultRow))
        {
            $insert = array(
                'content' => $content,
                'comment_id' => $commentId,
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

            if(! empty($content)) {
                $update['content'] = $content;
            }

            if(! empty($commentId)) {
                $update['comment_id'] = $commentId;
            }

            if(! empty($updateTime)) {
                $update['update_time'] = $updateTime;
            }

            $this->db_master($default)->where(array('content_id' => $contentId))->update($update, $this->table);
            $affectedRows = $this->db_master($default)->affected_rows();
            if(empty($affectedRows)) {
                return errorMsg(500, 'update error~');
            }
        }


        return successMsg('ok~');
    }

}
