<?php

/**
 * Class Music_extend_info_model shell auto create
 * @author xuyi
 * @date 2019-09-27 11:43:15
 */
class Music_extend_info_model extends Core_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = $this->getTable();
    }

    
    /**
    * getMusicExtendInfoListByExtendIdArr
    * @param $extendIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-27 11:43:15
    * @return array
    */
    public function getMusicExtendInfoListByExtendIdArr($extendIdArr, $fields, $dbMaster = FALSE)
    {
        $extendIdArr = array_map('intval',$extendIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($extendIdArr) || empty($fields)) {
            return array();
        }

        $default = 'music';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'extend_id' => $extendIdArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($extendIdArr))->get()->result_array('extend_id');
    }

    /**
    * getMusicExtendInfoRowByExtendId
    * @param $extendId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-27 11:43:15
    * @return array
    */
    public function getMusicExtendInfoRowByExtendId($extendId, $fields, $dbMaster = FALSE)
    {
        $extendId = intval($extendId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($extendId) || empty($fields)) {
            return array();
        }

        $default = 'music';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'extend_id' => $extendId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }

    /**
    * getMusicExtendInfoListByMusicIdArr
    * @param $musicIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-27 11:43:15
    * @return array
    */
    public function getMusicExtendInfoListByMusicIdArr($musicIdArr, $fields, $dbMaster = FALSE)
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
    * getMusicExtendInfoRowByMusicId
    * @param $musicId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-27 11:43:15
    * @return array
    */
    public function getMusicExtendInfoRowByMusicId($musicId, $fields, $dbMaster = FALSE)
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
     * saveMusicExtendInfo
     * @param $extendId
     * @param $saveData
     * @author shell auto create
     * @date 2019-09-27 11:43:15
     * @return array
     */
    public function saveMusicExtendInfo($extendId, $saveData)
    {
        $extendId  = intval($extendId);
        $saveData = !is_array($saveData) ? array() : $saveData;

        if(empty($saveData)) {
            return array();
        }

        # 参数整理
        $extendId = empty($saveData['extend_id']) ? 0 : $saveData['extend_id'];
        $musicId = empty($saveData['music_id']) ? 0 : $saveData['music_id'];
        $musicCover = empty($saveData['music_cover']) ? '' : $saveData['music_cover'];
        $musicDesc = empty($saveData['music_desc']) ? '' : $saveData['music_desc'];
        $musicPrice = empty($saveData['music_price']) ? 0 : $saveData['music_price'];
        $updateTime = empty($saveData['update_time']) ? 0 : $saveData['update_time'];
        $createTime = empty($saveData['create_time']) ? 0 : $saveData['create_time'];

        $default = 'music';
        $resultRow = $this->getMusicExtendInfoRowByExtendId($extendId, $fields = 'extend_id');
        if(empty($resultRow))
        {
            $insert = array(
                'music_id' => $musicId,
                'music_cover' => $musicCover,
                'music_desc' => $musicDesc,
                'music_price' => $musicPrice,
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

            if(! empty($musicId)) {
                $update['music_id'] = $musicId;
            }

            if(! empty($musicCover)) {
                $update['music_cover'] = $musicCover;
            }

            if(! empty($musicDesc)) {
                $update['music_desc'] = $musicDesc;
            }

            if(! empty($musicPrice)) {
                $update['music_price'] = $musicPrice;
            }

            if(! empty($updateTime)) {
                $update['update_time'] = $updateTime;
            }

            if(! empty($createTime)) {
                $update['create_time'] = $createTime;
            }

            $this->db_master($default)->where(array('extend_id' => $extendId))->update($update, $this->table);
            $affectedRows = $this->db_master($default)->affected_rows();
            if(empty($affectedRows)) {
                return errorMsg(500, 'update error~');
            }
        }


        return successMsg('ok~');
    }


    /**
     * editMusicExtendInfoBy{@EDIT_BY_COLUMN@}
     * @param $extendId
     * @param $status
     * @author shell auto create
     * @date 2019-09-27 11:43:15
     * @return array
     */
    public function editMusicExtendInfoBy{@EDIT_BY_COLUMN@}($extendId, $status)
    {
        $extendId  = intval($extendId);
        $status = in_array($status, array({@STATUS_LIST@})) ? 0 : $status;

        if(empty($extendId) || empty($status)) {
            return errorMsg(404, 'edit databases params is empty');
        }

        $whereArr = array(
            'extend_id' => $extendId
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
