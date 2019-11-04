<?php
/**
 * Created by PhpStorm.
 * author: 零上一度
 * date: 2019/6/13 14:37
 */
class Chapter_images_info_model extends Driver_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $chapterId
     * @param $fields
     * @author 零上一度
     * @date 2019/6/13
     * @return array
     */
    public function getChapterImagesInfoListByChapterId($chapterId, $fields)
    {
        $chapterId = intval($chapterId);
        $fields = trim($fields);

        if(empty($chapterId) || empty($fields)) {
            return array();
        }

        $tableName = $this->getTable($chapterId);
        return $this->db_slave('comic')->select($fields)->from($tableName)
            ->where('chapter_id', $chapterId)->get()->result_array();
    }
}
