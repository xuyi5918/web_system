<?php
class Users_browsing_history_log_model extends Driver_Model
{
    public function __construct()
    {
        parent::__construct();

        $this->table = $this->getTable();
    }

    /**
     * @param $usersId
     * @param $rowsNum
     * @param $pageNum
     * @param $fields
     * @author xuyi
     * @date 2019/8/16
     * @return array
     */
    public function getUsersBrowsingHistoryLogListByPage($usersId, $rowsNum, $pageNum, $fields, $dbMaster = FALSE)
    {
        $usersId = intval($usersId);
        $rowsNum = intval($rowsNum);
        $pageNum = intval($pageNum);
        $fields = trim($fields);

        $offset = ($pageNum - 1) * $rowsNum;

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);


        $resultPage = array(
            'data'       => array(),
            'rows_total' => 0,
            'page_total' => 0,
            'rows_num'   => $rowsNum,
            'page_num'   => $pageNum,
        );

        $whereArr = array('users_id' => $usersId);
        $resultTotal = $db->select('*')->from($this->table)->where($whereArr)->count_all_results();
        if(empty($resultTotal)) {
            return $resultPage;
        }


        $resultList = $db->select($fields)->from($this->table)->where($whereArr)->limit($rowsNum, $offset)
            ->order_by('update_time DESC')->get()->result_array();

        return array(
            'data'       => $resultList,
            'rows_total' => $resultTotal,
            'page_total' => ceil($resultTotal / $rowsNum),
            'rows_num'   => $rowsNum,
            'page_num'   => $pageNum,
        );
    }

    /**
     *
     * @param $comicId
     * @param $fields
     * @param bool $dbMaster
     * @author xuyi
     * @date 2019/8/16
     * @return array
     */
    public function getUsersBrowsingHistoryLogRowByUsersId($usersId, $objectId, $fields, $dbMaster = FALSE)
    {
        $objectId = intval($objectId);
        $usersId = intval($usersId);
        $fields = trim($fields);
        if(empty($objectId) || empty($usersId) || empty($fields)) {
            return array();
        }

        $whereArr = array(
            'users_id' => $usersId,
            'object_id' => $objectId
        );

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);


        return $db->db_slave('users')->select($fields)->from($this->table)->where($whereArr)->limit(1)->get()->row_array();
    }

    /**
     * save users browsing history
     * @param $addRow
     * @author xuyi
     * @date 2019/8/16
     * @return array
     */
    public function saveUsersBrowsingHistoryLog($addRow)
    {
        $addRow = !is_array($addRow) ? array() : $addRow;
        if(empty($addRow)) {
            return errorMsg(2, 'save item empty !');
        }

        $usersId   = empty($addRow['users_id']) ? 0 : intval($addRow['users_id']);

        $objectId   = empty($addRow['object_id']) ? 0 : intval($addRow['object_id']);
        $objectBrowsProgress = empty($addRow['object_brows_progress']) ? 0 : intval($addRow['object_brows_progress']);

        $parentId   = empty($addRow['parent_id']) ? 0 : intval($addRow['parent_id']);

        $browsingType = empty($addRow['browsing_type']) ? 0 : intval($addRow['browsing_type']);

        $fields = 'log_id';
        $logRow = $this->getUsersBrowsingHistoryLogRowByUsersId($usersId, $objectId, $fields);
        $logId  = empty($logRow['log_id']) ? 0 : intval($logRow['log_id']);

        $default = 'users';
        if(empty($logId))
        {
            $insertData = array(
                'users_id'  => $usersId,
                'object_id' => $objectId,
                'object_brows_progress' => $objectBrowsProgress,
                'parent_id'     => $parentId,
                'browsing_type' => $browsingType,
                'create_time'   => time(),
                'update_time'   => time()
            );

            $this->db_master($default)->insert($insertData, $this->table);
            $insertId = $this->db_master($default)->insert_id();
            if(empty($insertId)) {
               return errorMsg(500, 'insert db error');
            }

        } else {

            $update = array();
            if(! empty($chapterImageId)) {
                $update['object_brows_progress'] = $chapterImageId;
            }

            $affectedRows = 0;
            if(! empty($update))
            {
                $update['update_time'] = time();
                $this->db_master($default)->where('log_id', $logId)->update($update, $this->table);
                $affectedRows = $this->db_master($default)->affected_rows();
            }

            if($affectedRows <= 0) {
                return errorMsg(500, 'update db error');
            }
        }

        return successMsg('success');
    }
}
