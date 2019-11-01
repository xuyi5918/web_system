<?php
/**
 * Created by PhpStorm.
 * Author: root
 * Date: 2019/6/1
 * Time: 18:10
 */

class Index extends Core_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * PC 网站 首页
     * @author 零上一度
     * @date 2019/6/2
     */
    public function index()
    {
        $recommend = self::classes('recommend_info_class', 'basic');

        $this->displayTpl(function() use($recommend)
        {
            $homePage = $recommend->getRecommendInfoListByMca($recommendPage = 'default', 'pc');
            $homePage['result']['recommend_page'] = $recommendPage;
            return $homePage;

        }, CACHE_OUT_TIME_ONE_HOUR);
    }
}
