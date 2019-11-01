<?php
/**
 * Created by PhpStorm.
 * author: xuyi
 * date: 2019/10/2 12:25
 */
class comic_order_class
{
    public $setOrder = array();
    public $load = NULL;

    const CHAPTER_FREE    = 1;
    const CHAPTER_PAYMENT = 2;

    const PRODUCT_TYPE    = 1; # 商品类型

    const COMIC_PAYMENT         = 1;
    const COMIC_CHAPTER_PAYMENT = 2;
    const COMIC_FREE            = 3;

    public function __construct()
    {
        $this->load = app();
    }

    /**
     * 用户是否已经购买了阅读权限
     * @param $usersId
     * @param $comicId
     * @author xuyi
     * @date 2019/10/3
     * @return array
     */
    public function isComicReadPaymentAllow($usersId, $comicId, $chapterId = 0)
    {
        $usersId = intval($usersId);
        $comicId = intval($comicId);
        $chapterId = intval($chapterId);

        if(empty($usersId)) {
            return errorMsg(NO_LOGIN_CODE, NO_LOGIN_MSG);
        }

        $resultList = array(
            'users_pay_chapter' => $chapterIdArr = array(),
        );

        if(empty($comicId)) {
            return successMsg('isComicReadAllow', $resultList);
        }

        # 查询出用户已经购买的章节
        if(empty($chapterId))
        {
            $this->load->model('users_order_info_model', 'users');
            $productType = self::PRODUCT_TYPE; # 商品类型

            $fields = 'product_id';
            $orderList = $this->load->users_order_info_model->cache(CACHE_OUT_TIME_THIRTY_SECO)->getUsersOrderInfoListByObjectId($comicId, $usersId, $productType, $fields);
            $chapterIdArr = array_column($orderList, 'product_id');
            $chapterIdArr = array_filter($chapterIdArr);
        }

        # chapterId 不等于 0 则只查询章节可读状态
        if(! empty($chapterId))
        {
           $r = $this->load->classes('users_order_class', 'users')->isProductPaymentStatus($usersId, $chapterId, $productType = self::PRODUCT_TYPE);
           if(validate($r)) {
               $chapterIdArr = array($chapterId);
           }
        }


        $resultList['users_pay_chapter'] = $chapterIdArr;

        return successMsg('isComicReadAllow', $resultList);
    }

    /**
     * 订单参数设置
     * @param array $paramArr
     * @author xuyi
     * @date 2019/10/2
     * @return $this
     */
    public function setOrder($paramArr = array())
    {
        $this->setOrder = is_array($paramArr) ? $paramArr : array();
        return $this;
    }

    /**
     * verifyChapter
     * @param $chapterId
     * @author xuyi
     * @date 2019/10/11
     * @return array
     */
    public function verifyChapter($chapterId)
    {
        $chapterId  = intval($chapterId);

        $fields     = 'chapter_id, chapter_name, comic_id, is_payment, chapter_status, chapter_price, create_time, update_time';
        $chapterRow = $this->load->server('comic_info_class', 'comic', SITE_COMIC)->getComicChapterRowByChapterId($chapterId, $fields)->result('result');

        if(empty($chapterRow) || $chapterRow['chapter_status'] != NORMAL) {
            return errorMsg(404, '章节信息不存在~');
        }

        if(intval($chapterRow['is_payment']) == self::CHAPTER_FREE) {
            return errorMsg(500, '章节为免费章节无需购买~');
        }

        $comicId = empty($chapterRow['comic_id']) ? 0 : intval($chapterRow['comic_id']);

        if(empty($comicId)) {
            return errorMsg(500, '章节信息异常为查询到章节所属的漫画书籍信息~');
        }

        return successMsg('chapter verify success~', $chapterRow);
    }

