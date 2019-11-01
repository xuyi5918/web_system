<?php
/**
 * Created by PhpStorm.
 * author: 零上一度
 * date: 2019/6/2 20:08
 */
class Comic extends Core_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取漫画详情页
     * @author xuyi
     * @date 2019/8/13
     */
    public function get_comic_info_by_id()
    {
        $comicId = self::get('comic_id', 1, 'intval');
        $usersId = $this->getLoginUsersId();

        # 注入依赖
        $this->di('users_fav_class', $this->server('users_fav_class', 'users', SITE_USERS));
        $this->di('users_id', $usersId);

        $comic = self::classes('comic_info_class', 'comic');

        if(! empty($comicId)) {
            $comic->incrComicNumInfo($comicId, $incrFields = 'pv_num', $incrNum = 1);
        }

        $comicShow = $comic->getComicShow($comicId);

        $this->displayJson($comicShow);
    }

    /**
     * 获取漫画章节列表页
     * @author xuyi
     * @date 2019/8/21
     */
    public function get_comic_chapter_list()
    {
        $comicId = self::get('comic_id', 0, 'intval');

        $comic = self::classes('comic_info_class', 'comic');

        $this->displayJson(function() use ($comicId, $comic)
        {
            return $comic->getChapterListByComicId($comicId);

        }, CACHE_OUT_TIME_ONE_HOUR);
    }


    /**
     * get chapter show
     * @author xuyi
     * @date 2019/8/14
     */
    public function get_chapter_info_by_id()
    {
        $chapterId = self::get('chapter_id', 1, 'intval');
        $usersId   = $this->getLoginUsersId();

        $this->di('users_history_class', $this->server('users_browsing_history_class', 'users', SITE_USERS));
        $this->di('users_id', $usersId);

        $r = self::classes('comic_info_class', 'comic')->getChapterShow($chapterId);

        $this->displayJson($r);
    }

    public function get_comic_index_page()
    {

    }
}
