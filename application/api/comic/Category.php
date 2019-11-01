<?php
/**
 * Created by PhpStorm.
 * author: xuyi
 * date: 2019/10/13 10:24
 */
class Category extends Core_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 查询索引条件信息
     * @author xuyi
     * @date 2019/10/13
     */
    public function get_category_index_show()
    {
        $category = self::classes('comic_category_class', 'comic');
        $this->displayJson(function() use($category)
        {
            return $category->getCategoryIndexShow();

        }, CACHE_OUT_TIME_ONE_HOUR);
    }
}
