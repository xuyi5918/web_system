<?php
/**
 * Created by PhpStorm.
 * author: xuyi
 * date: 2019/9/20 13:32
 */
class users_info_class
{
    public $load;

    public function __construct()
    {
        $this->load = app();
    }

    /***************************Users Login Logic********************************/
    /**
     * 用户登录逻辑信息
     * @param $userName
     * @param $passWord
     * @author xuyi
     * @date 2019/9/20
     * @return array
     */
    public function usersLogin($userName, $passWord = '', $usersPlatform = NULL)
    {
        if(empty($userName)) {
            return errorMsg(500, '用户账号不能为空~');
        }

        if(empty($usersPlatform) && empty($passWord)) {
            return errorMsg(500, '用户密码不能为空~');
        }

        # 验证账号信息
        $r = $this->getUsersIsExists($userName, $usersPlatform);

        if(! validate($r)) {
            return $r;
        }

        $result = empty($r['result']) ? array() : $r['result'];

        # 验证密码信息
        $usersPassword = !isset($result['users_password']) ? '' : trim($result['users_password']);
        $usersInfoId   = !isset($result['id']) ? 0 : intval($result['id']);
        $usersId       = !isset($result['users_id']) ? 0 : intval($result['users_id']);
        $usersStatus   = !isset($result['users_status']) ? 0 : intval($result['users_status']);

        if($usersStatus !== NORMAL) {
            return errorMsg(100, '用户状态异常~');
        }

        if(empty($usersPlatform) && $usersPassword !== md5($passWord)) {
            return errorMsg(500, '用户密码错误!~');
        }

        # 设置账号状态
        $editStatus = $this->editUsersInfoRecentlyActive($usersId, time());

        if(! validate($editStatus)) {
            return $editStatus;
        }

        # 设置登录后状态信息
        return $this->saveUsersLoginStatus($usersId, $usersInfoId);
    }

    /**
     * 保存usersId登录状态
     * @param $usersId
     * @author xuyi
     * @date 2019/9/21
     * @return array
     */
    public function saveUsersLoginStatus($usersId, $usersInfoId)
    {
        $usersId     = intval($usersId);
        $usersInfoId = intval($usersInfoId);

        if(empty($usersId) || empty($usersInfoId)) {
            return errorMsg(404, '用户信息异常, 登录失败~');
        }


        # 保存用户登录状态到redis
        $cache = $this->load->cache($adapter = 'redis', $backup = 'redis');
        $ttl = time() + (3600 * LOGIN_OUT_TIME); // 登录状态保存7天


        $usersIdKey = $cache->key(USERS_USERS_ID_SIGNAL_KEY, array($usersId));


        $oldSignal = $cache->get($usersIdKey); // 获取旧登录标识
        if(! empty($oldSignal))
        {
            # 删除旧登录标识关联关系
            $usersSignalKey = $cache->key(USERS_SIGNAL_USERS_ID_KEY, array($oldSignal));
            $cache->delete($usersSignalKey);
        }


        $signal = md5(http_build_query(array('users_id'=>$usersId, 'create_time'=>time())));
        $result = $cache->save($usersIdKey, $signal, $ttl);
        if($result !== TRUE) {
            return errorMsg(500, '服务器缓存服务异常, 登录失败~');
        }

        # 设置新登录标识关联关系
        $usersSignalKey = $cache->key(USERS_SIGNAL_USERS_ID_KEY, array($signal));
        $result = $cache->save($usersSignalKey, $usersId, $ttl);

        if($result !== TRUE) {
            return errorMsg(500, '服务器缓存服务异常, 登录失败~');
        }


        return successMsg('登录成功~', array('signal'=>$signal));
    }

    /**
     * 检查用户类型
     * @param $username
     * @param $usersPlatform
     * @author xuyi
     * @date 2019/9/21
     * @return string
     */
    private function checkUsersType($username, $usersPlatform)
    {
        $username = trim($username);
        $usersPlatform = intval($usersPlatform);
        $usersPlatform = in_array($usersPlatform, array(1, 2, 3, 4)) ? $usersPlatform : 0;

        if(! empty($usersPlatform) && ! empty($username)) {
            return 'platform';
        }

        if(preg_match("/\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/", $username)) {
            return 'mail';
        }

        if(preg_match("/^1[34578]{1}\d{9}$/", $username)) {
            return 'mobile';
        }

        return '';
    }

