<?php

/**
 * Class Music_tag_map_model shell auto create
 * @author xuyi
 * @date 2019-09-27 11:43:56
 */
class Music_tag_map_model extends Driver_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = $this->getTable();
    }


    /**
    * getMusicTagMapListByTagMapIdArr
    * @param $tagMapIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-27 11:43:56
    * @return array
    */
    public function getMusicTagMapListByTagMapIdArr($tagMapIdArr, $fields, $dbMaster = FALSE)
    {
        $tagMapIdArr = array_map('intval',$tagMapIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($tagMapIdArr) || empty($fields)) {
            return array();
        }

        $default = 'music';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'tag_map_id' => $tagMapIdArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($tagMapIdArr))->get()->result_array('tag_map_id');
    }

    /**
    * getMusicTagMapRowByTagMapId
    * @param $tagMapId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-27 11:43:56
    * @return array
    */
    public function getMusicTagMapRowByTagMapId($tagMapId, $fields, $dbMaster = FALSE)
    {
        $tagMapId = intval($tagMapId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($tagMapId) || empty($fields)) {
            return array();
        }

        $default = 'music';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'tag_map_id' => $tagMapId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }

    /**
    * getMusicTagMapListByMusicIdArr
    * @param $musicIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-27 11:43:56
    * @return array
    */
    public function getMusicTagMapListByMusicIdArr($musicIdArr, $fields, $dbMaster = FALSE)
    {
        $musicIdArr = array_map('intval',$musicIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($musicIdArr) || empty($fields)) {
            return array();
        }

        $default = 'music';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'music_id' => $musicIdArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($musicIdArr))->get()->result_array('music_id');
    }

    /**
    * getMusicTagMapRowByMusicId
    * @param $musicId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-27 11:43:56
    * @return array
    */
    public function getMusicTagMapRowByMusicId($musicId, $fields, $dbMaster = FALSE)
    {
        $musicId = intval($musicId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($musicId) || empty($fields)) {
            return array();
        }

        $default = 'music';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'music_id' => $musicId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }

    /**
    * getMusicTagMapListByTagIdArr
    * @param $tagIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-27 11:43:56
    * @return array
    */
    public function getMusicTagMapListByTagIdArr($tagIdArr, $fields, $dbMaster = FALSE)
    {
        $tagIdArr = array_map('intval',$tagIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($tagIdArr) || empty($fields)) {
            return array();
        }

        $default = 'music';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'tag_id' => $tagIdArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($tagIdArr))->get()->result_array('tag_id');
    }

    /**
    * getMusicTagMapRowByTagId
    * @param $tagId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-27 11:43:56
    * @return array
    */
    public function getMusicTagMapRowByTagId($tagId, $fields, $dbMaster = FALSE)
    {
        $tagId = intval($tagId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($tagId) || empty($fields)) {
            return array();
        }

        $default = 'music';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'tag_id' => $tagId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }



    /**
     * saveMusicTagMap
     * @param $tagMapId
     * @param $saveData
     * @author shell auto create
     * @date 2019-09-27 11:43:56
     * @return array
     */
    public function saveMusicTagMap($tagMapId, $saveData)
    {
        $tagMapId  = intval($tagMapId);
        $saveData = !is_array($saveData) ? array() : $saveData;

        if(empty($saveData)) {
            return array();
        }

        # 参数整理
        $tagMapId = empty($saveData['tag_map_id']) ? 0 : $saveData['tag_map_id'];
        $musicId = empty($saveData['music_id']) ? 0 : $saveData['music_id'];
        $tagId = empty($saveData['tag_id']) ? 0 : $saveData['tag_id'];
        $createTime = empty($saveData['create_time']) ? 0 : $saveData['create_time'];

        $default = 'music';
        $resultRow = $this->getMusicTagMapRowByTagMapId($tagMapId, $fields = 'tag_map_id');
        if(empty($resultRow))
        {
            $insert = array(
                'music_id' => $musicId,
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

            if(! empty($musicId)) {
                $update['music_id'] = $musicId;
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
