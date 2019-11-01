<?php
/**
 * 推介位置類方法
 * author: 零上一度
 * date: 2019/7/11 9:27
 */
class recommend_info_class
{
    public $load;
    public function __construct()
    {
        $this->load = app();
    }


    /**
     * 根据mca获取推荐内容
     *
     * @param $mca
     * @param $platfrom
     * @author xuyi
     * @date 2019/7/14
     * @return array
     */
    public function getRecommendInfoListByMca($mca, $platform)
    {
        $mca          = trim($mca);
        $platform     = trim($platform);

        $typeConf     = config('config','platform_type');
        $platform     = ! isset($typeConf[$platform]) ? 0 : $typeConf[$platform];

        $platform     = intval($platform);


        $resultArr = array();
        if(empty($mca) || empty($platform)) {
            return successMsg('ok1', $resultArr);
        }

        # 查询推荐页面
        $this->load->model( 'recommend_page_info_model', 'basic');
        $fields = 'page_id, platform, mca, page_title, page_desc';
        $recommendPage = $this->load->recommend_page_info_model->cache(CACHE_OUT_TIME_ONE_HOUR)->getRecommendPageInfoRowByMca($mca, $fields);

        if(empty($recommendPage)) {
            return errorMsg(404);
        }


        # 查询推荐当前页面推荐位置
        $pageId = empty($recommendPage['page_id']) ? 0 : intval($recommendPage['page_id']);

        $this->load->model('recommend_location_info_model', 'basic');
        $fields = 'location_id, location_name, create_time';
        $locationList = $this->load->recommend_location_info_model->cache(CACHE_OUT_TIME_THIRTY_MINU)->getRecommendLocationInfoListByPageId($pageId, $fields, $dbMaster = FALSE);

        $locationIdArr = array_column($locationList, 'location_id');
        $locationIdArr = array_filter($locationIdArr);

        if(empty($locationIdArr)) {
            return successMsg('ok3', $resultArr);
        }

        # 查询推荐的具体内容
        $this->load->model('recommend_content_info_model', 'basic');
        $fields = 'content_id, parent_id, group_id, location_id, content, link, title, type, atter, create_time';
        $contentList = $this->load->recommend_content_info_model->cache(CACHE_OUT_TIME_THIRTY_MINU)
            ->getRecommendContentInfoListByLocationIdArr($locationIdArr, $fields);

        foreach ($contentList as $item=>$contentRow) {
            $contentList[$item]['atter'] = empty($contentRow['atter']) ? array() : json_decode($contentRow['atter'], TRUE);
        }

        # 查询推荐内容分组
        $groupIdArr = array_column($contentList, 'group_id');
        $this->load->model('recommend_group_info_model', 'basic');
        $groupList = $this->load->recommend_group_info_model->getRecommendGroupInfoListByGroupIdArr($groupIdArr, $fields = '*');

        $resultArr['location_list'] = $locationList;
        $resultArr['content_list']  = $contentList;
        $resultArr['group_list']    = $groupList;
        $resultArr['recommend_page_row'] = $recommendPage;

        $resultList = $this->getRecommendFormatList($resultArr);

        return successMsg('ok', $resultList);

    }

    /**
     * @param $resultArr
     * @author xuyi
     * @date 2019/9/30
     * @return mixed
     */
    public function getRecommendFormatList($resultArr)
    {

        $locationList = empty($resultArr['location_list']) ? array() : $resultArr['location_list'];
        $contentList  = empty($resultArr['content_list']) ? array() : $resultArr['content_list'];


        $locationContentList = array();

        foreach($locationList as $item=>$locationRow)
        {
            $locationId = empty($locationList[$item]['location_id']) ? 0 : intval($locationList[$item]['location_id']);
            if(empty($locationId)) {
                continue;
            }

            $locationContentList[$locationId] = array();
        }


        foreach ($contentList as $item => $contentRow)
        {
            $locationId = empty($contentRow['location_id']) ? 0 : intval($contentRow['location_id']);
            if(empty($locationId)) {
                continue;
            }

            $locationContentList[$locationId][] = $contentRow;
        }


        $resultArr['content_list'] = $locationContentList;

        return $resultArr;
    }

}
