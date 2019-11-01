<?php
class hooks
{
	public $actionTime	= '';
	public $actionRun	= TRUE;
	public $hooksFile	= '';
	public $config		= array();
	private $classes	= NULL;

	public function __construct($param = array())
	{

	}

    /**
     * @author 零上一度
     * @date 2019/6/13
     */
	public function run()
	{
	    # 1: 查询当前url需要执行的hooks配置

        # 2:
		$this->classes->exec();
	}

    /**
     * @param string $driveName
     * @author 零上一度
     * @date 2019/6/13
     * @return $this
     * @throws Exception
     */
	public function drive($driveName = '')
	{
		$driveName = trim($driveName);

		if(empty($driveName)) {
			throw new Exception("Error Processing Request", 1);
		}

		$explodeArr = explode('/', $driveName);
		$group 		= isset($explodeArr[0]) ? trim($explodeArr[0]) : '';
		$name 		= isset($explodeArr[1]) ? trim($explodeArr[1]) : '';

		if(empty($group) || empty($name)) {
			throw new Exception("Error Processing Request", 1);
		}

		$this->classes = load_class($name, $group, $paramArr, FALSE, TRUE);
		return $this;
	}

    /**
     * @param array $paramArr
     * @author 零上一度
     * @date 2019/6/13
     */
	public function config($paramArr = array())
	{

	}
}
?>