    /**
     * check users info
     * @param $username
     * @param $password
     * @author xuyi
     * @date 2019/9/20
     * @return array
     */
    public function getUsersIsExists($username, $usersPlatform)
    {
        $username       = trim($username);
        $usersPlatform  = intval($usersPlatform);

        # 检查账号类型
        $usersType = $this->checkUsersType($username, $usersPlatform);

        # 查询账号是否存在
        $usersRow   = $this->getUsersInfoRowByUsersType($usersType, $username);

        $usersId    = empty($usersRow['users_id']) ? 0 : intval($usersRow['users_id']);
        if(empty($usersId)) {
            return errorMsg(404, '用户信息不存在~');
        }

        # 查询账号信息
        $this->load->model('users_info_model', 'users');
        $fields = 'id, users_id, users_password, users_status';
        $usersRow = $this->load->users_info_model->cache(CACHE_OUT_TIME_ONE_MINU)->getUsersInfoRowByUsersId($usersId, $fields);


        if(empty($usersRow)) {
            return errorMsg(500, '用户信息存在异常~');
        }

        return successMsg('ok', $usersRow);
    }

    /**
     * edit users active time
     * @param $usersId
     * @param $activeTime
     * @author xuyi
     * @date 2019/9/20
     */
    private function editUsersInfoRecentlyActive($usersId, $activeTime)
    {
        $usersId = intval($usersId);
        $activeTime = intval($activeTime);

        if(empty($usersId)) {
            return errorMsg(404, '主键ID不能为空！');
        }

        $saveData = array('recently_active' => $activeTime, 'update_time'=>time());
        $this->load->model('users_info_model', 'users');
        return $this->load->users_info_model->saveUsersInfo($usersId, $saveData);
    }

    /**
     * 根据账号类型查询数据库
     * @param $usersType
     * @param $username
     * @author xuyi
     * @date 2019/9/20
     */
    public function getUsersInfoRowByUsersType($usersType, $username)
    {
        $usersType = trim($usersType);
        $username  = trim($username);

        switch ($usersType)
        {
            case 'mobile':
                $this->load->model('users_mobile_model', 'users');
                $usersRow = $this->load->users_mobile_model->cache(CACHE_OUT_TIME_ONE_MINU)->getUsersMobileRowByMobile($username, $fields = 'users_id');
                break;
            case 'mail':
                $this->load->model('users_mail_model', 'users');
                $usersRow = $this->load->users_mail_model->cache(CACHE_OUT_TIME_ONE_MINU)->getUsersMailRowBySign(md5($username), $fields = 'users_id');
                break;
            case 'platform':
                $this->load->model('users_platform_model', 'users');
                $usersRow = $this->load->users_platform_model->cache(CACHE_OUT_TIME_ONE_MINU)->getUsersPlatformRowBySign(md5($username), $fields = 'users_id');
                break;
            default:
                $usersRow = array();

        }

        return $usersRow;
    }

    /**************************users register logic *************************************/

