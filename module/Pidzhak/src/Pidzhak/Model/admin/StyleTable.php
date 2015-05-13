<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 17/04/2015
 * Time: 10:56
 */

namespace Pidzhak\Model\admin;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class StyleTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select(new Expression('DISTINCT style_num, cloth_type'));
        return $resultSet;
    }

    public function fetchPage($rowCount, $offset, $orderby, $searchPhrase){

        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from($this->tableGateway->table)
            ->join('clothers', 'stylestable.cloth_type = clothers.id');
        $select->columns(array(new Expression('DISTINCT style_num, cloth_type')));

        if ($rowCount < 0)
            $select->offset(0);
        else
            $select->limit($rowCount)->offset($offset);
        $select->order($orderby);

        /*if($searchPhrase)
            $select->where->like('firstname', '%'.strtolower($searchPhrase).'%')->OR->like('lastname', '%'.strtolower($searchPhrase).'%');*/

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

//        var_dump($resultSet);

        return $resultSet;
    }

    public function getCount(){
        $resultSet = $this->tableGateway->select();
        return $resultSet->count();
    }

    public function getStyle($id) {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function getStyleByIdAndClothType($id, $clothType){
        $id = (int)$id;
        $clothType = (int)$clothType;

        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from($this->tableGateway->table);

        $where = new  Where();
        $where->equalTo('style_num', $id);
        $where->equalTo('cloth_type', $clothType);
        $select->where($where);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);

//        $row = $resultSet->current();

//        var_dump($resultSet->current());

//        if (!$row) {
//            throw new \Exception("Could not find row $id");
//        }
        return $resultSet;
    }

    public function saveStyle(Style $style)
    {
        $data = array(
            'style_num'  => $style->style_num,
            'cloth_type' => $style->cloth_type,
            'style_code' => $style->style_code,
            'style_code_fabric' => $style->style_code_fabric,
            'style_code_desc' => $style->style_code_desc,
        );

        $id = (int) $style->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getStyle($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Style_id does not exist');
            }
        }
    }

    public function deleteStyle($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }
}