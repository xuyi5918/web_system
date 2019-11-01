<?php
/**
 * Created by PhpStorm.
 * author: xuyi
 * date: 2019/9/25 11:14
 */

class users_book_comment_class
{
    public $load = NULL;
    public function __construct()
    {
        $this->load = app();
    }

    /**
     * getUsersBookReplyContentInfoListByReplyIdArr
     * @param $replyIdArr
     * @author xuyi
     * @date 2019/9/25
     * @return array
     */
    public function getUsersBookReplyContentList($replyIdArr)
    {
        $replyIdArr = array_map('intval', $replyIdArr);

        $this->load->model('users_book_reply_content_info_model', 'users');
        $fields = '*';
        $contentList = $this->load->users_book_reply_content_info_model->cache(CACHE_OUT_TIME_ONE_MINU)
            ->getUsersBookReplyContentInfoListByReplyIdArr($replyIdArr, $fields, $dbMaster = FALSE);

        return successMsg('ok~', $contentList);
    }

    /**
     * getUsersBookReplyInfoListByReplyIdArr
     * @param $replyIdArr
     * @author xuyi
     * @date 2019/9/25
     */
    public function getUsersBookReplyList($replyIdArr)
    {

        $replyIdArr = array_map('intval', $replyIdArr);
        $fields     = '*';

        $this->load->model('users_book_reply_info_model', 'users');
        $replyList = $this->load->users_book_reply_info_model->cache(CACHE_OUT_TIME_ONE_MINU)
            ->getUsersBookReplyInfoListByReplyIdArr($replyIdArr, $fields, $dbMaster = FALSE);

        $replyIdArr = array_column($replyList, 'reply_id');
        if(! empty($replyIdArr)) {
            $replyContentList = $this->getUsersBookReplyContentList($replyIdArr);
        }


        $resultList['reply_list'] = $replyList;
        $resultList['reply_content_list'] = $replyContentList;

        return successMsg('ok~', $resultList);
    }

    /**
     * getUsersBookReplyInfoRowByReplyId
     * @param $replyId
     * @author xuyi
     * @date 2019/9/25
     * @return array
     */
    public function getUsersBookReplyInfoRowByReplyId($replyId)
    {
        $replyId = intval($replyId);
        $fields  = 'reply_id, comment_id, reply_status, users_id';

        $this->load->model('users_book_reply_info_model', 'users');
        $resultRow = $this->load->users_book_reply_info_model->cache(CACHE_OUT_TIME_THIRTY_SECO)
            ->getUsersBookReplyInfoRowByReplyId($replyId, $fields, $dbMaster = FALSE);

        return successMsg('ok~', $resultRow);
    }

    /**
     * getUsersBookCommentInfoRowByCommentId
     * @param $commentId
     * @author xuyi
     * @date 2019/9/25
     * @return array
     */
    public function getUsersBookCommentInfoRowByCommentId($commentId)
    {
        $commentId = intval($commentId);

        $fields     = 'comment_id, book_id, reply_num, comment_status, users_id, like_num, create_time';
        $this->load->model('users_book_comment_info_model', 'users');

        $commentRow = $this->load->users_book_comment_info_model
            ->cache(CACHE_OUT_TIME_THIRTY_MINU)->getUsersBookCommentInfoRowByCommentId($commentId, $fields, $dbMaster = FALSE);

        return successMsg('ok~', $commentRow);
    }

    /**
     * getUsersBookCommentInfoRowByCommentId
     * @param $commentId
     * @param $fields
     * @param bool $dbMaster
     * @author xuyi
     * @date 2019/9/25
     * @return array
     */
    public function getUsersBookCommentInfo($commentId)
    {
        $commentId  = intval($commentId);

        $resultRow  = $this->getUsersBookCommentInfoRowByCommentId($commentId);
        $commentRow = empty($resultRow['result']) ? array() : $resultRow['result'];

        $contentRow = array();
        if(! empty($commentRow))
        {
            $fields = 'content';
            $commentId = empty($commentRow['comment_id']) ? 0 : intval($commentRow['comment_id']);
            $this->load->model('users_book_comment_content_info_model', 'users');
            $contentRow = $this->load->users_book_comment_content_info_model->cache(CACHE_OUT_TIME_ONE_HOUR)
                ->getUsersBookCommentContentInfoRowByCommentId($commentId, $fields, $dbMaster = FALSE);
        }

        $resultRow = array(
            'comment_row' => $commentRow,
            'content_row' => $contentRow
        );

       return successMsg('ok~', $resultRow);
    }

