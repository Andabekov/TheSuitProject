<?php
namespace Pidzhak\Model\Seller;

use Pidzhak\Model\seller\OrderClothes as OrderClothesSeller;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Ddl\Column\Date;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class OrderClothesTable
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

    public function fetchPage($rowCount, $offset, $orderby, $searchPhrase, $order_id, $seller_name, $order_status)
    {
        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from($this->tableGateway->table)
            ->join('clothers', 'orderclothes.product_id = clothers.id')
            ->join('cyclestable', 'orderclothes.cycle_id = cyclestable.id')
            ->join('fabricstable', 'orderclothes.textile_id = fabricstable.id')
            ->join('clothstatustable', 'orderclothes.status_id = clothstatustable.id')
            ->join('ordertable', 'orderclothes.order_id = ordertable.id')
            ->join('userstable', 'ordertable.seller_id = userstable.id')
            ->join('customer', 'ordertable.customer_id = customer.id')
            ->columns(array(
                '*', new Expression("CONCAT(customer.firstname, ' ' , customer.lastname) as customer_full_name,
                                    CONCAT(userstable.name, ' ' , userstable.surname) as seller_full_name,
                                    orderclothes.id as my_id")
            ))
        ;
        if ($rowCount < 0)
            $select->offset(0);
        else
            $select->limit($rowCount)->offset($offset);
        //$select->order($orderby);

        /*if($searchPhrase)
            $select->where->like('firstname', '%'.strtolower($searchPhrase).'%')->OR->like('lastname', '%'.strtolower($searchPhrase).'%');*/

        if ($order_id != -1) {
            $where = new  Where();
            $where->equalTo('order_id', $order_id);
            $select->where($where);
        }

        if($seller_name!= ''){
            $where = new  Where();
            $where->equalTo('userstable.username', $seller_name);
            $select->where($where);
        }

        //you can check your query by echo-ing :
        // echo $select->getSqlString();
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }

    public function getClientName($orderId){
        $id = (int)$orderId;
        $sql = new Sql($this->tableGateway->adapter);

        $select = $sql->select();
        $select->from($this->tableGateway->table)
            ->join('ordertable', 'orderclothes.order_id = ordertable.id')
            ->join('customer', 'ordertable.customer_id = customer.id')
        ;

        $where = new  Where();
        $where->equalTo('orderclothes.order_id', $id);
        $select->where($where);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        $row = $resultSet->current();
        if (!$row) {
            throw new \Exception("newnew Could not find row $id");
        }
        return $row;
    }

    public function getCount()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet->count();
    }

    public function getCountOfClothesByOrder($order_id)
    {
        $resultSet = $this->tableGateway->select(function (Select $select) use ($order_id) {
            $where = new  Where();
            $where->equalTo('order_id', $order_id);
            $select->where($where);
        });
        return $resultSet->count();
    }

    public function getOrderClothes($id)
    {
        $id = (int)$id;
        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from($this->tableGateway->table)
            ->join('fabricstable', 'orderclothes.textile_id = fabricstable.id');

        $where = new  Where();
        $where->equalTo('orderclothes.id', $id);
        $select->where($where);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        $row = $resultSet->current();
        if (!$row) {
            throw new \Exception("New Could not find row $id");
        }

        $result = new OrderClothesSeller($this->tableGateway->adapter);

        foreach ($row as $key => $value)
        {
            $result->$key = $value;
        }

        return $result;



//        $id  = (int) $id;
//        $rowset = $this->tableGateway->select(array('id' => $id));
//        $row = $rowset->current();
//        if (!$row) {
//            throw new \Exception("Could not find row $id");
//        }
//        return $row;
    }

    public function setFittingDate($date, $id){

        $id = (int)$id;
        $sql = new Sql($this->tableGateway->adapter);
        $update = $sql->update();
        $update->table('orderclothes');
        $update->set(array(
            'fitting_date' => $date,
            'status_id' => 8
        ));
        $update->where(array('order_id' => $id));
        $statement = $sql->prepareStatementForSqlObject($update);

        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }

    public function giveToClient($id){

        $id = (int)$id;
        $sql = new Sql($this->tableGateway->adapter);
        $update = $sql->update();
        $update->table('orderclothes');
        $update->set(array(
            'finished_date' => date('Y-m-d'),
            'status_id' => 9
        ));
        $update->where(array('order_id' => $id));
        $statement = $sql->prepareStatementForSqlObject($update);

        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }

    public function giveToTailor($id, $tailorId){
        $id = (int)$id;
        $sql = new Sql($this->tableGateway->adapter);
        $update = $sql->update();
        $update->table('orderclothes');
        $update->set(array(
            'tailor_id' => $tailorId,
            'status_id' => 10
        ));
        $update->where(array('order_id' => $id));
        $statement = $sql->prepareStatementForSqlObject($update);

        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }

    public function rollBackOrder($id, $comment){

        $dbAdapter = $this->tableGateway->adapter;
        $sql       = "update orderclothes set seller_comment=CONCAT(COALESCE(seller_comment), ' Причина отката: ".$comment."', status_id=1) where id=".$id;
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }

    public function confirmOrder($id){
        $id = (int)$id;
        $sql = new Sql($this->tableGateway->adapter);
        $update = $sql->update();
        $update->table('orderclothes');
        $update->set(array(
            'status_id' => 4
        ));
        $update->where(array('id' => $id));
        $statement = $sql->prepareStatementForSqlObject($update);

        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }

    public function sendToProd($id, $startDate, $endDate){

        $id = (int)$id;
        $sql = new Sql($this->tableGateway->adapter);
        $update = $sql->update();
        $update->table('orderclothes');
        $update->set(array(
            'production_start_date' => $startDate,
            'production_finish_date' => $endDate,
            'status_id' => 5
        ));
        $update->where(array('id' => $id));
        $statement = $sql->prepareStatementForSqlObject($update);

        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }

    public function setShipDate($id, $date){

        $id = (int)$id;
        $sql = new Sql($this->tableGateway->adapter);
        $update = $sql->update();
        $update->table('orderclothes');
        $update->set(array(
            'shipping_date' => $date,
            'status_id' => 6
        ));
        $update->where(array('id' => $id));
        $statement = $sql->prepareStatementForSqlObject($update);

        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }

    public function saveOrderClothes(OrderClothes $orderclothes)
    {
        $data = array(
            'order_id' => $orderclothes->order_id,
            'cycle_id' => $orderclothes->cycle_id,
            'preferred_date' => $orderclothes->preferred_date,
            'product_id' => $orderclothes->product_id,
            'pricelistnum' => $orderclothes->pricelistnum,
            'actual_amount' => $orderclothes->actual_amount,
            'paytype_id' => $orderclothes->paytype_id,
            'textile_id' => $orderclothes->textile_id,
            'typeof_measure' => $orderclothes->typeof_measure,
            'label_brand' => $orderclothes->label_brand,
            'style_id' => $orderclothes->style_id,
            'first_monogram_location' => $orderclothes->first_monogram_location,
            'first_monogram_font' => $orderclothes->first_monogram_font,
            'first_monogram_font_color' => $orderclothes->first_monogram_font_color,
            'first_monogram_caption' => $orderclothes->first_monogram_caption,
            'second_monogram_location' => $orderclothes->second_monogram_location,
            'second_monogram_font' => $orderclothes->second_monogram_font,
            'second_monogram_font_color' => $orderclothes->second_monogram_font_color,
            'second_monogram_caption' => $orderclothes->second_monogram_caption,
            'seller_comment' => $orderclothes->seller_comment,
        );


        $id = (int)$orderclothes->id;
        if ($id == 0) {
            $retval = $this->tableGateway->insert($data);
        } else {
            if ($this->getOrderClothes($id)) {
                $retval = $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('OrderClothes id does not exist');
            }
        }

    }

    public function sendClothToRedactor($id){

        $id = (int)$id;
        $sql = new Sql($this->tableGateway->adapter);
        $update = $sql->update();
        $update->table('orderclothes');
        $update->set(array(
            'status_id' => 1
        ));
        $update->where(array('id' => $id));
        $statement = $sql->prepareStatementForSqlObject($update);

        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }

    public function changeCloth($id)
    {
        $id = (int)$id;
        $sql = new Sql($this->tableGateway->adapter);
        $update = $sql->update();
        $update->table('orderclothes');
        $update->set(array(
            'status_id' => 10
        ));
        $update->where(array('id' => $id));
        $statement = $sql->prepareStatementForSqlObject($update);

        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }

    public function deleteOrderClothes($id)
    {
        $this->tableGateway->delete(array('id' => (int)$id));
    }


}