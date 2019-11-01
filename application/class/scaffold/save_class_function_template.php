
    /**
     * save{@TABLE_NAME@}
     * @param ${@PK_COLUMN@}
     * @param $saveData
     * @author {@AUTHOR@}
     * @date {@TIME@}
     * @return array
     */
    public function save{@TABLE_NAME@}(${@PK_COLUMN@}, $saveData)
    {
        ${@FIND_COLUMN@}  = intval(${@FIND_COLUMN@});
        $saveData = !is_array($saveData) ? array() : $saveData;

        if(empty($saveData)) {
            return array();
        }

        # 参数整理
        {is-empty-param}

        $default = '{@DATE_NAME@}';
        $resultRow = $this->get{@TABLE_NAME@}RowBy{@BY_COLUMN@}(${@PK_COLUMN@}, $fields = '{@FIELDS_COLUMN@}');
        if(empty($resultRow))
        {
            $insert = array(
                {array-block}
            );

            $this->db_master($default)->insert($insert, $this->table);
            $id = $this->db_master($default)->insert_id();
            if(empty($id)) {
                return errorMsg(500, 'insert db error~');
            }

        }else
        {
            $update = array();

            {if-block}

            $this->db_master($default)->where(array('{@PK_COLUMN_FIND@}' => ${@FIND_COLUMN@}))->update($update, $this->table);
            $affectedRows = $this->db_master($default)->affected_rows();
            if(empty($affectedRows)) {
                return errorMsg(500, 'update error~');
            }
        }


        return successMsg('ok~');
    }


    /**
     * edit{@TABLE_NAME@}By{@EDIT_BY_COLUMN@}
     * @param ${@PK_COLUMN@}
     * @param $status
     * @author {@AUTHOR@}
     * @date {@TIME@}
     * @return array
     */
    public function edit{@TABLE_NAME@}By{@EDIT_BY_COLUMN@}(${@PK_COLUMN@}, $status)
    {
        ${@FIND_COLUMN@}  = intval(${@FIND_COLUMN@});
        $status = in_array($status, array({@STATUS_LIST@})) ? 0 : $status;

        if(empty(${@FIND_COLUMN@}) || empty($status)) {
            return errorMsg(404, 'edit databases params is empty');
        }

        $whereArr = array(
            '{@PK_COLUMN_FIND@}' => ${@PK_COLUMN@}
        );

        $update = array('{@EDIT_COLUMN@}'=>$status);

        $default = '{@DATE_NAME@}';
        $this->db_master($default)->where($whereArr)->update($this->table, $update);
        $num = $this->db_master($default)->affected_rows();

        if(empty($num)) {
            return errorMsg(500, 'edit error~');
        }

        return successMsg('ok~');
    }
