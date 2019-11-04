<?php
/**
 * Created by PhpStorm.
 * author: xuyi
 * date: 2019/9/26 12:52
 */

class Book extends Driver_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->authorization();
    }

    /**
     * get_book_comment_by_id
     * @author xuyi
     * @date 2019/9/25
     */
    public function get_book_comment_by_id()
    {
        $commentId = self::get('comment_id', 0, 'intval');

        $app = self::classes('users_book_comment_class', 'users');

        $this->displayJson(function() use($app, $commentId)
        {
            return $app->getUsersBookCommentInfoRowByCommentId($commentId);

        }, CACHE_OUT_TIME_ONE_HOUR);
    }

    /**
     * book_reply_list
     * @author xuyi
     * @date 2019/9/25
     */
    public function get_book_reply_page()
    {
        $commentId  = self::get('comment_id', 0, 'intval');
        $rowsNum    = self::get('rows_num', 20, 'intval');
        $pageNum    = self::get('page_num', 1, 'intval');

        $app        = self::classes('users_book_comment_class', 'users');

        $this->displayJson(function() use($app, $commentId, $rowsNum, $pageNum)
        {
            return $app->getUsersBookReplyListByPage($commentId, $rowsNum, $pageNum);

        }, CACHE_OUT_TIME_ONE_MINU);
    }

    /**
     * book_comment_list
     * @author xuyi
     * @date 2019/9/25
     */
    public function get_book_comment_page()
    {
        $bookId   = self::get('book_id', 0, 'intval');
        $rowsNum  = self::get('rows_num', 20, 'intval');
        $pageNum  = self::get('page_num', 1, 'intval');

        $app      = self::classes('users_book_comment_class', 'users');

        $this->displayJson(function() use($bookId, $rowsNum, $pageNum, $app)
        {
            return $app->getUsersBookCommentListByPage($bookId, $rowsNum, $pageNum);

        }, CACHE_OUT_TIME_ONE_MINU);
    }

    /**
     * save_book_comment
     * @author xuyi
     * @date 2019/9/25
     */
    public function save_book_comment()
    {
        $commentId = 0;
        $content   = self::post('content', '', 'trim');
        $bookId    = self::post('book_id', 0, 'intval');

        $saveRow = array(
            'book_id' => $bookId,
            'content' => htmlspecialchars($content),
            'users_id'=> $this->getLoginUsersId()
        );

        $result = self::classes('users_book_comment_class', 'users')->saveUsersBookComment($commentId, $saveRow);

        $this->displayJson($result);
    }

    /**
     * save_book_reply
     * @author xuyi
     * @date 2019/9/25
     */
    public function save_book_reply()
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

        $result = self::classes('users_book_comment_class', 'users')->saveUsersBookReplyInfo($replyId = 0, $saveData);

        # 服务通知
        if(validate($result))
        {
            $queue  = self::classes('queue_notice_class', 'tools');
            $signal = 'book_comment';
            $queueList = array(
                'comment_id' => $commentId
            );

            $queue->setSignal($signal)->addQueue($queueList)->setQueue();
        }

        $this->displayJson($result);
    }


    /**
     * edit_book_comment
     * @author xuyi
     * @date 2019/9/25
     */
    public function edit_book_comment()
    {
        $commentId = self::post('comment_id', 0, 'intval');
        $content   = self::post('content', '', 'trim');

        $usersId = $this->getLoginUsersId();

        $result = self::classes('users_book_comment_class', 'users')->editUsersBookCommentByContent($commentId, $usersId, $content);

        $this->displayJson($result);
    }

    /**
     * edit_book_reply
     * @author xuyi
     * @date 2019/9/26
     */
    public function edit_book_reply()
    {
        $replyId = self::post('reply_id', 0, 'intval');
        $content = self::post('content', '', 'trim');

        $usersId = $this->getLoginUsersId();

        $result = self::classes('users_book_comment_class', 'users')->editUsersBookReplyByContent($replyId, $usersId, $content);

        $this->displayJson($result);
    }

    /**
     * del_book_comment
     * @author xuyi
     * @date 2019/9/25
     */
    public function del_book_comment()
    {
        $commentId = self::post('comment_id', 0, 'intval');
        $usersId   = $this->getLoginUsersId();

        $result = self::classes('users_book_comment_class', 'users')->delUsersBookComment($commentId, $usersId);
        $this->displayJson($result);
    }

    /**
     * del_book_reply
     * @author xuyi
     * @date 2019/9/26
     */
    public function del_book_reply()
    {
        $replyId = self::post('reply_id', 0, 'intval');
        $usersId = $this->getLoginUsersId();

        $result  = self::classes('users_book_comment_class', 'users')->delUsersBookReply($replyId, $usersId);

        if(validate($result))
        {
            $replyRow = empty($result['result']) ? array() : $result['result'];
            $queue  = self::classes('queue_notice_class', 'tools');
            $signal = 'book_comment';
            $queueList = array(
                'comment_id' => empty($replyRow['comment_id']) ? 0 : intval($replyRow['comment_id'])
            );

            $queue->setSignal($signal)->addQueue($queueList)->setQueue();
        }

        $this->displayJson($result);
    }

}
