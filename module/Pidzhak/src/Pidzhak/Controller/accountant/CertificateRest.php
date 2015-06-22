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

class CertificateRest extends AbstractRestfulController
{
    protected $certificateTable;

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
        $customers= $this->getCertificateTable()->fetchPage(intval($rowCount), $offset, $sortField.' '.$sortType, $searchPhrase);
        $count= $this->getCertificateTable()->getCount();

        return new JsonModel(array(
            'current' => intval($current),
            'rowCount' => intval($rowCount),
            'rows' => $customers->toArray(),
            "total"=> $count,
        ));
    }

    public function getCertificateTable()
    {
        if (!$this->certificateTable) {
            $sm = $this->getServiceLocator();
            $this->certificateTable = $sm->get('Pidzhak\Model\accountant\CertificateTable');
        }
        return $this->certificateTable;
    }
}