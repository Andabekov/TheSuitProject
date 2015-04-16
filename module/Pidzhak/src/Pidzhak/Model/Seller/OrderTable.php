<?php
namespace Pidzhak\Model\Seller;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class OrderTable
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

            /*if($searchPhrase)
                $select->where->like('firstname', '%'.strtolower($searchPhrase).'%')->OR->like('lastname', '%'.strtolower($searchPhrase).'%');*/
        });

        return $resultSet;
    }

    public function getCount(){
        $resultSet = $this->tableGateway->select();
        return $resultSet->count();
    }

    public function getOrder($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveOrder(Order $order)
    {
        $data = array(
            'dateofsale' => $order->dateofsale,
            'pricelistnum' => $order->pricelistnum,
            'payamount' => $order->payamount,
            'paytype' => $order->paytype,
            'cashlocation' => $order->cashlocation,
            'cityofsale' => $order->cityofsale,
            'pointofsale' => $order->pointofsale,
            'seller' => $order->seller,
        );

        $id = (int) $order->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getOrder($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Order id does not exist');
            }
        }
    }

    public function deleteOrder($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }
}