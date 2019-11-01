<?php
/**
 * 漫画相关索引查询类
 * author: xuyi
 * date: 2019/10/12 21:11
 */

class comic_category_class
{
    public $load = NULL;

    const TAG_TYPE  = 1;
    const AREA_TYPE = 1;

    public function __construct()
    {
        $this->load = app();
    }

    /**
     * 获取漫画分类信息检索条件
     * @author xuyi
     * @date 2019/10/13
     * @return array
     */
    public function getCategoryIndexShow()
    {
        # 漫画更新状态
        $isEnd = array(
            1 => '连载',
            2 => '完结'
        );

        # 收付费状态信息
        $payStatus = array(
            1 => '整本付费',
            2 => '章节付费',
            3 => '免费',
        );

        # RPC 地区分类信息
        $fields = 'area_id, area_name, create_time';
        $areaList = $this->load->server('basic_info_class', 'basic', SITE_BASIC)->getAreaInfoListByType(self::AREA_TYPE, $fields)->result('result');

        # RPC tag 信息
        $fields  = 'tag_id, tag_name, create_time';
        $tagList = $this->load->server('basic_info_class', 'basic', SITE_BASIC)->getTagInfoListByType(self::TAG_TYPE, $fields)->result('result');

        $resultList = array(
            'tag_list'      => $tagList,
            'area_list'     => $areaList,
            'pay_status'    => $payStatus,
            'is_end_status' => $isEnd
        );

        return successMsg('category index', $resultList);
    }
}
