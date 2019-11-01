<?php
/**
 * Created by PhpStorm.
 * author: xuyi
 * date: 2019/10/2 12:06
 */
class users_order_class
{
    public $load = NULL;
    const SIGNAL = 'c31740457389899b40769217fe73114a';

    private $productTypeMap = array(
        1   =>  'comic',
        2   =>  'anime',
        3   =>  'novel',
        4   =>  'music',
        5   =>  'v_property'
    );
    # 订单数据类型
    private $orderProductMap = array(
        'comic'      => 'comic_order_class',
        'v_property' => 'product_order_class'
    );

    private $paymentMap = array(
        'v_coin'    => 'vcoin_payment_class',
        'ali'       => 'ali_payment_class',
        'wechat'    => 'wechat_payment_class'
    );

    public function __construct()
    {
        $this->load = app();
    }

    /**
     * signal
     * @param $orderSn
     * @param $price
     * @param $payType
     * @author xuyi
     * @date 2019/10/11
     * @return string
     */
    public function signal($orderSn, $price, $productId)
    {
        $paramArr = array(
            'order_sn' => $orderSn,
            'price'    => $price,
            'product_id' => $productId,
            'signal'     => self::SIGNAL
        );

        ksort($paramArr);
        return md5(http_build_query($paramArr));
    }

    /**
     * eqSignal
     * @param $signal
     * @param $paramArr
     * @author xuyi
     * @date 2019/10/11
     * @return bool
     */
    public function eqSignal($signal, $paramArr)
    {
        $orderSn    = empty($paramArr['order_sn']) ? '' : trim($paramArr['order_sn']);
        $price      = empty($paramArr['price']) ? '' : trim($paramArr['price']);
        $productId  = empty($paramArr['product_id']) ? '' : trim($paramArr['product_id']);

        return $this->signal($orderSn, $price, $productId) == $signal;
    }

    /**
     * getUsersOrderInfoRowByOrderSn
     * @param $orderSn
     * @param $fields
     * @param bool $dbMaster
     * @author xuyi
     * @date 2019/10/2
     * @return mixed
     */
    public function getUsersOrderInfoRowByOrderSn($orderSn, $fields, $dbMaster = FALSE)
    {
        $this->load->model('users_order_info_model', 'users');
        return $this->load->users_order_info_model->getUsersOrderInfoRowByOrderSn($orderSn, $fields, $dbMaster);
    }

    /**
     * 创建订单
     * @param $usersId
     * @param $productType
     * @param $productId
     * @param $productNum
     * @author xuyi
     * @date 2019/10/2
     */
    public function createOrder($usersId, $productType, $productId, $productNum)
    {
        $usersId     = intval($usersId);
        $productType = trim($productType);
        $productId   = intval($productId);
        $productNum  = intval($productNum);

        if(empty($usersId)) {
            return errorMsg(NO_LOGIN_CODE, NO_LOGIN_MSG);
        }

        $orderProductMap = empty($this->orderProductMap[$productType]) ? '' : $this->orderProductMap[$productType];

        if(empty($orderProductMap)) {
            return errorMsg(500, '商品类型错误, 没有这种商品~');
        }


        $setOrder = array(
            'users_id'      => $usersId,
            'product_type'  => $productType = 1,
            'product_id'    => $productId,
            'product_num'   => $productNum
        );

        # 验证订单新是否异常
        $createOrder = $this->load->classes($orderProductMap, 'order');
        $result      = $createOrder->setOrder($setOrder)->verifyOrder();

        if(! validate($result)) {
            return $result;
        }

        $saveRow = empty($result['result']) ? array() : $result['result'];

        $result = $this->saveOrderInfo($saveRow);
        if(! validate($result)) {
            return $result;
        }

       return $createOrder->callback($result); // 后续操作
    }

