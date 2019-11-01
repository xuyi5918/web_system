<?php
/**
 * 漫画基础业务逻辑代码
 * author: 零上一度
 * date: 2019/6/5 10:30
 */
class comic_info_class
{
    public $load = NULL;

    const COMIC_READ_NUM_COMIC_ID_KEY = 'comic:comic_id:%s:comic_num_fields:%s';

    public function __construct()
    {
        $this->load = app();
    }

    /**
     * getComicInfoListByComicIdArr
     * @param $comicIdArr
     * @param $fields
     * @author xuyi
     * @date 2019/9/24
     * @return mixed
     */
    public function getComicInfoListByComicIdArr($comicIdArr, $fields)
    {
        $comicIdArr = array_map('intval', $comicIdArr);
        $fields     = trim($fields);

        $this->load->model('comic_info_model', 'comic');
        $comicList = $this->load->comic_info_model->cache(CACHE_OUT_TIME_THIRTY_MINU)->getComicInfoListByComicIdArr($comicIdArr, $fields);

        return successMsg('ok~', $comicList);
    }

    /**
     * getComicInfoRowByComicId
     * @param $comicId
     * @param $fields
     * @author xuyi
     * @date 2019/10/10
     * @return mixed
     */
    public function getComicInfoRowByComicId($comicId, $fields)
    {
        $comicId = intval($comicId);
        $fields = trim($fields);

        $this->load->model('comic_info_model', 'comic');
        return $this->load->comic_info_model->cache(CACHE_OUT_TIME_THIRTY_MINU)->getComicInfoRowByComicId($comicId, $fields);
    }

    /**
     * @param $comicId
     * @param $platform string 平台
     * @author 零上一度
     * @date 2019/6/13
     * @return array
     */
    public function getComicShow($comicId, $platform = 'pc')
    {
        $comicId = intval($comicId);

        # get comic info table row
        $fields = 'comic_id, comic_name, comic_status, free_platform, create_time';
        $comicRow = $this->getComicInfoRowByComicId($comicId, $fields);

        # 获取注入对象(调用RPC获取远程查询结果)
        $usersId = $this->load->make('users_id');
        if(! empty($usersId))
        {
            $usersFav  = $this->load->make('users_fav_class');
            $usersFav  = $usersFav->isUsersFavComic($usersId, $comicId)->result('result');
            $resultList['users_fav'] = $usersFav;
        }

        # 阅读权限
        $readAllow  = $this->isComicReadAllow($usersId, $platform, $comicId);
        $resultList['read_allow'] = $readAllow;

        # get comic extend info table row
        $fields = 'new_chapter_id, comic_desc, comic_cover, comic_price, chapter_default_price';
        $comicExtendInfoRow = $this->getComicExtendInfoRowByComicId($comicId, $fields);

        # get chapter info table list
        $chapterList = $this->getChapterListByComicId($comicId);

        $comicNumRow = $this->getComicNumInfoRowByComicId($comicId);

        $resultList['comic_row'] = empty($comicRow) ? array() : $comicRow;
        $resultList['comic_num_row'] = empty($comicNumRow) ? array() : $comicNumRow;
        $resultList['comic_extend_row'] = empty($comicExtendInfoRow) ? array() : $comicExtendInfoRow;
        $resultList = array_merge( empty($chapterList['result']) ? array() : $chapterList['result'], $resultList);

        return successMsg('ok', $resultList);
    }

    /**
     * getComicExtendInfoRowByComicId
     * @param $comicId
     * @param $fields
     * @author xuyi
     * @date 2019/10/10
     * @return mixed
     */
    public function getComicExtendInfoRowByComicId($comicId, $fields)
    {
        $this->load->model('comic_extend_info_model', 'comic');
        return $this->load->comic_extend_info_model->cache(CACHE_OUT_TIME_ONE_MINU)->getComicExtendInfoRowByComicId($comicId, $fields);
    }

