<?php
/**
 * Created by PhpStorm.
 * author: 零上一度
 * date: 2019/6/5 11:27
 */
class Comic_num_info_model extends Core_Model
{
    public function __construct()
    {
        parent::__construct();

        $this->table = $this->getTable();
    }


    /**
     * @param $comicId
     * @param $updateFields
     * @param $comicNum
     * @author 零上一度
     * @date 2019/6/13
     * @return array
     */
    public function editComicNumInfo($comicId, $updateFields, $comicNum)
    {
        $comicId  = intval($comicId);
        $comicNum = intval($comicNum);
        $updateFields = trim($updateFields);

        if(empty($comicId) || empty($updateFields) || empty($comicNum)) {
            return errorMsg('comic id or edit num is empty!');
        }


        $comicNumRow = $this->getComicNumInfoRowByComicId($comicId, $fields = 'num_id');
        $numId = empty($comicNumRow['num_id']) ? 0 : $comicNumRow['num_id'];

        if(empty($numId))
        {
            $saveData = array(
                'fav_num'       => 0,
                'comment_num'   => 0,
                'pv_num'        => 0,
                'create_time'   => time()
            );

            $this->db_master('comic')->insert($this->table, $saveData);
            $numId = $this->db_master('comic')->insert_id();

        }

        $this->db_master('comic')->where('num_id', $numId)
            ->set($updateFields, "{$updateFields} + {$comicNum}", FALSE)->update($this->table);

        return successMsg('ok');
    }

    /**
     * @param $comicId
     * @param $fields
     * @author 零上一度
     * @date 2019/6/13
     * @return array
     */
    public function getComicNumInfoRowByComicId($comicId, $fields)
    {
        $comicId = intval($comicId);
        $fields = trim($fields);

        if(empty($comicId) || empty($fields)) {
            return array();
        }

        $whereArr = array('comic_id' => $comicId);

        return $this->db_slave('comic')->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }
}
