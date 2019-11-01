<?php
/**
 * Created by PhpStorm.
 * author: xuyi
 * date: 2019/10/12 17:01
 */
class Article extends Core_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 展示文章详情页
     * @author xuyi
     * @date 2019/10/12
     */
    public function article_show()
    {
        $articleId = self::get('article_id', 0, 'intval');
    }
}