    /**
     * getUsersBookReplyListByPage
     * @param $commentId
     * @param $rowsNum
     * @param $pageNum
     * @author xuyi
     * @date 2019/9/25
     * @return array
     */
    public function getUsersBookReplyListByPage($commentId, $rowsNum, $pageNum)
    {
        $commentId = intval($commentId);
        $rowsNum   = intval($rowsNum);
        $pageNum   = intval($pageNum);


        $this->load->model('users_book_reply_info_model', 'users');
        $fields    = 'reply_id, comment_id, replay_status, reply_parent_id, like_num, create_time';
        $replyList = $this->load->users_book_reply_info_model->cache(CACHE_OUT_TIME_ONE_MINU)
            ->getUsersBookReplyListByPage($commentId, $fields, $rowsNum, $pageNum, $dbMaster = FALSE);

        $usersIdArr = array_column($replyList['data'], 'users_id');
        $usersList  = array();
        if(! empty($usersIdArr))
        {
            $this->load->model('users_info_model', 'users');
            $fields    = '*';
            $usersList = $this->load->users_info_model->cache(CACHE_OUT_TIME_ONE_MINU)
                ->getUsersInfoListByUsersIdArr($usersIdArr, $fields, $dbMaster = FALSE);
        }

        $replyIdArr = array_column($replyList['data'], 'reply_id');
        if(! empty($replyIdArr)) {
            $contentList = $this->getUsersBookReplyContentList($replyIdArr);
        }

        $replyList['users_list']   = empty($usersList) ? array() : $usersList;
        $replyList['content_list'] = empty($contentList['result']) ? array() : $contentList['result'];

        return successMsg('ok~', $replyList);
    }

    /**
     * getUsersBookCommentListByPage
     * @param $bookId
     * @param $rowsNum
     * @param $pageNum
     * @author xuyi
     * @date 2019/9/25
     * @return array
     */
    public function getUsersBookCommentListByPage($bookId, $rowsNum, $pageNum)
    {
        $bookId    = intval($bookId);
        $rowsNum   = intval($rowsNum);
        $pageNum   = intval($pageNum);


        $this->load->model('users_book_comment_info_model', 'users');
        $fields     = 'comment_id, book_id, replay_num, like_num, new_reply_id_arr, create_time';
        $resultPage = $this->load->users_book_comment_info_model->cache(CACHE_OUT_TIME_ONE_MINU)
            ->getUsersBookCommentListByPage($bookId, $fields, $rowsNum, $pageNum, $dbMaster = FALSE);

        $usersIdArr = array_column($resultPage['data'], 'users_id');
        $usersList  = array();
        if(! empty($usersIdArr))
        {
            $this->load->model('users_info_model', 'users');
            $fields    = '*';
            $usersList = $this->load->users_info_model->cache(CACHE_OUT_TIME_ONE_MINU)
                ->getUsersInfoListByUsersIdArr($usersIdArr, $fields, $dbMaster = FALSE);
        }

        $commentIdArr = array_column($resultPage['data'], 'comment_id');
        $contentList  = array();
        if(! empty($commentIdArr))
        {
            $this->load->model('users_book_comment_content_info_model', 'users');
            $fields = 'content';
            $contentList = $this->load->users_book_comment_content_info_model->cache(CACHE_OUT_TIME_ONE_MINU)
                ->getUsersBookCommentContentInfoListByCommentIdArr($commentIdArr, $fields, $dbMaster = FALSE);
        }

        # 查询最新回复内容
        $replyIdArr = empty($resultPage['new_reply_id_arr']) ? array() : json_decode($resultPage['new_reply_id_arr'], TRUE);
        $replyList  = $this->getUsersBookReplyList($replyIdArr);

        $resultList = empty($replyList['result']) ? array() : $replyList['result'];

        $resultPage['users_list'] = empty($usersList) ? array() : $usersList;
        $resultPage['reply_list'] = empty($resultList['reply_list']) ? array() : $resultList['reply_list'];
        $resultPage['reply_content_list']   = empty($resultPage['reply_content_list']) ? array() : $resultPage['reply_content_list'];
        $resultPage['comment_content_list'] = empty($contentList) ? array() : $contentList;

        return successMsg('ok~', $resultPage);
    }

    /**
     * editUsersBookCommentByContent
     * @param $commentId
     * @param $usersId
     * @param $content
     * @author xuyi
     * @date 2019/9/25
     * @return array
     */
    public function editUsersBookCommentByContent($commentId, $usersId, $content)
    {
        $commentId = intval($commentId);
        $content   = trim($content);

        if(empty($usersId)) {
            return errorMsg(NO_LOGIN_CODE, NO_LOGIN_MSG);
        }

        $commentRow = $this->getUsersBookCommentInfoRowByCommentId($commentId);

        if(empty($commentRow) || $commentRow['comment_status'] != NORMAL) {
            return errorMsg(404, '评论内容不存在~');
        }

        if($commentRow['users_id'] != $usersId) {
            return errorMsg(500, '不能修改他人评论内容~');
        }

        $saveData = array(
            'content'     => $content,
            'update_time' => time()
        );

        $this->load->model('users_book_comment_content_info_model', 'users');
        $result = $this->load->users_book_comment_content_info_model->saveUsersBookCommentContentInfo($commentId, $saveData);
        if(!validate($result)) {
            return $result;
        }

        return successMsg('修改评论内容成功~');
    }

