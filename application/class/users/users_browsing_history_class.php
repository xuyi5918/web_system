<?php
/**
 * Created by PhpStorm.
 * author: xuyi
 * date: 2019/8/16 22:19
 */
class users_browsing_history_class
{
    public $load = NULL;
    public function __construct()
    {
        $this->load = app();
    }

    /**
     * addUsersHistoryLog
     * @param $usersId
     * @param $objectId
     * @param $browsingType
     * @param $progress
     * @author xuyi
     * @date 2019/8/17
     * @return array
     */
    public function addUsersHistoryLog($usersId, $objectId, $browsingType, $progress)
    {
        $usersId      = intval($usersId);
        $objectId     = intval($objectId);
        $browsingType = intval($browsingType);
        $progress     = intval($progress);

        if(empty($usersId)) {
            return errorMsg(NO_LOGIN_CODE, NO_LOGIN_MSG);
        }

        if(empty($objectId) || empty($browsingType)) {
            return errorMsg(500, '用户日志参数错误~');
        }

        # save read log
        $queue  = $this->load->classes('queue_notice_class', 'tools');

        $addRow = array(
            'users_id'          => $usersId,
            'object_id'         => $objectId,
            'object_brows_progress' => $progress,
            'browsing_type'     => $browsingType,
            'create_time'       => time(),
            'update_time'       => time()
        );

        $signal = 'users_browsing_history';
        $result = $queue->setSignal($signal)->addQueue($addRow)->setQueue();

        return successMsg('ok', array('queue'=>$result));
    }

    /**
     * getUsersBrowsingHistoryListByPage
     * @param $usersId
     * @param $rowsNum
     * @param $pageNum
     * @author xuyi
     * @date 2019/8/16
     */
    public function getUsersBrowsingHistoryListByPage($usersId, $rowsNum, $pageNum)
    {
        $usersId = intval($usersId);
        $rowsNum = intval($rowsNum);
        $pageNum = intval($pageNum);

        if(empty($usersId) || empty($rowsNum) || empty($pageNum)) {
            return successMsg('ok');
        }

        $this->load->model('users_browsing_history_log', 'users');
        $readList = $this->load->users_browsing_history_log_model->cache(CACHE_OUT_TIME_THIRTY_SECO)
            ->getUsersBrowsingHistoryLogListByPage($usersId, $rowsNum, $pageNum, $fields = '*');


        # 獲取附加數據列表
        $readList = $this->getBrowsingHistoryExtendInfo($readList);

        return successMsg('ok', $readList);
    }

    /**
     * extend info
     * @param array $readList
     * @author xuyi
     * @date 2019/8/17
     * @return array
     */
    public function getBrowsingHistoryExtendInfo($readList = array())
    {

        $resultList = empty($readList['data']) ? array() : $readList['data'];

        # 非空查询相关数据
        if(empty($resultList)) {
            return $readList;
        }

        $resultMapId = array(
            'comic'=>array(),
            'video'=>array(),
            'book'=>array(),
            'article'=>array()

        );



        $confBrowsingType = config('config', 'browsing_type');

        $readList['browsing_type_conf'] = $confBrowsingType;

        foreach ($resultList as $item=>$resultRow)
        {
            $browsingType = empty($resultRow['browsing_type']) ? 0 : intval($resultRow['browsing_type']);

            if(empty($browsingType)) {
                continue;
            }



            $browsing = empty($confBrowsingType[$browsingType]) ? '' : trim($confBrowsingType[$browsingType]);
            if(empty($browsing)) {
                continue;
            }


            if(!isset($resultMapId[$browsing])){
                continue;
            }


            $resultMapId[$browsing]['parent_id_arr'][] = empty($resultRow['parent_id']) ? 0 : intval($resultRow['parent_id']);
            $resultMapId[$browsing]['object_id_arr'][] = empty($resultRow['object_id']) ? 0 : intval($resultRow['object_id']);
        }



        # 查詢對象數據列表
        foreach ($resultMapId as $item=>$mapRow)
        {
            $parentIdArr = empty($mapRow['parent_id_arr']) ? array() : $mapRow['parent_id_arr'];
            $objectIdArr = empty($mapRow['object_id_arr']) ? array() : $mapRow['object_id_arr'];

            $browsingHistoryList = $this->getBrowsingHistoryObjectListByBrowsingType($item, $objectIdArr, $parentIdArr);
            $readList[$item]['parent_list'] = empty($browsingHistoryList['parent_list']) ? array() : $browsingHistoryList['parent_list'];
            $readList[$item]['object_list'] = empty($browsingHistoryList['object_list']) ? array() : $browsingHistoryList['object_list'];
        }

        return $readList;
    }

    /**
     * 獲取用戶瀏覽歷史對象列表
     * @param $browsingType
     * @param $objectIdArr
     * @param $parentIdArr
     * @author xuyi
     * @date 2019/8/17
     */
    public function getBrowsingHistoryObjectListByBrowsingType($browsingType, $objectIdArr, $parentIdArr)
    {


        switch ($browsingType)
        {
            case 'music':

                $resultArr = array('parent_list'=>array(), 'object_list'=>array());
                break;
            case 'book':

                $resultArr = array('parent_list'=>array(), 'object_list'=>array());
                break;
            case 'comic':
                $comicIdArr = $parentIdArr;
                $this->load->model('comic_info', 'comic');
                $fields = 'comic_id, comic_name, comic_desc, comic_cover';
                rsort($comicIdArr);
                $comicList = $this->load->comic_info_model->cache(CACHE_OUT_TIME_ONE_MINU)->getComicInfoListByComicIdArr($comicIdArr, $fields);

                $chapterIdArr = $objectIdArr;
                $this->load->model('comic_chapter_info', 'comic');
                $fields = '*';
                rsort($chapterIdArr);
                $chapterList = $this->load->comic_chapter_info_model->cache(CACHE_OUT_TIME_THIRTY_MINU)->getComicChapterListByChapterIdArr($chapterIdArr, $fields);

                $resultArr = array('parent_list'=>$comicList, 'object_list'=>$chapterList);
                break;
            case 'anime':

                $resultArr = array('parent_list'=>array(), 'object_list'=>array());
                break;
            case 'article':

                $resultArr = array('parent_list'=>array(), 'object_list'=>array());
                break;
            default:
                $resultArr = array('parent_list'=>array(), 'object_list'=>array());
        }

        return successMsg('ok', $resultArr);

    }

}