    /**
     * 完成订单交易
     * @param $productType
     * @param $orderSN
     * @param $paramsArr
     * @author xuyi
     * @date 2019/10/8
     * @return array
     */
    public function completeOrder($productType, $orderSN, $paramsArr)
    {
        $orderSN = trim($orderSN);
        $orderProductMap = empty($this->orderProductMap[$productType]) ? '' : $this->orderProductMap[$productType];

        # 参数整理
        $traceOrderSn = empty($paramsArr['trace_order_sn']) ? '' : trim($paramsArr['trace_order_sn']);
        $payType      = empty($paramsArr['pay_type']) ? 0 : intval($paramsArr['pay_type']);
        $signal       = empty($paramsArr['signal']) ? '' : trim($paramsArr['signal']);

        if(empty($orderProductMap)) {
            return errorMsg(500, '商品类型错误, 没有这种商品~');
        }

        $fields = 'order_id, pay_status, product_id, price';
        $orderRow = $this->getUsersOrderInfoRowByOrderSn($orderSN, $fields, $dbMaster = TRUE);
        if(empty($orderRow)) {
            return errorMsg(500, '订单不存在~');
        }

        if($orderRow['pay_status'] == 2) {
            return errorMsg(500, '订单已支付~');
        }

        # 订单加密信息计算验证
        $productId = empty($orderRow['product_id']) ? 0 : intval($orderRow['product_id']);
        $price     = empty($orderRow['price']) ? 0 : intval($orderRow['price']);
        $signalArr = array(
            'order_sn' => $orderSN,
            'price'    => $price,
            'product_id' => $productId,
        );

        $eqSignal = $this->eqSignal($signal, $signalArr);

        if($eqSignal) {
            return errorMsg(500, '订单签名验证失败~');
        }

        $orderId  = empty($orderRow['order_id']) ? 0 : intval($orderRow['order_id']);

        # 商品支付后回调逻辑
        $completeOrder = $this->load->classes($orderProductMap, 'order');
        $result = $completeOrder->completeOrder($orderId);

        if(! validate($result)) {
            return $result;
        }

        # 记录交易流水日志
        $saveData = array(
            'order_id'       => $orderId,
            'trace_order_sn' => $traceOrderSn,
            'pay_type'       => $payType,
            'create_time'    => time(),
        );

        $this->load->model('users_order_trace_log_model', 'users');
        $result = $this->load->users_order_trace_log_model->saveUsersOrderTraceLog($orderId, $saveData);
        if(! validate($result)) {
            return $result;
        }

        return successMsg('complete order success');
    }

    /**
     * createPayment
     * @param $orderSn
     * @param $payType
     * @param string $payFomat | APP, H5, PC, Scan
     * @author xuyi
     * @date 2019/10/8
     * @return array
     */
    public function createPayment($orderSn, $payType, $payFormat = 'Scan')
    {
        $orderSn = trim($orderSn);
        $payType = trim($payType);
        $payFormat = trim($payFormat);
        $payFormat = strtoupper($payFormat);

        $paymentMap = empty($this->paymentMap[$payType]) ? '' : $this->paymentMap[$payType];

        if(empty($paymentMap)) {
            return errorMsg(500, '支付类型错误, 没有这种支付方式~');
        }


        # 获取最新订单信息
        $fields   = 'order_id, order_sn, users_id, product_type, product_id, price, pay_status, create_time';
        $orderRow = $this->getUsersOrderInfoRowByOrderSn($orderSn, $fields, $dbMaster = TRUE);
        if(empty($orderRow)) {
            return errorMsg(500, '支付信息异常, 请重新生成订单~');
        }

        $payStatus = empty($orderRow['pay_status']) ? 1 : intval($orderRow['pay_status']);
        if($payStatus == 2) {
            return errorMsg(500, '订单已经支付, 无重复支付~');
        }

        $price = empty($orderRow['price']) ? 0 : intval($orderRow['price']);
        $usersId = empty($orderRow['users_id']) ? 0 : intval($orderRow['users_id']);
        $productId = empty($orderRow['product_id']) ? 0 : intval($orderRow['product_id']);
        $productType = empty($orderRow['product_type']) ? 0 : intval($orderRow['product_type']);


        $signal = $this->signal($orderSn, $price, $productId);

        $paramArr = array(
            'order_sn' => $orderSn,
            'price'    => $price,
            'title'    => '', # 订单名称
            'users_id' => $usersId,
            'signal'   => $signal, # 订单加密计算标识
            'product_type'=> !isset($this->productTypeMap[$productType]) ? '' : $this->productTypeMap[$productType],
        );

        $payment = $this->load->classes($paymentMap, 'payment');
        return $payment->payment($payFormat, $paramArr); # 返回支付信息
    }

    /**
     * isProductPaymentStatus
     * @param $usersId
     * @param $comicId
     * @author xuyi
     * @date 2019/10/2
     * @return array
     */
    public function isProductPaymentStatus($usersId, $productId, $productType = 'comic_chapter')
    {
        $usersId = intval($usersId);
        $productId = intval($productId);

        if(empty($usersId)) {
            return errorMsg(NO_LOGIN_CODE, NO_LOGIN_MSG);
        }

        if(empty($productId)) {
            return errorMsg(404,'comic id 不能为空~');
        }

        $this->load->model('users_order_info_model', 'users');
        $fields      = '*';
        $orderRow    = $this->load->users_order_info_model->getUsersOrderInfoRowByProductId($productId, $usersId, $productType,$fields, $dbMaster = FALSE);
        if(empty($orderRow)) {
            return errorMsg(500, '商品未购买~');
        }

        return successMsg('ok', $orderRow);
    }

