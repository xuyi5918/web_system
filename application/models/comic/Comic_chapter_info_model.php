<?php
/**
 * Created by PhpStorm.
 * author: 零上一度
 * date: 2019/6/13 14:16
 */
class Comic_chapter_info_model extends Driver_Model
{
    public function __construct()
    {
        parent::__construct();

        $this->table = $this->getTable();
    }

    /**
     * @param $chapterId
     * @param $fields
     * @author 零上一度
     * @date 2019/6/13
     * @return array
     */
    public function getComicChapterRowByChapterId($chapterId, $fields)
    {
        $chapterId = intval($chapterId);
        $fields = trim($fields);
        if(empty($chapterId) || empty($fields)) {
            return array();
        }

        return $this->db_slave('comic')->select($fields)->from($this->table)
            ->where('chapter_id', $chapterId)->get()->row_array();
    }

    /**
     * @param $comicId
     * @param $fields
     * @author 零上一度
     * @date 2019/6/13
     * @return array
     */
    public function getComicChapterListByComicId($comicId, $fields)
    {
        $comicId = intval($comicId);
        $fields = trim($fields);

        if(empty($comicId) || empty($fields)) {
            return array();
        }

        return $this->db_slave('comic')->select($fields)->from($this->table)
            ->where('comic_id', $comicId)->where('chapter_status', 1)->get()->result_array();
    }

    /**
     * @param $chapterIdArr
     * @param $fields
     * @author xuyi
     * @date 2019/8/16
     * @return array
     */
    public function getComicChapterListByChapterIdArr($chapterIdArr, $fields)
    {
        $chapterIdArr = array_map('intval', $chapterIdArr);
        $fields = trim($fields);

        if(empty($chapterIdArr) || empty($fields)) {
            return array();
        }

        return $this->db_slave('comic')->select($fields)->from($this->table)
            ->where_in('chapter_id', $chapterIdArr)->where('chapter_status', 1)->get()->result_array('chapter_id');
    }
}
