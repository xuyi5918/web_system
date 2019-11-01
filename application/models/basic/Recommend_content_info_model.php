<?php

/**
 * Class Recommend_content_info_model shell auto create
 * @author xuyi
 * @date 2019-09-30 10:21:03
 */
class Recommend_content_info_model extends Core_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = $this->getTable();
    }


    /**
    * getRecommendContentInfoListByContentIdArr
    * @param $contentIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-30 10:21:03
    * @return array
    */
    public function getRecommendContentInfoListByContentIdArr($contentIdArr, $fields, $dbMaster = FALSE)
    {
        $contentIdArr = array_map('intval',$contentIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($contentIdArr) || empty($fields)) {
            return array();
        }

        $default = 'basic';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'content_id' => $contentIdArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($contentIdArr))->get()->result_array('content_id');
    }

    /**
    * getRecommendContentInfoRowByContentId
    * @param $contentId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-30 10:21:03
    * @return array
    */
    public function getRecommendContentInfoRowByContentId($contentId, $fields, $dbMaster = FALSE)
    {
        $contentId = intval($contentId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($contentId) || empty($fields)) {
            return array();
        }

        $default = 'basic';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'content_id' => $contentId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }

    /**
    * getRecommendContentInfoListByGroupIdArr
    * @param $groupIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-30 10:21:03
    * @return array
    */
    public function getRecommendContentInfoListByGroupIdArr($groupIdArr, $fields, $dbMaster = FALSE)
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
    * getRecommendContentInfoRowByGroupId
    * @param $groupId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-30 10:21:03
    * @return array
    */
    public function getRecommendContentInfoRowByGroupId($groupId, $fields, $dbMaster = FALSE)
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
    * getRecommendContentInfoListByLocationIdArr
    * @param $locationIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-30 10:21:03
    * @return array
    */
    public function getRecommendContentInfoListByLocationIdArr($locationIdArr, $fields, $dbMaster = FALSE)
    {
        $locationIdArr = array_map('intval',$locationIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($locationIdArr) || empty($fields)) {
            return array();
        }


        $default = 'basic';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        return $db->select($fields)->from($this->table)->where_in('location_id', $locationIdArr)
            ->get()->result_array();
    }

    /**
    * getRecommendContentInfoRowByLocationId
    * @param $locationId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-30 10:21:03
    * @return array
    */
    public function getRecommendContentInfoRowByLocationId($locationId, $fields, $dbMaster = FALSE)
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
     * saveRecommendContentInfo
     * @param $contentId
     * @param $saveData
     * @author shell auto create
     * @date 2019-09-30 10:21:03
     * @return array
     */
    public function saveRecommendContentInfo($contentId, $saveData)
    {
        $contentId  = intval($contentId);
        $saveData = !is_array($saveData) ? array() : $saveData;

        if(empty($saveData)) {
            return array();
        }

        # 参数整理
        $contentId = empty($saveData['content_id']) ? 0 : $saveData['content_id'];
        $groupId = empty($saveData['group_id']) ? 0 : $saveData['group_id'];
        $parentId = empty($saveData['parent_id']) ? 0 : $saveData['parent_id'];
        $locationId = empty($saveData['location_id']) ? 0 : $saveData['location_id'];
        $content = empty($saveData['content']) ? '' : $saveData['content'];
        $link = empty($saveData['link']) ? '' : $saveData['link'];
        $title = empty($saveData['title']) ? '' : $saveData['title'];
        $type = empty($saveData['type']) ? 0 : $saveData['type'];
        $atter = empty($saveData['atter']) ? '' : $saveData['atter'];
        $createTime = empty($saveData['create_time']) ? 0 : $saveData['create_time'];

        $default = 'basic';
        $resultRow = $this->getRecommendContentInfoRowByContentId($contentId, $fields = 'content_id');
        if(empty($resultRow))
        {
            $insert = array(
                'group_id' => $groupId,
                'parent_id' => $parentId,
                'location_id' => $locationId,
                'content' => $content,
                'link' => $link,
                'title' => $title,
                'type' => $type,
                'atter' => $atter,
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

            if(! empty($groupId)) {
                $update['group_id'] = $groupId;
            }

            if(! empty($parentId)) {
                $update['parent_id'] = $parentId;
            }

            if(! empty($locationId)) {
                $update['location_id'] = $locationId;
            }

            if(! empty($content)) {
                $update['content'] = $content;
            }

            if(! empty($link)) {
                $update['link'] = $link;
            }

            if(! empty($title)) {
                $update['title'] = $title;
            }

            if(! empty($type)) {
                $update['type'] = $type;
            }

            if(! empty($atter)) {
                $update['atter'] = $atter;
            }

            if(! empty($createTime)) {
                $update['create_time'] = $createTime;
            }

            $this->db_master($default)->where(array('content_id' => $contentId))->update($update, $this->table);
            $affectedRows = $this->db_master($default)->affected_rows();
            if(empty($affectedRows)) {
                return errorMsg(500, 'update error~');
            }
        }


        return successMsg('ok~');
    }
}
