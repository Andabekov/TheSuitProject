<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 17/06/2015
 * Time: 17:09
 */

namespace Pidzhak\Form\seller;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Form\Form;

class PhoneCallForm extends Form
{
    protected $adapter;

    public function __construct(AdapterInterface $dbAdapter = null, $name = null)
    {
        $this->adapter =$dbAdapter;

        parent::__construct('phonecalls');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));

        $this->add(array(
            'name' => 'call_date',
            'type' => 'Text'
        ));

        $this->add(array(
            'name' => 'appoint_date',
            'type' => 'Date',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Дата записи',
            ),
        ));

        $this->add(array(
            'name' => 'appoint_comment',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Заметки записи (время записи, что подготовить ко встрече)',
            ),
        ));

        $this->add(array(
            'name' => 'remind_date',
            'type' => 'Date',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Дата напоминания',
            ),
        ));

        $this->add(array(
            'name' => 'remind_comment',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Заметки напоминания (время напоминания, о чем напомнить)',
            ),
        ));

        $this->add(array(
            'name' => 'media',
            'type' => 'Select',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Медия-формат',
                'empty_option' => 'Не выбрано',
                'value_options' => array(
                    'Радио' => 'Радио',
                    'Автобус' => 'Автобус',
                    'Инстаграм' => 'Инстаграм',
                    'Гугл' => 'Гугл',
                    'ВКонтакте' => 'ВКонтакте',
                    'Веб-сайт' => 'Веб-сайт',
                    'Знакомый' => 'Знакомый',
                    'Экран' => 'Экран',
                    'Вывеска' => 'Вывеска',
                    'Другое' => 'Другое',
                ),
            ),
        ));

        $this->add(array(
            'name' => 'client_id',
            'type' => 'Text'
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