<?php
namespace Pidzhak\Model\Seller;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Form\Element\DateTime;

class CustomerTable
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

    public function fetchPage($rowCount, $offset, $orderby, $searchPhrase, $bdmode){

        if($bdmode==-1){
            $resultSet = $this->tableGateway->select(function(Select $select) use ($rowCount, $offset, $orderby, $searchPhrase){
                if($rowCount<0)
                    $select->offset(0);
                else
                    $select->limit($rowCount)->offset($offset);
                $select->order($orderby);

                if($searchPhrase)
                    $select->where->like('firstname', '%'.strtolower($searchPhrase).'%')->OR->like('lastname', '%'.strtolower($searchPhrase).'%');
            });
        } else {

            $dbAdapter = $this->tableGateway->adapter;
            $sql       = "select id, firstname, lastname, middlename, birthday, mobilephone, email, country, city, address, homephone, work, position, workaddress, workphone, DATE_FORMAT(birthday,'%m-%d') as bdmonthday from customer";
            $statement = $dbAdapter->query($sql);
            $result    = $statement->execute();

            $bdtoday = array();
            $bd3 = array();
            $bd10 = array();

            $monthday = date('m-d');
            $month = date('m');
            $day = date('d');
            $day3_1 = $day-3;
            $day3_2 = $day+3;
            $day10_1 = $day-10;
            $day10_2 = $day+10;

            foreach ($result as $res) {
                if($res['bdmonthday']==$monthday) array_push($bdtoday, $res);
                if($month.'-'.$day3_1<$res['bdmonthday'] && $res['bdmonthday']<$month.'-'.$day3_2) array_push($bd3, $res);
                if($month.'-'.$day10_1<$res['bdmonthday'] && $res['bdmonthday']<$month.'-'.$day10_2) array_push($bd10, $res);
            }

            if($bdmode==0)
                $finalres=$bdtoday;
            else if($bdmode==1)
                $finalres=$bd3;
            else
                $finalres=$bd10;

            $resultSet = new ResultSet();
            $resultSet->initialize($finalres);

        }

        return $resultSet;
    }

    public function getCount(){
        $resultSet = $this->tableGateway->select();
        return $resultSet->count();
    }

    public function getCustomer($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveCustomer(Customer $customer)
    {
        $retval = null;
        $data = array(
            'firstname' => $customer->firstname,
            'lastname' => $customer->lastname,
            'middlename' => $customer->middlename,
            'birthday' => $customer->birthday,
            'mobilephone' => $customer->mobilephone,
            'email' => $customer->email,
            'country' => $customer->country,
            'city' => $customer->city,
            'address' => $customer->address,
            'homephone' => $customer->homephone,
            'work' => $customer->work,
            'position' => $customer->position,
            'workaddress' => $customer->workaddress,
            'workphone' => $customer->workphone,
        );

        $id = (int) $customer->id;
        if ($id == 0) {
            $retval = $this->tableGateway->insert($data);
        } else {
            if ($this->getCustomer($id)) {
                $retval = $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Customer id does not exist');
            }
        }

        return $retval;
    }

    public function deleteCustomer($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }

    public function insertedCustomer()
    {
        return $this->tableGateway->lastInsertValue;
    }
}