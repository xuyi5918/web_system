<?php
/**
 * Created by PhpStorm.
 * author: xuyi
 * date: 2019/9/24 16:48
 */
class Comic extends Core_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->authorization();
    }

    /**
     * get comic fav
     * @author xuyi
     * @date 2019/9/24
     */
    public function get_comic_fav_page()
    {
        $usersId = $this->getLoginUsersId();

        $rowsNum = self::get('rows_num', 20, 'intval');
        $pageNum = self::get('page_num', 1, 'intval');

        $usersFav = self::classes('users_fav_class', 'users');

        $resultPage = $usersFav->getUsersFavComicListByPage($usersId, $rowsNum, $pageNum);

        $this->displayJson($resultPage);
    }

    public function save_comic_fav()
    {

    }
}
