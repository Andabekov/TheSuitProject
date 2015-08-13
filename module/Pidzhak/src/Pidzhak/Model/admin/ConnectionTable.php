<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 12/08/2015
 * Time: 17:38
 */

namespace Pidzhak\Model\admin;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class ConnectionTable{

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

    public function fetchPage($rowCount, $offset, $orderby, $searchPhrase)
    {

        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from('supplier_fabric_class')->join('fabric_suppliers', 'supplier_fabric_class.supplier_id = fabric_suppliers.id');

        if($rowCount<0)
            $select->offset(0);
        else
            $select->limit($rowCount)->offset($offset);

        $select->order($orderby);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }

    public function getCount(){
        $resultSet = $this->tableGateway->select();
        return $resultSet->count();
    }

    public function deleteConnection($supp_id, $fabric)
    {
        $this->tableGateway->delete(array('supplier_id'=>$supp_id, 'fabric_class'=>$fabric));
    }

    public function saveConnection(Connection $connection){
        $data = array(
            'supplier_id' => $connection->supplier_id,
            'fabric_class' => $connection->fabric_class,
        );

        $this->tableGateway->insert($data);
    }
}