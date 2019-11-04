<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2017, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2017, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/user_guide/libraries/config.html
 */
class CI_Model
{
    public static $getDb = array();
	/**
	 * Class constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		log_message('info', 'Model Class Initialized');
	}

	// --------------------------------------------------------------------

	/**
	 * __get magic
	 *
	 * Allows models to access CI's loaded classes using the same
	 * syntax as controllers.
	 *
	 * @param	string	$key
	 */
	public function __get($key)
	{
		// Debugging note:
		//	If you're here because you're getting an error message
		//	saying 'Undefined Property: system/core/Model.php', it's
		//	most likely a typo in your model code.
		return get_instance()->$key;
	}

	public function elastics_slave()
    {

    }

    public function elastics_master()
    {

    }
    
    public function mongo_slave($mongoName)
    {

    }

	public function mongo_master($mongoName)
    {

    }

    /**
     * @param $dbName
     * @author 零上一度
     * @date 2019/6/3
     * @return mixed
     */
    public function db_slave($dbName)
    {
        $dbName = trim($dbName);
        $dbName = empty($dbName) ? 'default' : $dbName;

        $hash   = md5('slave:'. $dbName);

        if(isset(self::$getDb[$hash])) {
            return self::$getDb[$hash];
        }


        $databaseList = config('database', 'slave');

        $paramsList = empty($databaseList[$dbName]) ? array() : $databaseList[$dbName];
        if(empty($paramsList)) {
            error(errorMsg(500, 'db slave content params empty! (2001)'));
        }

        $paramsRow = $paramsList[array_rand(array_keys($paramsList), 1)]; // 随机取一条从库配置

        $this->load->database($paramsRow, $return = FALSE, $query_builder = TRUE);
        self::$getDb[$hash] = $this->db;
        return self::$getDb[$hash];
    }

    /**
     * @param $dbName
     * @author 零上一度
     * @date 2019/6/3
     * @return mixed
     */
	public function db_master($dbName)
    {
        $dbName = trim($dbName);
        $dbName = empty($dbName) ? 'default' : $dbName;


        $hash   = md5('master:' . $dbName);

        if(isset(self::$getDb[$hash])) {
            return self::$getDb[$hash];
        }

        $databaseList = config('database', 'master');

        $paramsList = empty($databaseList[$dbName]) ? array() : $databaseList[$dbName];


        if(empty($paramsList)) {
            error(errorMsg(500, 'db master content params empty! (2001)'));
        }

        $paramsRow = $paramsList[array_rand(array_keys($paramsList), 1)]; // 随机取一条主库库配置


        $this->load->database($paramsRow, $return = FALSE, $query_builder = TRUE);
        self::$getDb[$hash] = $this->db;
        return self::$getDb[$hash];
    }

}
