<?php

/**
 * Class Product_info_model shell auto create
 * @author xuyi
 * @date 2019-10-12 11:18:05
 */
class Product_info_model extends Core_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = $this->getTable();
    }


    /**
    * getProductInfoListByProductIdArr
    * @param $productIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-10-12 11:18:05
    * @return array
    */
    public function getProductInfoListByProductIdArr($productIdArr, $fields, $dbMaster = FALSE)
    {
        $productIdArr = array_map('intval',$productIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($productIdArr) || empty($fields)) {
            return array();
        }

        $default = 'basic';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'product_id' => $productIdArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($productIdArr))->get()->result_array('product_id');
    }

    /**
    * getProductInfoRowByProductId
    * @param $productId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-10-12 11:18:05
    * @return array
    */
    public function getProductInfoRowByProductId($productId, $fields, $dbMaster = FALSE)
    {
        $productId = intval($productId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($productId) || empty($fields)) {
            return array();
        }

        $default = 'basic';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'product_id' => $productId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }



    /**
     * saveProductInfo
     * @param $productId
     * @param $saveData
     * @author shell auto create
     * @date 2019-10-12 11:18:05
     * @return array
     */
    public function saveProductInfo($productId, $saveData)
    {
        $productId  = intval($productId);
        $saveData = !is_array($saveData) ? array() : $saveData;

        if(empty($saveData)) {
            return array();
        }

        # 参数整理
        $productId = empty($saveData['product_id']) ? 0 : $saveData['product_id'];
        $productName = empty($saveData['product_name']) ? '' : $saveData['product_name'];
        $productType = empty($saveData['product_type']) ? 0 : $saveData['product_type'];
        $productStatus = empty($saveData['product_status']) ? 0 : $saveData['product_status'];
        $virtualValue = empty($saveData['virtual_value']) ? 0 : $saveData['virtual_value'];
        $sort = empty($saveData['sort']) ? 0 : $saveData['sort'];
        $price = empty($saveData['price']) ? 0 : $saveData['price'];
        $createTime = empty($saveData['create_time']) ? 0 : $saveData['create_time'];
        $updateTime = empty($saveData['update_time']) ? 0 : $saveData['update_time'];

        $default = 'basic';
        $resultRow = $this->getProductInfoRowByProductId($productId, $fields = 'product_id');
        if(empty($resultRow))
        {
            $insert = array(
                'product_name' => $productName,
                'product_type' => $productType,
                'product_status' => $productStatus,
                'virtual_value' => $virtualValue,
                'sort' => $sort,
                'price' => $price,
                'create_time' => $createTime,
                'update_time' => $updateTime,
            );

            $this->db_master($default)->insert($insert, $this->table);
            $id = $this->db_master($default)->insert_id();
            if(empty($id)) {
                return errorMsg(500, 'insert db error~');
            }

        }else
        {
            $update = array();

            if(! empty($productName)) {
                $update['product_name'] = $productName;
            }

            if(! empty($productType)) {
                $update['product_type'] = $productType;
            }

            if(! empty($productStatus)) {
                $update['product_status'] = $productStatus;
            }

            if(! empty($virtualValue)) {
                $update['virtual_value'] = $virtualValue;
            }

            if(! empty($sort)) {
                $update['sort'] = $sort;
            }

            if(! empty($price)) {
                $update['price'] = $price;
            }

            if(! empty($createTime)) {
                $update['create_time'] = $createTime;
            }

            if(! empty($updateTime)) {
                $update['update_time'] = $updateTime;
            }

            $this->db_master($default)->where(array('product_id' => $productId))->update($update, $this->table);
            $affectedRows = $this->db_master($default)->affected_rows();
            if(empty($affectedRows)) {
                return errorMsg(500, 'update error~');
            }
        }


        return successMsg('ok~');
    }


    /**
     * editProductInfoByProductStatus
     * @param $productId
     * @param $status
     * @author shell auto create
     * @date 2019-10-12 11:18:05
     * @return array
     */
    public function editProductInfoByProductStatus($productId, $status)
    {
        $productId  = intval($productId);
        $status = in_array($status, array(1, 2, 3)) ? 0 : $status;

        if(empty($productId) || empty($status)) {
            return errorMsg(404, 'edit databases params is empty');
        }

        $whereArr = array(
            'product_id' => $productId
        );

        $update = array('product_status'=>$status);

        $default = 'basic';
        $this->db_master($default)->where($whereArr)->update($this->table, $update);
        $num = $this->db_master($default)->affected_rows();

        if(empty($num)) {
            return errorMsg(500, 'edit error~');
        }

        return successMsg('ok~');
    }

    /**
     * getProductInfoListByPage
     * @param $productType
     * @param $rowsNum
     * @param $pageNum
     * @param $fields
     * @param bool $dbMaster
     * @author xuyi
     * @date 2019/10/12
     * @return array
     */
    public function getProductInfoListByPage($productType, $rowsNum, $pageNum, $fields, $dbMaster = FALSE)
    {
        $productType = intval($productType);
        $rowsNum = intval($rowsNum);
        $pageNum = intval($pageNum);
        $fields  = trim($fields);

        $offset  = ($pageNum - 1) * $rowsNum;

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $resultPage = array(
            'data'       => array(),
            'rows_total' => 0,
            'page_total' => 0,
            'rows_num'   => $rowsNum,
            'page_num'   => $pageNum,
        );

        if(empty($usersId) || empty($fields)) {
            return $resultPage;
        }

        $whereArr = array(
            'product_status'  => NORMAL,
            'product_type'    => $productType
        );

        $whereArr = array_filter($whereArr);

        $resultTotal = $db->select('*')->from($this->table)->where($whereArr)->count_all_results();

        if(empty($resultTotal)) {
            return $resultPage;
        }

        $resultList = $db->select($fields)->from($this->table)->where($whereArr)->limit($rowsNum, $offset)
            ->order_by('sort DESC')->get()->result_array();

        return array(
            'data'       => $resultList,
            'rows_total' => $resultTotal,
            'page_total' => ceil($resultTotal / $rowsNum),
            'rows_num'   => $rowsNum,
            'page_num'   => $pageNum,
        );
    }
}
