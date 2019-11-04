<?php

/**
 * Class Recommend_group_info_model shell auto create
 * @author xuyi
 * @date 2019-09-27 06:37:53
 */
class Recommend_group_info_model extends Driver_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = $this->getTable();
    }


    /**
    * getRecommendGroupInfoListByGroupIdArr
    * @param $groupIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-27 06:37:53
    * @return array
    */
    public function getRecommendGroupInfoListByGroupIdArr($groupIdArr, $fields, $dbMaster = FALSE)
    {
        $groupIdArr = array_map('intval',$groupIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($groupIdArr) || empty($fields)) {
            return array();
        }

        $default = 'basic';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'group_id' => $groupIdArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($groupIdArr))->get()->result_array('group_id');
    }

    /**
    * getRecommendGroupInfoRowByGroupId
    * @param $groupId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-27 06:37:53
    * @return array
    */
    public function getRecommendGroupInfoRowByGroupId($groupId, $fields, $dbMaster = FALSE)
    {
        $groupId = intval($groupId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($groupId) || empty($fields)) {
            return array();
        }

        $default = 'basic';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'group_id' => $groupId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }



    /**
     * saveRecommendGroupInfo
     * @param $groupId
     * @param $saveData
     * @author shell auto create
     * @date 2019-09-27 06:37:53
     * @return array
     */
    public function saveRecommendGroupInfo($groupId, $saveData)
    {
        $groupId  = intval($groupId);
        $saveData = !is_array($saveData) ? array() : $saveData;

        if(empty($saveData)) {
            return array();
        }

        # 参数整理
        $groupId = empty($saveData['group_id']) ? 0 : $saveData['group_id'];
        $groupName = empty($saveData['group_name']) ? '' : $saveData['group_name'];
        $groupType = empty($saveData['group_type']) ? '' : $saveData['group_type'];
        $createTime = empty($saveData['create_time']) ? 0 : $saveData['create_time'];

        $default = 'basic';
        $resultRow = $this->getRecommendGroupInfoRowByGroupId($groupId, $fields = 'group_id');
        if(empty($resultRow))
        {
            $insert = array(
                'group_name' => $groupName,
                'group_type' => $groupType,
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

            if(! empty($groupName)) {
                $update['group_name'] = $groupName;
            }

            if(! empty($groupType)) {
                $update['group_type'] = $groupType;
            }

            if(! empty($createTime)) {
                $update['create_time'] = $createTime;
            }

            $this->db_master($default)->where(array('group_id' => $groupId))->update($update, $this->table);
            $affectedRows = $this->db_master($default)->affected_rows();
            if(empty($affectedRows)) {
                return errorMsg(500, 'update error~');
            }
        }


        return successMsg('ok~');
    }
}
