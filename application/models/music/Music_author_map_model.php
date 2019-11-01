<?php

/**
 * Class Music_author_map_model shell auto create
 * @author xuyi
 * @date 2019-09-27 11:42:56
 */
class Music_author_map_model extends Core_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = $this->getTable();
    }

    
    /**
    * getMusicAuthorMapListByMapIdArr
    * @param $mapIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-27 11:42:56
    * @return array
    */
    public function getMusicAuthorMapListByMapIdArr($mapIdArr, $fields, $dbMaster = FALSE)
    {
        $mapIdArr = array_map('intval',$mapIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($mapIdArr) || empty($fields)) {
            return array();
        }

        $default = 'music';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'map_id' => $mapIdArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($mapIdArr))->get()->result_array('map_id');
    }

    /**
    * getMusicAuthorMapRowByMapId
    * @param $mapId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-27 11:42:56
    * @return array
    */
    public function getMusicAuthorMapRowByMapId($mapId, $fields, $dbMaster = FALSE)
    {
        $mapId = intval($mapId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($mapId) || empty($fields)) {
            return array();
        }

        $default = 'music';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'map_id' => $mapId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }

    /**
    * getMusicAuthorMapListByAuthorIdArr
    * @param $authorIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-27 11:42:56
    * @return array
    */
    public function getMusicAuthorMapListByAuthorIdArr($authorIdArr, $fields, $dbMaster = FALSE)
    {
        $authorIdArr = array_map('intval',$authorIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($authorIdArr) || empty($fields)) {
            return array();
        }

        $default = 'music';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'author_id' => $authorIdArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($authorIdArr))->get()->result_array('author_id');
    }

    /**
    * getMusicAuthorMapRowByAuthorId
    * @param $authorId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-27 11:42:56
    * @return array
    */
    public function getMusicAuthorMapRowByAuthorId($authorId, $fields, $dbMaster = FALSE)
    {
        $authorId = intval($authorId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($authorId) || empty($fields)) {
            return array();
        }

        $default = 'music';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'author_id' => $authorId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }

    /**
    * getMusicAuthorMapListByMusicIdArr
    * @param $musicIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-27 11:42:56
    * @return array
    */
    public function getMusicAuthorMapListByMusicIdArr($musicIdArr, $fields, $dbMaster = FALSE)
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
    * getMusicAuthorMapRowByMusicId
    * @param $musicId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-27 11:42:56
    * @return array
    */
    public function getMusicAuthorMapRowByMusicId($musicId, $fields, $dbMaster = FALSE)
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
     * saveMusicAuthorMap
     * @param $mapId
     * @param $saveData
     * @author shell auto create
     * @date 2019-09-27 11:42:56
     * @return array
     */
    public function saveMusicAuthorMap($mapId, $saveData)
    {
        $mapId  = intval($mapId);
        $saveData = !is_array($saveData) ? array() : $saveData;

        if(empty($saveData)) {
            return array();
        }

        # 参数整理
        $mapId = empty($saveData['map_id']) ? 0 : $saveData['map_id'];
        $authorId = empty($saveData['author_id']) ? 0 : $saveData['author_id'];
        $musicId = empty($saveData['music_id']) ? 0 : $saveData['music_id'];
        $createTime = empty($saveData['create_time']) ? 0 : $saveData['create_time'];

        $default = 'music';
        $resultRow = $this->getMusicAuthorMapRowByMapId($mapId, $fields = 'map_id');
        if(empty($resultRow))
        {
            $insert = array(
                'author_id' => $authorId,
                'music_id' => $musicId,
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

            if(! empty($authorId)) {
                $update['author_id'] = $authorId;
            }

            if(! empty($musicId)) {
                $update['music_id'] = $musicId;
            }

            if(! empty($createTime)) {
                $update['create_time'] = $createTime;
            }

            $this->db_master($default)->where(array('map_id' => $mapId))->update($update, $this->table);
            $affectedRows = $this->db_master($default)->affected_rows();
            if(empty($affectedRows)) {
                return errorMsg(500, 'update error~');
            }
        }


        return successMsg('ok~');
    }


    /**
     * editMusicAuthorMapBy{@EDIT_BY_COLUMN@}
     * @param $mapId
     * @param $status
     * @author shell auto create
     * @date 2019-09-27 11:42:56
     * @return array
     */
    public function editMusicAuthorMapBy{@EDIT_BY_COLUMN@}($mapId, $status)
    {
        $mapId  = intval($mapId);
        $status = in_array($status, array({@STATUS_LIST@})) ? 0 : $status;

        if(empty($mapId) || empty($status)) {
            return errorMsg(404, 'edit databases params is empty');
        }

        $whereArr = array(
            'map_id' => $mapId
        );

        $update = array('{@EDIT_COLUMN@}'=>$status);

        $default = 'music';
        $this->db_master($default)->where($whereArr)->update($this->table, $update);
        $num = $this->db_master($default)->affected_rows();

        if(empty($num)) {
            return errorMsg(500, 'edit error~');
        }

        return successMsg('ok~');
    }


}
