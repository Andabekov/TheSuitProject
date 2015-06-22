<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 17/06/2015
 * Time: 16:48
 */

namespace Pidzhak\Model\accountant;

use Pidzhak\Model\accountant\Certificate;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Sql;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class CertificateTable
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
//        $resultSet = $this->tableGateway->select(function (Select $select) use ($rowCount, $offset) {
//            $select->limit($rowCount)->offset($offset);
//        });

        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from($this->tableGateway->table)
            ->join('userstable', 'certificates.seller_id = userstable.id')
            ->columns(array(
                '*', new Expression("curdate()>certificates.valid_date as valid,
                                    CONCAT(userstable.name, ' ', userstable.surname) as seller_name,
                                    certificates.id as my_id
                                    ")
            ))
        ;

        if ($rowCount < 0)
            $select->offset(0);
        else
            $select->limit($rowCount)->offset($offset);
        $select->order($orderby);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }

    public function getCount()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet->count();
    }

    public function changeStatus($id){

        $id = (int)$id;
        $sql = new Sql($this->tableGateway->adapter);
        $update = $sql->update();
        $update->table('certificates');
        $update->set(array(
            'status' => 0
        ));
        $update->where(array('id' => $id));
        $statement = $sql->prepareStatementForSqlObject($update);

        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }

    public function getCertificate($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveCertificate(Certificate $certificate)
    {
        $data = array(
            'given_date' => $certificate->given_date,
            'valid_date' => $certificate->valid_date,
            'seller_id' => $certificate->seller_id,
            'comment' => $certificate->comment,
            'cost' => $certificate->cost,
            'cert_num' => $certificate->cert_num,
        );

        $id = (int) $certificate->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getCertificate($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('financeOperations id does not exist');
            }
        }
    }

    public function deleteCertificate($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }

    public function confirm($id){
        $id = (int)$id;
        $sql = new Sql($this->tableGateway->adapter);
        $update = $sql->update();
        $update->table('certificates');
        $update->set(array(
            'status' => 0
        ));
        $update->where(array('id' => $id));
        $statement = $sql->prepareStatementForSqlObject($update);

        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        return $resultSet;
    }
}