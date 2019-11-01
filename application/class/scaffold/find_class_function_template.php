
    /**
    * get{@TABLE_NAME@}ListBy{@BY_COLUMN@}Arr
    * @param ${@PK_COLUMN@}Arr
    * @param $fields
    * @param bool $dbMaster
    * @author {@AUTHOR@}
    * @date {@TIME@}
    * @return array
    */
    public function get{@TABLE_NAME@}ListBy{@BY_COLUMN@}Arr(${@PK_COLUMN@}Arr, $fields, $dbMaster = FALSE)
    {
        ${@PK_COLUMN@}Arr = array_map('intval',${@PK_COLUMN@}Arr);
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty(${@PK_COLUMN@}Arr) || empty($fields)) {
            return array();
        }

        $default = '{@DATE_NAME@}';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            '{@PK_COLUMN_FIND@}' => ${@PK_COLUMN@}Arr
        );

        return $db->select($fields)->from($this->table)->where_in($whereArr)
            ->limit(count(${@PK_COLUMN@}Arr))->get()->result_array('{@PK_COLUMN_FIND@}');
    }

    /**
    * get{@TABLE_NAME@}RowBy{@BY_COLUMN@}
    * @param ${@PK_COLUMN@}
    * @param $fields
    * @param bool $dbMaster
    * @author {@AUTHOR@}
    * @date {@TIME@}
    * @return array
    */
    public function get{@TABLE_NAME@}RowBy{@BY_COLUMN@}(${@PK_COLUMN@}, $fields, $dbMaster = FALSE)
    {
        ${@PK_COLUMN@} = intval(${@PK_COLUMN@});
        $fields  = trim($fields);
        $dbMaster = is_bool($dbMaster) ? $dbMaster : FALSE;

        if(empty(${@PK_COLUMN@}) || empty($fields)) {
            return array();
        }

        $default = '{@DATE_NAME@}';
        $db = $dbMaster === TRUE ? $this->db_master($default) : $this->db_slave($default);

        $whereArr = array(
            '{@PK_COLUMN_FIND@}' => ${@PK_COLUMN@}
        );

        return $db->select($fields)->from($this->table)->where($whereArr)
            ->limit(1)->get()->row_array();
    }
