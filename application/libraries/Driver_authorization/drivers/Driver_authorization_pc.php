<?php
/**
 * Created by PhpStorm.
 * author: xuyi
 * date: 2019/10/31 20:23
 */
class Driver_authorization_pc extends CI_Driver
{

    public function __construct()
    {

    }

    public function validate($validate = array())
    {

        return true;
    }

    /**
     * 执行验证
     * @author xuyi
     * @date 2019/10/31
     * @return mixed
     */
    public function exec()
    {
        return array(1);
    }
}
