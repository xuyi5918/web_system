<?php

/**
 * Class Users_article_model shell auto create
 * @author xuyi
 * @date 2019-09-06 10:08:57
 */
class Users_article_model extends Driver_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = $this->getTable();
    }


    /**
    * getUsersArticleListByArticleIdArr
    * @param $articleIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-06 10:08:57
    * @return array
    */
    public function getUsersArticleListByArticleIdArr($articleIdArr, $fields, $dbMaster = FALSE)
    {
        $articleIdArr = array_map('intval',$articleIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($articleIdArr) || empty($fields)) {
            return array();
        }

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'article_id' => $articleIdArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($articleIdArr))->get()->result_array('article_id');
    }

    /**
    * getUsersArticleRowByArticleId
    * @param $articleId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-06 10:08:57
    * @return array
    */
    public function getUsersArticleRowByArticleId($articleId, $fields, $dbMaster = FALSE)
    {
        $articleId = intval($articleId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($articleId) || empty($fields)) {
            return array();
        }

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'article_id' => $articleId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }

    /**
    * getUsersArticleListByUsersIdArr
    * @param $usersIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-06 10:08:57
    * @return array
    */
    public function getUsersArticleListByUsersIdArr($usersIdArr, $fields, $dbMaster = FALSE)
    {
        $usersIdArr = array_map('intval',$usersIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($usersIdArr) || empty($fields)) {
            return array();
        }

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'users_id' => $usersIdArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($usersIdArr))->get()->result_array('users_id');
    }

    /**
    * getUsersArticleRowByUsersId
    * @param $usersId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-06 10:08:57
    * @return array
    */
    public function getUsersArticleRowByUsersId($usersId, $fields, $dbMaster = FALSE)
    {
        $usersId = intval($usersId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($usersId) || empty($fields)) {
            return array();
        }

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'users_id' => $usersId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }

    /**
    * getUsersArticleListByCategoryIdArr
    * @param $categoryIdArr
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-06 10:08:57
    * @return array
    */
    public function getUsersArticleListByCategoryIdArr($categoryIdArr, $fields, $dbMaster = FALSE)
    {
        $categoryIdArr = array_map('intval',$categoryIdArr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($categoryIdArr) || empty($fields)) {
            return array();
        }

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'category_id' => $categoryIdArr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count($categoryIdArr))->get()->result_array('category_id');
    }

    /**
    * getUsersArticleRowByCategoryId
    * @param $categoryId
    * @param $fields
    * @param bool $dbMaster
    * @author shell auto create
    * @date 2019-09-06 10:08:57
    * @return array
    */
    public function getUsersArticleRowByCategoryId($categoryId, $fields, $dbMaster = FALSE)
    {
        $categoryId = intval($categoryId);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty($categoryId) || empty($fields)) {
            return array();
        }

        $default = 'users';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            'category_id' => $categoryId
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }



    /**
     * saveUsersArticle
     * @param $articleId
     * @param $saveData
     * @author shell auto create
     * @date 2019-09-06 10:08:57
     * @return array
     */
    public function saveUsersArticle($articleId, $saveData)
    {
        $articleId  = intval($articleId);
        $saveData = is_array($saveData) ? array() : $saveData;

        if(empty($saveData)) {
            return array();
        }

        # 参数整理
        $articleTitle = empty($saveData['article_title']) ? '' : $saveData['article_title'];
        $content = empty($saveData['content']) ? '' : $saveData['content'];
        $articleStatus = empty($saveData['article_status']) ? 0 : $saveData['article_status'];
        $usersId = empty($saveData['users_id']) ? 0 : $saveData['users_id'];
        $categoryId = empty($saveData['category_id']) ? 0 : $saveData['category_id'];
        $createTime = empty($saveData['create_time']) ? 0 : $saveData['create_time'];
        $updateTime = empty($saveData['update_time']) ? 0 : $saveData['update_time'];

        $default = 'users';
        $usersRow = $this->getUsersArticleRowByArticleId($articleId, $fields = 'article_id');
        if(empty($usersRow))
        {
            $insert = array(
                'article_title' => $articleTitle,
                'content' => $content,
                'article_status' => $articleStatus,
                'users_id' => $usersId,
                'category_id' => $categoryId,
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

            if(! empty($articleTitle)) {
                $update['article_title'] = $articleTitle;
            }

            if(! empty($content)) {
                $update['content'] = $content;
            }

            if(! empty($articleStatus)) {
                $update['article_status'] = $articleStatus;
            }

            if(! empty($usersId)) {
                $update['users_id'] = $usersId;
            }

            if(! empty($categoryId)) {
                $update['category_id'] = $categoryId;
            }

            if(! empty($createTime)) {
                $update['create_time'] = $createTime;
            }

            if(! empty($updateTime)) {
                $update['update_time'] = $updateTime;
            }

            $this->db_master($default)->where(array('article_id' => $articleId))->update($update, $this->table);
            $affectedRows = $this->db_master($default)->affected_rows();
            if(empty($affectedRows)) {
                return errorMsg(500, 'update error~');
            }
        }


        return successMsg('ok~');
    }


    /**
     * editUsersArticleByArticleStatus
     * @param $articleId
     * @param $status
     * @author shell auto create
     * @date 2019-09-06 10:08:57
     * @return array
     */
    public function editUsersArticleByArticleStatus($articleId, $status)
    {
        $articleId  = intval($articleId);
        $status = in_array($status, array(1, 2, 3)) ? 0 : $status;

        if(empty($articleId) || empty($status)) {
            return errorMsg(404, 'edit databases params is empty');
        }

        $whereArr = array(
            'article_id' => $articleId
        );

        $update = array('article_status'=>$status);

        $default = 'users';
        $this->db_master($default)->where($whereArr)->update($this->table, $update);
        $num = $this->db_master($default)->affected_rows();

        if(empty($num)) {
            return errorMsg(500, 'edit error~');
        }

        return successMsg('ok~');
    }


}
