<?php
/**
 * Created by PhpStorm.
 * author: xuyi
 * date: 2019/9/24 19:54
 */
class book_info_class
{
    public $load = NULL;
    public function __construct()
    {
        $this->load = app();
    }

    /**
     * getBookInfoListByBookIdArr
     * @param $bookIdArr
     * @param $fields
     * @param bool $dbMaster
     * @author xuyi
     * @date 2019/9/24
     * @return array
     */
    public function getBookInfoListByBookIdArr($bookIdArr, $fields, $dbMaster = FALSE)
    {
        $bookIdArr = array_map('intval', $bookIdArr);
        $fields    = trim($fields);

        $this->load->model('book_info_model', 'book');
        $bookList = $this->load->book_info_model->cache(CACHE_OUT_TIME_THIRTY_MINU)->getBookInfoListByBookIdArr($bookIdArr, $fields, $dbMaster);
        return successMsg('ok~', $bookList);
    }

    /**
     * getBookInfoRowByBookId
     * @param $bookId
     * @param $fields
     * @param bool $dbMaster
     * @author xuyi
     * @date 2019/9/25
     * @return array
     */
    public function getBookInfoRowByBookId($bookId, $fields, $dbMaster = FALSE)
    {
        $bookId = intval($bookId);
        $fields = trim($fields);

        $this->load->model('book_info_model', 'book');
        $bookRow = $this->load->book_info_model->cache(CACHE_OUT_TIME_THIRTY_MINU)->getBookInfoRowByBookId($bookId, $fields, $dbMaster);
        return successMsg('ok~', $bookRow);
    }
}
