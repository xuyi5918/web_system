<?php
/**
 * Created by PhpStorm.
 * author: xuyi
 * date: 2019/10/13 12:35
 */

class credit_rules_class
{
    public $load = NULL;
    public $rulesTypeMap = array(
        'comic' => 1,
        'novel' => 2,
        'music' => 3,
        'anime' => 4,
    );
    public function __construct()
    {
        $this->load = app();
    }

    /**
     * validationCreditRules
     * @param $rulesType
     * @param $creditType
     * @author xuyi
     * @date 2019/10/13
     * @return array
     */
    public function validationCreditRules($rulesType, $creditType, $paramArr)
    {
        $rulesType  = trim($rulesType);
        $creditType = trim($creditType);

        if(empty($rulesType) || empty($creditType)) {
            return errorMsg(404, 'error~');
        }


        $rulesTypeId = isset($this->rulesTypeMap[$rulesType]) ? $this->rulesTypeMap[$rulesType] : '';
        if(empty($rulesTypeId)) {
            return errorMsg(500, '没有这种积分验证规则~');
        }


        $this->load->model('credit_rules_info_model', 'basic');
        $fields    = '*';
        $rulesList = $this->load->credit_rules_info_model->cache(CACHE_OUT_TIME_THIRTY_MINU)
            ->getCreditRulesInfoListByRulesType($rulesTypeId, $creditType, $fields, $dbMaster = FALSE);

        $rulesFiles = "credit_{$rulesType}_rules_class";
        $this->load->classes($rulesFiles, 'credit_rules');

        return successMsg();
    }
}
