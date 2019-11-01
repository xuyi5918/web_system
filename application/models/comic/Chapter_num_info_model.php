<?php

/**
 * Class Chapter_num_info_model shell auto create
 * @author xuyi
 * @date 2019-10-07 13:18:56
 */
class Chapter_num_info_model extends Core_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = $this->getTable();
    }


    /**
    * getChapterNumInfoListByNumIdArr
    * @param $numIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-10-07 13:18:56
    * @return array
    */
    public function getChapterNumInfoListByNumIdArr($numIdArr, $fields, $dbMaster = FALSE)
    {
        $numIdArr = array_map('intval',$numIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($numIdArr) || empty($fields)) {
            return array();
        }

        $default = 'comic';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'num_id' => $numIdArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($numIdArr))->get()->result_array('num_id');
    }

    /**
    * getChapterNumInfoRowByNumId
    * @param $numId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-10-07 13:18:56
    * @return array
    */
    public function getChapterNumInfoRowByNumId($numId, $fields, $dbMaster = FALSE)
    {
        $numId = intval($numId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($numId) || empty($fields)) {
            return array();
        }

        $default = 'comic';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'num_id' => $numId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }

    /**
    * getChapterNumInfoListByChapterIdArr
    * @param $chapterIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-10-07 13:18:56
    * @return array
    */
    public function getChapterNumInfoListByChapterIdArr($chapterIdArr, $fields, $dbMaster = FALSE)
    {
        $chapterIdArr = array_map('intval',$chapterIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($chapterIdArr) || empty($fields)) {
            return array();
        }

        $default = 'comic';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        return $db->select($fields)->from($this->table)->where_in('chapter_id', $chapterIdArr)
            ->limit(count($chapterIdArr))->get()->result_array('chapter_id');
    }

    /**
    * getChapterNumInfoRowByChapterId
    * @param $chapterId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-10-07 13:18:56
    * @return array
    */
    public function getChapterNumInfoRowByChapterId($chapterId, $fields, $dbMaster = FALSE)
    {
        $chapterId = intval($chapterId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($chapterId) || empty($fields)) {
            return array();
        }

        $default = 'comic';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'chapter_id' => $chapterId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }



    /**
     * saveChapterNumInfo
     * @param $numId
     * @param $saveData
     * @author shell auto create
     * @date 2019-10-07 13:18:56
     * @return array
     */
    public function saveChapterNumInfo($numId, $saveData)
    {
        $numId  = intval($numId);
        $saveData = !is_array($saveData) ? array() : $saveData;

        if(empty($saveData)) {
            return array();
        }

        # 参数整理
        $numId = empty($saveData['num_id']) ? 0 : $saveData['num_id'];
        $chapterId = empty($saveData['chapter_id']) ? 0 : $saveData['chapter_id'];
        $likeNum = empty($saveData['like_num']) ? 0 : $saveData['like_num'];
        $commentNum = empty($saveData['comment_num']) ? 0 : $saveData['comment_num'];
        $createTime = empty($saveData['create_time']) ? 0 : $saveData['create_time'];

        $default = 'comic';
        $resultRow = $this->getChapterNumInfoRowByNumId($numId, $fields = 'num_id');
        if(empty($resultRow))
        {
            $insert = array(
                'chapter_id' => $chapterId,
                'like_num' => $likeNum,
                'comment_num' => $commentNum,
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

            if(! empty($chapterId)) {
                $update['chapter_id'] = $chapterId;
            }

            if(! empty($likeNum)) {
                $update['like_num'] = $likeNum;
            }

            if(! empty($commentNum)) {
                $update['comment_num'] = $commentNum;
            }

            if(! empty($createTime)) {
                $update['create_time'] = $createTime;
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