    /**
     * editUsersOrderInfoByPayStatus
     * @param $orderId
     * @param $status
     * @author xuyi
     * @date 2019/10/10
     * @return mixed
     */
    public function editUsersOrderInfoByPayStatus($orderId, $status)
    {
        $orderId = intval($orderId);
        $status  = intval($status);

        $this->load->model('users_order_info_model', 'users');
        return $this->load->users_order_info_model->editUsersOrderInfoByPayStatus($orderId, $status);
    }

    /**
     * 保存订单信息
     * @param $orderNo
     * @param $usersId
     * @param $productId
     * @param $productType
     * @author xuyi
     * @date 2019/10/2
     * @return array
     */
    private function saveOrderInfo($saveRow = array())
    {

        $usersId     = empty($saveRow['users_id']) ? 0 : intval($saveRow['users_id']);
        $productId   = empty($saveRow['product_id']) ? 0 : intval($saveRow['product_id']);
        $productType = empty($saveRow['product_type']) ? 0 : intval($saveRow['product_type']);
        $objectId    = empty($saveRow['object_id']) ? 0 : intval($saveRow['object_id']);
        $price       = empty($saveRow['price']) ? 0 : intval($saveRow['price']);

        $this->load->model('users_order_info_model', 'users');

        $fields   = 'users_id, order_id, order_sn, pay_status';
        $orderRow = $this->load->users_order_info_model->getUsersOrderInfoRowByUsersId($usersId, $payStatus = 1, $fields, $dbMaster = FALSE);
        $orderId  = 0;
        if(! empty($orderRow)) {
            $orderId = empty($orderRow['order_id']) ? 0 : intval($orderRow['order_id']);
        }

        $orderNo = createOrderNo();
        if(empty($orderNo)) {
            return errorMsg(500, '服务器存在异常, 创建订单号失败~');
        }

        $saveData = array(
            'order_sn'      => $orderNo,
            'object_id'     => $objectId,
            'users_id'      => $usersId,
            'price'         => $price,
            'product_id'    => $productId,
            'product_type'  => $productType,
            'pay_type'      => 0,
            'pay_status'    => 1,
            'create_time'   => time(),
            'update_time'   => time()
        );

        $r = $this->load->users_order_info_model->saveUsersOrderInfo($orderId, $saveData);
        if(! validate($r)) {
            return $r;
        }

        return successMsg('创建订单成功~', $saveData);
    }

    /**
     * getUsersOrderInfoListByPage
     * @param $usersId
     * @param $type
     * @param $rowsNum
     * @param $pageNum
     * @author xuyi
     * @date 2019/10/12
     */
    public function getUsersOrderInfoListByPage($usersId, $type, $rowsNum, $pageNum)
    {
        $usersId = intval($usersId);
        $type    = intval($type);
        $rowsNum = intval($rowsNum);
        $pageNum = intval($pageNum);


        $this->load->model('users_order_info_model', 'users');
        $fields = 'order_id, order_sn, object_id, product_id, price, product_type, pay_type, product_total, create_time';
        $resultPage = $this->load->users_order_info_model->cache(CACHE_OUT_TIME_ONE_MINU)
            ->getUsersOrderInfoListByPage($usersId, $type, $rowsNum, $pageNum, $fields, $dbMaster = FALSE);

        $data = empty($resultPage['data']) ? array() : $resultPage['data'];

        $productIdArr = array_column($data, 'product_id');
        $objectIdArr  = array_column($data, 'object_id');

        # 查询订单购买的对象信息
        $productType = empty($this->productTypeMap[$type]) ? 'comic' : $this->productTypeMap[$type];
        if($productType == 'comic') # 购买漫画章节
        {
            # RPC 查询
            $fields = '*';
            $comicList = $this->load->server('comic_info_class', 'comic', SITE_COMIC)
                ->getComicInfoListByComicIdArr($objectIdArr, $fields)->result('result');

            $fields = '*';
            $chapterList = $this->load->server('comic_info_class', 'comic', SITE_COMIC)
                ->getComicChapterListByChapterIdArr($productIdArr, $fields)->result('result');

            $resultPage['product_list'] = $chapterList;
            $resultPage['object_list']  = $comicList;
        }


        if($productType == 'novel')
        {

        }

        if($productType == 'music')
        {

        }

        if($productType == 'v_property')
        {
            # RPC 查询
            $fields = '*';
            $productList = $this->load->server('product_info_class', 'basic', SITE_BASIC)
                ->getProductInfoListByProductIdArr($productIdArr, $fields)->result('result');

            $resultPage['product_list'] = $productList;
            $resultPage['object_list']  = array();
        }

        return successMsg('order list ok ~', $resultPage);
    }
}
