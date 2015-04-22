<?php
namespace Pidzhak\Model\Seller;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;
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
        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from($this->tableGateway->table)
            ->join('userstable', 'userstable.id = ordertable.seller_id');
        if($rowCount<0)
            $select->offset(0);
        else
            $select->limit($rowCount)->offset($offset);
        $select->order($orderby);

        /*if($searchPhrase)
            $select->where->like('firstname', '%'.strtolower($searchPhrase).'%')->OR->like('lastname', '%'.strtolower($searchPhrase).'%');*/

        $where = new  Where();
        $where->equalTo('status', 1) ;
        $select->where($where);

        //you can check your query by echo-ing :
        // echo $select->getSqlString();
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
            'customer_id' => $order->customer_id,
            'dateofsale' => $order->dateofsale,
            'cityofsale' => $order->cityofsale,
            'pointofsale' => $order->pointofsale,
            'seller_id' => $order->seller_id,
            'status' => $order->status,
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

    public function insertedOrder()
    {
        return $this->tableGateway->lastInsertValue;
    }
}