    /**
     * editUsersBookReplyByContent
     * @param $replyId
     * @param $usersId
     * @param $content
     * @author xuyi
     * @date 2019/9/26
     * @return array
     */
    public function editUsersBookReplyByContent($replyId, $usersId, $content)
    {
        $replyId = intval($replyId);
        $usersId = intval($usersId);
        $content = trim($content);

        if(empty($usersId)) {
            return errorMsg(NO_LOGIN_CODE, NO_LOGIN_MSG);
        }


        $resultRow = $this->getUsersBookReplyInfoRowByReplyId($replyId);
        $replyRow  = empty($resultRow['result']) ? array() : $resultRow['result'];
        if(empty($replyRow) || $replyRow['replay_status'] != NORMAL) {
            return errorMsg(404, '回复内容不存在~');
        }

        if($replyRow['users_id'] != $usersId) {
            return errorMsg(500, '不能修改他人回复内容~');
        }

        $saveData = array(
            'content'     => $content,
            'update_time' => time()
        );

        $this->load->model('users_book_reply_content_info_model', 'users');
        $result = $this->load->users_book_reply_content_info_model->saveUsersBookReplyContentInfo($replyId, $saveData);
        if(!validate($result)) {
            return $result;
        }

        return successMsg('修改回复内容成功~');
    }

    /**
     * saveUsersBookComment
     * @param $commentId
     * @param $saveRow
     * @author xuyi
     * @date 2019/9/25
     * @return array
     */
    public function saveUsersBookComment($commentId, $saveRow)
    {
        $commentId  = intval($commentId);
        $content    = empty($saveRow['content']) ? '' : trim($saveRow['content']);
        $bookId     = empty($saveRow['book_id']) ? 0  : intval($saveRow['book_id']);
        $usersId    = empty($saveRow['users_id']) ? 0 : intval($saveRow['users_id']);

        $createTime = time();
        $updateTime = time();

        if(empty($usersId)) {
            return errorMsg(NO_LOGIN_CODE, NO_LOGIN_MSG);
        }

        $fields  = 'book_id, book_status';
        $bookRow = $this->load->server('book_info_class', 'book')->getBookInfoRowByBookId($bookId, $fields, $dbMaster = FALSE)->result('result');
        if(empty($bookRow) || $bookRow['book_status'] != NORMAL) {
            return errorMsg(404, '评论对象不存在~');
        }

        $saveData = array(
            'book_id'       => $bookId,
            'comment_status'=> NORMAL,
            'create_time'   => $createTime,
            'update_time'   => $updateTime
        );

        $this->load->model('users_book_comment_info_model', 'users');
        $r = $this->load->users_book_comment_info_model->saveUsersBookCommentInfo($commentId, $saveData);
        if(! validate($r)) {
            return $r;
        }

        $resultRow = empty($r['result']) ? array() : $r['result'];
        $commentId = empty($resultRow['comment_id']) ? 0 : intval($resultRow['comment_id']);

        $saveData = array(
            'content'     => $content,
            'comment_id'  => $commentId,
            'create_time' => $createTime,
            'update_time' => $updateTime,
        );

        $this->load->model('users_book_comment_content_info_model', 'users');
        $result = $this->load->users_book_comment_content_info_model->saveUsersBookCommentContentInfo($commentId, $saveData);
        if(!validate($result)) {
            return $result;
        }


        return successMsg('save comment ok~', $saveData);
    }

