<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 03/05/2015
 * Time: 18:04
 */

namespace Pidzhak\Model\redactor;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class SystemCodeTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function fetchPage($rowCount, $offset, $orderby, $searchPhrase){
        $resultSet = $this->tableGateway->select(function(Select $select) use ($rowCount, $offset, $orderby, $searchPhrase){
            if($rowCount<0)
                $select->offset(0);
            else
                $select->limit($rowCount)->offset($offset);
            $select->order($orderby);

        });

        return $resultSet;
    }

    public function getCount(){
        $resultSet = $this->tableGateway->select();
        return $resultSet->count();
    }

    public function getSystemCode($id) {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('order_cloth_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function getSystemCodeList($id) {
        $id  = (int) $id;
        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from($this->tableGateway->table);

        $where = new Where();
        $where->equalTo('order_cloth_id', $id);
        $select->where($where);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }

    public function saveSystemCode(SystemCode $systemCode)
    {
        $data = array(
            'id'                => $systemCode->id,
            'order_cloth_id'    => $systemCode->order_cloth_id,
            'code'              => $systemCode->code,
            'fabric_optional'   => $systemCode->fabric_optional,
            'description'       => $systemCode->description,
        );

        $id = (int) $systemCode->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getSystemCode($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('systemCode does not exist');
            }
        }
    }

    public function deleteSystemCode($id)
    {
        $this->tableGateway->delete(array('order_cloth_id' => (int) $id));
    }
}