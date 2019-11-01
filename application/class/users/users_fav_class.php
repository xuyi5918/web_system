<?php
/**
 * Created by PhpStorm.
 * author: xuyi
 * date: 2019/9/23 19:40
 */
class users_fav_class
{
    public $load;

    public function __construct()
    {
        $this->load = app();
    }

    /***********************************Comic Fav Logic *******************************/

    /**
     * getUsersFavComicRowByComicId
     * @param $comicId
     * @param $usersId
     * @param $fields
     * @param bool $dbMaster
     * @author xuyi
     * @date 2019/9/23
     * @return array
     */
    public function getUsersFavComicRowByComicId($comicId, $usersId, $fields, $dbMaster = FALSE)
    {
        $this->load->model('users_fav_comic_model', 'users');

        $favRow = $this->load->users_fav_comic_model->cache(CACHE_OUT_TIME_THIRTY_SECO)->getUsersFavComicRowByComicId($comicId, $usersId, $fields, $dbMaster);
        return successMsg('fav comic row', $favRow);
    }

    /**
     * isUsersFavComic
     * @param $usersId
     * @param $comicId
     * @author xuyi
     * @date 2019/9/24
     * @return array
     */
    public function isUsersFavComic($usersId, $comicId)
    {
        $usersId = intval($usersId);
        $comicId = intval($comicId);

        $resultRow = array(
            'fav_status' => 2
        );

        if(empty($usersId) || empty($comicId)) {
            return successMsg('fav status ~', $resultRow);
        }

        $usersFav = $this->getUsersFavComicRowByComicId($comicId, $usersId, $fields = 'fav_id, fav_status');
        $resultRow['fav_status'] = empty($usersFav['fav_status']) ? 2 : intval($usersFav['fav_status']);
        return successMsg('fav status ~', $resultRow);
    }

    /**
     * 编辑关注状态
     * @param $usersId
     * @param $comicId
     * @param $status
     * @author xuyi
     * @date 2019/9/24
     */
    public function editUsersFavComicByFavStatus($usersId, $comicId, $status)
    {
        $usersId = intval($usersId);
        $comicId = intval($comicId);
        $status  = intval($status);

        if(empty($usersId) || empty($comicId) || empty($status)) {
            return errorMsg(500, 'edit fav status error param is empty~');
        }

        $this->load->model('users_fav_comic_model', 'users');

        $fields   = 'fav_id, status';
        $usersFav = $this->getUsersFavComicRowByComicId($comicId, $usersId, $fields);
        $favId    = empty($usersFav['fav_id']) ? 0 : intval($usersFav['fav_id']);

        $saveData = array(
            'comic_id'      => $comicId,
            'users_id'      => $usersId,
            'fav_status'    => $status,
            'create_time'   => time(),
        );

        if(empty($usersFav) && $status !== NORMAL) {
           return successMsg('success~', $saveData);
        }

        if(! empty($favId)) {
            $r = $this->load->users_fv_comic_model->editUsersFavComicByFavStatus($favId, $status);
        }else{
            $r = $this->load->users_fav_comic_model->saveUsersFavComic($favId, $saveData);
        }

        if(validate($r)) {
            return successMsg('success', $saveData);
        }

        return errorMsg(500, '保存关注信息失败, 请重试~');
    }

    /**
     * getUsersFavComicListByPage
     * @param $usersId
     * @param $rowsNum
     * @param $pageNum
     * @author xuyi
     * @date 2019/9/24
     * @return array
     */
    public function getUsersFavComicListByPage($usersId, $rowsNum, $pageNum)
    {
        $usersId = intval($usersId);
        $rowsNum = intval($rowsNum);
        $pageNum = intval($pageNum);


        $this->load->model('users_fav_comic_model', 'users');
        $fields     = 'fav_id, comic_id, users_id, create_time';

        $resultPage = $this->load->users_fav_comic_model->cache(CACHE_OUT_TIME_ONE_MINU)
            ->getUsersFavComicListByPage($usersId, $fields, $rowsNum, $pageNum, $dbMaster = FALSE);

        $comicIdArr = array_column($resultPage['data'], 'comic_id');
        $comicList  = array();

        if(! empty($comicIdArr))
        {
            $fields    = 'comic_id, comic_name, comic_desc, comic_cover';
            $comicList = $this->load->server('comic_info_class', 'comic')->getComicInfoListByComicIdArr($comicIdArr, $fields)->result('result');
        }


        $resultPage['comic_list'] = $comicList;

        return successMsg('ok', $resultPage);
    }

    /***************************************Book Fav Logic*****************************************/

    /**
     * getUsersFavBookRowByBookId
     * @param $bookId
     * @param $usersId
     * @param $fields
     * @param bool $dbMaster
     * @author xuyi
     * @date 2019/9/24
     * @return array
     */
    public function  getUsersFavBookRowByBookId($bookId, $usersId, $fields, $dbMaster = FALSE)
    {
        $this->load->model('users_fav_book_model', 'users');

        $favRow = $this->load->users_fav_comic_model->cache(CACHE_OUT_TIME_THIRTY_SECO)
            ->getUsersFavBookRowByBookId($bookId, $usersId, $fields, $dbMaster);
        return successMsg('fav book row', $favRow);
    }

    /**
     * isUsersFavBook
     * @param $usersId
     * @param $bookId
     * @author xuyi
     * @date 2019/9/24
     * @return array
     */
    public function isUsersFavBook($usersId, $bookId)
    {
        $usersId = intval($usersId);
        $bookId  = intval($bookId);

        $resultRow = array(
            'fav_status' => 2
        );

        if(empty($usersId) || empty($bookId)) {
            return successMsg('fav status ~', $resultRow);
        }

        $usersFav = $this->getUsersFavBookRowByBookId($bookId, $usersId, $fields = 'fav_id, fav_status');
        $resultRow['fav_status'] = empty($usersFav['fav_status']) ? 2 : intval($usersFav['fav_status']);
        return successMsg('fav status ~', $resultRow);
    }

    /**
     * getUsersFavComicListByPage
     * @param $usersId
     * @param $rowsNum
     * @param $pageNum
     * @author xuyi
     * @date 2019/9/24
     * @return array
     */
    public function getUsersFavBookListByPage($usersId, $rowsNum, $pageNum)
    {
        $usersId = intval($usersId);
        $rowsNum = intval($rowsNum);
        $pageNum = intval($pageNum);


        $this->load->model('users_fav_book_model', 'users');
        $fields     = 'fav_id, book_id, users_id, create_time';

        $resultPage = $this->load->users_fav_comic_model->cache(CACHE_OUT_TIME_ONE_MINU)
            ->getUsersFavComicListByPage($usersId, $fields, $rowsNum, $pageNum, $dbMaster = FALSE);

        $bookIdArr = array_column($resultPage['data'], 'book_id');
        $comicList  = array();

        if(! empty($comicIdArr))
        {
            $fields    = 'comic_id, comic_name, comic_desc, comic_cover';
            $comicList = $this->load->server('book_info_class', 'book')->getBookInfoListByBookIdArr($bookIdArr, $fields, $dbMaster = FALSE)->result('result');
        }


        $resultPage['comic_list'] = $comicList;

        return successMsg('ok', $resultPage);
    }
}