    /**
     * 账号注册逻辑
     * @param $username
     * @param $saveRow
     * @author xuyi
     * @date 2019/9/22
     * @return array
     */
    public function usersRegister($username, $saveRow)
    {
        $username = trim($username);

        if(empty($username)) {
            return errorMsg(404, '用户账号不能为空~');
        }


        $usersPlatform   = empty($saveRow['users_platform']) ? 0 : intval($saveRow['users_platform']);
        $usersNickname   = empty($saveRow['users_nickname']) ? '' : trim($saveRow['users_nickname']);
        $usersPassword   = empty($saveRow['users_password']) ? '' : trim($saveRow['users_password']);


        $usersType = $this->checkUsersType($username, $usersPlatform);
        if(empty($usersType)) {
            return errorMsg(500, '错误的账号格式错误~');
        }

        $usersRow = $this->getUsersInfoRowByUsersType($usersType, $username);

        if(!empty($usersRow)) {
            return errorMsg(500, '当前账号已经注册~');
        }

        # 检查保存账号信息是否异常
//        $r = $this->verifyUsersInfo();
//        if(! validate($r)) {
//            return $r;
//        }

        # 获取 users 主键ID
        $nextId = $this->load->classes('next_unique_id_class', 'tools');
        $usersNextId = $nextId->getUsersNextUsersId();
        if(empty($usersNextId)) {
            return errorMsg(500, '账号主键生成失败~');
        }

        # 1:保存用户账号信息
        $usersData = array(
            'users_id' => $usersNextId,
            'username' => $username,
            'platform' => $usersPlatform
        );

        $r = $this->saveUsersAccount($usersType, $usersData);
        if(!validate($r)) {
            return $r;
        }

        # 2: 保存用户信息表
        $saveData = array(
            'users_nickname' => $usersNickname,
            'users_password' => md5($usersPassword),
            'users_status'   => NORMAL,
            'create_time'    => time(),
            'update_time'    => time()
        );

        $this->load->model('users_info_model', 'users');
        $r = $this->load->users_info_model->saveUsersInfo($usersNextId, $saveData);
        if(!validate($r)) {
            return $r;
        }

        $resultRow = array_merge($usersData, $saveData);
        return successMsg('注册成功~', $resultRow);
    }

    /**
     * 保存用户账号信息
     * @param $usersId
     * @param $saveRow
     * @author xuyi
     * @date 2019/9/22
     * @return array
     */
    private function saveUsersAccount($usersType, $saveRow)
    {
        $username = empty($saveRow['username']) ? '' : trim($saveRow['username']);
        $usersId  = empty($saveRow['users_id']) ? 0 : intval($saveRow['users_id']);
        $platform = empty($saveRow['platform']) ? 0 : intval($saveRow['platform']);

        # 默认返回值
        $r = errorMsg(500, '保存用户账号失败,请重试~');

        switch ($usersType)
        {
            case 'mobile':
                $this->load->model('users_mobile_model', 'users');
                $saveData = array(
                    'users_id'    => $usersId,
                    'mobile'      => $username,
                    'create_time' => time()
                );
                $r = $this->load->users_mobile_model->saveUsersMobile($mobileId = 0, $saveData);
                if(validate($r)) {
                    $r = successMsg('保存成功~');
                }
                break;
            case 'mail':
                $this->load->model('users_mail_model', 'users');
                $saveData = array(
                    'users_id'    => $usersId,
                    'mail'        => $username,
                    'sign'        => md5($username),
                    'create_time' => time()
                );
                $r = $this->load->users_mail_model->saveUsersMail($mailId = 0, $saveData);

                if(validate($r)) {
                    $r = successMsg('保存成功~');
                }
                break;
            case 'platform':
                $this->load->model('users_platform_model', 'users');
                $saveData = array(
                    'users_id'      => $usersId,
                    'platform_sign' => $username,
                    'platform'      => $platform,
                    'sign'          => md5($username),
                    'create_time'   => time()
                );
                $r = $this->load->users_platform_model->saveUsersPlatform($platformId = 0, $saveData);
                if(validate($r)) {
                    $r = successMsg('保存成功~');
                }
                break;
        }

        return $r;
    }

    /**
     * 获取用户基本信息
     * @param $usersId
     * @author xuyi
     * @date 2019/9/20
     * @return array
     */
    public function getUsersInfo($usersId)
    {
        $usersId  = intval($usersId);
        if(empty($usersId)) {
            return successMsg('ok', array());
        }


        $this->load->model('users_info_model', 'users');
        $fields   = '*';
        $usersRow = $this->load->users_info_model->cache(CACHE_OUT_TIME_ONE_MINU)
            ->getUsersInfoRowByUsersId($usersId, $fields, $dbMaster = FALSE);

        return successMsg('ok', $usersRow);
    }

}
