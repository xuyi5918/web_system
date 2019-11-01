<?php
/**
 * 商品基础信息
 * author: xuyi
 * date: 2019/10/12 19:19
 */
class product_info_class
{
    public $load = NULL;
    public function __construct()
    {
        $this->load = app();

        $this->load->model('product_info_model', 'basic');
    }

    /**
     * getProductInfoListByProductIdArr
     * @param $productIdArr
     * @param $fields
     * @param bool $dbMaster
     * @author xuyi
     * @date 2019/10/12
     */
    public function getProductInfoListByProductIdArr($productIdArr, $fields)
    {
        $productIdArr = is_array($productIdArr) ? $productIdArr : array();
        $productIdArr = array_map('intval', $productIdArr);
        $fields       = trim($fields);

        return $this->load->product_info_model->cache(CACHE_OUT_TIME_ONE_MINU)
            ->getProductInfoListByProductIdArr($productIdArr, $fields, $dbMaster = FALSE);
    }

    /**
     * getProductInfoShow
     * @param $productId
     * @author xuyi
     * @date 2019/10/13
     * @return array
     */
    public function getProductInfoShow($productId)
    {
        $productId = intval($productId);

        $fields = 'product_id, product_name, product_type, product_status, virtual_value, price, create_time';
        $productRow = $this->load->product_info_model->cache(CACHE_OUT_TIME_ONE_MINU)->getProductInfoRowByProductId($productId, $fields, $dbMaster = FALSE);

        if(empty($productRow) || $productRow['product_status'] != NORMAL) {
            $productRow = array();
        }

        return $productRow;
    }

    /**
     * getProductInfoListByPage
     * @param $productType
     * @param $rowsNum
     * @param $pageNum
     * @author xuyi
     * @date 2019/10/12
     * @return mixed
     */
    public function getProductInfoListByPage($productType, $rowsNum, $pageNum)
    {
        $pageNum = intval($pageNum);
        $rowsNum = intval($rowsNum);
        $productType = intval($productType);

        $fields = 'product_id, product_name, product_type, virtual_value, price, create_time';
        return $this->load->product_info_model->cache(CACHE_OUT_TIME_ONE_MINU)
            ->getProductInfoListByPage($productType, $rowsNum, $pageNum, $fields, $dbMaster = FALSE);
    }
}
