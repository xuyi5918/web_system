<?php

/**
 * Class Anime_tag_map_model shell auto create
 * @author xuyi
 * @date 2019-09-26 11:30:51
 */
class Anime_tag_map_model extends Driver_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = $this->getTable();
    }


    /**
    * getAnimeTagMapListByTagMapIdArr
    * @param $tagMapIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-26 11:30:51
    * @return array
    */
    public function getAnimeTagMapListByTagMapIdArr($tagMapIdArr, $fields, $dbMaster = FALSE)
    {
        $tagMapIdArr = array_map('intval',$tagMapIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($tagMapIdArr) || empty($fields)) {
            return array();
        }

        $default = 'anime';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'tag_map_id' => $tagMapIdArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($tagMapIdArr))->get()->result_array('tag_map_id');
    }

    /**
    * getAnimeTagMapRowByTagMapId
    * @param $tagMapId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-26 11:30:51
    * @return array
    */
    public function getAnimeTagMapRowByTagMapId($tagMapId, $fields, $dbMaster = FALSE)
    {
        $tagMapId = intval($tagMapId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($tagMapId) || empty($fields)) {
            return array();
        }

        $default = 'anime';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'tag_map_id' => $tagMapId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }

    /**
    * getAnimeTagMapListByAnimeIdArr
    * @param $animeIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-26 11:30:51
    * @return array
    */
    public function getAnimeTagMapListByAnimeIdArr($animeIdArr, $fields, $dbMaster = FALSE)
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
    * getAnimeTagMapRowByAnimeId
    * @param $animeId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-26 11:30:51
    * @return array
    */
    public function getAnimeTagMapRowByAnimeId($animeId, $fields, $dbMaster = FALSE)
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
    * getAnimeTagMapListByTagIdArr
    * @param $tagIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-26 11:30:51
    * @return array
    */
    public function getAnimeTagMapListByTagIdArr($tagIdArr, $fields, $dbMaster = FALSE)
    {
        $tagIdArr = array_map('intval',$tagIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($tagIdArr) || empty($fields)) {
            return array();
        }

        $default = 'anime';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'tag_id' => $tagIdArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($tagIdArr))->get()->result_array('tag_id');
    }

    /**
    * getAnimeTagMapRowByTagId
    * @param $tagId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-26 11:30:51
    * @return array
    */
    public function getAnimeTagMapRowByTagId($tagId, $fields, $dbMaster = FALSE)
    {
        $tagId = intval($tagId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($tagId) || empty($fields)) {
            return array();
        }

        $default = 'anime';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'tag_id' => $tagId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }



    /**
     * saveAnimeTagMap
     * @param $tagMapId
     * @param $saveData
     * @author shell auto create
     * @date 2019-09-26 11:30:51
     * @return array
     */
    public function saveAnimeTagMap($tagMapId, $saveData)
    {
        $tagMapId  = intval($tagMapId);
        $saveData = !is_array($saveData) ? array() : $saveData;

        if(empty($saveData)) {
            return array();
        }

        # 参数整理
        $tagMapId = empty($saveData['tag_map_id']) ? 0 : $saveData['tag_map_id'];
        $animeId = empty($saveData['anime_id']) ? 0 : $saveData['anime_id'];
        $tagId = empty($saveData['tag_id']) ? 0 : $saveData['tag_id'];
        $createTime = empty($saveData['create_time']) ? 0 : $saveData['create_time'];

        $default = 'anime';
        $resultRow = $this->getAnimeTagMapRowByTagMapId($tagMapId, $fields = 'tag_map_id');
        if(empty($resultRow))
        {
            $insert = array(
                'anime_id' => $animeId,
                'tag_id' => $tagId,
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

            if(! empty($tagId)) {
                $update['tag_id'] = $tagId;
            }

            if(! empty($createTime)) {
                $update['create_time'] = $createTime;
            }

            $this->db_master($default)->where(array('tag_map_id' => $tagMapId))->update($update, $this->table);
            $affectedRows = $this->db_master($default)->affected_rows();
            if(empty($affectedRows)) {
                return errorMsg(500, 'update error~');
            }
        }


        return successMsg('ok~');
    }

}
