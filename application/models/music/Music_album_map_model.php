<?php

/**
 * Class Music_album_map_model shell auto create
 * @author xuyi
 * @date 2019-09-27 11:42:46
 */
class Music_album_map_model extends Core_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = $this->getTable();
    }


    /**
    * getMusicAlbumMapListByMapIdArr
    * @param $mapIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-27 11:42:46
    * @return array
    */
    public function getMusicAlbumMapListByMapIdArr($mapIdArr, $fields, $dbMaster = FALSE)
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
    * getMusicAlbumMapRowByMapId
    * @param $mapId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-27 11:42:46
    * @return array
    */
    public function getMusicAlbumMapRowByMapId($mapId, $fields, $dbMaster = FALSE)
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
    * getMusicAlbumMapListByMusicIdArr
    * @param $musicIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-27 11:42:46
    * @return array
    */
    public function getMusicAlbumMapListByMusicIdArr($musicIdArr, $fields, $dbMaster = FALSE)
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
    * getMusicAlbumMapRowByMusicId
    * @param $musicId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-27 11:42:46
    * @return array
    */
    public function getMusicAlbumMapRowByMusicId($musicId, $fields, $dbMaster = FALSE)
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
    * getMusicAlbumMapListByAlbumIdArr
    * @param $albumIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-27 11:42:46
    * @return array
    */
    public function getMusicAlbumMapListByAlbumIdArr($albumIdArr, $fields, $dbMaster = FALSE)
    {
        $albumIdArr = array_map('intval',$albumIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($albumIdArr) || empty($fields)) {
            return array();
        }

        $default = 'music';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'album_id' => $albumIdArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($albumIdArr))->get()->result_array('album_id');
    }

    /**
    * getMusicAlbumMapRowByAlbumId
    * @param $albumId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-27 11:42:46
    * @return array
    */
    public function getMusicAlbumMapRowByAlbumId($albumId, $fields, $dbMaster = FALSE)
    {
        $albumId = intval($albumId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($albumId) || empty($fields)) {
            return array();
        }

        $default = 'music';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'album_id' => $albumId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }



    /**
     * saveMusicAlbumMap
     * @param $mapId
     * @param $saveData
     * @author shell auto create
     * @date 2019-09-27 11:42:46
     * @return array
     */
    public function saveMusicAlbumMap($mapId, $saveData)
    {
        $mapId  = intval($mapId);
        $saveData = !is_array($saveData) ? array() : $saveData;

        if(empty($saveData)) {
            return array();
        }

        # 参数整理
        $mapId = empty($saveData['map_id']) ? 0 : $saveData['map_id'];
        $musicId = empty($saveData['music_id']) ? 0 : $saveData['music_id'];
        $albumId = empty($saveData['album_id']) ? 0 : $saveData['album_id'];
        $createTime = empty($saveData['create_time']) ? 0 : $saveData['create_time'];

        $default = 'music';
        $resultRow = $this->getMusicAlbumMapRowByMapId($mapId, $fields = 'map_id');
        if(empty($resultRow))
        {
            $insert = array(
                'music_id' => $musicId,
                'album_id' => $albumId,
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

            if(! empty($albumId)) {
                $update['album_id'] = $albumId;
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

}
