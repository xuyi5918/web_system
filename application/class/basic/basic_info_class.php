<?php
/**
 * 网站基础配置信息类
 * author: xuyi
 * date: 2019/10/13 10:23
 */
class basic_info_class
{
    public $load = NULL;
    public function __construct()
    {
        $this->load = app();
    }

    /**
     * getTagInfoListByType
     * @param $tagType
     * @param $fields
     * @author xuyi
     * @date 2019/10/13
     * @return mixed
     */
    public function getTagInfoListByType($tagType, $fields)
    {
        $tagType = intval($tagType);
        $fields = trim($fields);

        $this->load->model('tag_info_model', 'basic');
        return $this->load->tag_info_model->cache(CACHE_OUT_TIME_ONE_MINU)->getTagInfoListByType($tagType, $fields, $dbMaster = FALSE);
    }

    /**
     * getAreaInfoListByType
     * @param $areaType
     * @param $fields
     * @author xuyi
     * @date 2019/10/13
     * @return mixed
     */
    public function getAreaInfoListByType($areaType, $fields)
    {
        $areaType = intval($areaType);
        $fields = trim($fields);

        $this->load->model('area_info_model', 'basic');
        return $this->load->area_info_model->cache(CACHE_OUT_TIME_ONE_MINU)->getAreaInfoListByType($areaType, $fields, $dbMaster = FALSE);
    }
}
