<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 17/04/2015
 * Time: 12:46
 */

namespace Pidzhak\Controller\admin;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class CycleRestController extends AbstractRestfulController
{
    protected $cycleTable;

    public function create($data)
    {
        $current = $data['current'];
        $rowCount = $data['rowCount'];
        $sort = $data['sort'];
        $sortField = key($sort);
        $sortType = $sort[$sortField];
        $searchPhrase = $data['searchPhrase'];
        $id = $data['id'];
        $active_cycles = $data['active_cycles'];

        $offset = intval($rowCount) * (intval($current)-1);
        $cycles= $this->getCycleTable()->fetchPage(intval($rowCount), $offset, $sortField.' '.$sortType, $searchPhrase, $active_cycles);
        $count= $this->getCycleTable()->getCount();

        return new JsonModel(array(
            'current' => intval($current),
            'rowCount' => intval($rowCount),
            'rows' => $cycles->toArray(),
            "total"=> $count,
        ));
    }

    /*Inversion of Control*/
    public function getCycleTable()
    {
        if (!$this->cycleTable) {
            $sm = $this->getServiceLocator();
            $this->cycleTable = $sm->get('Pidzhak\Model\admin\CycleTable');
        }
        return $this->cycleTable;
    }
}