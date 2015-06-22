<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 17/04/2015
 * Time: 10:56
 */

namespace Pidzhak\Model\admin;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class FabricTable
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
            if($searchPhrase)
                $select->where->like('id', '%'.strtolower($searchPhrase).'%')->OR->like('fabric_class', '%'.strtolower($searchPhrase).'%');
            $select->order($orderby);
        });

        return $resultSet;
    }

    public function getCount(){
        $resultSet = $this->tableGateway->select();
        return $resultSet->count();
    }

    public function getFabric($id) {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveFabric(Fabric $fabric)
    {
        $data = array(
            'id' => $fabric->id,
            'fabric_class' => $fabric->fabric_class,
        );

        $id = (int) $fabric->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getFabric($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Fabric_id does not exist');
            }
        }
    }

    public function deleteFabric($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }
}