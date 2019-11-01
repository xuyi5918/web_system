<?php
/**
 * 用户积分保存逻辑
 * author: xuyi
 * date: 2019/10/13 18:13
 */
class users_credit_level_class
{
    public $load = NULL;
    public function __construct()
    {
        $this->load = app();
    }

    /********************用户积分相关业务逻辑**************************/

    public function incrUsersCredit()
    {



        return $this->addUsersCreditLog();
    }

    public function decrUsersCredit()
    {


        return $this->addUsersCreditLog();
    }

    public function addUsersCreditLog()
    {


        return successMsg('add log ok~', array());
    }


    /********************用户LEVEL相关业务逻辑**************************/


}
