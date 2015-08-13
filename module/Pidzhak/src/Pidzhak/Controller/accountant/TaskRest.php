<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 17/06/2015
 * Time: 21:38
 */

namespace Pidzhak\Controller\accountant;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class TaskRest extends AbstractRestfulController
{
    protected $taskTable;

    public function create($data)
    {
        $current = $data['current'];
        $rowCount = $data['rowCount'];
        $sort = $data['sort'];
        $sortField = key($sort);
        $sortType = $sort[$sortField];
        $searchPhrase = $data['searchPhrase'];
        $id = $data['id'];
        $task_type = $data['task_type'];

        $offset = intval($rowCount) * (intval($current)-1);
        $customers= $this->getTaskTable()->fetchPage(intval($rowCount), $offset, $sortField.' '.$sortType, $searchPhrase, $task_type);
        $count= $this->getTaskTable()->getCount();

        return new JsonModel(array(
            'current' => intval($current),
            'rowCount' => intval($rowCount),
            'rows' => $customers->toArray(),
            "total"=> $count,
        ));
    }

    public function getTaskTable()
    {
        if (!$this->taskTable) {
            $sm = $this->getServiceLocator();
            $this->taskTable = $sm->get('Pidzhak\Model\accountant\TaskTable');
        }
        return $this->taskTable;
    }
}