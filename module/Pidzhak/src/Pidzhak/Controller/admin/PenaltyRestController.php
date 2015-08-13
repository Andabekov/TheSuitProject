<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 17/04/2015
 * Time: 15:46
 */

namespace Pidzhak\Controller\admin;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class PenaltyRestController extends AbstractRestfulController
{
    protected $penaltyTable;

    public function create($data)
    {
        $current = $data['current'];
        $rowCount = $data['rowCount'];
        $sort = $data['sort'];
        $sortField = key($sort);
        $sortType = $sort[$sortField];
        $searchPhrase = $data['searchPhrase'];
        $id = $data['id'];

        $offset = intval($rowCount) * (intval($current)-1);
        $fabrics= $this->getPenaltyTable()->fetchPage(intval($rowCount), $offset, $sortField.' '.$sortType, $searchPhrase);
        $count= $this->getPenaltyTable()->getCount();

        return new JsonModel(array(
            'current' => intval($current),
            'rowCount' => intval($rowCount),
            'rows' => $fabrics->toArray(),
            "total"=> $count,
        ));
    }

    /*Inversion of Control*/
    public function getPenaltyTable()
    {
        if (!$this->penaltyTable) {
            $sm = $this->getServiceLocator();
            $this->penaltyTable = $sm->get('Pidzhak\Model\admin\PenaltyTable');
        }
        return $this->penaltyTable;
    }
}