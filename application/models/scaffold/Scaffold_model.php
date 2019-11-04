<?php
/**
 * Created by PhpStorm.
 * author: xuyi
 * date: 2019/7/18 13:23
 */
class Scaffold_model extends Driver_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取数据表对象信息
     * @param $object
     * @param $table
     * @author xuyi
     * @date 2019/7/18
     */
    public function getObjectInfoListByObject($object, $table)
    {

        $whereArr = array(
            'table_name' => $table,
        );

        $table = 'information_schema.COLUMNS';
        return $this->db_master($object)->from($table)->select('*')->where($whereArr)
            ->get()->result_array();

    }
}
