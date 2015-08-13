<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 01/07/2015
 * Time: 18:10
 */

namespace Pidzhak\Form\redactor;

use Zend\Form\Element;
use Zend\Form\Form;
use Zend\InputFilter\FileInput;
use Zend\InputFilter\InputFilter;

class FileUploadForm extends Form
{
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        $this->addElements();
    }

    public function addElements()
    {
        // File Input
        $file = new Element\File('excel-file');
        $file->setLabel('Test execl')
             ->setAttribute('id', 'excel-file');
        $this->add($file);
    }
}