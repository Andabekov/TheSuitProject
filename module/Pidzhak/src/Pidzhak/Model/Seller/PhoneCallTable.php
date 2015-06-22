<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 17/06/2015
 * Time: 16:48
 */

namespace Pidzhak\Model\Seller;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class PhoneCallTable
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

    public function fetchPage($rowCount, $offset, $orderby, $searchPhrase)
    {
        $resultSet = $this->tableGateway->select(function (Select $select) use ($rowCount, $offset) {
            $select->limit($rowCount)->offset($offset);
        });

        return $resultSet;
    }

    public function getCount()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet->count();
    }

    public function getPhoneCall($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function savePhoneCall(PhoneCall $call)
    {
        $data = array(
            'call_date' => $call->call_date,
            'appoint_date' => $call->appoint_date,
            'appoint_comment' => $call->appoint_comment,
            'remind_date' => $call->remind_date,
            'remind_comment' => $call->remind_comment,
            'media' => $call->media,
            'client_id' => $call->client_id,
            'seller_id' => $call->seller_id,
        );

        $id = (int) $call->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getPhoneCall($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('PhoneCall id does not exist');
            }
        }
    }

    public function deletePhoneCall($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }


    public function getCallsByDateAndSeller($from_date, $to_date, $seller_id){

        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from($this->tableGateway->table)
            ->join('userstable', 'phonecalls.seller_id = userstable.id')
            ->join('customer','phonecalls.client_id = customer.id')
            ->columns(array(
                '*', new Expression("CONCAT(COALESCE(customer.firstname,''), ' ' , COALESCE(customer.lastname,'')) as customer_full_name,
                                    CONCAT(userstable.name, ' ' , userstable.surname) as seller_full_name")
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
        if($seller_id!=0){
            $where->equalTo('seller_id', $seller_id);
            $where->and;
        }

        $where->greaterThan('phonecalls.id','0');
        $select->where($where);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }
}