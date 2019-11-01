<?php
class Comic_tag_info_model extends Core_Model
{
    public function __construct()
    {
        parent::__construct();

        $this->table = $this->getTable();
    }

    /**
     * @param $tagIdArr
     * @param $fields
     * @param bool $dbMaster
     * @author xuyi
     * @date 2019/8/22
     * @return array
     */
    public function getComicTagInfoListByTagIdArr($tagIdArr, $fields, $dbMaster = FALSE)
    {
        $tagIdArr = array_map('intval', $tagIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($tagIdArr) || empty($fields)) {
            return array();
        }

        $default = 'comic';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'tag_id' => $tagIdArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(1)->get()->result_array('tag_id');
    }

    /**
     * @param $tagId
     * @param $fields
     * @param bool $dbMaster
     * @author xuyi
     * @date 2019/8/22
     * @return array
     */
    public function getComicTagInfoRowByTagId($tagId, $fields, $dbMaster = FALSE)
    {
        $tagId = intval($tagId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($tagId) || empty($fields)) {
            return array();
        }

        $default = 'comic';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'tag_id' => $tagId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }

    /**
     * @param $tagStatus
     * @param $fields
     * @author xuyi
     * @date 2019/8/22
     * @return array
     */
    public function getComicTagInfoListByTagStatus($tagStatus, $fields)
    {
        $tagStatus = intval($tagStatus);
        $fields = trim($fields);

        if(empty($tagStatus) || empty($fields)) {
            return array();
        }

        $whereArr = array(
            'tag_status' => $tagStatus
        );

        return $this->db_slave('comic')->from($this->table)->where($whereArr)
            ->get()->result_array('tag_id');
    }
}
