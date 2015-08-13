<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 17/06/2015
 * Time: 16:48
 */

namespace Pidzhak\Model\seller;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Sql;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class FinanceOperationsTable
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
        $resultSet = $this->tableGateway->select(function (Select $select) use ($rowCount, $offset, $orderby, $searchPhrase) {
            $select
                ->limit($rowCount)
                ->offset($offset)
                ->order($orderby)
                ->where
                ->like('id', '%'.mb_strtolower($searchPhrase, 'UTF-8').'%')
                ->or
                ->like('oper_type', '%'.mb_strtolower($searchPhrase, 'UTF-8').'%')
                ->or
                ->like('oper_comment', '%'.mb_strtolower($searchPhrase, 'UTF-8').'%')
                ->or
                ->like('oper_status', '%'.mb_strtolower($searchPhrase, 'UTF-8').'%')
            ;
        });


        return $resultSet;
    }

    public function getCount()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet->count();
    }

    public function getFinanceOperations($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveFinanceOperations(FinanceOperations $financeOperations)
    {

        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from($this->tableGateway->table)
            ->columns(array(new Expression("SUM(oper_cost) as total_cost")
            ))
        ;
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        $cashbox = $resultSet->current()->total_cost;
        if($cashbox==null) $cashbox=0;

        $sign = '';
        switch($financeOperations->oper_type){
            case "Затраты": $sign='-'; break;
            case "Перевод (из кассы)": $sign='-'; break;
            case "Клиент заплатил карточкой": $sign='-'; break;
            case "Клиент должен денег": $sign='-'; break;
            case "Клиент взял рассрочку": $sign='-'; break;
            case "Клиент заплатил сертификатом": $sign='-'; break;
        }

        $data = array(
            'oper_date' => $financeOperations->oper_date,
            'oper_type' => $financeOperations->oper_type,
            'oper_comment' => $financeOperations->oper_comment,
            'oper_status' => $financeOperations->oper_status,
            'oper_cost' => $sign.$financeOperations->oper_cost,
            'cashbox' => (int)$cashbox+(int)($sign.$financeOperations->oper_cost),
        );

        $id = (int) $financeOperations->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getFinanceOperations($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('financeOperations id does not exist');
            }
        }
    }

    public function deleteFinanceOperations($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }

    public function confirm($id){
        $id = (int)$id;
        $sql = new Sql($this->tableGateway->adapter);
        $update = $sql->update();
        $update->table('accounting');
        $update->set(array(
            'oper_status' => 1
        ));
        $update->where(array('id' => $id));
        $statement = $sql->prepareStatementForSqlObject($update);

        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }
}