<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 22/04/2015
 * Time: 14:37
 */

namespace Pidzhak\Controller\admin;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class SmsRestController extends AbstractRestfulController
{
    protected $smsTable;

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
        $smss= $this->getSmsTable()->fetchPage(intval($rowCount), $offset, $sortField.' '.$sortType, $searchPhrase);
        $count= $this->getSmsTable()->getCount();

        return new JsonModel(array(
            'current' => intval($current),
            'rowCount' => intval($rowCount),
            'rows' => $smss->toArray(),
            "total"=> $count,
        ));
    }

    /*Inversion of Control*/
    public function getSmsTable()
    {
        if (!$this->smsTable) {
            $sm = $this->getServiceLocator();
            $this->smsTable = $sm->get('Pidzhak\Model\admin\SmsTable');
        }
        return $this->smsTable;
    }
}