<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/14
 * Time: 18:28
 */

namespace Admin\Model;


class DbMysqlInterfaceImplModel implements DbMysqlInterfaceModel {
    /**
     * DB connect
     *
     * @access public
     *
     * @return resource connection link
     */
    public function connect() {
        // TODO: Implement connect() method.
        echo 'connect...';
        exit;
    }

    /**
     * Disconnect from DB
     *
     * @access public
     *
     * @return viod
     */
    public function disconnect() {
        // TODO: Implement disconnect() method.
        echo 'disconnect...';
        exit;
    }

    /**
     * Free result
     *
     * @access public
     * @param resource $result query resourse
     *
     * @return viod
     */
    public function free($result) {
        // TODO: Implement free() method.
        echo 'free...';
        exit;
    }

    /**
     * Execute simple query
     *
     * @access public
     * @param string $sql SQL query
     * @param array $args query arguments
     *
     * @return resource|bool query result
     */
    public function query($sql, array $args = array()) {
        // TODO: Implement query() method.
        $tempSql = $this->buildSQL(func_get_args());
        return M()->execute($tempSql);
    }

    /**
     * Insert query method
     *
     * @access public
     * @param string $sql SQL query
     * @param array $args query arguments
     *
     * @return int|false last insert id
     */
    public function insert($sql, array $args = array()) {
        // TODO: Implement insert() method.
        //�õ�ʵ�ʲ���
        $params=func_get_args();
        //ȡ��SQL���ģ��
        $sql=array_shift($params);
        //ȡ�����ݱ������
        $table_name=array_shift($params);
        //��?T�滻�����ݱ���
        $sql=str_replace('?T',$table_name,$sql);

        //���set������
        $tempSet='';
        foreach($params[0] as $k=>$v){
            $tempSet.=($k.'="'.$v.'",');
        }
        //ȥ���������
        $tempSet=rtrim($tempSet,',');
        //��?%�滻��set������
        $sql=str_replace('?%',$tempSet,$sql);

        //ִ��sql���
        $model=M();
        $rst=$model->execute($sql);
        if($rst===false){
            return false;
        }else{
            return $model->getLastInsID();
        }
    }

    /**
     * Update query method
     *
     * @access public
     * @param string $sql SQL query
     * @param array $args query arguments
     *
     * @return int|false affected rows
     */
    public function update($sql, array $args = array()) {
        // TODO: Implement update() method.
        echo 'update...';
        exit;
    }

    /**
     * Get all query result rows as associated array
     *
     * @access public
     * @param string $sql SQL query
     * @param array $args query arguments
     *
     * @return array associated data array (two level array)
     */
    public function getAll($sql, array $args = array()) {
        // TODO: Implement getAll() method.
        echo 'getAll...';
        exit;
    }

    /**
     * Get all query result rows as associated array with first field as row key
     *
     * @access public
     * @param string $sql SQL query
     * @param array $args query arguments
     *
     * @return array associated data array (two level array)
     */
    public function getAssoc($sql, array $args = array()) {
        // TODO: Implement getAssoc() method.
        echo 'getAssoc...';
        exit;
    }

    /**
     * Get only first row from query
     *
     * @access public
     * @param string $sql SQL query
     * @param array $args query arguments
     *
     * @return array associated data array
     */
    public function getRow($sql, array $args = array()) {
        // TODO: Implement getRow() method.
        $tempSql = $this->buildSQL(func_get_args());
        $row=M()->query($tempSql);
        return empty($row)?false:$row[0];
    }

    /**
     * Get first column of query result
     *
     * @access public
     * @param string $sql SQL query
     * @param array $args query arguments
     *
     * @return array one level data array
     */
    public function getCol($sql, array $args = array()) {
        // TODO: Implement getCol() method.
        echo 'getCol...';
        exit;
    }

    /**
     * Get one first field value from query result
     *
     * @access public
     * @param string $sql SQL query
     * @param array $args query arguments
     *
     * @return string field value
     */
    public function getOne($sql, array $args = array()) {
        // TODO: Implement getOne() method.
        //�õ�ʵ�ʲ���
        $tempSql = $this->buildSQL(func_get_args());
        $rows=M()->query($tempSql);
        $value=array_values($rows[0]);
        return $value[0];
    }

    /**
     * ����Ҫִ�е�sql���
     * @param $params
     * @return string
     */
    private function buildSQL($params) {
        $sqls = preg_split('/\?[FTN]/', array_shift($params));
        $tempSql = '';
        foreach ($sqls as $k => $v) {
            $tempSql .= ($v . $params[$k]);
        }
        return $tempSql;
    }

}