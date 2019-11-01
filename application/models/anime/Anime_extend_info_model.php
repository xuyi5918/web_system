<?php

/**
 * Class Anime_extend_info_model shell auto create
 * @author xuyi
 * @date 2019-09-26 11:28:00
 */
class Anime_extend_info_model extends Core_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = $this->getTable();
    }


    /**
    * getAnimeExtendInfoListByAnimeExtendIdArr
    * @param $animeExtendIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-26 11:28:00
    * @return array
    */
    public function getAnimeExtendInfoListByAnimeExtendIdArr($animeExtendIdArr, $fields, $dbMaster = FALSE)
    {
        $animeExtendIdArr = array_map('intval',$animeExtendIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($animeExtendIdArr) || empty($fields)) {
            return array();
        }

        $default = 'anime';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'anime_extend_id' => $animeExtendIdArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($animeExtendIdArr))->get()->result_array('anime_extend_id');
    }

    /**
    * getAnimeExtendInfoRowByAnimeExtendId
    * @param $animeExtendId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-26 11:28:00
    * @return array
    */
    public function getAnimeExtendInfoRowByAnimeExtendId($animeExtendId, $fields, $dbMaster = FALSE)
    {
        $animeExtendId = intval($animeExtendId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($animeExtendId) || empty($fields)) {
            return array();
        }

        $default = 'anime';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'anime_extend_id' => $animeExtendId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }

    /**
    * getAnimeExtendInfoListByAnimeIdArr
    * @param $animeIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-26 11:28:00
    * @return array
    */
    public function getAnimeExtendInfoListByAnimeIdArr($animeIdArr, $fields, $dbMaster = FALSE)
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
    * getAnimeExtendInfoRowByAnimeId
    * @param $animeId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-26 11:28:00
    * @return array
    */
    public function getAnimeExtendInfoRowByAnimeId($animeId, $fields, $dbMaster = FALSE)
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
     * saveAnimeExtendInfo
     * @param $animeExtendId
     * @param $saveData
     * @author shell auto create
     * @date 2019-09-26 11:28:00
     * @return array
     */
    public function saveAnimeExtendInfo($animeExtendId, $saveData)
    {
        $animeExtendId  = intval($animeExtendId);
        $saveData = !is_array($saveData) ? array() : $saveData;

        if(empty($saveData)) {
            return array();
        }

        # 参数整理
        $animeExtendId = empty($saveData['anime_extend_id']) ? 0 : $saveData['anime_extend_id'];
        $animeId = empty($saveData['anime_id']) ? 0 : $saveData['anime_id'];
        $animeCover = empty($saveData['anime_cover']) ? '' : $saveData['anime_cover'];
        $animeDesc = empty($saveData['anime_desc']) ? '' : $saveData['anime_desc'];
        $newChapterId = empty($saveData['new_chapter_id']) ? 0 : $saveData['new_chapter_id'];
        $animePrice = empty($saveData['anime_price']) ? 0 : $saveData['anime_price'];
        $chapterDefaultPrice = empty($saveData['chapter_default_price']) ? 0 : $saveData['chapter_default_price'];
        $createTime = empty($saveData['create_time']) ? 0 : $saveData['create_time'];
        $updateTime = empty($saveData['update_time']) ? 0 : $saveData['update_time'];

        $default = 'anime';
        $resultRow = $this->getAnimeExtendInfoRowByAnimeExtendId($animeExtendId, $fields = 'anime_extend_id');
        if(empty($resultRow))
        {
            $insert = array(
                'anime_id' => $animeId,
                'anime_cover' => $animeCover,
                'anime_desc' => $animeDesc,
                'new_chapter_id' => $newChapterId,
                'anime_price' => $animePrice,
                'chapter_default_price' => $chapterDefaultPrice,
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

            if(! empty($animeId)) {
                $update['anime_id'] = $animeId;
            }

            if(! empty($animeCover)) {
                $update['anime_cover'] = $animeCover;
            }

            if(! empty($animeDesc)) {
                $update['anime_desc'] = $animeDesc;
            }

            if(! empty($newChapterId)) {
                $update['new_chapter_id'] = $newChapterId;
            }

            if(! empty($animePrice)) {
                $update['anime_price'] = $animePrice;
            }

            if(! empty($chapterDefaultPrice)) {
                $update['chapter_default_price'] = $chapterDefaultPrice;
            }

            if(! empty($createTime)) {
                $update['create_time'] = $createTime;
            }

            if(! empty($updateTime)) {
                $update['update_time'] = $updateTime;
            }

            $this->db_master($default)->where(array('anime_extend_id' => $animeExtendId))->update($update, $this->table);
            $affectedRows = $this->db_master($default)->affected_rows();
            if(empty($affectedRows)) {
                return errorMsg(500, 'update error~');
            }
        }


        return successMsg('ok~');
    }

}
