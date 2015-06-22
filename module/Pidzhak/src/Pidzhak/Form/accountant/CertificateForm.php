<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 17/06/2015
 * Time: 17:09
 */

namespace Pidzhak\Form\accountant;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Form\Form;

class CertificateForm extends Form
{

    protected $adapter;

    public function __construct(AdapterInterface $dbAdapter = null, $name = null)
    {
        $this->adapter =$dbAdapter;

        parent::__construct('certificates');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));

        $this->add(array(
            'name' => 'valid_date',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Действителен до',
            ),
        ));

        $this->add(array(
            'name' => 'comment',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Комментарий',
            ),
        ));

        $this->add(array(
            'name' => 'cert_num',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Номер сертификата',
            ),
        ));

        $this->add(array(
            'name' => 'cost',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Сумма',
            ),
        ));

        $this->add(array(
            'name' => 'seller_id',
            'type' => 'Select',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Продавец',
                'empty_option' => 'Не выбрано',
                'value_options' => $this->getSellers(),
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Добавить',
                'class' => 'btn btn-primary'
            ),
        ));
    }


    public function highlightErrorElements()
    {
        foreach ($this->getElements() as $element) {
            if ($element->getMessages()) {
                $element->setAttribute('style', 'border-color:#a94442; box-shadow:inset 0 1px 1px rgba(0,0,0,.075);');
                $element->setLabelAttributes(array(
                    'class' => 'control-label col-xs-2',
                    'style' => 'color:#a94442'));
            }
        }
    }

    public function getSellers()
    {
        $dbAdapter = $this->adapter;
        $sql       = 'SELECT id, name FROM userstable where access_type_id=1 ORDER BY id ASC';
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();

        $selectData = array();

        foreach ($result as $res) {
            $selectData[$res['id']] = $res['name'];
        }
        return $selectData;
    }
}