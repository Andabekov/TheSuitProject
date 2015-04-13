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
            'Pidzhak\Controller\Index' => 'Pidzhak\Controller\IndexController',
            'Pidzhak\Controller\Success' => 'Pidzhak\Controller\AuthSuccessController',
            'Pidzhak\seller\Index'=>'Pidzhak\Controller\seller\IndexController',
            'Pidzhak\redactor\Index'=>'Pidzhak\Controller\redactor\IndexController',
            'Pidzhak\accountant\Index'=>'Pidzhak\Controller\accountant\IndexController',
            'Pidzhak\director\Index'=>'Pidzhak\Controller\director\IndexController',
            'Pidzhak\delivery\Index'=>'Pidzhak\Controller\delivery\IndexController',
            'Pidzhak\admin\Index'=>'Pidzhak\Controller\admin\IndexController'
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

            'login' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/login',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Pidzhak\Controller',
                        'controller'    => 'Pidzhak\Controller\Index',
                        'action'        => 'login',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'process' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:action][/:accessTypeId]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'accessTypeId'     => '[0-9]+',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),

            'seller' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/seller[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Pidzhak\seller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),

            'redactor' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/redactor[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Pidzhak\redactor\Index',
                        'action'     => 'index',
                    ),
                ),
            ),

            'accountant' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/accountant[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Pidzhak\accountant\Index',
                        'action'     => 'index',
                    ),
                ),
            ),

            'director' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/director[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Pidzhak\director\Index',
                        'action'     => 'index',
                    ),
                ),
            ),

            'delivery' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/delivery[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Pidzhak\delivery\Index',
                        'action'     => 'index',
                    ),
                ),
            ),

            'admin' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Pidzhak\admin\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),

    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
            'Zend\Authentication\AuthenticationService' => 'AuthService',
        ),
    ),

    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);