    /**
     * get comic chapter list
     * @param $comicId
     * @author xuyi
     * @date 2019/8/21
     * @return mixed
     */
    public function getChapterListByComicId($comicId)
    {
        $comicId = intval($comicId);

        $this->load->model('comic_chapter_info_model', 'comic');
        $chapterList = $this->load->comic_chapter_info_model->cache(CACHE_OUT_TIME_ONE_HOUR)->getComicChapterListByComicId($comicId, $fields = '*');
        $chapterIdArr = array_column($chapterList, 'chapter_id');

        $numList = array();
        if(! empty($chapterIdArr))
        {
            $this->load->model('chapter_num_info_model', 'comic');
            $numList = $this->load->chapter_num_info_model->cache(CACHE_OUT_TIME_ONE_HOUR)->getChapterNumInfoListByChapterIdArr($chapterIdArr, $fields, $dbMaster = FALSE);
        }

        $resultList = array(
            'chapter_list'     => $chapterList,
            'chapter_num_list' => $numList
        );

        return successMsg('chapter list', $resultList);
    }

    /**
     * getComicNumInfoRowByComicId
     * @param $comicId
     * @param $fields
     * @author xuyi
     * @date 2019/10/7
     * @return mixed
     */
    public function getComicNumInfoRowByComicId($comicId)
    {
        $comicId = intval($comicId);

        $fields  = 'comic_id, fav_num, comment_num, pv_num';
        $this->load->model('comic_num_info_model', 'comic');
        return $this->load->comic_num_info_model->cache(CACHE_OUT_TIME_ONE_MINU)->getComicNumInfoRowByComicId($comicId, $fields);
    }

    /**
     * redis 延迟写入DB
     * @param $comicId
     * @param $incrFields
     * @param $incrNum
     * @author xuyi
     * @date 2019/7/2
     * @return array
     */
    public function incrComicNumInfo($comicId, $incrFields, $incrNum)
    {
        $incrFields = trim($incrFields);
        $comicId = intval($comicId);
        $incrNum = intval($incrNum);


        if(empty($incrFields) && empty($incrNum) && empty($comicId)) {
            return errorMsg(404, 'param empty');
        }

        # get row info
        $this->load->model('comic_info_model', 'comic');
        $fields = 'comic_id, comic_name, comic_status, free_platform, create_time';
        $comicRow = $this->load->comic_info_model->cache(CACHE_OUT_TIME_ONE_MINU)->getComicInfoRowByComicId($comicId, $fields);
        if(empty($comicRow)) {
            return errorMsg(404, 'param comic_id error');
        }

        $redis = $this->load->cache($adapter = 'redis');

        $comicIncrFieldsKey = $redis->key(self::COMIC_READ_NUM_COMIC_ID_KEY, array($comicId, $incrFields));
        if(!$redis->get($comicIncrFieldsKey)) {
            $redis->save($comicIncrFieldsKey, 0, -1);
        }

        $redis->increment($comicIncrFieldsKey, $incrNum);
        $incrNum = $redis->get($comicIncrFieldsKey);
        $incrNum = intval($incrNum);

        if($incrNum >= DELAY_NUM)
        {
            $this->load->model('comic_num_info_model', 'comic');
            $this->load->comic_num_info_model->editComicNumInfo($comicId, $incrFields, $incrNum);
            $redis->delete($comicIncrFieldsKey);
        }

        return successMsg('ok');
    }

    /**
     * getComicChapterRowByChapterId
     * @param $chapterId
     * @param $fields
     * @param $dbMa
     * @author xuyi
     * @date 2019/10/10
     * @return mixed
     */
    public function getComicChapterRowByChapterId($chapterId, $fields)
    {
        $chapterId = intval($chapterId);
        $fields = trim($fields);

        $this->load->model('comic_chapter_info_model', 'comic');
        return $this->load->comic_chapter_info_model->cache(CACHE_OUT_TIME_ONE_MINU)->getComicChapterRowByChapterId($chapterId, $fields);
    }

