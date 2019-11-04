<?php

/**
 * Class Users_comic_reply_content_info_model shell auto create
 * @author xuyi
 * @date 2019-09-26 02:21:16
 */
class Users_comic_reply_content_info_model extends Driver_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = $this->getTable();
    }


    /**
    * getUsersComicReplyContentInfoListByContentIdArr
    * @param $contentIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-26 02:21:16
    * @return array
    */
    public function getUsersComicReplyContentInfoListByContentIdArr($contentIdArr, $fields, $dbMaster = FALSE)
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
    * getUsersComicReplyContentInfoRowByContentId
    * @param $contentId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-26 02:21:16
    * @return array
    */
    public function getUsersComicReplyContentInfoRowByContentId($contentId, $fields, $dbMaster = FALSE)
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
    * getUsersComicReplyContentInfoListByReplyIdArr
    * @param $replyIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-26 02:21:16
    * @return array
    */
    public function getUsersComicReplyContentInfoListByReplyIdArr($replyIdArr, $fields, $dbMaster = FALSE)
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
    * getUsersComicReplyContentInfoRowByReplyId
    * @param $replyId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-26 02:21:16
    * @return array
    */
    public function getUsersComicReplyContentInfoRowByReplyId($replyId, $fields, $dbMaster = FALSE)
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
     * saveUsersComicReplyContentInfo
     * @param $contentId
     * @param $saveData
     * @author shell auto create
     * @date 2019-09-26 02:21:16
     * @return array
     */
    public function saveUsersComicReplyContentInfo($contentId, $saveData)
    {
        $contentId  = intval($contentId);
        $saveData = !is_array($saveData) ? array() : $saveData;

        if(empty($saveData)) {
            return array();
        }

        # 参数整理
        $contentId = empty($saveData['content_id']) ? 0 : $saveData['content_id'];
        $content = empty($saveData['content']) ? '' : $saveData['content'];
        $replyId = empty($saveData['reply_id']) ? 0 : $saveData['reply_id'];
        $createTime = empty($saveData['create_time']) ? 0 : $saveData['create_time'];
        $updateTime = empty($saveData['update_time']) ? 0 : $saveData['update_time'];

        $default = 'users';
        $resultRow = $this->getUsersComicReplyContentInfoRowByContentId($contentId, $fields = 'content_id');
        if(empty($resultRow))
        {
            $insert = array(
                'content' => $content,
                'reply_id' => $replyId,
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

            if(! empty($content)) {
                $update['content'] = $content;
            }

            if(! empty($replyId)) {
                $update['reply_id'] = $replyId;
            }

            if(! empty($createTime)) {
                $update['create_time'] = $createTime;
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