    /**
     * saveUsersBookReplyInfo
     * @param $replyId
     * @param $saveData
     * @author xuyi
     * @date 2019/9/25
     * @return array
     */
    public function saveUsersBookReplyInfo($replyId, $saveData)
    {
        $replyId    = intval($replyId);
        $content    = empty($saveData['content']) ? '' : trim($saveData['content']);
        $commentId  = empty($saveData['comment_id']) ? 0  : intval($saveData['comment_id']);
        $usersId    = empty($saveData['users_id']) ? 0 : intval($saveData['users_id']);
        $replyParentId = empty($saveData['reply_parent_id']) ? 0 : intval($saveData['reply_parent_id']);
        $createTime = time();
        $updateTime = time();

        if(empty($usersId)) {
            return errorMsg(NO_LOGIN_CODE, NO_LOGIN_MSG);
        }

        if(!empty($replyParentId))
        {
            $resultRow = $this->getUsersBookReplyInfoRowByReplyId($replyParentId);
            $replyRow  = empty($resultRow['result']) ? array() : $resultRow['result'];
            if(empty($replyRow) || $replyRow['reply_status'] != NORMAL) {
                return errorMsg(404, '回复评论内容不存在~');
            }
        }


        $resultRow = $this->getUsersBookCommentInfoRowByCommentId($commentId);
        $commentRow = empty($resultRow['result']) ? array() : $resultRow['result'];
        if(empty($commentRow) || $commentRow['comment_status'] != NORMAL) {
            return errorMsg(404, '评论内容不存在~');
        }

        $commentId = empty($commentRow['comment_id']) ? 0 : intval($commentRow['comment_id']);

        $saveData = array(
            'comment_id'      => $commentId,
            'reply_status'    => NORMAL,
            'reply_parent_id' => $replyParentId,
            'users_id'        => $usersId,
            'create_time'     => $createTime,
            'update_time'     => $updateTime
        );

        $this->load->model('users_book_reply_info_model', 'users');
        $r = $this->load->users_book_reply_info_model->saveUsersBookReplyInfo($replyId, $saveData);
        if(! validate($r)) {
            return $r;
        }

        $resultRow = empty($r['result']) ? array() : $r['result'];

        $replyId  = empty($resultRow['reply_id']) ? 0 : intval($resultRow['reply_id']);

        $saveData = array(
            'content'     => $content,
            'create_time' => $createTime,
            'update_time' => $updateTime
        );

        $this->load->model('users_book_reply_content_info_model', 'users');
        $r = $this->load->users_book_reply_content_info_model->saveUsersBookReplyContentInfo($replyId, $saveData);
        if(! validate($r)) {
            return $r;
        }

        return successMsg('回复成功~', array('comment_id' => $commentId));
    }

    /**
     *
     * @param $commentId
     * @param $status
     * @author xuyi
     * @date 2019/9/25
     * @return mixed
     */
    public function editUsersBookCommentInfoByCommentStatus($commentId, $status)
    {
        $commentId = intval($commentId);
        $status    = intval($status);

        $this->load->model('users_book_comment_info_model', 'users');
        $result = $this->load->users_book_comment_info_model->editUsersBookCommentInfoByCommentStatus($commentId, $status);
        return $result;
    }

    /**
     * editUsersBookReplyInfoByReplayStatus
     * @param $replyId
     * @param $status
     * @author xuyi
     * @date 2019/9/26
     * @return mixed
     */
    public function editUsersBookReplyInfoByReplyStatus($replyId, $status)
    {
        $replyId = intval($replyId);
        $status  = intval($status);

        $this->load->model('users_book_reply_info_model', 'users');
        $result = $this->load->users_book_reply_info_model->editUsersBookReplyInfoByReplyStatus($replyId, $status);

        return $result;
    }

    /**
     * delUsersBookComment
     * @param $commentId
     * @param $usersId
     * @author xuyi
     * @date 2019/9/25
     * @return array|mixed
     */
    public function delUsersBookComment($commentId, $usersId)
    {
        $usersId    = intval($usersId);
        $commentId  = intval($commentId);

        if(empty($usersId)) {
            return errorMsg(NO_LOGIN_CODE, NO_LOGIN_MSG);
        }


        $resultRow = $this->getUsersBookCommentInfoRowByCommentId($commentId);
        $result    = empty($resultRow['result']) ? array() : $resultRow['result'];

        if(empty($result) || $result['comment_status'] != NORMAL) {
            return errorMsg(404, '评论内容不存在~');
        }

        if($result['users_id'] != $usersId) {
            return errorMsg(500, '不能删除他人评论内容~');
        }

        return $this->editUsersBookCommentInfoByCommentStatus($commentId, $status = DELETED);
    }

    /**
     * delUsersBookReply
     * @param $replyId
     * @param $usersId
     * @author xuyi
     * @date 2019/9/26
     * @return array|mixed
     */
    public function delUsersBookReply($replyId, $usersId)
    {
        $replyId = intval($replyId);
        $usersId = intval($usersId);

        if(empty($usersId)) {
            return errorMsg(NO_LOGIN_CODE, NO_LOGIN_MSG);
        }

        $resultRow = $this->getUsersBookReplyInfoRowByReplyId($replyId);
        $replyRow  = empty($resultRow['result']) ? array() : $resultRow['result'];
        if(empty($replyRow) || $replyRow['reply_status'] != NORMAL) {
            return errorMsg(404, '回复内容不存在~');
        }

        if($replyRow['users_id'] != $usersId) {
            return errorMsg(500, '不能删除他人评论内容~');
        }

        $r = $this->editUsersBookReplyInfoByReplyStatus($replyId, $status = DELETED);
        if(!validate($r)) {
            return $r;
        }

        return successMsg('删除回复成功~', $replyRow);
    }

}
