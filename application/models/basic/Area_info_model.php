<?php

/**
 * Class Area_info_model shell auto create
 * @author xuyi
 * @date 2019-10-13 02:57:42
 */
class Area_info_model extends Driver_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = $this->getTable();
    }

    /**
     * getAreaInfoListByType
     * @param $areaType
     * @param $fields
     * @param bool $dbMaster
     * @author xuyi
     * @date 2019/10/13
     * @return array
     */
    public function getAreaInfoListByType($areaType, $fields, $dbMaster = FALSE)
    {
        $areaType = intval($areaType);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($areaType) || empty($fields)) {
            return array();
        }

        $default = 'basic';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'area_type' => $areaType,
            'area_status' => NORMAL
        );

        return $db->select($fields)->from($this->table)->where($whereArr)->get()->result_array();
    }

    /**
    * getAreaInfoListByAreaIdArr
    * @param $areaIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-10-13 02:57:42
    * @return array
    */
    public function getAreaInfoListByAreaIdArr($areaIdArr, $fields, $dbMaster = FALSE)
    {
        $areaIdArr = array_map('intval',$areaIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($areaIdArr) || empty($fields)) {
            return array();
        }

        $default = 'basic';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'area_id' => $areaIdArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($areaIdArr))->get()->result_array('area_id');
    }

    /**
    * getAreaInfoRowByAreaId
    * @param $areaId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-10-13 02:57:42
    * @return array
    */
    public function getAreaInfoRowByAreaId($areaId, $fields, $dbMaster = FALSE)
    {
        $areaId = intval($areaId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($areaId) || empty($fields)) {
            return array();
        }

        $default = 'basic';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'area_id' => $areaId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }



    /**
     * saveAreaInfo
     * @param $areaId
     * @param $saveData
     * @author shell auto create
     * @date 2019-10-13 02:57:42
     * @return array
     */
    public function saveAreaInfo($areaId, $saveData)
    {
        $areaId  = intval($areaId);
        $saveData = !is_array($saveData) ? array() : $saveData;

        if(empty($saveData)) {
            return array();
        }

        # 参数整理
        $areaId = empty($saveData['area_id']) ? 0 : $saveData['area_id'];
        $areaName = empty($saveData['area_name']) ? '' : $saveData['area_name'];
        $areaStatus = empty($saveData['area_status']) ? 0 : $saveData['area_status'];
        $areaType = empty($saveData['area_type']) ? 0 : $saveData['area_type'];
        $createTime = empty($saveData['create_time']) ? 0 : $saveData['create_time'];
        $updateTime = empty($saveData['update_time']) ? 0 : $saveData['update_time'];

        $default = 'basic';
        $resultRow = $this->getAreaInfoRowByAreaId($areaId, $fields = 'area_id');
        if(empty($resultRow))
        {
            $insert = array(
                'area_name' => $areaName,
                'area_status' => $areaStatus,
                'area_type' => $areaType,
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

            if(! empty($areaName)) {
                $update['area_name'] = $areaName;
            }

            if(! empty($areaStatus)) {
                $update['area_status'] = $areaStatus;
            }

            if(! empty($areaType)) {
                $update['area_type'] = $areaType;
            }

            if(! empty($createTime)) {
                $update['create_time'] = $createTime;
            }

            if(! empty($updateTime)) {
                $update['update_time'] = $updateTime;
            }

            $this->db_master($default)->where(array('area_id' => $areaId))->update($update, $this->table);
            $affectedRows = $this->db_master($default)->affected_rows();
            if(empty($affectedRows)) {
                return errorMsg(500, 'update error~');
            }
        }


        return successMsg('ok~');
    }


    /**
     * editAreaInfoByAreaStatus
     * @param $areaId
     * @param $status
     * @author shell auto create
     * @date 2019-10-13 02:57:42
     * @return array
     */
    public function editAreaInfoByAreaStatus($areaId, $status)
    {
        $areaId  = intval($areaId);
        $status = in_array($status, array(1, 2, 3)) ? 0 : $status;

        if(empty($areaId) || empty($status)) {
            return errorMsg(404, 'edit databases params is empty');
        }

        $whereArr = array(
            'area_id' => $areaId
        );

        $update = array('area_status'=>$status);

        $default = 'basic';
        $this->db_master($default)->where($whereArr)->update($this->table, $update);
        $num = $this->db_master($default)->affected_rows();

        if(empty($num)) {
            return errorMsg(500, 'edit error~');
        }

        return successMsg('ok~');
    }


}
