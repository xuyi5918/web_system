<?php
class Recommend_group_info_model extends Core_Model
{
    public function __construct()
    {
        parent::__construct();

        $this->table = $this->getTable();
    }

    /**
     *
     * @param $groupId
     * @param $fields
     * @param bool $dbMaster
     * @author xuyi
     * @date 2019/7/24
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

        $default = 'configure';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'group_id' => $groupId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }

    /**
     *
     * @param $groupIdArr
     * @param $fields
     * @param bool $dbMaster
     * @author xuyi
     * @date 2019/7/24
     * @return array
     */
    public function getRecommendGroupInfoListByGroupIdArr($groupIdArr, $fields, $dbMaster = FALSE)
    {
        $groupIdArr = array_map('intval', $groupIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($groupIdArr) || empty($fields)) {
            return array();
        }

        $default = 'configure';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);
        return $db->select($fields)->from($this->table)->where_in('group_id', $groupIdArr)
            ->limit(count($groupIdArr))->get()->result_array('group_id');
    }
}
