<?php

/**
 * Class Anime_info_model shell auto create
 * @author xuyi
 * @date 2019-09-26 11:27:02
 */
class Anime_info_model extends Core_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = $this->getTable();
    }

    
    /**
    * getAnimeInfoListByAnimeIdArr
    * @param $animeIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-26 11:27:02
    * @return array
    */
    public function getAnimeInfoListByAnimeIdArr($animeIdArr, $fields, $dbMaster = FALSE)
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
    * getAnimeInfoRowByAnimeId
    * @param $animeId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-26 11:27:02
    * @return array
    */
    public function getAnimeInfoRowByAnimeId($animeId, $fields, $dbMaster = FALSE)
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
    * getAnimeInfoListByAuthorIdArr
    * @param $authorIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-26 11:27:02
    * @return array
    */
    public function getAnimeInfoListByAuthorIdArr($authorIdArr, $fields, $dbMaster = FALSE)
    {
        $authorIdArr = array_map('intval',$authorIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($authorIdArr) || empty($fields)) {
            return array();
        }

        $default = 'anime';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'author_id' => $authorIdArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($authorIdArr))->get()->result_array('author_id');
    }

    /**
    * getAnimeInfoRowByAuthorId
    * @param $authorId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-26 11:27:02
    * @return array
    */
    public function getAnimeInfoRowByAuthorId($authorId, $fields, $dbMaster = FALSE)
    {
        $authorId = intval($authorId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($authorId) || empty($fields)) {
            return array();
        }

        $default = 'anime';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'author_id' => $authorId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }


    
    /**
     * saveAnimeInfo
     * @param $animeId
     * @param $saveData
     * @author shell auto create
     * @date 2019-09-26 11:27:02
     * @return array
     */
    public function saveAnimeInfo($animeId, $saveData)
    {
        $animeId  = intval($animeId);
        $saveData = !is_array($saveData) ? array() : $saveData;

        if(empty($saveData)) {
            return array();
        }

        # 参数整理
        $animeId = empty($saveData['anime_id']) ? 0 : $saveData['anime_id'];
        $animeName = empty($saveData['anime_name']) ? '' : $saveData['anime_name'];
        $animeStatus = empty($saveData['anime_status']) ? 0 : $saveData['anime_status'];
        $areaId = empty($saveData['area_id']) ? 0 : $saveData['area_id'];
        $authorId = empty($saveData['author_id']) ? 0 : $saveData['author_id'];
        $freePlatform = empty($saveData['free_platform']) ? 0 : $saveData['free_platform'];
        $paymentType = empty($saveData['payment_type']) ? 0 : $saveData['payment_type'];
        $updateTime = empty($saveData['update_time']) ? 0 : $saveData['update_time'];
        $createTime = empty($saveData['create_time']) ? 0 : $saveData['create_time'];

        $default = 'anime';
        $resultRow = $this->getAnimeInfoRowByAnimeId($animeId, $fields = 'anime_id');
        if(empty($resultRow))
        {
            $insert = array(
                'anime_name' => $animeName,
                'anime_status' => $animeStatus,
                'area_id' => $areaId,
                'author_id' => $authorId,
                'free_platform' => $freePlatform,
                'payment_type' => $paymentType,
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

            if(! empty($animeName)) {
                $update['anime_name'] = $animeName;
            }

            if(! empty($animeStatus)) {
                $update['anime_status'] = $animeStatus;
            }

            if(! empty($areaId)) {
                $update['area_id'] = $areaId;
            }

            if(! empty($authorId)) {
                $update['author_id'] = $authorId;
            }

            if(! empty($freePlatform)) {
                $update['free_platform'] = $freePlatform;
            }

            if(! empty($paymentType)) {
                $update['payment_type'] = $paymentType;
            }

            if(! empty($updateTime)) {
                $update['update_time'] = $updateTime;
            }

            if(! empty($createTime)) {
                $update['create_time'] = $createTime;
            }

            $this->db_master($default)->where(array('anime_id' => $animeId))->update($update, $this->table);
            $affectedRows = $this->db_master($default)->affected_rows();
            if(empty($affectedRows)) {
                return errorMsg(500, 'update error~');
            }
        }


        return successMsg('ok~');
    }


    /**
     * editAnimeInfoByAnimeStatus
     * @param $animeId
     * @param $status
     * @author shell auto create
     * @date 2019-09-26 11:27:02
     * @return array
     */
    public function editAnimeInfoByAnimeStatus($animeId, $status)
    {
        $animeId  = intval($animeId);
        $status = in_array($status, array(1, 2, 3)) ? 0 : $status;

        if(empty($animeId) || empty($status)) {
            return errorMsg(404, 'edit databases params is empty');
        }

        $whereArr = array(
            'anime_id' => $animeId
        );

        $update = array('anime_status'=>$status);

        $default = 'anime';
        $this->db_master($default)->where($whereArr)->update($this->table, $update);
        $num = $this->db_master($default)->affected_rows();

        if(empty($num)) {
            return errorMsg(500, 'edit error~');
        }

        return successMsg('ok~');
    }


}
