<?php

/**
 * Class Credit_rules_info_model shell auto create
 * @author xuyi
 * @date 2019-10-13 12:02:34
 */
class Credit_rules_info_model extends Core_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = $this->getTable();
    }


    /**
    * getCreditRulesInfoListByRulesIdArr
    * @param $rulesIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-10-13 12:02:34
    * @return array
    */
    public function getCreditRulesInfoListByRulesIdArr($rulesIdArr, $fields, $dbMaster = FALSE)
    {
        $rulesIdArr = array_map('intval',$rulesIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($rulesIdArr) || empty($fields)) {
            return array();
        }

        $default = 'basic';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'rules_id' => $rulesIdArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($rulesIdArr))->get()->result_array('rules_id');
    }

    /**
    * getCreditRulesInfoRowByRulesId
    * @param $rulesId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-10-13 12:02:34
    * @return array
    */
    public function getCreditRulesInfoRowByRulesId($rulesId, $fields, $dbMaster = FALSE)
    {
        $rulesId = intval($rulesId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($rulesId) || empty($fields)) {
            return array();
        }

        $default = 'basic';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'rules_id' => $rulesId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }

    /**
    * getCreditRulesInfoListByRulesTypeArr
    * @param $rulesTypeArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-10-13 12:02:34
    * @return array
    */
    public function getCreditRulesInfoListByRulesTypeArr($rulesTypeArr, $fields, $dbMaster = FALSE)
    {
        $rulesTypeArr = array_map('intval',$rulesTypeArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($rulesTypeArr) || empty($fields)) {
            return array();
        }

        $default = 'basic';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'rules_type' => $rulesTypeArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($rulesTypeArr))->get()->result_array('rules_type');
    }

    /**
    * getCreditRulesInfoListByRulesType
    * @param $rulesType
    * @param $creditType
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-10-13 12:02:34
    * @return array
    */
    public function getCreditRulesInfoListByRulesType($rulesType, $creditType, $fields, $dbMaster = FALSE)
    {
        $rulesType = intval($rulesType);
        $fields  = trim($fields);
        $creditType = trim($creditType);

        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($rulesType) || empty($fields) || empty($creditType)) {
            return array();
        }

        $default = 'basic';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'rules_type' => $rulesType,
            'credit_type' => $creditType
        );

        return $db->select($fields)->from($this->table)->where($whereArr)->get()->result_array();
    }



    /**
     * saveCreditRulesInfo
     * @param $rulesId
     * @param $saveData
     * @author shell auto create
     * @date 2019-10-13 12:02:34
     * @return array
     */
    public function saveCreditRulesInfo($rulesId, $saveData)
    {
        $rulesId  = intval($rulesId);
        $saveData = !is_array($saveData) ? array() : $saveData;

        if(empty($saveData)) {
            return array();
        }

        # 参数整理
        $rulesId = empty($saveData['rules_id']) ? 0 : $saveData['rules_id'];
        $rulesName = empty($saveData['rules_name']) ? '' : $saveData['rules_name'];
        $creditVal = empty($saveData['credit_val']) ? 0 : $saveData['credit_val'];
        $rulesType = empty($saveData['rules_type']) ? 0 : $saveData['rules_type'];
        $creditType = empty($saveData['credit_type']) ? '' : $saveData['credit_type'];
        $rulesCycle = empty($saveData['rules_cycle']) ? 0 : $saveData['rules_cycle'];
        $rulesStatus = empty($saveData['rules_status']) ? 0 : $saveData['rules_status'];
        $rulesJson = empty($saveData['rules_json']) ? '' : $saveData['rules_json'];
        $createTime = empty($saveData['create_time']) ? 0 : $saveData['create_time'];
        $updateTime = empty($saveData['update_time']) ? 0 : $saveData['update_time'];

        $default = 'basic';
        $resultRow = $this->getCreditRulesInfoRowByRulesId($rulesId, $fields = 'rules_id');
        if(empty($resultRow))
        {
            $insert = array(
                'rules_name' => $rulesName,
                'credit_val' => $creditVal,
                'rules_type' => $rulesType,
                'credit_type' => $creditType,
                'rules_cycle' => $rulesCycle,
                'rules_status' => $rulesStatus,
                'rules_json' => $rulesJson,
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

            if(! empty($rulesName)) {
                $update['rules_name'] = $rulesName;
            }

            if(! empty($creditVal)) {
                $update['credit_val'] = $creditVal;
            }

            if(! empty($rulesType)) {
                $update['rules_type'] = $rulesType;
            }

            if(! empty($creditType)) {
                $update['credit_type'] = $creditType;
            }

            if(! empty($rulesCycle)) {
                $update['rules_cycle'] = $rulesCycle;
            }

            if(! empty($rulesStatus)) {
                $update['rules_status'] = $rulesStatus;
            }

            if(! empty($rulesJson)) {
                $update['rules_json'] = $rulesJson;
            }

            if(! empty($createTime)) {
                $update['create_time'] = $createTime;
            }

            if(! empty($updateTime)) {
                $update['update_time'] = $updateTime;
            }

            $this->db_master($default)->where(array('rules_id' => $rulesId))->update($update, $this->table);
            $affectedRows = $this->db_master($default)->affected_rows();
            if(empty($affectedRows)) {
                return errorMsg(500, 'update error~');
            }
        }


        return successMsg('ok~');
    }


    /**
     * editCreditRulesInfoByRulesStatus
     * @param $rulesId
     * @param $status
     * @author shell auto create
     * @date 2019-10-13 12:02:34
     * @return array
     */
    public function editCreditRulesInfoByRulesStatus($rulesId, $status)
    {
        $rulesId  = intval($rulesId);
        $status = in_array($status, array(1, 2, 3)) ? 0 : $status;

        if(empty($rulesId) || empty($status)) {
            return errorMsg(404, 'edit databases params is empty');
        }

        $whereArr = array(
            'rules_id' => $rulesId
        );

        $update = array('rules_status'=>$status);

        $default = 'basic';
        $this->db_master($default)->where($whereArr)->update($this->table, $update);
        $num = $this->db_master($default)->affected_rows();

        if(empty($num)) {
            return errorMsg(500, 'edit error~');
        }

        return successMsg('ok~');
    }


}
