<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 12/08/2015
 * Time: 17:38
 */

namespace Pidzhak\Model\admin;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class SupplierTable{

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

    public function deleteSupplier($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }

    public function getSupplier($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveSupplier(Supplier $supplier){
        $data = array(
            'supplier_name' => $supplier->supplier_name,
            'supplier_email' => $supplier->supplier_email,
            'email_content' => $supplier->email_content,
        );

        $id = (int) $supplier->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getSupplier($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Task id does not exist');
            }
        }
    }
}