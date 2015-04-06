<?php
/**
 * Created by PhpStorm.
 * User: abu.andabekov
 * Date: 05.04.2015
 * Time: 14:36
 */

return array(

    'controllers' => array(
        'invokables' => array(
            'Pidzhak\Controller\Index' => 'Pidzhak\Controller\IndexController'
        )
    ),

    'router' => array(
        'routes' => array(
            'pidzhak' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/pidzhak[/:action][/:accessTypeId]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'accessTypeId'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Pidzhak\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),


    'view_manager' => array(
        'template_path_stack' => array(
            'album' => __DIR__ . '/../view',
        ),
    ),
);