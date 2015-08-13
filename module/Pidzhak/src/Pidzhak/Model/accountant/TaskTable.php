<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 12/08/2015
 * Time: 13:53
 */

namespace Pidzhak\Model\accountant;

use Zend\Db\Sql\Predicate\Like;
use Zend\Db\Sql\Predicate\PredicateSet;
use Zend\Db\Sql\Sql;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class TaskTable
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

    public function fetchPage($rowCount, $offset, $orderby, $searchPhrase, $task_type)
    {
        $resultSet = $this->tableGateway->select(function(Select $select) use ($rowCount, $offset, $orderby, $searchPhrase, $task_type){
            if($rowCount<0)
                $select->offset(0);
            else
                $select->limit($rowCount)->offset($offset);

            if($task_type=='seller'){
                $select->where(array(
                    new PredicateSet(
                        array(
                            new Like('task_type', 'Продавцы'),
                            new Like('task_type', 'Все сотрудники'),
                        ),
                        PredicateSet::COMBINED_BY_OR
                    )));
            } else if($task_type=='redactor'){
                $select->where(array(
                    new PredicateSet(
                        array(
                            new Like('task_type', 'Редактор'),
                            new Like('task_type', 'Все сотрудники'),
                        ),
                        PredicateSet::COMBINED_BY_OR
                    )));
            }
            $select->order($orderby);

        });
        return $resultSet;
    }

    public function getCount(){
        $resultSet = $this->tableGateway->select();
        return $resultSet->count();
    }

    public function getTask($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveTask(Task $task){
        $data = array(
            'task_given_date' => $task->task_given_date,
            'task_due_date' => $task->task_due_date,
            'task_type' => $task->task_type,
            'task_body' => $task->task_body,
            'task_status' => $task->task_status,
        );

        $id = (int) $task->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getTask($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Task id does not exist');
            }
        }
    }

    public function setTaskStarted($id){

        $id = (int)$id;
        $sql = new Sql($this->tableGateway->adapter);
        $update = $sql->update();
        $update->table('tasks');
        $update->set(array(
            'task_status' => 'В процессе'
        ));
        $update->where(array('id' => $id));
        $statement = $sql->prepareStatementForSqlObject($update);

        $statement->execute();
    }

    public function setTaskFinished($id){
        $id = (int)$id;
        $sql = new Sql($this->tableGateway->adapter);
        $update = $sql->update();
        $update->table('tasks');
        $update->set(array(
            'task_status' => 'Выполнен'
        ));
        $update->where(array('id' => $id));
        $statement = $sql->prepareStatementForSqlObject($update);

        $statement->execute();
    }

    public function deleteTask($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }
}