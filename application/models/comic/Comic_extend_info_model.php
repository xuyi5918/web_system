<?php
/**
 * Created by PhpStorm.
 * author: 零上一度
 * date: 2019/6/13 22:45
 */
class comic_extend_info_model extends Driver_Model
{
    public function __construct()
    {
        parent::__construct();

        $this->table = $this->getTable();

    }

    /**
     * @param $comicIdArr
     * @param $fields
     * @author 零上一度
     * @date 2019/6/13
     * @return array
     */
    public function getComicExtendInfoListByComicIdArr($comicIdArr, $fields)
    {
        $comicIdArr = array_map('intval', $comicIdArr);
        $fields = trim($fields);

        if(empty($comicIdArr) || empty($fields)) {
            return array();
        }

        return $this->db_slave('comic')->select($fields)->from($this->table)
            ->where_in('comic_id', $comicIdArr)->get()->result_array('comic_id');
    }

    /**
     * @param $comicId
     * @author 零上一度
     * @date 2019/6/13
     */
    public function getComicExtendInfoRowByComicId($comicId, $fields)
    {
        $comicId = intval($comicId);
        if(empty($comicId) || empty($fields)) {
            return array();
        }

        return $this->db_slave('comic')->select($fields)->from($this->table)
            ->where('comic_id', $comicId)->get()->row_array();
    }
}
