<?php

/**
 * Class Recommend_page_info_model shell auto create
 * @author xuyi
 * @date 2019-09-27 07:03:38
 */
class Recommend_page_info_model extends Core_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = $this->getTable();
    }


    /**
    * getRecommendPageInfoListByPageIdArr
    * @param $pageIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-27 07:03:38
    * @return array
    */
    public function getRecommendPageInfoListByPageIdArr($pageIdArr, $fields, $dbMaster = FALSE)
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
    * getRecommendPageInfoRowByPageId
    * @param $pageId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-27 07:03:38
    * @return array
    */
    public function getRecommendPageInfoRowByPageId($pageId, $fields, $dbMaster = FALSE)
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
            ->limit(1)->get()->row_array();
    }

    /**
    * getRecommendPageInfoListByMcaArr
    * @param $mcaArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-27 07:03:38
    * @return array
    */
    public function getRecommendPageInfoListByMcaArr($mcaArr, $fields, $dbMaster = FALSE)
    {
        $mcaArr = array_map('intval',$mcaArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($mcaArr) || empty($fields)) {
            return array();
        }

        $default = 'basic';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'mca' => $mcaArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($mcaArr))->get()->result_array('mca');
    }

    /**
    * getRecommendPageInfoRowByMca
    * @param $mca
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-27 07:03:38
    * @return array
    */
    public function getRecommendPageInfoRowByMca($mca, $fields, $dbMaster = FALSE)
    {
        $mca = trim($mca);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($mca) || empty($fields)) {
            return array();
        }

        $default = 'basic';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'mca' => $mca
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }

    /**
     * getRecommendPageInfoRowByPlatform
     * @param $mca
     * @param $fields
     * @param bool $dbMaster
     * @author shell auto create
     * @date 2019-09-27 07:03:38
     * @return array
     */
    public function getRecommendPageInfoRowByPlatform($platform, $mca, $fields, $dbMaster = FALSE)
    {
        $mca = intval($mca);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($mca) || empty($fields)) {
            return array();
        }

        $default = 'basic';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'platform'  => $platform,
            'mca'       => $mca
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }

    /**
     * saveRecommendPageInfo
     * @param $pageId
     * @param $saveData
     * @author shell auto create
     * @date 2019-09-27 07:03:38
     * @return array
     */
    public function saveRecommendPageInfo($pageId, $saveData)
    {
        $pageId  = intval($pageId);
        $saveData = !is_array($saveData) ? array() : $saveData;

        if(empty($saveData)) {
            return array();
        }

        # 参数整理
        $pageId = empty($saveData['page_id']) ? 0 : $saveData['page_id'];
        $platform = empty($saveData['platform']) ? 0 : $saveData['platform'];
        $mca = empty($saveData['mca']) ? '' : $saveData['mca'];
        $createTime = empty($saveData['create_time']) ? 0 : $saveData['create_time'];

        $default = 'basic';
        $resultRow = $this->getRecommendPageInfoRowByPageId($pageId, $fields = 'page_id');
        if(empty($resultRow))
        {
            $insert = array(
                'platform' => $platform,
                'mca' => $mca,
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

            if(! empty($platform)) {
                $update['platform'] = $platform;
            }

            if(! empty($mca)) {
                $update['mca'] = $mca;
            }

            if(! empty($createTime)) {
                $update['create_time'] = $createTime;
            }

            $this->db_master($default)->where(array('page_id' => $pageId))->update($update, $this->table);
            $affectedRows = $this->db_master($default)->affected_rows();
            if(empty($affectedRows)) {
                return errorMsg(500, 'update error~');
            }
        }


        return successMsg('ok~');
    }
}
