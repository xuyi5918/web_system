<?php
/**
 * Created by PhpStorm.
 * author: xuyi
 * date: 2019/11/3 11:35
 */
class Driver_xmlrpc extends CI_Driver_Library
{

    protected $driver = 'server';

    protected $valid_drivers = array(
        'client',
        'server'
    );

    public function initialize()
    {

    }

    /**
     * driver
     * @param $driver
     * @author xuyi
     * @date 2019/11/3
     * @return $this
     */
    public function driver($driver)
    {
        $this->driver = $driver;
        return $this;
    }

    /**
     * request_stream
     * @param $file
     * @param $directory
     * @author xuyi
     * @date 2019/11/3
     */
    public function request_stream($stream)
    {
        $this->{$this->driver}->stream($stream);
        return $this;
    }

    /**
     * 执行的文件和目录
     * @param $file
     * @param $directory
     * @author xuyi
     * @date 2019/11/3
     * @return mixed
     */
    public function exec()
    {
        return $this->{$this->driver}->exec();
    }
}
