<?php
/**
 * Created by PhpStorm.
 * author: 零上一度
 * date: 2019/6/5 10:33
 */
class Comic_info_model extends Core_Model
{
    public $table;
    public function __construct()
    {
        parent::__construct();

        $this->table = $this->getTable();
    }


    /**
     * @param $comicId
     * @param $fields
     * @param bool $dbMaster
     * @author 零上一度
     * @date 2019/6/5
     * @return array
     */
    public function getComicInfoRowByComicId($comicId, $fields, $dbMaster = FALSE)
    {
        $comicId = intval($comicId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;


        if(empty($comicId) || empty($fields)) {
            return array();
        }

        $default = 'comic';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'comic_id' => $comicId
        );


        $comicRow = $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();

        return $comicRow;
    }

    /**
     * @param $comicIdArr
     * @param $fields
     * @author 零上一度
     * @date 2019/6/5
     * @return array
     */
    public function getComicInfoListByComicIdArr($comicIdArr, $fields)
    {

        $comicIdArr = array_map('intval', $comicIdArr);
        $fields = trim($fields);

        if(empty($comicIdArr) || empty($fields)) {
            return array();
        }


        $comicList = $this->db_slave('comic')->select($fields)->from($this->table)->where_in('comic_id', $comicIdArr)
            ->where('comic_status', 1)->limit(count($comicIdArr))->get()->result_array('comic_id');

        return $comicList;
    }

    /**
     * 查询漫画列表信息
     * @param $condition
     * @param $rowsNum
     * @param $pageNum
     * @param $fields
     * @author xuyi
     * @date 2019/8/20
     * @return array
     */
    public function getComicInfoListByPage(array $condition, $rowsNum, $pageNum, $fields)
    {
        $rowsNum = intval($rowsNum);
        $pageNum = intval($pageNum);
        $fields = trim($fields);
        $condition = empty($condition) ? array() : $condition;

        # 查询条件
        $paymentTypeArr = empty($condition['payment_type_arr']) ? array() : $condition['payment_type'];
        $paymentTypeArr = array_map('intval', $paymentTypeArr);

        $paymentPlatfromArr = empty($condition['payment_platfrom_arr']) ? array() : $condition['payment_platfrom_arr'];
        $paymentPlatfromArr = array_map('intval', $paymentPlatfromArr);

        $areaId = empty($condition['area_id']) ? 0 : intval($condition['area_id']);

        $tagId = empty($condition['tag_id']) ? 0 : intval($condition['tag_id']);

        $this->db_slave('comic')->select($fields)->from($this->table)->where('comic_status', 1)
            ->join('comic_tag_map', 'comic_tag_map.comic_id = comic_info.comic_id', 'left');

        #付费状态
        $whereInArr = array();
        if(! empty($paymentTypeArr)) {
            $whereInArr['payment_type'] = $paymentTypeArr;
        }

        #付费平台
        if(! empty($paymentPlatfromArr)) {
            $whereInArr['payment_platfrom'] = $paymentPlatfromArr;
        }

        #地区
        $whereArr = array();
        if(! empty($areaId)) {
            $whereArr['area_id'] = $areaId;
        }

        # 链表查询tag_map
        if(! empty($tagId))
        {
            $whereArr['comic_tag_map.tag_id'] = $tagId;
            $whereArr['comic_tag_map.status'] =1;
        }


        $resultTotal = $this->db_slave('comic')->where($whereArr)->where_in($whereInArr)->count_all_results();;
        if(empty($resultTotal)) {
            return array();
        }

        $resultList = $this->db_slave('comic')->where($whereArr)->where_in($whereInArr)->get()->result_array();
        if(empty($resultList)) {
            return $resultList;
        }

        return array();
    }
}
