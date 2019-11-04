<?php
/**
 *
 * @Date:       2018/11/9 21:02
 * @version:    ${Id}
 * @Author:     零上一度 <xuyi5918@live.cn>
 */
class Configure_model extends Driver_Model
{
    const Table_Site_Config = 'site_config';
    const Table_Site_Config_Group = 'site_config_group';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * call function get Db config
     * @param $method
     * @param $args
     */
    public function __call($method, $args)
    {

        $fields = 'group_id, create_at';
        $whereArr = array('group_app'=>APPNAME);

        $groupRow = $this->getDbMaster('configure')->select($fields)->from(self::SITE_CONFIG_GROUP)->where($whereArr)
            ->limit(1)->get()->row_array();

        if(empty($groupRow)) {
            return NULL;
        }

        $fields = 'configure_values';
        $whereArr = array();
        $configRow = $this->getDbMaster('configure')->select($fields)->from(self::SITE_CONFIG)->where($whereArr)
            ->limit(1)->get()->row_array();

        $configureValues = empty($configRow['configure_values']) ? NULL : $configRow['configure_values'];
        return $configureValues;
    }
}
