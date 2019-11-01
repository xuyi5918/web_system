<?php
/**
 * credit 相关逻辑
 * author: xuyi
 * date: 2019/10/13 18:02
 */
class comic_credit_class
{
    public $load = NULL;
    public function __construct()
    {
        $this->load = app();
    }

    /**
     * 增加积分方法
     * @author xuyi
     * @date 2019/10/13
     * @return array
     */
    public function incrComicCredit()
    {
        # RPC 调用规则验证
        $rules = $this->load->server('credit_rules_class', 'basic', SITE_BASIC)->validationCreditRules()->result();
        if(! validate($rules)) {
            return $rules;
        }

        $credit = $this->load->server('users_credit_level_class', 'users', SITE_USERS)->incrUsersCredit()->result();
        if(! validate($credit)) {
            return $credit;
        }

        $result = array();
        return successMsg('incr credit ok~', $result);
    }
}
