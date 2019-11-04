<?php
/**
 * 搜索驱动
 * author: xuyi
 * date: 2019/11/3 19:02
 */
class Driver_search extends CI_Driver_Library
{
    protected $valid_drivers = array(
        'elasticSearch',
        'sphinx'
    );
}
