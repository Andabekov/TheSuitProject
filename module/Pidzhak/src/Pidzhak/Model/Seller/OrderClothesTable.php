<?php
namespace Pidzhak\Model\seller;

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
        $select->from($this->tableGateway->table);

        if($order_status==12){
            $select->join('tailorstable', 'orderclothes.tailor_id = tailorstable.id')
                ->join('clothers', 'orderclothes.product_id = clothers.id')
                ->join('cyclestable', 'orderclothes.cycle_id = cyclestable.id')
                ->join('fabricstable', 'orderclothes.textile_id = fabricstable.id')
                ->join('clothstatustable', 'orderclothes.status_id = clothstatustable.id')
                ->join('ordertable', 'orderclothes.order_id = ordertable.id')
                ->join('userstable', 'ordertable.seller_id = userstable.id')
                ->join('customer', 'ordertable.customer_id = customer.id')
                ->columns(array(
                    '*', new Expression("CONCAT(COALESCE(customer.firstname, ''), ' ' , COALESCE(customer.lastname, '')) as customer_full_name,
                                    CONCAT(userstable.name, ' ' , userstable.surname) as seller_full_name,
                                    cyclestable.arrive_deadline_date as cycle_date,
                                    orderclothes.id as my_id,
                                    tailorstable.name as tailor_name
                                    ")
                ));
        } else if($order_status==16 || $order_status==2 || $order_status==17){
            $select->join('userstable', 'orderclothes.assist_id = userstable.id')
                ->join('clothers', 'orderclothes.product_id = clothers.id')
                ->join('cyclestable', 'orderclothes.cycle_id = cyclestable.id')
                ->join('fabricstable', 'orderclothes.textile_id = fabricstable.id')
                ->join('clothstatustable', 'orderclothes.status_id = clothstatustable.id')
                ->join('ordertable', 'orderclothes.order_id = ordertable.id')
                ->join('customer', 'ordertable.customer_id = customer.id')
                ->columns(array(
                    '*', new Expression("CONCAT(COALESCE(customer.firstname, ''), ' ' , COALESCE(customer.lastname, '')) as customer_full_name,
                                        CONCAT(userstable.name, ' ' , userstable.surname) as seller_full_name,
                                        cyclestable.arrive_deadline_date as cycle_date,
                                        orderclothes.id as my_id
                                        ")
                ));
        } else {
                $select->join('clothers', 'orderclothes.product_id = clothers.id')
                    ->join('cyclestable', 'orderclothes.cycle_id = cyclestable.id')
                    ->join('fabricstable', 'orderclothes.textile_id = fabricstable.id')
                    ->join('clothstatustable', 'orderclothes.status_id = clothstatustable.id')
                    ->join('ordertable', 'orderclothes.order_id = ordertable.id')
                    ->join('userstable', 'ordertable.seller_id = userstable.id')
                    ->join('customer', 'ordertable.customer_id = customer.id')
                    ->columns(array(
                        '*', new Expression("CONCAT(COALESCE(customer.firstname, ''), ' ' , COALESCE(customer.lastname, '')) as customer_full_name,
                                        CONCAT(userstable.name, ' ' , userstable.surname) as seller_full_name,
                                        cyclestable.arrive_deadline_date as cycle_date,
                                        orderclothes.id as my_id")
                    ))
                ;
            }

        $where = new  Where();

        if($order_id==-1 && $order_status==-1 && $searchPhrase){

            $searchArray = explode("+", $searchPhrase);

            foreach($searchArray as $search){
                $where->NEST
                    ->like('firstname', '%'.mb_strtolower($search, 'UTF-8').'%')
                    ->OR->like('lastname', '%'.mb_strtolower($search, 'UTF-8').'%')
                    ->OR->like('clother', '%'.mb_strtolower($search, 'UTF-8').'%')
                    ->OR->like('status_name', '%'.mb_strtolower($search, 'UTF-8').'%')
                    ->OR->like('userstable.name', '%'.mb_strtolower($search, 'UTF-8').'%')
                    ->OR->like('userstable.surname', '%'.mb_strtolower($search, 'UTF-8').'%')
                    ->UNNEST;
                ;
                $where->and;
            }

        } else if($searchPhrase) {
            $searchArray = explode("+", $searchPhrase);

            foreach($searchArray as $search) {
                $where->NEST
                    ->like('firstname', '%' . mb_strtolower($search, 'UTF-8') . '%')
                    ->OR->like('lastname', '%' . mb_strtolower($search, 'UTF-8') . '%')
                    ->OR->like('clother', '%' . mb_strtolower($search, 'UTF-8') . '%')
                    ->OR->like('userstable.name', '%' . mb_strtolower($search, 'UTF-8') . '%')
                    ->OR->like('userstable.surname', '%' . mb_strtolower($search, 'UTF-8') . '%')
                    ->OR->like('cycle_id', '%' . mb_strtolower($search, 'UTF-8') . '%')
                    ->OR->like('orderclothes.id', '%' . mb_strtolower($search, 'UTF-8') . '%')
                    ->OR->like('clothers.clother', '%' . mb_strtolower($search, 'UTF-8') . '%')
                    ->UNNEST;;
                $where->and;
            }
        }

        if ($order_id != -1 && $order_id!=0) {
            $where->equalTo('order_id', $order_id);
            $where->and;
        } else if($order_id==0){
            $where->equalTo('order_id', -999);
            $where->and;
        }

        if($order_status==-1){
            $where->notEqualTo('status_id', $order_status);
            $where->and;
        } else if($order_status == 777){
            $where->equalTo('status_id', 9)
                ->or
                ->equalTo('status_id', 13);
            $where->and;
            $where->equalTo('customer.id', $seller_name);
            $where->and;
        } else if($order_status == 101){
            $where->equalTo('orderclothes.status_id', 1);
            $where->and;
            $where->nest()
                    ->equalTo('orderclothes.cycle_id', -10)
                    ->or
                    ->equalTo('orderclothes.cycle_id', -14)
                    ->unnest();
        }
        else if($order_status==1) {
            $where->equalTo('orderclothes.status_id', $order_status);
            $where->and;
            $where->notEqualTo('orderclothes.cycle_id', -10)
                ->and
                ->notEqualTo('orderclothes.cycle_id', -14);
        } else if($order_status == 20){ // обычные ткани в корзине
            $where->notEqualTo('orderclothes.cycle_id', -10)->and
                  ->notEqualTo('orderclothes.cycle_id', -14)->and
                  ->equalTo('orderclothes.fabric_status', 6);
        } else if($order_status == 21){ // срочные ткани в корзине
            $where->nest()
                ->equalTo('orderclothes.cycle_id', -10)->or->equalTo('orderclothes.cycle_id', -14)
                ->unnest();
            $where->and;
            $where->equalTo('orderclothes.fabric_status', 6);
        } else if($order_status == 22){ // обычные ткани в пути
            $where->notEqualTo('orderclothes.cycle_id', -10)->and
                  ->notEqualTo('orderclothes.cycle_id', -14)->and
                  ->equalTo('orderclothes.fabric_status', 2);
        } else if($order_status == 23){ // срочные ткани в пути
            $where->nest()
                ->equalTo('orderclothes.cycle_id', -10)->or->equalTo('orderclothes.cycle_id', -14)
                ->unnest();
            $where->and;
            $where->equalTo('orderclothes.fabric_status', 2);
        }
        else {
            $where->equalTo('orderclothes.status_id', $order_status);
        }

        $select->where($where);
        $select->order($orderby);

        if ($rowCount < 0)
            $select->offset(0);
        else
            $select->limit($rowCount)->offset($offset);

        //you can check your query by echo-ing :
        // echo $select->getSqlString();
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }

    public function setFabricUrl($url){
        $sql = new Sql($this->tableGateway->adapter);
        $update = $sql->update();
        $update->table('fabricurl');
        $update->set(array(
            'url' => $url,
        ));
        $update->where(array('id' => 111));
        $statement = $sql->prepareStatementForSqlObject($update);

        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }

    public function getFabricUrl(){
        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from('fabricurl');

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet->current();
    }

    public function getProfitSum($from_date, $to_date, $seller_id){

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
            ->join('pricestable', 'fabricstable.fabric_class = pricestable.fabric_class')
            ->columns(array(new Expression("SUM(actual_amount) as sum_actual_cost, SUM(profit-discount_amount) as sum_actual_profit")
            ))
        ;

        $where = new Where();

        if($from_date!=''){
            $where->greaterThanOrEqualTo('dateofsale', $from_date);
            $where->and;
        }
        if($to_date!=''){
            $where->lessThanOrEqualTo('dateofsale', $to_date);
            $where->and;
        }
        if($seller_id!=0){
            $where->equalTo('seller_id', $seller_id);
            $where->and;
        }

        $where->greaterThan('orderclothes.id','0');
        $select->where($where);
        $select->group('orderclothes.id');

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;


    }

    public function getByDateAndSeller($from_date, $to_date, $seller_id){

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
            ->join('pricestable', 'fabricstable.fabric_class = pricestable.fabric_class and orderclothes.product_id=pricestable.cloth_type')
            ->columns(array(
                '*', new Expression("CONCAT(COALESCE(customer.firstname,''), ' ' , COALESCE(customer.lastname,'')) as customer_full_name,
                                    CONCAT(COALESCE(userstable.name,''), ' ' , COALESCE(userstable.surname,'')) as seller_full_name,
                                    cyclestable.arrive_deadline_date as cycle_date,
                                    orderclothes.id as my_id
                                    ")
            ))
        ;

        $where = new Where();

        if($from_date!=''){
            $where->greaterThanOrEqualTo('dateofsale', $from_date);
            $where->and;
        }
        if($to_date!=''){
            $where->lessThanOrEqualTo('dateofsale', $to_date);
            $where->and;
        }
        if($seller_id!=0){
            $where->equalTo('seller_id', $seller_id);
            $where->and;
        }

        $where->greaterThan('orderclothes.id','0');
        $select->where($where);
//        $select->group(array('orderclothes.id', 'price'));

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }

    public function getSellers()
    {
        $dbAdapter = $this->tableGateway->adapter;
        $sql       = 'SELECT * FROM userstable where access_type_id=1 ORDER BY id ASC';
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();

//        $selectData = array();
//
//        foreach ($result as $res) {
//            $selectData[$res['id']] = $res['name'];
//        }
        return $result;
    }

    public function checkDiscount($fabric, $cloth_type, $discount){

        $dbAdapter = $this->tableGateway->adapter;
        $sql       = "select (max_discount<".$discount.") as flag from pricestable
                        where cloth_type=".$cloth_type." and
                              fabric_class in
                              (select fabric_class from fabricstable where id='".$fabric."')";
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        $row = $resultSet->current();

        if($row->flag==1){
            return 'nothing';
        }

        return $row;
    }

    public function getFabric($fabric_id, $cloth_type){
        $sql = new Sql($this->tableGateway->adapter);

        $select = $sql->select();
        $select->from('fabricstable');

        $where = new  Where();
        $where->equalTo('id', $fabric_id);
        $select->where($where);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        $row = $resultSet->current();
        if (!$row) {
            return 'nothing';
        } else if($cloth_type!='' && $cloth_type!=0){

                $dbAdapter = $this->tableGateway->adapter;
                $sql2       = "select price from pricestable where cloth_type=".$cloth_type." and fabric_class in
                                (select fabric_class from fabricstable where id='".$fabric_id."')";
                $statement2 = $dbAdapter->query($sql2);
                $result2    = $statement2->execute();

                $resultSet2 = new ResultSet();
                $resultSet2->initialize($result2);

                $row2 = $resultSet2->current();
                return $row2;
        }
        return $row;
    }

    public function getClientName2($orderId){
        $id = (int)$orderId;
        $sql = new Sql($this->tableGateway->adapter);

        $select = $sql->select();
        $select->from($this->tableGateway->table)
            ->join('ordertable', 'orderclothes.order_id = ordertable.id')
            ->join('customer', 'ordertable.customer_id = customer.id')
        ;

        $where = new  Where();
        $where->equalTo('orderclothes.order_id', $id); //
        $select->where($where);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        $row = $resultSet->current();
        if (!$row) {
            throw new \Exception("client name Could not find row $id");
        }
        return $row;
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
        $where->equalTo('orderclothes.id', $id); // orderclothes.order_id
        $select->where($where);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        $row = $resultSet->current();
        if (!$row) {
            throw new \Exception("client name Could not find row $id");
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

    public function getOrderClothesByOrderId($id){

        $id = (int)$id;
        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from($this->tableGateway->table);

        $where = new  Where();
        $where->equalTo('orderclothes.order_id', $id);
        $select->where($where);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

//        $resultSet = new ResultSet();
//        $resultSet->initialize($result);

        $selectData = array();

        foreach ($result as $res) {
//            $selectData[$res['typeof_measure']] = $res['id'];
            array_push($selectData, $res);
        }

        return $selectData;
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

    public function setArrived($id, $comment){
        $id = (int)$id;
        $sql = new Sql($this->tableGateway->adapter);
        $update = $sql->update();
        $update->table('orderclothes');
        $update->set(array(
            'arrival_date' => date("Y-m-d"),
            'arrival_comment' => $comment,
            'status_id' => 7
        ));
        $update->where(array('id' => $id));
        $statement = $sql->prepareStatementForSqlObject($update);

        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }

    public function feedback($id, $comment){
        $id = (int)$id;
        $sql = new Sql($this->tableGateway->adapter);
        $update = $sql->update();
        $update->table('orderclothes');
        $update->set(array(
            'arrival_comment' => $comment,
            'status_id' => 13
        ));
        $update->where(array('id' => $id));
        $statement = $sql->prepareStatementForSqlObject($update);

        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
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
        $update->where(array('order_id' => $id, 'status_id' => 7));
        $statement = $sql->prepareStatementForSqlObject($update);

        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }

    public function giveToClient($id, $comment){

        $id = (int)$id;
        $sql = new Sql($this->tableGateway->adapter);
        $update = $sql->update();
        $update->table('orderclothes');
        $update->set(array(
            'finished_date' => date('Y-m-d'),
            'status_id' => 9,
            'arrival_comment' => $comment
        ));
        $update->where(array('id' => $id));
        $statement = $sql->prepareStatementForSqlObject($update);

        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }

    public function giveToTailor($id, $tailorId, $arrival_comment){
        $id = (int)$id;
        $sql = new Sql($this->tableGateway->adapter);
        $update = $sql->update();
        $update->table('orderclothes');
        $update->set(array(
            'tailor_id' => $tailorId,
            'status_id' => 12,
            'arrival_comment' => $arrival_comment
        ));
        $update->where(array('id' => $id));
        $statement = $sql->prepareStatementForSqlObject($update);

        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }

    public function rollBackOrder($id, $comment){

        $dbAdapter = $this->tableGateway->adapter;
        $sql       = "update orderclothes set seller_comment=CONCAT(COALESCE(seller_comment, ''), ' Проверка продавцом не успешна. Причина: ".$comment."'), status_id=1 where id=".$id;
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
            'status_id' => 2
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
//            'pricelistnum' => $orderclothes->pricelistnum,
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
            'discount_amount' => $orderclothes->discount_amount,
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

    public function accounting($order){

        $order_id=$order->id;

        $order_clothes = $this->getOrderClothesByOrderId($order_id);

        foreach($order_clothes as $order_cloth){

            $date_od_sale=$order->dateofsale;
            $oper_date=date('Y-m-d');

            $client_name=$this->getClientName($order_cloth['id']);
            $client_name = $client_name->lastname.' '.$client_name->firstname.' '.$client_name->middlename;

            $seller_name=$this->getSellerName($order->id);
            $seller_name=$seller_name->name." ".$seller_name->surname;

            $cloth_type='';
            switch($order_cloth['product_id']){
                case '1': $cloth_type='Пиджак'; break;
                case '2': $cloth_type='Брюки'; break;
                case '4': $cloth_type='Сорочка'; break;
                case '3': $cloth_type='Жилетка'; break;
                case '5': $cloth_type='Пальто'; break;
                case '7': $cloth_type='Бабочка'; break;
                case '6': $cloth_type='Кушак'; break;
                case '8': $cloth_type='Плоточек'; break;
                case '9': $cloth_type='Ворот'; break;
                case '10': $cloth_type='Манжет'; break;
            }

            $fabric_class=$this->getFabricClass($order_cloth['id']);
            $fabric_class=$fabric_class->fabric_class;

            $official_price=$order_cloth['official_price'];

            $max_discount=$this->getMaxDiscount($fabric_class, $order_cloth['product_id']);

            $actual_price=$order_cloth['actual_amount'];
            $details=$order_cloth['paytype_id'];

            $oper_comment = "
                            Номер заказа:".$order_id."<br>
                            Дата продажи:".$date_od_sale."<br>
                            Дата операции:".$oper_date."<br>
                            Клиент:".$client_name."<br>
                            Продавец:".$seller_name."<br>
                            Тип изделия:".$cloth_type."<br>
                            Класс ткани:".$fabric_class."<br>
                            Официальная цена:".$official_price."<br>
                            Фактическая сумма отплаты (со скидкой):".$actual_price."<br>
                            Подробности оплаты:".$details."
                            ";

            $sql2 = new Sql($this->tableGateway->adapter);
            $select2 = $sql2->select();
            $select2->from('accounting')
                ->columns(array(new Expression("SUM(oper_cost) as total_cost")
                ))
            ;
            $statement2 = $sql2->prepareStatementForSqlObject($select2);
            $result2 = $statement2->execute();
            $resultSet2 = new ResultSet();
            $resultSet2->initialize($result2);

            $cashbox = $resultSet2->current()->total_cost;
            if($cashbox==null) $cashbox=0;

            $cost=(int)$cashbox+(int)$order_cloth['actual_amount'];

            $dbAdapter = $this->tableGateway->adapter;
            $sql       = "insert into accounting (oper_date, oper_type, oper_comment, oper_cost, cashbox) VALUES
                          (
                            '".$oper_date."',
                            'Поступление',
                            '".$oper_comment."',
                            '".$order_cloth['actual_amount']."',
                            '".$cost."'
                            )";

            $statement = $dbAdapter->query($sql);
            $statement->execute();
        }
    }

    public function changeStatusTo11($order_id){


        $dbAdapter = $this->tableGateway->adapter;
        $sql2       = "select orderclothes.id, pricestable.price
                          FROM orderclothes
                          join fabricstable on orderclothes.textile_id=fabricstable.id
                          join pricestable on (orderclothes.product_id=pricestable.cloth_type and fabricstable.fabric_class=pricestable.fabric_class)
                        where orderclothes.order_id=".$order_id;
        $statement2 = $dbAdapter->query($sql2);
        $result2    = $statement2->execute();

        $ids = array();
        $prices = array();

        foreach ($result2 as $res) {
            array_push($ids, $res['id']);
            array_push($prices, $res['price']);
        }

        for($i=0; $i<count($ids); $i++){
            $sql       = "update orderclothes set official_price=".$prices[$i]." where id=".$ids[$i];
            $statement = $dbAdapter->query($sql);
            $statement->execute();
        }

        $sql       = "update orderclothes set status_id=11 where order_id=".$order_id." and status_id=14";
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;

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

        $sql2 = new Sql($this->tableGateway->adapter);
        $delete = $sql2->delete('orderclothsystemcodes');
        $delete->where(array('order_cloth_id' => $id));
        $statement2 = $sql2->prepareStatementForSqlObject($delete);
        $statement2->execute();

        $sql3 = new Sql($this->tableGateway->adapter);
        $delete3 = $sql3->delete('orderclothredactor');
        $delete3->where(array('order_cloth_id' => $id));
        $statement3 = $sql3->prepareStatementForSqlObject($delete3);
        $statement3->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }

    public function deleteOrderClothes($id)
    {
        $this->tableGateway->delete(array('id' => (int)$id));
    }

    public function setStatus15($id){
        $id = (int)$id;
        $sql = new Sql($this->tableGateway->adapter);
        $delete = $sql->delete('orderclothes')->where("id = $id");

        $statement = $sql->prepareStatementForSqlObject($delete);
        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }

    public function setStatus7($id){
        $id = (int)$id;
        $sql = new Sql($this->tableGateway->adapter);
        $update = $sql->update();
        $update->table('orderclothes');
        $update->set(array(
            'status_id' => 7
        ));

        $update->where(array('id' => $id));
        $statement = $sql->prepareStatementForSqlObject($update);
        $statement->execute();
    }

    public function setStatus17($id){
        $id = (int)$id;
        $sql = new Sql($this->tableGateway->adapter);
        $update = $sql->update();
        $update->table('orderclothes');
        $update->set(array(
            'status_id' => 17
        ));

        $update->where(array('id' => $id));
        $statement = $sql->prepareStatementForSqlObject($update);
        $statement->execute();
    }

    public function setStatus1($id){
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

    public function sendToSeller($id, $excel_order_id){

        $id = (int)$id;
        $sql = new Sql($this->tableGateway->adapter);
        $update = $sql->update();
        $update->table('orderclothes');
        $update->set(array(
            'status_id' => 3,
            'excel_order_id' => $excel_order_id
        ));

        $update->where(array('id' => $id));
        $statement = $sql->prepareStatementForSqlObject($update);
        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }

    public function backToRedactor($id, $assist_comment){

        $id = (int)$id;
        $sql = new Sql($this->tableGateway->adapter);
        $update = $sql->update();
        $update->table('orderclothes');
        $update->set(array(
            'status_id' => 1,
            'code_status' => 7,
        ));

        $update->where(array('id' => $id));
        $statement = $sql->prepareStatementForSqlObject($update);
        $statement->execute();

//        orderclothredactor

        $text = "\nПомошник вернул, причина: ";

        $dbAdapter = $this->tableGateway->adapter;
        $sql       = "update orderclothredactor set redactor_comment=CONCAT(COALESCE(redactor_comment, ''), '".$text.$assist_comment."') where order_cloth_id=".$id;
        $statement = $dbAdapter->query($sql);
        $statement->execute();
    }

    public function setStatus3($id){
        $id = (int)$id;
        $sql = new Sql($this->tableGateway->adapter);
        $update = $sql->update();
        $update->table('orderclothes');
        $update->set(array(
            'status_id' => 3
        ));

        $update->where(array('id' => $id));
        $statement = $sql->prepareStatementForSqlObject($update);
        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }

    public function setStatus4($id){
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

    public function setStatus10redactor($orderId){
        $id = (int)$orderId;
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

        $sql2 = new Sql($this->tableGateway->adapter);
        $delete = $sql2->delete('orderclothsystemcodes');
        $delete->where(array('order_cloth_id' => $id));
        $statement2 = $sql2->prepareStatementForSqlObject($delete);
        $statement2->execute();

        $sql3 = new Sql($this->tableGateway->adapter);
        $delete3 = $sql3->delete('orderclothredactor');
        $delete3->where(array('order_cloth_id' => $id));
        $statement3 = $sql3->prepareStatementForSqlObject($delete3);
        $statement3->execute();

//        $dbAdapter = $this->tableGateway->adapter;
//        $sql2       = "delete from orderclothsystemcodes where fabric_class='".$fabric_class."' and cloth_type=".$cloth_type;
//        $statement2 = $dbAdapter->query($sql2);
//        $statement2->execute();

        return $resultSet;
    }

    public function setStatus10($orderId){
        $id = (int)$orderId;
        $sql = new Sql($this->tableGateway->adapter);
        $update = $sql->update();
        $update->table('orderclothes');
        $update->set(array(
            'status_id' => 10
        ));
        $update->where(array('order_id' => $id));
        $statement = $sql->prepareStatementForSqlObject($update);

        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }

    public function getSellerName($orderClothId){
        $id = (int)$orderClothId;
        $sql = new Sql($this->tableGateway->adapter);

        $select = $sql->select();
        $select->from('ordertable')
            ->join('userstable', 'ordertable.seller_id = userstable.id')
        ;

        $where = new  Where();
        $where->equalTo('ordertable.id', $id);
        $select->where($where);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        $row = $resultSet->current();
        if (!$row) {
            throw new \Exception("seller name Could not find row $id");
        }
        return $row;
    }

    public function getFabricClass($orderClothId){
        $id = (int)$orderClothId;
        $sql = new Sql($this->tableGateway->adapter);

        $select = $sql->select();
        $select->from($this->tableGateway->table)
            ->join('fabricstable', 'orderclothes.textile_id = fabricstable.id')
        ;

        $where = new  Where();
        $where->equalTo('orderclothes.id', $id);
        $select->where($where);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        $row = $resultSet->current();
        if (!$row) {
            throw new \Exception("fabric class Could not find row $id");
        }
        return $row;
    }

    public function getMaxDiscount($fabric_class, $cloth_type){

        $dbAdapter = $this->tableGateway->adapter;
        $sql       = "select * from pricestable where fabric_class='".$fabric_class."' and cloth_type=".$cloth_type;
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        $row = $resultSet->current();
        if (!$row) {
            throw new \Exception("max discount Could not find row");
        }
        return $row;
    }

    public function setFabricReady($id){
        $id = (int)$id;
        $sql = new Sql($this->tableGateway->adapter);
        $update = $sql->update();
        $update->table('orderclothes');
        $update->set(array(
            'fabric_status' => 3
        ));

        $update->where(array('id' => $id));
        $statement = $sql->prepareStatementForSqlObject($update);
        $statement->execute();
    }



    //  FUNCTIONS FOR MY DAY
    public function getFittings($from_date, $to_date){
        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from($this->tableGateway->table)
            ->join('clothers', 'orderclothes.product_id = clothers.id')
            ->join('ordertable', 'orderclothes.order_id = ordertable.id')
            ->join('userstable', 'ordertable.seller_id = userstable.id')
            ->join('customer', 'ordertable.customer_id = customer.id')
            ->columns(array(
                '*', new Expression("CONCAT(COALESCE(customer.firstname,''), ' ' , COALESCE(customer.lastname,'')) as customer_full_name,
                                    CONCAT(userstable.name, ' ' , userstable.surname) as seller_full_name
                                    ")
            ));

        $where = new Where();

        $today = date('Y-m-d');

        if($from_date!=''){
            $where->greaterThanOrEqualTo('fitting_date', $from_date);
            $where->and;
        }
        if($to_date!=''){
            $where->lessThanOrEqualTo('fitting_date', $to_date);
            $where->and;
        }
        if($from_date=='' && $to_date==''){
//            $where->greaterThanOrEqualTo('fitting_date', date_format(date_sub(date_create(date('Y-m-d')),date_interval_create_from_date_string("10 days")),"Y-m-d"));
//            $where->and;
//            $where->lessThanOrEqualTo('fitting_date', date('Y-m-d'));
//            $where->and;
            $where->equalTo('fitting_date', date('Y-m-d'));
            $where->and;
        }

        $where->isNotNull('fitting_date');
        $select->where($where);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }
    public function getAppoints($from_date, $to_date){
        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from('phonecalls')
            ->join('userstable', 'phonecalls.seller_id = userstable.id')
            ->join('customer', 'phonecalls.client_id = customer.id')
            ->columns(array(
                '*', new Expression("CONCAT(COALESCE(customer.firstname,''), ' ' , COALESCE(customer.lastname,'')) as customer_full_name,
                                    CONCAT(userstable.name, ' ' , userstable.surname) as seller_full_name
                                    ")
            ));

        $where = new Where();

        if($from_date!=''){
            $where->greaterThanOrEqualTo('appoint_date', $from_date);
            $where->and;
        }
        if($to_date!=''){
            $where->lessThanOrEqualTo('appoint_date', $to_date);
            $where->and;
        }
        if($from_date=='' && $to_date==''){
//            $where->greaterThanOrEqualTo('appoint_date', date_format(date_sub(date_create(date('Y-m-d')),date_interval_create_from_date_string("10 days")),"Y-m-d"));
//            $where->and;
//            $where->lessThanOrEqualTo('appoint_date', date('Y-m-d'));
//            $where->and;
            $where->equalTo('appoint_date', date('Y-m-d'));
            $where->and;
        }

        $where->isNotNull('appoint_date');
        $select->where($where);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }
    public function getReminds($from_date, $to_date){
        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from('phonecalls')
            ->join('userstable', 'phonecalls.seller_id = userstable.id')
            ->join('customer', 'phonecalls.client_id = customer.id')
            ->columns(array(
                '*', new Expression("CONCAT(COALESCE(customer.firstname,''), ' ' , COALESCE(customer.lastname,'')) as customer_full_name,
                                    CONCAT(userstable.name, ' ' , userstable.surname) as seller_full_name
                                    ")
            ));

        $where = new Where();

        if($from_date!=''){
            $where->greaterThanOrEqualTo('remind_date', $from_date);
            $where->and;
        }
        if($to_date!=''){
            $where->lessThanOrEqualTo('remind_date', $to_date);
            $where->and;
        }
        if($from_date=='' && $to_date==''){
//            $where->greaterThanOrEqualTo('remind_date', date_format(date_sub(date_create(date('Y-m-d')),date_interval_create_from_date_string("10 days")),"Y-m-d"));
//            $where->and;
//            $where->lessThanOrEqualTo('remind_date', date('Y-m-d'));
//            $where->and;
            $where->equalTo('remind_date', date('Y-m-d'));
            $where->and;
        }

        $where->isNotNull('remind_date');
        $select->where($where);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }
    public function getBdays($from_date, $to_date){

        $dbAdapter = $this->tableGateway->adapter;
        $sql       = "select id, firstname, lastname, middlename, birthday, mobilephone, email, country, city, address, homephone, work, position, workaddress, workphone, DATE_FORMAT(birthday,'%m-%d') as bdmonthday from customer";
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();

        $bdtoday = array();

        $monthday = date('m-d');


        foreach ($result as $res) {
            if($from_date!='' && $to_date==''){
                $monthdayFrom = explode("-", $from_date)[1].'-'.explode("-", $from_date)[2];
                if($monthdayFrom<=$res['bdmonthday']) array_push($bdtoday, $res);
            }
            if($to_date!='' && $from_date==''){
                $monthdayTo = explode("-", $to_date)[1].'-'.explode("-", $to_date)[2];
                if($res['bdmonthday']<$monthdayTo) array_push($bdtoday, $res);
            }
            if($from_date=='' && $to_date==''){
                if($res['bdmonthday']==$monthday) array_push($bdtoday, $res);
            }
            if($from_date!='' && $to_date!=''){
                $monthdayFrom = explode("-", $from_date)[1].'-'.explode("-", $from_date)[2];
                $monthdayTo = explode("-", $to_date)[1].'-'.explode("-", $to_date)[2];
                if($monthdayFrom<=$res['bdmonthday'] && $res['bdmonthday']<$monthdayTo) array_push($bdtoday, $res);
            }
        }

        $finalres=$bdtoday;

        $resultSet = new ResultSet();
        $resultSet->initialize($finalres);

        return $resultSet;
    }
    public function getDeadlines($from_date, $to_date){
        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from($this->tableGateway->table)
            ->join('clothers', 'orderclothes.product_id = clothers.id')
            ->join('ordertable', 'orderclothes.order_id = ordertable.id')
            ->join('userstable', 'ordertable.seller_id = userstable.id')
            ->join('customer', 'ordertable.customer_id = customer.id')
            ->columns(array(
                '*', new Expression("CONCAT(COALESCE(customer.firstname,''), ' ' , COALESCE(customer.lastname,'')) as customer_full_name,
                                    CONCAT(userstable.name, ' ' , userstable.surname) as seller_full_name
                                    ")
            ));

        $where = new Where();

        if($from_date!=''){
            $where->greaterThanOrEqualTo('preferred_date', $from_date);
            $where->and;
        }
        if($to_date!=''){
            $where->lessThanOrEqualTo('preferred_date', $to_date);
            $where->and;
        }
        if($from_date=='' && $to_date==''){
//            $where->greaterThanOrEqualTo('preferred_date', date_format(date_sub(date_create(date('Y-m-d')),date_interval_create_from_date_string("10 days")),"Y-m-d"));
//            $where->and;
//            $where->lessThanOrEqualTo('preferred_date', date('Y-m-d'));
//            $where->and;
            $where->equalTo('preferred_date', date('Y-m-d'));
            $where->and;
        }

        $where->isNotNull('preferred_date');
        $select->where($where);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }
    public function getStartcycles($from_date, $to_date){
        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from('cyclestable');

        $where = new Where();

        if($from_date!=''){
            $where->greaterThanOrEqualTo('order_accept_start_date', $from_date);
            $where->and;
        }
        if($to_date!=''){
            $where->lessThanOrEqualTo('order_accept_start_date', $to_date);
            $where->and;
        }
        if($from_date=='' && $to_date==''){
//            $where->greaterThanOrEqualTo('order_accept_start_date', date_format(date_sub(date_create(date('Y-m-d')),date_interval_create_from_date_string("10 days")),"Y-m-d"));
//            $where->and;
//            $where->lessThanOrEqualTo('order_accept_start_date', date_format(date_add(date_create(date('Y-m-d')),date_interval_create_from_date_string("10 days")),"Y-m-d")); // date_add
//            $where->and;
            $where->equalTo('order_accept_start_date', date('Y-m-d'));
            $where->and;
        }

        $where->isNotNull('order_accept_start_date');
        $select->where($where);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }
    public function getEndcycles($from_date, $to_date){
        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from('cyclestable');

        $where = new Where();

        if($from_date!=''){
            $where->greaterThanOrEqualTo('order_accept_finish_date', $from_date);
            $where->and;
        }
        if($to_date!=''){
            $where->lessThanOrEqualTo('order_accept_finish_date', $to_date);
            $where->and;
        }
        if($from_date=='' && $to_date==''){
//            $where->greaterThanOrEqualTo('order_accept_finish_date', date_format(date_sub(date_create(date('Y-m-d')),date_interval_create_from_date_string("10 days")),"Y-m-d"));
//            $where->and;
//            $where->lessThanOrEqualTo('order_accept_finish_date', date_format(date_add(date_create(date('Y-m-d')),date_interval_create_from_date_string("10 days")),"Y-m-d"));
//            $where->and;
            $where->equalTo('order_accept_finish_date', date('Y-m-d'));
            $where->and;
        }

        $where->isNotNull('order_accept_finish_date');
        $select->where($where);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }
    public function getClothes($from_date, $to_date){
        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from($this->tableGateway->table)
            ->join('clothers', 'orderclothes.product_id = clothers.id')
            ->join('ordertable', 'orderclothes.order_id = ordertable.id')
            ->join('userstable', 'ordertable.seller_id = userstable.id')
            ->join('customer', 'ordertable.customer_id = customer.id')
            ->columns(array(
                '*', new Expression("CONCAT(COALESCE(customer.firstname,''), ' ' , COALESCE(customer.lastname,'')) as customer_full_name,
                                    CONCAT(userstable.name, ' ' , userstable.surname) as seller_full_name
                                    ")
            ));

        $where = new Where();

        if($from_date!=''){
            $where->greaterThanOrEqualTo('dateofsale', $from_date);
            $where->and;
        }
        if($to_date!=''){
            $where->lessThanOrEqualTo('dateofsale', $to_date);
            $where->and;
        }
        if($from_date=='' && $to_date==''){
//            $where->greaterThanOrEqualTo('dateofsale', date_format(date_sub(date_create(date('Y-m-d')),date_interval_create_from_date_string("5 days")),"Y-m-d"));
//            $where->and;
//            $where->lessThanOrEqualTo('dateofsale', date('Y-m-d'));
//            $where->and;
            $where->equalTo('dateofsale', date('Y-m-d'));
            $where->and;
        }

        $where->isNotNull('dateofsale');
        $select->where($where);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }
    public function getCerts($from_date, $to_date){
        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from('certificates')
            ->join('userstable', 'certificates.seller_id = userstable.id')
            ->columns(array(
                '*', new Expression("CONCAT(userstable.name, ' ' , userstable.surname) as seller_full_name
                                    ")
            ));

        $where = new Where();

        if($from_date!=''){
            $where->greaterThanOrEqualTo('given_date', $from_date);
            $where->and;
        }
        if($to_date!=''){
            $where->lessThanOrEqualTo('given_date', $to_date);
            $where->and;
        }
        if($from_date=='' && $to_date==''){

//            date_format(date_sub(date_create(date('Y-m-d')),date_interval_create_from_date_string("5 days")),"Y-m-d");
//            $where->greaterThanOrEqualTo('given_date', date_format(date_sub(date_create(date('Y-m-d')),date_interval_create_from_date_string("5 days")),"Y-m-d"));
//            $where->and;
//            $where->lessThanOrEqualTo('given_date', date('Y-m-d'));
//            $where->and;
            $where->equalTo('given_date', date('Y-m-d'));
            $where->and;
        }

        $where->isNotNull('given_date');
        $select->where($where);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }
    public function getGivenclothes($from_date, $to_date){
        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from($this->tableGateway->table)
            ->join('clothers', 'orderclothes.product_id = clothers.id')
            ->join('ordertable', 'orderclothes.order_id = ordertable.id')
            ->join('userstable', 'ordertable.seller_id = userstable.id')
            ->join('customer', 'ordertable.customer_id = customer.id')
            ->columns(array(
                '*', new Expression("CONCAT(COALESCE(customer.firstname,''), ' ' , COALESCE(customer.lastname,'')) as customer_full_name,
                                    CONCAT(userstable.name, ' ' , userstable.surname) as seller_full_name
                                    ")
            ));

        $where = new Where();

        if($from_date!=''){
            $where->greaterThanOrEqualTo('finished_date', $from_date);
            $where->and;
        }
        if($to_date!=''){
            $where->lessThanOrEqualTo('finished_date', $to_date);
            $where->and;
        }
        if($from_date=='' && $to_date==''){
//            $where->greaterThanOrEqualTo('finished_date', date_format(date_sub(date_create(date('Y-m-d')),date_interval_create_from_date_string("5 days")),"Y-m-d"));
//            $where->and;
//            $where->lessThanOrEqualTo('finished_date', date('Y-m-d'));
//            $where->and;
            $where->equalTo('finished_date', date('Y-m-d'));
            $where->and;
        }

        $where->isNotNull('finished_date');
        $select->where($where);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }
    public function getCalls($from_date, $to_date){
        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from('phonecalls')
            ->join('userstable', 'phonecalls.seller_id = userstable.id')
            ->join('customer', 'phonecalls.client_id = customer.id')
            ->columns(array(
                '*', new Expression("CONCAT(COALESCE(customer.firstname,''), ' ' , COALESCE(customer.lastname,'')) as customer_full_name,
                                    CONCAT(userstable.name, ' ' , userstable.surname) as seller_full_name
                                    ")
            ));

        $where = new Where();

        if($from_date!=''){
            $where->greaterThanOrEqualTo('call_date', $from_date);
            $where->and;
        }
        if($to_date!=''){
            $where->lessThanOrEqualTo('call_date', $to_date);
            $where->and;
        }
        if($from_date=='' && $to_date==''){
//            $where->greaterThanOrEqualTo('call_date', date_format(date_sub(date_create(date('Y-m-d')),date_interval_create_from_date_string("5 days")),"Y-m-d"));
//            $where->and;
//            $where->lessThanOrEqualTo('call_date', date('Y-m-d'));
//            $where->and;
            $where->equalTo('call_date', date('Y-m-d'));
            $where->and;
        }

        $where->isNotNull('call_date');
        $select->where($where);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }
    //  FUNCTIONS FOR MY DAY


    // FUNCITONS FOR MY_DAY (REDACTOR)

    public function getSuperSubmit(){

        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from($this->tableGateway->table)
            ->join('clothers', 'orderclothes.product_id = clothers.id')
            ->join('ordertable', 'orderclothes.order_id = ordertable.id')
            ->join('customer', 'ordertable.customer_id = customer.id')
            ->join('fabricstable', 'orderclothes.textile_id = fabricstable.id')
            ->join('clothstatustable', 'orderclothes.status_id=clothstatustable.id')
            ->columns(array(
                '*', new Expression("CONCAT(COALESCE(customer.firstname,''), ' ' , COALESCE(customer.lastname,'')) as customer_full_name
                                    ")
            ));

        $where = new Where();
        $where->in('status_id', [1,2,3,4,10,11])
            ->and
            ->equalTo('cycle_id', '-10');

        $select->where($where);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }
    public function getSuperShip(){

        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from($this->tableGateway->table)
            ->join('clothers', 'orderclothes.product_id = clothers.id')
            ->join('ordertable', 'orderclothes.order_id = ordertable.id')
            ->join('customer', 'ordertable.customer_id = customer.id')
            ->join('fabricstable', 'orderclothes.textile_id = fabricstable.id')
            ->join('clothstatustable', 'orderclothes.status_id=clothstatustable.id')
            ->columns(array(
                '*', new Expression("CONCAT(COALESCE(customer.firstname,''), ' ' , COALESCE(customer.lastname,'')) as customer_full_name
                                    ")
            ));

        $where = new Where();
        $where->in('status_id', [5])
            ->and
            ->equalTo('cycle_id', '-10');

        $select->where($where);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }

    public function getFastSubmit(){

        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from($this->tableGateway->table)
            ->join('clothers', 'orderclothes.product_id = clothers.id')
            ->join('ordertable', 'orderclothes.order_id = ordertable.id')
            ->join('customer', 'ordertable.customer_id = customer.id')
            ->join('fabricstable', 'orderclothes.textile_id = fabricstable.id')
            ->join('clothstatustable', 'orderclothes.status_id=clothstatustable.id')
            ->columns(array(
                '*', new Expression("CONCAT(COALESCE(customer.firstname,''), ' ' , COALESCE(customer.lastname,'')) as customer_full_name
                                    ")
            ));

        $where = new Where();
        $where->in('status_id', [1,2,3,4,10,11])
            ->and
            ->equalTo('cycle_id', '-14');

        $select->where($where);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }
    public function getFastShip(){
        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from($this->tableGateway->table)
            ->join('clothers', 'orderclothes.product_id = clothers.id')
            ->join('ordertable', 'orderclothes.order_id = ordertable.id')
            ->join('customer', 'ordertable.customer_id = customer.id')
            ->join('fabricstable', 'orderclothes.textile_id = fabricstable.id')
            ->join('clothstatustable', 'orderclothes.status_id=clothstatustable.id')
            ->columns(array(
                '*', new Expression("CONCAT(COALESCE(customer.firstname,''), ' ' , COALESCE(customer.lastname,'')) as customer_full_name
                                    ")
            ));

        $where = new Where();
        $where->in('status_id', [5])
            ->and
            ->equalTo('cycle_id', '-14');

        $select->where($where);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }

    public function getSlowSubmit($from_date, $to_date){

        $dbAdapter = $this->tableGateway->adapter;
        $sql       = 'select * from cyclestable where 1=1 ';

        if($from_date!=''){
            $sql = $sql." and submit_deadline_date>='".$from_date."'";
        }
        if($to_date!=''){
            $sql = $sql." and submit_deadline_date<='".$to_date."'";
        }
        if($from_date=='' && $to_date==''){
            // DATE_SUB(current_date(), INTERVAL 10 DAY)
            $sql = $sql." and submit_deadline_date>=DATE_SUB(curdate(), INTERVAL 10 DAY) and submit_deadline_date<=DATE_ADD(curdate(), INTERVAL 10 DAY)";
//            $sql = $sql." and submit_deadline_date=curdate()";
        }

        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }
    public function getSlowShip($from_date, $to_date){

        $dbAdapter = $this->tableGateway->adapter;
        $sql       = 'select * from cyclestable where 1=1 ';

        if($from_date!=''){
            $sql = $sql." and ship_deadline_date>='".$from_date."'";
        }
        if($to_date!=''){
            $sql = $sql." and ship_deadline_date<='".$to_date."'";
        }
        if($from_date=='' && $to_date==''){
            $sql = $sql." and ship_deadline_date>=DATE_SUB(curdate(), INTERVAL 10 DAY) and ship_deadline_date<=DATE_ADD(curdate(), INTERVAL 10 DAY)";
//            $sql = $sql." and ship_deadline_date=curdate()";
        }

        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }

    // FUNCITONS FOR MY_DAY (REDACTOR)




    // FUNCTIONS FOR SMS
    public function getRedactorNums(){
        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from('userstable');

        $where = new Where();
        $where->equalTo('access_type_id', 2);
        $select->where($where);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }
    public function getDirectorNums(){
        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from('userstable');

        $where = new Where();
        $where->equalTo('access_type_id', 4);
        $select->where($where);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }
    // FUNCTIONS FOR SMS


    public function getOrderSum($order_id){
        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from($this->tableGateway->table);

        $where = new Where();
        $where->equalTo('order_id', $order_id);
        $select->where($where);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }

    public function changeCodeStatus($id, $status){
        $id = (int)$id;
        $sql = new Sql($this->tableGateway->adapter);
        $update = $sql->update();
        $update->table('orderclothes');
        $update->set(array(
            'code_status' => $status
        ));
        $update->where(array('id' => $id));
        $statement = $sql->prepareStatementForSqlObject($update);

        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }

    public function changeFabricStatus($id, $status){
        $id = (int)$id;
        $sql = new Sql($this->tableGateway->adapter);
        $update = $sql->update();
        $update->table('orderclothes');
        $update->set(array(
            'fabric_status' => $status
        ));
        $update->where(array('id' => $id));
        $statement = $sql->prepareStatementForSqlObject($update);

        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }

    public function sendToAssistant($id, $assist_id){
        $id = (int)$id;
        $sql = new Sql($this->tableGateway->adapter);
        $update = $sql->update();
        $update->table('orderclothes');
        $update->set(array(
            'status_id' => 16,
            'assist_id' => $assist_id
        ));
        $update->where(array('id' => $id));
        $statement = $sql->prepareStatementForSqlObject($update);

        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }

    public function addRequest($request_type, $request_body){
        $dbAdapter = $this->tableGateway->adapter;
        $sql       = "insert into requests (request_date, request_type, request_body) VALUES
                          (
                            '".date('Y-m-d')."',
                            '".$request_type."',
                            '".$request_body."'
                            )";

        $statement = $dbAdapter->query($sql);
        $statement->execute();
    }

    public function getTailorsList(){

        $tailors=array();

        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from('tailorstable');

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        foreach($resultSet as $res) {
            $tailors[$res['id']] = $res['name'];
        }

        return $tailors;
    }


    public function getAssistantsList(){

        $assistants=array();

        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from('userstable');
        $select->where(array('access_type_id'=>'5'));

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        foreach($resultSet as $res) {
            $assistants[$res['id']] = $res['name'];
        }

        return $assistants;
    }

    public function getSellersList(){

        $sellers=array();

        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from('userstable');
        $select->where(array('access_type_id'=>'1'));

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        foreach($resultSet as $res) {
            $sellers[$res['id']] = $res['name'].' '.$res['surname'];
        }

        return $sellers;
    }

    public function getPenaltiesList(){

        $penalties=array();

        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from('penalties');

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        foreach($resultSet as $res) {
            $penalties[$res['penalty_name']] = $res['penalty_comment'];
        }

        return $penalties;
    }

    public function getCyclesAndAcceptDates(){

        $results=array();

        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from('cyclestable');

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        foreach($resultSet as $res) {
            $results[$res['id']] = $res['order_accept_finish_date'];
        }

        return $results;
    }
}