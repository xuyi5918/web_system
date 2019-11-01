<?php

/**
 * Class Recommend_location_info_model shell auto create
 * @author xuyi
 * @date 2019-09-30 11:07:26
 */
class Recommend_location_info_model extends Core_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = $this->getTable();
    }


    /**
    * getRecommendLocationInfoListByLocationIdArr
    * @param $locationIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-30 11:07:26
    * @return array
    */
    public function getRecommendLocationInfoListByLocationIdArr($locationIdArr, $fields, $dbMaster = FALSE)
    {
        $locationIdArr = array_map('intval',$locationIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($locationIdArr) || empty($fields)) {
            return array();
        }

        $default = 'basic';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'location_id' => $locationIdArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($locationIdArr))->get()->result_array('location_id');
    }

    /**
    * getRecommendLocationInfoRowByLocationId
    * @param $locationId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-30 11:07:26
    * @return array
    */
    public function getRecommendLocationInfoRowByLocationId($locationId, $fields, $dbMaster = FALSE)
    {
        $locationId = intval($locationId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($locationId) || empty($fields)) {
            return array();
        }

        $default = 'basic';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'location_id' => $locationId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }

    /**
    * getRecommendLocationInfoListByPageIdArr
    * @param $pageIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-30 11:07:26
    * @return array
    */
    public function getRecommendLocationInfoListByPageIdArr($pageIdArr, $fields, $dbMaster = FALSE)
    {
        $pageIdArr = array_map('intval',$pageIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($pageIdArr) || empty($fields)) {
            return array();
        }

        $default = 'basic';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'page_id' => $pageIdArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($pageIdArr))->get()->result_array('page_id');
    }

    /**
    * getRecommendLocationInfoRowByPageId
    * @param $pageId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-30 11:07:26
    * @return array
    */
    public function getRecommendLocationInfoListByPageId($pageId, $fields, $dbMaster = FALSE)
    {
        $pageId = intval($pageId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($pageId) || empty($fields)) {
            return array();
        }

        $default = 'basic';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'page_id' => $pageId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->order_by('location_sort DESC')->get()->result_array('location_name');
    }



    /**
     * saveRecommendLocationInfo
     * @param $locationId
     * @param $saveData
     * @author shell auto create
     * @date 2019-09-30 11:07:26
     * @return array
     */
    public function saveRecommendLocationInfo($locationId, $saveData)
    {
        $locationId  = intval($locationId);
        $saveData = !is_array($saveData) ? array() : $saveData;

        if(empty($saveData)) {
            return array();
        }

        # 参数整理
        $locationId = empty($saveData['location_id']) ? 0 : $saveData['location_id'];
        $locationName = empty($saveData['location_name']) ? '' : $saveData['location_name'];
        $pageId = empty($saveData['page_id']) ? 0 : $saveData['page_id'];
        $locationStatus = empty($saveData['location_status']) ? 0 : $saveData['location_status'];
        $createTime = empty($saveData['create_time']) ? 0 : $saveData['create_time'];

        $default = 'basic';
        $resultRow = $this->getRecommendLocationInfoRowByLocationId($locationId, $fields = 'location_id');
        if(empty($resultRow))
        {
            $insert = array(
                'location_name' => $locationName,
                'page_id' => $pageId,
                'location_status' => $locationStatus,
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

            if(! empty($locationName)) {
                $update['location_name'] = $locationName;
            }

            if(! empty($pageId)) {
                $update['page_id'] = $pageId;
            }

            if(! empty($locationStatus)) {
                $update['location_status'] = $locationStatus;
            }

            if(! empty($createTime)) {
                $update['create_time'] = $createTime;
            }

            $this->db_master($default)->where(array('location_id' => $locationId))->update($update, $this->table);
            $affectedRows = $this->db_master($default)->affected_rows();
            if(empty($affectedRows)) {
                return errorMsg(500, 'update error~');
            }
        }


        return successMsg('ok~');
    }


    /**
     * editRecommendLocationInfoByLocationStatus
     * @param $locationId
     * @param $status
     * @author shell auto create
     * @date 2019-09-30 11:07:26
     * @return array
     */
    public function editRecommendLocationInfoByLocationStatus($locationId, $status)
    {
        $locationId  = intval($locationId);
        $status = in_array($status, array('')) ? 0 : $status;

        if(empty($locationId) || empty($status)) {
            return errorMsg(404, 'edit databases params is empty');
        }

        $whereArr = array(
            'location_id' => $locationId
        );

        $update = array('location_status'=>$status);

        $default = 'basic';
        $this->db_master($default)->where($whereArr)->update($this->table, $update);
        $num = $this->db_master($default)->affected_rows();

        if(empty($num)) {
            return errorMsg(500, 'edit error~');
        }

        return successMsg('ok~');
    }


}
