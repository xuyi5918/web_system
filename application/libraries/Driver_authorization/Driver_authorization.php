<?php
/**
 * Created by PhpStorm.
 * author: xuyi
 * date: 2019/10/31 19:56
 */
class Driver_authorization extends CI_Driver_Library
{
    protected $drive = 'default';

    public $authorization = FALSE; # 认值状态

    protected $valid_drivers = array(
        'pc',
        'h5',
        'ios',
        'android',
        'windows'
    );

    /**
     * @param $drive
     * @author xuyi
     * @date 2019/10/31
     */
    public function drive($drive)
    {
        $this->drive = $drive;
        return $this;
    }

    /**
     * validate params
     * @param array $validate
     * @author xuyi
     * @date 2019/10/31
     * @return $this
     */
    public function validate($validate = array())
    {
        $this->{$this->drive}->validate($validate);
        return $this;
    }

    /**
     * 执行验证
     * @author xuyi
     * @date 2019/10/31
     * @return mixed
     */
    public function exec()
    {
        $result = $this->{$this->drive}->exec();
        $this->authorization = $this->{$this->drive}->authorization;

        return $result;
    }
}
