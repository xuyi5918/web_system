<?php
/**
 * Created by PhpStorm.
 * author: xuyi
 * date: 2019/10/12 19:26
 */

class Product extends Core_Controller
{
    public function __construct()
    {
        parent::__construct();

        # 权限鉴定
        $this->authorization();

    }

    /**
     * 获取商品列表
     * @author xuyi
     * @date 2019/10/12
     */
    public function get_product_page()
    {
        $productType = self::get('product_type', 0, 'intval');
        $rowsNum = self::get('rows_num', 20, 'intval');
        $pageNum = self::get('page_num', 1, 'intval');

        $product = self::classes('product_info_class', 'basic');

        $this->displayJson(function() use($productType, $rowsNum, $pageNum, $product)
        {
            return $product->getProductInfoListByPage($productType, $rowsNum, $pageNum);

        }, CACHE_OUT_TIME_ONE_MINU);
    }

    /**
     * 获取商品信息
     * @author xuyi
     * @date 2019/10/13
     */
    public function get_product_show()
    {
        $productId = self::get('product_id', 0, 'intval');
        $product = self::classes('product_info_class', 'basic');

        $this->displayJson(function() use($product, $productId)
        {
            return $product->getProductInfoShow($productId);

        }, CACHE_OUT_TIME_ONE_HOUR);
    }
}
