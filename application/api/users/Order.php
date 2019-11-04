<?php
/**
 * Created by PhpStorm.
 * author: xuyi
 * date: 2019/9/15 15:40
 */
class Order extends Driver_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->authorization();
    }

    /**
     * 获取订单详情信息
     * @author xuyi
     * @date 2019/9/23
     */
    public function get_order_by_sn()
    {
        $usersId = $this->getLoginUsersId();
        $orderSn = self::get('order_sn');

        $fields   = 'order_id, order_sn, users_id, product_type, pay_type, pay_status';
        $orderRow = self::classes('users_order_class', 'users')
            ->getUsersOrderInfoRowByOrderSn($orderSn, $fields, $dbMaster = FALSE);

        $r = successMsg('ok', $orderRow);
        if(empty($orderRow) || intval($orderRow['users_id']) != $usersId) {
            $r = successMsg('ok', array());
        }

        $this->displayJson($r);
    }

    /**
     * 支付订单逻辑
     * @author xuyi
     * @date 2019/10/2
     */
    public function create_order()
    {
        $usersId     = $this->getLoginUsersId();
        $productType = self::post('product_type', 'comic', 'trim');
        $productId   = self::post('product_id', 1,'intval');
        $productNum  = self::post('product_num', 1,'intval');
        $payType     = self::post('pay_type', 'v_coin', 'trim');
        $payFormat   = self::post('pay_format', 'pc', 'trim');

        $usersOrder = self::classes('users_order_class', 'users');
        $r = $usersOrder->createOrder($usersId, $productType, $productId, $productNum);

        if(validate($r))
        {
            $orderSn = $r['result']['order_sn'];
            $r = $usersOrder->createPayment($orderSn, $payType, $payFormat);
        }

        $this->displayJson($r);
    }

    /**
     * 获取用户订单列表
     * @author xuyi
     * @date 2019/10/8
     */
    public function get_order_page()
    {
        $usersId = $this->getLoginUsersId();

        $type    = self::get('product_type', 1, 'intval');
        $rowsNum = self::get('rows_num', 20, 'intval');
        $pageNum = self::get('page_num', 1, 'intval');

        $usersOrder = self::classes('users_order_class', 'users');

        $resultPage = $usersOrder->getUsersOrderInfoListByPage($usersId, $type, $rowsNum, $pageNum);
        $this->displayJson($resultPage);
    }
}
