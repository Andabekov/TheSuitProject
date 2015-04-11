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
            'Pidzhak\Controller\Seller' => 'Pidzhak\Controller\SellerController',
            'Pidzhak\Controller\Customer' => 'Pidzhak\Controller\CustomerController',
            'Pidzhak\Controller\CustomerRest' => 'Pidzhak\Controller\CustomerRestController',
            'Pidzhak\Controller\Measure' => 'Pidzhak\Controller\MeasureController',
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

            'seller' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/seller[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Pidzhak\Controller\Seller',
                        'action'     => 'index',
                    ),
                ),
            ),

            'customer' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/customer[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Pidzhak\Controller\Customer',
                        'action'     => 'index',
                    ),
                ),
            ),

            'measure' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/measure[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Pidzhak\Controller\Measure',
                        'action'     => 'index',
                    ),
                ),
            ),


            'customer-rest' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/restcustomer',
                    'defaults' => array(
                        'controller' => 'Pidzhak\Controller\CustomerRest',
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

            'seller2' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/seller2',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Pidzhak\Controller',
                        'controller'    => 'Pidzhak\Controller\Success',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
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
        'strategies' => array(
            'ViewJsonStrategy'
        ),
    ),
);