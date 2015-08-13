<?php
namespace Pidzhak\Model\seller;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Expression;
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

    public function fetchPage($rowCount, $offset, $orderby, $searchPhrase, $status_id){

        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from($this->tableGateway->table)
            ->join('userstable', 'userstable.id = ordertable.seller_id')
            ->join('orderclothes', 'orderclothes.order_id = ordertable.id')
            ->join('customer', 'ordertable.customer_id = customer.id')
            ->join('citiestable', 'ordertable.cityofsale_id = citiestable.id')
            ->columns(array(
                '*', new Expression("CONCAT(COALESCE(customer.firstname,''), ' ' , COALESCE(customer.lastname,'')) as customer_full_name,
                                    CONCAT(COALESCE(userstable.name,''), ' ' , COALESCE(userstable.surname,'')) as seller_full_name,
                                    ordertable.id as my_id")
            ));

        if($rowCount<0)
            $select->offset(0);
        else
            $select->limit($rowCount)->offset($offset);

        $where = new  Where();

        if($searchPhrase) {
            $where->NEST
                ->like('firstname', '%'.mb_strtolower($searchPhrase, 'UTF-8').'%')
                ->OR
                ->like('lastname', '%'.mb_strtolower($searchPhrase, 'UTF-8').'%')
                ->OR
                ->like('city_name', '%'.mb_strtolower($searchPhrase, 'UTF-8').'%')
                ->OR
                ->like('pointofsale', '%'.mb_strtolower($searchPhrase, 'UTF-8').'%')
                ->OR
                ->like('userstable.name', '%'.mb_strtolower($searchPhrase, 'UTF-8').'%')
                ->OR
                ->like('userstable.surname', '%'.mb_strtolower($searchPhrase, 'UTF-8').'%')
                ->UNNEST;
            ;
            $where->and;
        }



        $where->equalTo('orderclothes.status_id', $status_id) ;
        $select->where($where);
        $select->group('ordertable.id');
        $select->order($orderby);

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
            'cityofsale_id' => $order->cityofsale_id,
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

    public function getClientIdByOrderId($id){

        $id = (int)$id;
        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from($this->tableGateway->table);

        $where = new  Where();
        $where->equalTo('id', $id);
        $select->where($where);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        $selectData = array();

        foreach ($result as $res) {
            array_push($selectData, $res['customer_id']);
        }

        return $selectData;
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