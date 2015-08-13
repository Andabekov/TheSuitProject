<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 06/06/2015
 * Time: 17:41
 */

namespace Pidzhak\Controller\seller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class MeasureRestController extends AbstractRestfulController{
    protected $bodyMeasureTable;

    public function create($data)
    {

        $current = $data['current'];
        $rowCount = $data['rowCount'];
        $sort = $data['sort'];
        $sortField = key($sort);
        $sortType = $sort[$sortField];
        $searchPhrase = $data['searchPhrase'];
        $id = $data['id'];
        $client_id = $data['client_id'];

        $offset = intval($rowCount) * (intval($current)-1);
        $customers= $this->getBodyMeasureTable()->fetchPage(intval($rowCount), $offset, $sortField.' '.$sortType, $searchPhrase, $client_id);
        $count= $this->getBodyMeasureTable()->getCount();

        return new JsonModel(array(
            'current' => intval($current),
            'rowCount' => intval($rowCount),
            'rows' => $customers->toArray(),
            "total"=> $count,
        ));
    }

    /*Inversion of Control*/
    public function getBodyMeasureTable()
    {
        if (!$this->bodyMeasureTable) {
            $sm = $this->getServiceLocator();
            $this->bodyMeasureTable = $sm->get('Pidzhak\Model\seller\BodyMeasureTable');
        }
        return $this->bodyMeasureTable;
    }
}
