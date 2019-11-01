<?php

/**
 * Class Book_chapter_info_model shell auto create
 * @author xuyi
 * @date 2019-09-15 05:55:57
 */
class Book_chapter_info_model extends Core_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = $this->getTable();
    }


    /**
    * getBookChapterInfoListByBookChapterIdArr
    * @param $bookChapterIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-15 05:55:57
    * @return array
    */
    public function getBookChapterInfoListByBookChapterIdArr($bookChapterIdArr, $fields, $dbMaster = FALSE)
    {
        $bookChapterIdArr = array_map('intval',$bookChapterIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($bookChapterIdArr) || empty($fields)) {
            return array();
        }

        $default = 'book';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'book_chapter_id' => $bookChapterIdArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($bookChapterIdArr))->get()->result_array('book_chapter_id');
    }

    /**
    * getBookChapterInfoRowByBookChapterId
    * @param $bookChapterId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-15 05:55:57
    * @return array
    */
    public function getBookChapterInfoRowByBookChapterId($bookChapterId, $fields, $dbMaster = FALSE)
    {
        $bookChapterId = intval($bookChapterId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($bookChapterId) || empty($fields)) {
            return array();
        }

        $default = 'book';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'book_chapter_id' => $bookChapterId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }

    /**
    * getBookChapterInfoListByBookIdArr
    * @param $bookIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-15 05:55:57
    * @return array
    */
    public function getBookChapterInfoListByBookIdArr($bookIdArr, $fields, $dbMaster = FALSE)
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
    * getBookChapterInfoRowByBookId
    * @param $bookId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-15 05:55:57
    * @return array
    */
    public function getBookChapterInfoRowByBookId($bookId, $fields, $dbMaster = FALSE)
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
     * saveBookChapterInfo
     * @param $bookChapterId
     * @param $saveData
     * @author shell auto create
     * @date 2019-09-15 05:55:57
     * @return array
     */
    public function saveBookChapterInfo($bookChapterId, $saveData)
    {
        $bookChapterId  = intval($bookChapterId);
        $saveData = is_array($saveData) ? array() : $saveData;

        if(empty($saveData)) {
            return array();
        }

        # 参数整理
        $bookId = empty($saveData['book_id']) ? 0 : $saveData['book_id'];
        $chapterName = empty($saveData['chapter_name']) ? '' : $saveData['chapter_name'];
        $chapterStatus = empty($saveData['chapter_status']) ? 0 : $saveData['chapter_status'];
        $textUrl = empty($saveData['text_url']) ? '' : $saveData['text_url'];
        $createTime = empty($saveData['create_time']) ? 0 : $saveData['create_time'];
        $updateTime = empty($saveData['update_time']) ? 0 : $saveData['update_time'];

        $default = 'book';
        $resultRow = $this->getBookChapterInfoRowByBookChapterId($bookChapterId, $fields = 'book_chapter_id');
        if(empty($resultRow))
        {
            $insert = array(
                'book_id' => $bookId,
                'chapter_name' => $chapterName,
                'chapter_status' => $chapterStatus,
                'text_url' => $textUrl,
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

            if(! empty($chapterName)) {
                $update['chapter_name'] = $chapterName;
            }

            if(! empty($chapterStatus)) {
                $update['chapter_status'] = $chapterStatus;
            }

            if(! empty($textUrl)) {
                $update['text_url'] = $textUrl;
            }

            if(! empty($createTime)) {
                $update['create_time'] = $createTime;
            }

            if(! empty($updateTime)) {
                $update['update_time'] = $updateTime;
            }

            $this->db_master($default)->where(array('book_chapter_id' => $bookChapterId))->update($update, $this->table);
            $affectedRows = $this->db_master($default)->affected_rows();
            if(empty($affectedRows)) {
                return errorMsg(500, 'update error~');
            }
        }


        return successMsg('ok~');
    }


    /**
     * editBookChapterInfoByChapterStatus
     * @param $bookChapterId
     * @param $status
     * @author shell auto create
     * @date 2019-09-15 05:55:57
     * @return array
     */
    public function editBookChapterInfoByChapterStatus($bookChapterId, $status)
    {
        $bookChapterId  = intval($bookChapterId);
        $status = in_array($status, array(1, 2, 3)) ? 0 : $status;

        if(empty($bookChapterId) || empty($status)) {
            return errorMsg(404, 'edit databases params is empty');
        }

        $whereArr = array(
            'book_chapter_id' => $bookChapterId
        );

        $update = array('chapter_status'=>$status);

        $default = 'book';
        $this->db_master($default)->where($whereArr)->update($this->table, $update);
        $num = $this->db_master($default)->affected_rows();

        if(empty($num)) {
            return errorMsg(500, 'edit error~');
        }

        return successMsg('ok~');
    }


}
