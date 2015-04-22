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

class PriceRestController extends AbstractRestfulController
{
    protected $priceTable;

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
        $prices= $this->getPriceTable()->fetchPage(intval($rowCount), $offset, $sortField.' '.$sortType, $searchPhrase);
        $count= $this->getPriceTable()->getCount();

        return new JsonModel(array(
            'current' => intval($current),
            'rowCount' => intval($rowCount),
            'rows' => $prices->toArray(),
            "total"=> $count,
        ));
    }

    /*Inversion of Control*/
    public function getPriceTable()
    {
        if (!$this->priceTable) {
            $sm = $this->getServiceLocator();
            $this->priceTable = $sm->get('Pidzhak\Model\admin\PriceTable');
        }
        return $this->priceTable;
    }
}