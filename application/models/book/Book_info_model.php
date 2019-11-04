<?php

/**
 * Class Book_info_model shell auto create
 * @author xuyi
 * @date 2019-09-24 11:47:04
 */
class Book_info_model extends Driver_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = $this->getTable();
    }


    /**
    * getBookInfoListByBookIdArr
    * @param $bookIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-24 11:47:04
    * @return array
    */
    public function getBookInfoListByBookIdArr($bookIdArr, $fields, $dbMaster = FALSE)
    {
        $bookIdArr = array_map('intval',$bookIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($bookIdArr) || empty($fields)) {
            return array();
        }

        $default = 'book';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'book_id' => $bookIdArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($bookIdArr))->get()->result_array('book_id');
    }

    /**
    * getBookInfoRowByBookId
    * @param $bookId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-24 11:47:04
    * @return array
    */
    public function getBookInfoRowByBookId($bookId, $fields, $dbMaster = FALSE)
    {
        $bookId = intval($bookId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($bookId) || empty($fields)) {
            return array();
        }

        $default = 'book';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'book_id' => $bookId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }

    /**
    * getBookInfoListByCategoryIdArr
    * @param $categoryIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-24 11:47:04
    * @return array
    */
    public function getBookInfoListByCategoryIdArr($categoryIdArr, $fields, $dbMaster = FALSE)
    {
        $categoryIdArr = array_map('intval',$categoryIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($categoryIdArr) || empty($fields)) {
            return array();
        }

        $default = 'book';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'category_id' => $categoryIdArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($categoryIdArr))->get()->result_array('category_id');
    }

    /**
    * getBookInfoRowByCategoryId
    * @param $categoryId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-24 11:47:04
    * @return array
    */
    public function getBookInfoRowByCategoryId($categoryId, $fields, $dbMaster = FALSE)
    {
        $categoryId = intval($categoryId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($categoryId) || empty($fields)) {
            return array();
        }

        $default = 'book';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'category_id' => $categoryId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }

    /**
    * getBookInfoListByAuthorIdArr
    * @param $authorIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-24 11:47:04
    * @return array
    */
    public function getBookInfoListByAuthorIdArr($authorIdArr, $fields, $dbMaster = FALSE)
    {
        $authorIdArr = array_map('intval',$authorIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($authorIdArr) || empty($fields)) {
            return array();
        }

        $default = 'book';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'author_id' => $authorIdArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($authorIdArr))->get()->result_array('author_id');
    }

    /**
    * getBookInfoRowByAuthorId
    * @param $authorId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-24 11:47:04
    * @return array
    */
    public function getBookInfoRowByAuthorId($authorId, $fields, $dbMaster = FALSE)
    {
        $authorId = intval($authorId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($authorId) || empty($fields)) {
            return array();
        }

        $default = 'book';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'author_id' => $authorId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }

    /**
    * getBookInfoListByAreaIdArr
    * @param $areaIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-24 11:47:04
    * @return array
    */
    public function getBookInfoListByAreaIdArr($areaIdArr, $fields, $dbMaster = FALSE)
    {
        $areaIdArr = array_map('intval',$areaIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($areaIdArr) || empty($fields)) {
            return array();
        }

        $default = 'book';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'area_id' => $areaIdArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($areaIdArr))->get()->result_array('area_id');
    }

    /**
    * getBookInfoRowByAreaId
    * @param $areaId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-24 11:47:04
    * @return array
    */
    public function getBookInfoRowByAreaId($areaId, $fields, $dbMaster = FALSE)
    {
        $areaId = intval($areaId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($areaId) || empty($fields)) {
            return array();
        }

        $default = 'book';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'area_id' => $areaId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }

    /**
    * getBookInfoListByAgeWorksArr
    * @param $ageWorksArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-24 11:47:04
    * @return array
    */
    public function getBookInfoListByAgeWorksArr($ageWorksArr, $fields, $dbMaster = FALSE)
    {
        $ageWorksArr = array_map('intval',$ageWorksArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($ageWorksArr) || empty($fields)) {
            return array();
        }

        $default = 'book';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'age_works' => $ageWorksArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($ageWorksArr))->get()->result_array('age_works');
    }

    /**
    * getBookInfoRowByAgeWorks
    * @param $ageWorks
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-24 11:47:04
    * @return array
    */
    public function getBookInfoRowByAgeWorks($ageWorks, $fields, $dbMaster = FALSE)
    {
        $ageWorks = intval($ageWorks);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($ageWorks) || empty($fields)) {
            return array();
        }

        $default = 'book';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'age_works' => $ageWorks
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }



    /**
     * saveBookInfo
     * @param $bookId
     * @param $saveData
     * @author shell auto create
     * @date 2019-09-24 11:47:04
     * @return array
     */
    public function saveBookInfo($bookId, $saveData)
    {
        $bookId  = intval($bookId);
        $saveData = !is_array($saveData) ? array() : $saveData;

        if(empty($saveData)) {
            return array();
        }

        # 参数整理
        $bookName = empty($saveData['book_name']) ? '' : $saveData['book_name'];
        $bookDesc = empty($saveData['book_desc']) ? '' : $saveData['book_desc'];
        $bookCover = empty($saveData['book_cover']) ? '' : $saveData['book_cover'];
        $bookStatus = empty($saveData['book_status']) ? 0 : $saveData['book_status'];
        $categoryId = empty($saveData['category_id']) ? 0 : $saveData['category_id'];
        $authorId = empty($saveData['author_id']) ? 0 : $saveData['author_id'];
        $areaId = empty($saveData['area_id']) ? 0 : $saveData['area_id'];
        $ageWorks = empty($saveData['age_works']) ? 0 : $saveData['age_works'];
        $updateTime = empty($saveData['update_time']) ? 0 : $saveData['update_time'];
        $createTime = empty($saveData['create_time']) ? 0 : $saveData['create_time'];

        $default = 'book';
        $resultRow = $this->getBookInfoRowByBookId($bookId, $fields = 'book_id');
        if(empty($resultRow))
        {
            $insert = array(
                'book_name' => $bookName,
                'book_desc' => $bookDesc,
                'book_cover' => $bookCover,
                'book_status' => $bookStatus,
                'category_id' => $categoryId,
                'author_id' => $authorId,
                'area_id' => $areaId,
                'age_works' => $ageWorks,
                'update_time' => $updateTime,
                'create_time' => $createTime,
            );

            $this->db_master($default)->insert($this->table, $insert);
            $id = $this->db_master($default)->insert_id();
            if(empty($id)) {
                return errorMsg(500, 'insert db error~');
            }

        }else
        {
            $update = array();

            if(! empty($bookName)) {
                $update['book_name'] = $bookName;
            }

            if(! empty($bookDesc)) {
                $update['book_desc'] = $bookDesc;
            }

            if(! empty($bookCover)) {
                $update['book_cover'] = $bookCover;
            }

            if(! empty($bookStatus)) {
                $update['book_status'] = $bookStatus;
            }

            if(! empty($categoryId)) {
                $update['category_id'] = $categoryId;
            }

            if(! empty($authorId)) {
                $update['author_id'] = $authorId;
            }

            if(! empty($areaId)) {
                $update['area_id'] = $areaId;
            }

            if(! empty($ageWorks)) {
                $update['age_works'] = $ageWorks;
            }

            if(! empty($updateTime)) {
                $update['update_time'] = $updateTime;
            }

            $this->db_master($default)->where(array('book_id' => $bookId))->update($this->table, $update);
            $affectedRows = $this->db_master($default)->affected_rows();
            if(empty($affectedRows)) {
                return errorMsg(500, 'update error~');
            }
        }


        return successMsg('ok~');
    }


    /**
     * editBookInfoByBookStatus
     * @param $bookId
     * @param $status
     * @author shell auto create
     * @date 2019-09-24 11:47:04
     * @return array
     */
    public function editBookInfoByBookStatus($bookId, $status)
    {
        $bookId  = intval($bookId);
        $status = in_array($status, array(1, 2, 3)) ? 0 : $status;

        if(empty($bookId) || empty($status)) {
            return errorMsg(404, 'edit databases params is empty');
        }

        $whereArr = array(
            'book_id' => $bookId
        );

        $update = array('book_status'=>$status);

        $default = 'book';
        $this->db_master($default)->where($whereArr)->update($this->table, $update);
        $num = $this->db_master($default)->affected_rows();

        if(empty($num)) {
            return errorMsg(500, 'edit error~');
        }

        return successMsg('ok~');
    }


}
