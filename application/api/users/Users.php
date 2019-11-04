<?php
/**
 * users 相關信息
 * author: xuyi
 * date: 2019/8/16 17:28
 */
class Users extends Driver_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->authorization();
    }

    /**
     * 登陆
     * @author xuyi
     * @date 2019/9/20
     */
    public function login()
    {
        $username = self::post('username', '13525044816', 'trim');
        $password = self::post('password', '1234', 'trim');
        $isPlatform = self::post('is_platform', 0, 'boolval');

        $r = errorMsg(500, 'request method post');

        if(self::isPost() || self::isGet())
        {
            $app = self::classes('users_info_class', 'users');
            $r = $app->usersLogin($username, $password, $isPlatform);
        }

        if(validate($r)) {
            $signal = empty($r['result']['signal']) ? '' : trim($r['result']['signal']);
            set_cookie('identifier', $signal, 3600, 'ipensoft.com', '/');
        }

        $this->displayJson($r);
    }

    /**
     * 注册
     * @author xuyi
     * @date 2019/9/22
     */
    public function register()
    {
        $username      = self::post('username', '13525044817', 'trim');
        $usersPassword = self::post('users_password', 'xuyi134', 'trim');
        $usersNickname = self::post('users_nickname', '徐熠', 'trim');
        $isPlatform = self::post('is_platform', 0, 'boolval');

        $r = errorMsg(500, 'request method post');

        if(self::isPost() || self::isGet())
        {
            $saveRow = array(
                'users_nickname' => $usersNickname,
                'users_password' => $usersPassword
            );

            $app = self::classes('users_info_class', 'users');
            $r = $app->usersRegister($username, $saveRow);
        }

        $this->displayJson($r);
    }

    /********************************************************************/
    /**
     * 获取登录用户信息
     * @author xuyi
     * @date 2019/9/20
     */
    public function get_login_users_info_by_identifier()
    {
        $usersId = $this->getLoginUsersId();

        $r = errorMsg(NO_LOGIN_CODE, NO_LOGIN_MSG);

        if(! empty($usersId)) {
            $app = self::classes('users_info_class', 'users');
            $r = $app->getUsersInfo($usersId);
        }

        $this->displayJson($r);
    }

    /**
     * 用戶信息獲取
     * @author xuyi
     * @date 2019/8/17
     */
    public function get_info_by_id()
    {
        $app = self::classes('users_info_class', 'users');
        $usersId = self::get('users_id', 0, 'intval');

        $this->displayJson(function() use($app, $usersId)
        {
            return $app->getUsersInfo($usersId);

        }, CACHE_OUT_TIME_ONE_HOUR);
    }

    /**
     * users_browsing_log
     * @author xuyi
     * @date 2019/8/16
     */
    public function get_users_browsing()
    {

        $rowsNum = self::get('rows_num', 10, 'intval');
        $pageNum = self::get('page_num', 1, 'intval');

        $usersId = $this->getLoginUsersId();

        $usersBrowsing = self::classes('users_browsing_history_class', 'users');

        $this->displayJson(function() use ($usersId, $rowsNum, $pageNum, $usersBrowsing)
        {
            return $usersBrowsing->getUsersBrowsingHistoryListByPage($usersId, $rowsNum, $pageNum);

        }, CACHE_OUT_TIME_THIRTY_SECO);
    }

    /**
     * add users browsing log
     * @author xuyi
     * @date 2019/8/17
     */
    public function add_users_browsing_log()
    {

        $objectId       = self::post('object_id', 1, 'intval');
        $browsingType   = self::post('browsing_type', 1, 'intval');
        $progress       = self::post('progress', 1, 'intval');

        # 非post 或者 用戶未登錄 請求展示錯誤頁面
        $r = errorMsg(500, 'request method post');
        if(! self::isPost()) {
           error($r, FALSE);
        }

        $r = errorMsg(NO_LOGIN_CODE, NO_LOGIN_MSG);

        $usersId = $this->getLoginUsersId();

        if(! empty($usersId))
        {
            $usersBrowsing = self::classes('users_browsing_history_class', 'users');
            $r = $usersBrowsing->addUsersHistoryLog($usersId, $objectId, $browsingType, $progress);
        }

        $this->displayJson($r);
    }
}
