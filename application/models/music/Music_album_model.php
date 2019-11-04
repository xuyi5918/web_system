<?php

/**
 * Class Music_album_model shell auto create
 * @author xuyi
 * @date 2019-09-27 11:41:57
 */
class Music_album_model extends Driver_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = $this->getTable();
    }


    /**
    * getMusicAlbumListByAlbumIdArr
    * @param $albumIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-27 11:41:57
    * @return array
    */
    public function getMusicAlbumListByAlbumIdArr($albumIdArr, $fields, $dbMaster = FALSE)
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
    * getMusicAlbumRowByAlbumId
    * @param $albumId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-27 11:41:57
    * @return array
    */
    public function getMusicAlbumRowByAlbumId($albumId, $fields, $dbMaster = FALSE)
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
    * getMusicAlbumListByAuthorIdArr
    * @param $authorIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-27 11:41:57
    * @return array
    */
    public function getMusicAlbumListByAuthorIdArr($authorIdArr, $fields, $dbMaster = FALSE)
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
    * getMusicAlbumRowByAuthorId
    * @param $authorId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-27 11:41:57
    * @return array
    */
    public function getMusicAlbumRowByAuthorId($authorId, $fields, $dbMaster = FALSE)
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
     * saveMusicAlbum
     * @param $albumId
     * @param $saveData
     * @author shell auto create
     * @date 2019-09-27 11:41:57
     * @return array
     */
    public function saveMusicAlbum($albumId, $saveData)
    {
        $albumId  = intval($albumId);
        $saveData = !is_array($saveData) ? array() : $saveData;

        if(empty($saveData)) {
            return array();
        }

        # 参数整理
        $albumId = empty($saveData['album_id']) ? 0 : $saveData['album_id'];
        $albumName = empty($saveData['album_name']) ? '' : $saveData['album_name'];
        $albumStatus = empty($saveData['album_status']) ? 0 : $saveData['album_status'];
        $desc = empty($saveData['desc']) ? '' : $saveData['desc'];
        $authorId = empty($saveData['author_id']) ? 0 : $saveData['author_id'];
        $updateTime = empty($saveData['update_time']) ? 0 : $saveData['update_time'];
        $createTime = empty($saveData['create_time']) ? 0 : $saveData['create_time'];

        $default = 'music';
        $resultRow = $this->getMusicAlbumRowByAlbumId($albumId, $fields = 'album_id');
        if(empty($resultRow))
        {
            $insert = array(
                'album_name' => $albumName,
                'album_status' => $albumStatus,
                'desc' => $desc,
                'author_id' => $authorId,
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

            if(! empty($albumName)) {
                $update['album_name'] = $albumName;
            }

            if(! empty($albumStatus)) {
                $update['album_status'] = $albumStatus;
            }

            if(! empty($desc)) {
                $update['desc'] = $desc;
            }

            if(! empty($authorId)) {
                $update['author_id'] = $authorId;
            }

            if(! empty($updateTime)) {
                $update['update_time'] = $updateTime;
            }

            if(! empty($createTime)) {
                $update['create_time'] = $createTime;
            }

            $this->db_master($default)->where(array('album_id' => $albumId))->update($update, $this->table);
            $affectedRows = $this->db_master($default)->affected_rows();
            if(empty($affectedRows)) {
                return errorMsg(500, 'update error~');
            }
        }


        return successMsg('ok~');
    }


    /**
     * editMusicAlbumByAlbumStatus
     * @param $albumId
     * @param $status
     * @author shell auto create
     * @date 2019-09-27 11:41:57
     * @return array
     */
    public function editMusicAlbumByAlbumStatus($albumId, $status)
    {
        $albumId  = intval($albumId);
        $status = in_array($status, array(1, 2, 3)) ? 0 : $status;

        if(empty($albumId) || empty($status)) {
            return errorMsg(404, 'edit databases params is empty');
        }

        $whereArr = array(
            'album_id' => $albumId
        );

        $update = array('album_status'=>$status);

        $default = 'music';
        $this->db_master($default)->where($whereArr)->update($this->table, $update);
        $num = $this->db_master($default)->affected_rows();

        if(empty($num)) {
            return errorMsg(500, 'edit error~');
        }

        return successMsg('ok~');
    }


}
