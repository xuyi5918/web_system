<?php
/**
 * 第三方支付回调控制器
 * author: xuyi
 * date: 2019/10/8 10:51
 */
class Order extends Core_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 微信支付回调地址
     * @author xuyi
     * @date 2019/10/9
     */
    public function weixin()
    {

        $productType = '';
        $orderSN     = '';
        $paramsArr   = array();
        $result = self::classes('users_order_class', 'order')->completeOrder($productType, $orderSN, $paramsArr);

        $this->displayJson($result);
    }
}
