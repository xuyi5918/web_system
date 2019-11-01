<?php

/**
 * Class Tag_info_model shell auto create
 * @author xuyi
 * @date 2019-09-27 06:38:25
 */
class Tag_info_model extends Core_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = $this->getTable();
    }

    /**
     * getTagInfoListByType
     * @param $tagType
     * @param $fields
     * @param bool $dbMaster
     * @author xuyi
     * @date 2019/10/13
     * @return array
     */
    public function getTagInfoListByType($tagType, $fields, $dbMaster = FALSE)
    {
        $tagType = intval($tagType);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($tagType) || empty($fields)) {
            return array();
        }

        $default = 'basic';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'tag_type'   => $tagType,
            'tag_status' => NORMAL
        );

        return $db->select($fields)->from($this->table)->where($whereArr)->get()->result_array();
    }

    /**
    * getTagInfoListByTagIdArr
    * @param $tagIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-27 06:38:25
    * @return array
    */
    public function getTagInfoListByTagIdArr($tagIdArr, $fields, $dbMaster = FALSE)
    {
        $tagIdArr = array_map('intval',$tagIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($tagIdArr) || empty($fields)) {
            return array();
        }

        $default = 'basic';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'tag_id' => $tagIdArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($tagIdArr))->get()->result_array('tag_id');
    }

    /**
    * getTagInfoRowByTagId
    * @param $tagId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-27 06:38:25
    * @return array
    */
    public function getTagInfoRowByTagId($tagId, $fields, $dbMaster = FALSE)
    {
        $tagId = intval($tagId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($tagId) || empty($fields)) {
            return array();
        }

        $default = 'basic';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'tag_id' => $tagId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }



    /**
     * saveTagInfo
     * @param $tagId
     * @param $saveData
     * @author shell auto create
     * @date 2019-09-27 06:38:25
     * @return array
     */
    public function saveTagInfo($tagId, $saveData)
    {
        $tagId  = intval($tagId);
        $saveData = !is_array($saveData) ? array() : $saveData;

        if(empty($saveData)) {
            return array();
        }

        # 参数整理
        $tagId = empty($saveData['tag_id']) ? 0 : $saveData['tag_id'];
        $tagName = empty($saveData['tag_name']) ? '' : $saveData['tag_name'];
        $createTime = empty($saveData['create_time']) ? 0 : $saveData['create_time'];
        $updateTime = empty($saveData['update_time']) ? 0 : $saveData['update_time'];

        $default = 'basic';
        $resultRow = $this->getTagInfoRowByTagId($tagId, $fields = 'tag_id');
        if(empty($resultRow))
        {
            $insert = array(
                'tag_name' => $tagName,
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

            if(! empty($tagName)) {
                $update['tag_name'] = $tagName;
            }

            if(! empty($createTime)) {
                $update['create_time'] = $createTime;
            }

            if(! empty($updateTime)) {
                $update['update_time'] = $updateTime;
            }

            $this->db_master($default)->where(array('tag_id' => $tagId))->update($update, $this->table);
            $affectedRows = $this->db_master($default)->affected_rows();
            if(empty($affectedRows)) {
                return errorMsg(500, 'update error~');
            }
        }


        return successMsg('ok~');
    }
}
