<?php
/**
 * 虚拟资产金币支付
 * author: xuyi
 * date: 2019/10/8 20:19
 */

class vcoin_payment_class
{
    const PROPORTION = 5; # RMB 兑换虚拟币比例 5(分) 兑换 1 个虚拟币

    public $load = NULL;
    public function __construct()
    {
        $this->load = app();
    }

    /**
     * 虚拟币余额验证
     * @param $usersId
     * @param $price
     * @author xuyi
     * @date 2019/10/10
     */
    public function checkUsersVirtualCoin($usersId, $price)
    {
        $usersId = intval($usersId);
        $price   = intval($price);

        if(empty($usersId) || empty($price)) {
            return errorMsg(500, '支付参数异常, 请重试~');
        }

        $this->load->model('users_info_model', 'users');
        $fields = 'users_id, virtual_coin';
        $usersRow = $this->load->users_info_model->getUsersInfoRowByUsersId($usersId, $fields, $dbMaster = FALSE);
        $vCoin = empty($usersRow['virtual_coin']) ? 0 : intval($usersRow['virtual_coin']);

        if($vCoin < $price) {
            return errorMsg(400, '账户余额不足, 请先充值~');
        }

        return successMsg('check users virtual coin ok~');
    }

    /**
     * 创建支付(虚拟财产交易类型立即支付)
     * @param $paymentType 支付类型
     * @param $paramArr    支付参数
     * @author xuyi
     * @date 2019/10/8
     */
    public function payment($payFormat, $paramArr)
    {
        # 完成支付
        $productType = empty($paramArr['product_type']) ? '' : $paramArr['product_type'];
        $orderSN     = empty($paramArr['order_sn']) ? '' : trim($paramArr['order_sn']);
        $price       = empty($paramArr['price']) ? 0 : intval($paramArr['price']);
        $usersId     = empty($paramArr['users_id']) ? 0 : intval($paramArr['users_id']);

        $paramsArr   = array();

        $vCoin = ceil($price / self::PROPORTION); # 计算虚拟币数量

        # 验证虚拟币余额是否满足支付
        $result = $this->checkUsersVirtualCoin($usersId, $vCoin);
        if(! validate($result)) {
            return $result;
        }


        $this->load->model('users_info_model', 'users');
        $result = $this->load->users_info_model->decrUsersInfoVCoinByUsersId($usersId, $vCoin);
        if(! validate($result)) {
            return errorMsg(500, '服务器异常, 扣款失败~');
        }

        return $this->load->classes('users_order_class', 'users')->completeOrder($productType, $orderSN, $paramsArr);
    }
}
