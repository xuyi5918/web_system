<?php
/**
 * Created by PhpStorm.
 * author: xuyi
 * date: 2019/9/26 12:51
 */

class Comic extends Driver_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->authorization();
    }

    /**
     * comic_comment_show
     * @author xuyi
     * @date 2019/9/25
     */
    public function comic_comment_show()
    {
        $commentId = self::get('comment_id', 0, 'intval');

        $app = self::classes('users_comic_comment_class', 'users');

        $this->displayJson(function() use($app, $commentId)
        {
            return $app->getUsersComicCommentInfoRowByCommentId($commentId);

        }, CACHE_OUT_TIME_ONE_HOUR);
    }

    /**
     * comic_reply_list
     * @author xuyi
     * @date 2019/9/25
     */
    public function comic_reply_list()
    {
        $commentId  = self::get('comment_id', 0, 'intval');
        $rowsNum    = self::get('rows_num', 20, 'intval');
        $pageNum    = self::get('page_num', 1, 'intval');

        $app        = self::classes('users_comic_comment_class', 'users');

        $this->displayJson(function() use($app, $commentId, $rowsNum, $pageNum)
        {
            return $app->getUsersComicReplyListByPage($commentId, $rowsNum, $pageNum);

        }, CACHE_OUT_TIME_ONE_MINU);
    }

    /**
     * comic_comment_list
     * @author xuyi
     * @date 2019/9/25
     */
    public function comic_comment_list()
    {
        $ComicId  = self::get('comic_id', 0, 'intval');
        $rowsNum  = self::get('rows_num', 20, 'intval');
        $pageNum  = self::get('page_num', 1, 'intval');

        $app      = self::classes('users_comic_comment_class', 'users');

        $this->displayJson(function() use($ComicId, $rowsNum, $pageNum, $app)
        {
            return $app->getUsersComicCommentListByPage($ComicId, $rowsNum, $pageNum);

        }, CACHE_OUT_TIME_ONE_MINU);
    }

    /**
     * save_comic_comment
     * @author xuyi
     * @date 2019/9/25
     */
    public function save_comic_comment()
    {
        $commentId = 0;
        $content   = self::post('content', '', 'trim');
        $comicId   = self::post('comic_id', 0, 'intval');

        $saveRow = array(
            'comic_id'  => $comicId,
            'content'   => htmlspecialchars($content),
            'users_id'  => $this->getLoginUsersId()
        );

        $result = self::classes('users_comic_comment_class', 'users')->saveUsersComicComment($commentId, $saveRow);

        $this->displayJson($result);
    }

    /**
     * save_comic_reply
     * @author xuyi
     * @date 2019/9/25
     */
    public function save_comic_reply()
    {
        $commentId = self::post('comment_id', 1, 'intval');
        $content   = self::post('content', 'asdfasdfasdf', 'trim');
        $replyParentId = self::post('reply_parent_id', 1, 'intval');

        $saveData = array(
            'comment_id'      => $commentId,
            'reply_parent_id' => $replyParentId,
            'content'         => htmlspecialchars($content),
            'users_id'        => $this->getLoginUsersId()
        );

        $result = self::classes('users_comic_comment_class', 'users')->saveUsersComicReplyInfo($replyId = 0, $saveData);

        # 服务通知
        if(validate($result))
        {
            $queue  = self::classes('queue_notice_class', 'tools');
            $signal = 'comic_comment';
            $queueList = array(
                'comment_id' => $commentId
            );

            $queue->setSignal($signal)->addQueue($queueList)->setQueue();
        }

        $this->displayJson($result);
    }


    /**
     * edit_comic_comment
     * @author xuyi
     * @date 2019/9/25
     */
    public function edit_comic_comment()
    {
        $commentId = self::post('comment_id', 0, 'intval');
        $content   = self::post('content', '', 'trim');

        $usersId = $this->getLoginUsersId();

        $result = self::classes('users_comic_comment_class', 'users')->editUsersComicCommentByContent($commentId, $usersId, $content);

        $this->displayJson($result);
    }

    /**
     * edit_comic_reply
     * @author xuyi
     * @date 2019/9/26
     */
    public function edit_comic_reply()
    {
        $replyId = self::post('reply_id', 0, 'intval');
        $content = self::post('content', '', 'trim');

        $usersId = $this->getLoginUsersId();

        $result  = self::classes('users_comic_comment_class', 'users')->editUsersComicReplyByContent($replyId, $usersId, $content);

        $this->displayJson($result);
    }

    /**
     * del_comic_comment
     * @author xuyi
     * @date 2019/9/25
     */
    public function del_comic_comment()
    {
        $commentId = self::post('comment_id', 0, 'intval');
        $usersId   = $this->getLoginUsersId();

        $result    = self::classes('users_comic_comment_class', 'users')->delUsersComicComment($commentId, $usersId);

        $this->displayJson($result);
    }

    /**
     * del_comic_reply
     * @author xuyi
     * @date 2019/9/26
     */
    public function del_comic_reply()
    {
        $replyId = self::post('reply_id', 0, 'intval');
        $usersId = $this->getLoginUsersId();

        $result  = self::classes('users_comic_comment_class', 'users')->delUsersComicReply($replyId, $usersId);

        if(validate($result))
        {
            $replyRow = empty($result['result']) ? array() : $result['result'];
            $queue  = self::classes('queue_notice_class', 'tools');
            $signal = 'comic_comment';
            $queueList = array(
                'comment_id' => empty($replyRow['comment_id']) ? 0 : intval($replyRow['comment_id'])
            );

            $queue->setSignal($signal)->addQueue($queueList)->setQueue();
        }

        $this->displayJson($result);
    }
}
