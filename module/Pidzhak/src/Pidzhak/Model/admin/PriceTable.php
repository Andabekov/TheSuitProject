<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 22/04/2015
 * Time: 11:03
 */

namespace Pidzhak\Model\admin;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class PriceTable
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

    public function getPrice($id) {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function savePrice(Price $price)
    {
        $data = array(
            'fabric_class'  => $price->fabric_class,
            'cloth_type' => $price->cloth_type,
            'price' => $price->price,
            'start_date' => $price->start_date,
            'end_date' => $price->end_date,
        );

        $id = (int) $price->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getPrice($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Price_id does not exist');
            }
        }
    }

    public function deletePrice($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }
}