    /**
     * getComicChapterListByChapterIdArr
     * @param $chapterIdArr
     * @param $fields
     * @author xuyi
     * @date 2019/10/12
     * @return mixed
     */
    public function getComicChapterListByChapterIdArr($chapterIdArr, $fields)
    {
        $chapterIdArr = array_map('intval', $chapterIdArr);
        $chapterIdArr = array_filter($chapterIdArr);
        $fields       = trim($fields);

        $this->load->model('comic_chapter_info_model', 'comic');
        return $this->load->comic_chapter_info_model->cache(CACHE_OUT_TIME_ONE_MINU)->getComicChapterListByChapterIdArr($chapterIdArr, $fields);
    }

    /**
     * 阅读权限检测
     * @param $usersId
     * @param $curPlatform
     * @param $comicId
     * @param $chapterId
     * @author xuyi
     * @date 2019/10/28
     * @return array
     */
    public function isComicReadAllow($usersId, $curPlatform, $comicId, $chapterId = 0)
    {
        $usersId      = intval($usersId);
        $curPlatform  = trim($curPlatform);
        $comicId      = intval($comicId);
        $chapterId    = intval($chapterId);

        $fields   = 'is_payment, free_platform, comic_id';
        $comicRow = $this->getComicInfoRowByComicId($comicId, $fields);

        $isPayment    = empty($comicRow['is_payment']) ? 3 : intval($comicRow['is_payment']);
        $freePlatform = empty($comicRow['free_platform']) ? 0 : intval($comicRow['free_platform']);

        $isPayment    = isPayment($isPayment, $freePlatform, $curPlatform); # 判断是否需要付费

        # 检测用户是否已经购买
        $paymentAllow = array();
        if(! empty($usersId) && $isPayment === TRUE)
        {
            $isReadChapter = $this->load->server('comic_order_class', 'order', SITE_USERS); # RPC 远端服务
            $paymentAllow  = $isReadChapter->isComicReadPaymentAllow($usersId, $comicId, $chapterId)->result('result');

            $currChapterAllow = in_array($chapterId, $paymentAllow['users_pay_chapter']);
        }

        # 其他情况的阅读权限验证
        $resultList['limit_time_free_chapter'] = array(); //限时免费章节


        $resultList['curr_chapter_read_allow'] = $currChapterAllow;
        $resultList['curr_comic_read_allow']   = FALSE;

        $resultList = array_merge($resultList, $paymentAllow); // 购买的阅读权限

        return $resultList;
    }

    /**
     * 获取章节内容
     * @param $chapterId
     * @author xuyi
     * @date 2019/8/14
     * @return array
     */
    public function getChapterShow($chapterId, $platform = 'pc')
    {
        $chapterId = intval($chapterId);

        $paramArr = array(
            'chapter_row'         => array(),
            'chapter_images_list' => array()
        );

        if(empty($chapterId)) {
            return successMsg('ok', $paramArr);
        }

        # 查询章节信息
        $fields = 'chapter_id, chapter_name, comic_id, is_payment, chapter_price, create_time, update_time';
        $chapterRow = $this->getComicChapterRowByChapterId($chapterId, $fields);

        if(empty($chapterRow)) {
            return successMsg('ok', $paramArr);
        }

        $chapterId = empty($chapterRow['chapter_id']) ? 0 : intval($chapterRow['chapter_id']);
        $comicId   = empty($chapterRow['comic_id']) ? 0 : intval($chapterRow['comic_id']);

        $usersId   = $this->load->make('users_id');

        $readAllow = $this->isComicReadAllow($usersId, $platform, $comicId, $chapterId);

        $chapterImagesList = array();
        if(isset($readAllow['curr_chapter_read_allow']) && $readAllow['curr_chapter_read_allow'] === TRUE)
        {
            # 获取章节图片列表
            $this->load->model('chapter_images_info_model', 'comic');
            $fields = 'images_id, images_width, images_height, images_sort, images_url';
            $chapterImagesList = $this->load->chapter_images_info_model->cache(CACHE_OUT_TIME_ONE_MINU)->getChapterImagesInfoListByChapterId($chapterId, $fields);
        }

        $paramArr = array(
            'chapter_row'         => $chapterRow,
            'chapter_images_list' => $chapterImagesList,
            'read_allow'          => $readAllow
        );

        return successMsg('ok', $paramArr);
    }

    public function getComicInfoListByPage()
    {

    }
}
