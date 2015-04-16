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
            'Pidzhak\seller\Index'=>'Pidzhak\Controller\seller\IndexController',
            'Pidzhak\redactor\Index'=>'Pidzhak\Controller\redactor\IndexController',
            'Pidzhak\accountant\Index'=>'Pidzhak\Controller\accountant\IndexController',
            'Pidzhak\director\Index'=>'Pidzhak\Controller\director\IndexController',
            'Pidzhak\delivery\Index'=>'Pidzhak\Controller\delivery\IndexController',
            'Pidzhak\admin\Index'=>'Pidzhak\Controller\admin\UserController',
            'Pidzhak\admin\AdminRest'=>'Pidzhak\Controller\admin\UserRestController',
            'Pidzhak\Controller\Seller' => 'Pidzhak\Controller\SellerController',
            'Pidzhak\Controller\Customer' => 'Pidzhak\Controller\CustomerController',
            'Pidzhak\Controller\CustomerRest' => 'Pidzhak\Controller\CustomerRestController',
            'Pidzhak\Controller\Measure' => 'Pidzhak\Controller\MeasureController',        
'Pidzhak\Controller\Seller\Order' => 'Pidzhak\Controller\Seller\OrderController',
            'Pidzhak\Controller\Seller\OrderClothes' => 'Pidzhak\Controller\Seller\OrderClothesController',
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

            'order' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/order[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Pidzhak\Controller\Seller\Order',
                        'action'     => 'index',
                    ),
                ),
            ),

            'orderclothes' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/orderclothes[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Pidzhak\Controller\Seller\OrderClothes',
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
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/seller[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),                    'defaults' => array(
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
                'may_terminate' => true,
                'child_routes' => array(
                    'user' => array(
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

            'admin-rest' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/restadmin',
                    'defaults' => array(
                        'controller' => 'Pidzhak\admin\AdminRest',
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