    /**
     * verifyComic
     * @param $comicId
     * @author xuyi
     * @date 2019/10/11
     * @return array
     */
    public function verifyComic($comicId)
    {
        $comicId = intval($comicId);

        # RPC 调用获取漫画章节信息
        $fields = 'comic_id, comic_name, comic_status, free_platform, is_payment, create_time';
        $comicRow = $this->load->server('comic_info_class', 'comic', SITE_COMIC)->getComicInfoRowByComicId($comicId, $fields)->result('result');

        if(empty($comicRow) || $comicRow['comic_status'] != NORMAL) {
            return errorMsg(404, '漫画信息不存在或已经下架~');
        }

        if(intval($comicRow['is_payment']) == self::COMIC_FREE) {
            return errorMsg(500, '当前漫画为免费漫画~');
        }

        if(intval($comicRow['is_payment']) != self::COMIC_CHAPTER_PAYMENT) {
            return errorMsg(500, '当前漫画不支持章节购买, 请整本购买~');
        }

        return successMsg('comic verify success~', $comicRow);
    }


    /**
     * 订单数据验证
     * @author xuyi
     * @date 2019/10/2
     * @return array
     */
    public function verifyOrder()
    {
        $usersId        = empty($this->setOrder['users_id']) ? 0 : intval($this->setOrder['users_id']);
        $productType    = empty($this->setOrder['product_type']) ? 0 : intval($this->setOrder['product_type']);
        $productId      = empty($this->setOrder['product_id']) ? 0 : intval($this->setOrder['product_id']);
        $productNum     = empty($this->setOrder['product_num']) ? 0 : intval($this->setOrder['product_num']);


        # 1 验证章节信息
        $chapter = $this->verifyChapter($productId);
        if(! validate($chapter)) {
            return $chapter;
        }

        $chapterRow = empty($chapter['result']) ? array() : $chapter['result'];
        $comicId    = empty($chapterRow['comic_id']) ? 0 : intval($chapterRow['comic_id']);
        $chapterId  = empty($chapterRow['chapter_id']) ? 0 : intval($chapterRow['chapter_id']);


        # 2 权限验证如果有当前漫画阅读权限禁止购买
        $readAllow = $this->isComicReadPaymentAllow($usersId, $comicId); # 获取当前用户阅读权限

        $usersPayChapter = !isset($readAllow['result']['users_pay_chapter']) ? array() : $readAllow['result']['users_pay_chapter'];
        if(in_array($chapterId, $usersPayChapter)) {
            return errorMsg(500, '你已经购买过该章节, 不要重复购买~');
        }

        # 3 验证漫画信息
        $comic = $this->verifyComic($comicId);
        if(! validate($comic)) {
            return $comic;
        }

        $fields = 'chapter_default_price';
        $comicExtendRow = $this->load->server('comic_info_class', 'comic', SITE_COMIC)->getComicExtendInfoRowByComicId($comicId, $fields)->result('result');

        $chapterPrice = intval($chapterRow['chapter_price']);
        $chapterDefaultPrice = empty($comicExtendRow['chapter_default_price']) ? 0.00 : intval($comicExtendRow['chapter_default_price']);
        if(empty($chapterDefaultPrice) && empty($chapterPrice)) {
            return errorMsg(500, '漫画价格信息异常~');
        }

        $chapterUnitPrice = empty($chapterPrice) ? $chapterDefaultPrice : $chapterPrice;
        $totalPrice = ($chapterUnitPrice * $productNum);

        # 验证后的数据结果集
        $resultList = array(
            'object_id'   => $comicId,
            'users_id'    => $usersId,
            'product_id'  => $chapterId,
            'price'       => $totalPrice,
            'product_type'=> $productType
        );

        return successMsg('verify ok~', $resultList);
    }

    /**
     * callback 回调方法在保存完成订单后执行完成其他附加操作
     * @param  $paramsArr
     * @author xuyi
     * @date 2019/10/2
     * @return array
     */
    public function callback($paramsArr)
    {

        return $paramsArr;

        $usersId = empty($this->setOrder['users_id']) ? 0 : intval($this->setOrder['users_id']);


    }

    /**
     * 完成订单
     * @author xuyi
     * @date 2019/10/8
     */
    public function completeOrder($orderId)
    {
        $orderId = trim($orderId);
        if(empty($orderId)) {
            return errorMsg(404, 'comic order 订单信息错误, 请重试');
        }

        $usersOrder = $this->load->classes('users_order_class', 'users');
        $edit = $usersOrder->editUsersOrderInfoByPayStatus($orderId, $status = 2);

        if(! validate($edit)) {
            return errorMsg(500,'修改订单状态失败~');
        }

        return successMsg('completeOrder OK~');
    }
}
