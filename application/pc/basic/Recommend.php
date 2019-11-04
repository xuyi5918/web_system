<?php
/**
 * Created by PhpStorm.
 * author: xuyi
 * date: 2019/9/30 16:53
 */
class Recommend extends Driver_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * get_recommend_page PC 推荐页
     * @author xuyi
     * @date 2019/9/30
     */
    public function get_recommend_page()
    {
        $recommendPage  = self::get('recommend_page', 'default', 'trim');

        $mca       = $this->urlPath()->uriArray;
        $directory = empty($mca['directory']) ? '' : $mca['directory'];
        $method    = empty($mca['method']) ? '' : $mca['method'];

        # 模板路径
        $tpl = $directory . DIRECTORY_SEPARATOR . $method . DIRECTORY_SEPARATOR . $recommendPage;
        $recommend = self::classes('recommend_info_class', 'basic');

        $this->displayTpl(function() use ($recommendPage, $recommend)
        {
           $recommendList = $recommend->getRecommendInfoListByMca($recommendPage, 'pc');
           $recommendList['result']['recommend_page'] = $recommendPage;

           return $recommendList;

        }, CACHE_OUT_TIME_THIRTY_SECO, $tpl);
    }
}
