<?php

/**
 * Class Anime_chapter_info_model shell auto create
 * @author xuyi
 * @date 2019-09-26 11:28:49
 */
class Anime_chapter_info_model extends Driver_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = $this->getTable();
    }


    /**
    * getAnimeChapterInfoListByAnimeChapterIdArr
    * @param $animeChapterIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-26 11:28:49
    * @return array
    */
    public function getAnimeChapterInfoListByAnimeChapterIdArr($animeChapterIdArr, $fields, $dbMaster = FALSE)
    {
        $animeChapterIdArr = array_map('intval',$animeChapterIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($animeChapterIdArr) || empty($fields)) {
            return array();
        }

        $default = 'anime';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'anime_chapter_id' => $animeChapterIdArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($animeChapterIdArr))->get()->result_array('anime_chapter_id');
    }

    /**
    * getAnimeChapterInfoRowByAnimeChapterId
    * @param $animeChapterId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-26 11:28:49
    * @return array
    */
    public function getAnimeChapterInfoRowByAnimeChapterId($animeChapterId, $fields, $dbMaster = FALSE)
    {
        $animeChapterId = intval($animeChapterId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($animeChapterId) || empty($fields)) {
            return array();
        }

        $default = 'anime';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'anime_chapter_id' => $animeChapterId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }

    /**
    * getAnimeChapterInfoListByAnimeIdArr
    * @param $animeIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-26 11:28:49
    * @return array
    */
    public function getAnimeChapterInfoListByAnimeIdArr($animeIdArr, $fields, $dbMaster = FALSE)
    {
        $animeIdArr = array_map('intval',$animeIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($animeIdArr) || empty($fields)) {
            return array();
        }

        $default = 'anime';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'anime_id' => $animeIdArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($animeIdArr))->get()->result_array('anime_id');
    }

    /**
    * getAnimeChapterInfoRowByAnimeId
    * @param $animeId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-26 11:28:49
    * @return array
    */
    public function getAnimeChapterInfoRowByAnimeId($animeId, $fields, $dbMaster = FALSE)
    {
        $animeId = intval($animeId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($animeId) || empty($fields)) {
            return array();
        }

        $default = 'anime';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'anime_id' => $animeId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }



    /**
     * saveAnimeChapterInfo
     * @param $animeChapterId
     * @param $saveData
     * @author shell auto create
     * @date 2019-09-26 11:28:49
     * @return array
     */
    public function saveAnimeChapterInfo($animeChapterId, $saveData)
    {
        $animeChapterId  = intval($animeChapterId);
        $saveData = !is_array($saveData) ? array() : $saveData;

        if(empty($saveData)) {
            return array();
        }

        # 参数整理
        $animeChapterId = empty($saveData['anime_chapter_id']) ? 0 : $saveData['anime_chapter_id'];
        $animeId = empty($saveData['anime_id']) ? 0 : $saveData['anime_id'];
        $chapterName = empty($saveData['chapter_name']) ? '' : $saveData['chapter_name'];
        $mediaUrl = empty($saveData['media_url']) ? '' : $saveData['media_url'];
        $chapterStatus = empty($saveData['chapter_status']) ? 0 : $saveData['chapter_status'];
        $isPayment = empty($saveData['is_payment']) ? 0 : $saveData['is_payment'];
        $chapterPrice = empty($saveData['chapter_price']) ? 0 : $saveData['chapter_price'];
        $chapterSort = empty($saveData['chapter_sort']) ? 0 : $saveData['chapter_sort'];
        $updateTime = empty($saveData['update_time']) ? 0 : $saveData['update_time'];
        $createTime = empty($saveData['create_time']) ? 0 : $saveData['create_time'];

        $default = 'anime';
        $resultRow = $this->getAnimeChapterInfoRowByAnimeChapterId($animeChapterId, $fields = 'anime_chapter_id');
        if(empty($resultRow))
        {
            $insert = array(
                'anime_id' => $animeId,
                'chapter_name' => $chapterName,
                'media_url' => $mediaUrl,
                'chapter_status' => $chapterStatus,
                'is_payment' => $isPayment,
                'chapter_price' => $chapterPrice,
                'chapter_sort' => $chapterSort,
                'update_time' => $updateTime,
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

            if(! empty($animeId)) {
                $update['anime_id'] = $animeId;
            }

            if(! empty($chapterName)) {
                $update['chapter_name'] = $chapterName;
            }

            if(! empty($mediaUrl)) {
                $update['media_url'] = $mediaUrl;
            }

            if(! empty($chapterStatus)) {
                $update['chapter_status'] = $chapterStatus;
            }

            if(! empty($isPayment)) {
                $update['is_payment'] = $isPayment;
            }

            if(! empty($chapterPrice)) {
                $update['chapter_price'] = $chapterPrice;
            }

            if(! empty($chapterSort)) {
                $update['chapter_sort'] = $chapterSort;
            }

            if(! empty($updateTime)) {
                $update['update_time'] = $updateTime;
            }

            if(! empty($createTime)) {
                $update['create_time'] = $createTime;
            }

            $this->db_master($default)->where(array('anime_chapter_id' => $animeChapterId))->update($update, $this->table);
            $affectedRows = $this->db_master($default)->affected_rows();
            if(empty($affectedRows)) {
                return errorMsg(500, 'update error~');
            }
        }


        return successMsg('ok~');
    }


    /**
     * editAnimeChapterInfoByChapterStatus
     * @param $animeChapterId
     * @param $status
     * @author shell auto create
     * @date 2019-09-26 11:28:49
     * @return array
     */
    public function editAnimeChapterInfoByChapterStatus($animeChapterId, $status)
    {
        $animeChapterId  = intval($animeChapterId);
        $status = in_array($status, array(1, 2, 3)) ? 0 : $status;

        if(empty($animeChapterId) || empty($status)) {
            return errorMsg(404, 'edit databases params is empty');
        }

        $whereArr = array(
            'anime_chapter_id' => $animeChapterId
        );

        $update = array('chapter_status'=>$status);

        $default = 'anime';
        $this->db_master($default)->where($whereArr)->update($this->table, $update);
        $num = $this->db_master($default)->affected_rows();

        if(empty($num)) {
            return errorMsg(500, 'edit error~');
        }

        return successMsg('ok~');
    }


}
