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
            'Pidzhak\redactor\Index' => 'Pidzhak\Controller\redactor\IndexController',
            'Pidzhak\accountant\Index' => 'Pidzhak\Controller\accountant\IndexController',
            'Pidzhak\director\Index' => 'Pidzhak\Controller\director\IndexController',
            'Pidzhak\delivery\Index' => 'Pidzhak\Controller\delivery\IndexController',
            'Pidzhak\admin\Index' => 'Pidzhak\Controller\admin\UserController',
            'Pidzhak\admin\Clients' => 'Pidzhak\Controller\admin\ClientController',
            'Pidzhak\admin\Tailors' => 'Pidzhak\Controller\admin\TailorController',
            'Pidzhak\admin\TailorRest' => 'Pidzhak\Controller\admin\TailorRestController',
            'Pidzhak\admin\Cycles' => 'Pidzhak\Controller\admin\CycleController',
            'Pidzhak\admin\CycleRest' => 'Pidzhak\Controller\admin\CycleRestController',
            'Pidzhak\admin\Fabrics' => 'Pidzhak\Controller\admin\FabricController',
            'Pidzhak\admin\FabricRest' => 'Pidzhak\Controller\admin\FabricRestController',
            'Pidzhak\admin\Styles' => 'Pidzhak\Controller\admin\StyleController',
            'Pidzhak\admin\StyleRest' => 'Pidzhak\Controller\admin\StyleRestController',
            'Pidzhak\admin\Prices' => 'Pidzhak\Controller\admin\PriceController',
            'Pidzhak\admin\PriceRest' => 'Pidzhak\Controller\admin\PriceRestController',
            'Pidzhak\admin\Sms' => 'Pidzhak\Controller\admin\SmsController',
            'Pidzhak\admin\SmsRest' => 'Pidzhak\Controller\admin\SmsRestController',
            'Pidzhak\admin\AdminRest' => 'Pidzhak\Controller\admin\UserRestController',
            'Pidzhak\admin\Penalties' => 'Pidzhak\Controller\admin\PenaltyController',
            'Pidzhak\admin\PenaltyRest' => 'Pidzhak\Controller\admin\PenaltyRestController',
            'Pidzhak\admin\Suppliers' => 'Pidzhak\Controller\admin\SupplierController',
            'Pidzhak\admin\SupplierRest' => 'Pidzhak\Controller\admin\SupplierRestController',
            'Pidzhak\admin\ConnectionRest' => 'Pidzhak\Controller\admin\ConnectionRestController',
            'Pidzhak\Controller\seller\seller' => 'Pidzhak\Controller\seller\SellerController',
            'Pidzhak\Controller\seller\Customer' => 'Pidzhak\Controller\seller\CustomerController',
            'Pidzhak\Controller\seller\CustomerRest' => 'Pidzhak\Controller\seller\CustomerRestController',
            'Pidzhak\Controller\seller\Measure' => 'Pidzhak\Controller\seller\MeasureController',
            'Pidzhak\Controller\seller\Order' => 'Pidzhak\Controller\seller\OrderController',
            'Pidzhak\Controller\seller\OrderClothes' => 'Pidzhak\Controller\seller\OrderClothesController',
			'Pidzhak\Controller\seller\OrderClothesRest' => 'Pidzhak\Controller\seller\OrderClothesRestController',
			'Pidzhak\Controller\seller\OrderRest' => 'Pidzhak\Controller\seller\OrderRestController',
			'Pidzhak\Controller\seller\Finance' => 'Pidzhak\Controller\seller\FinanceOperationsRest',
			'Pidzhak\Controller\accountant\Cert' => 'Pidzhak\Controller\accountant\CertificateRest',
			'Pidzhak\Controller\accountant\Request' => 'Pidzhak\Controller\accountant\RequestRest',
			'Pidzhak\Controller\accountant\Task' => 'Pidzhak\Controller\accountant\TaskRest',
		)    ),

    'router' => array(
        'routes' => array(
            'pidzhak' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/pidzhak[/:action][/:accessTypeId]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'accessTypeId' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Pidzhak\Controller\Index',
                        'action' => 'index',
                    ),
                ),
            ),

            'seller' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/seller[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Pidzhak\Controller\seller\seller',
                        'action' => 'index',
                    ),
                ),
            ),

            'customer' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/customer[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Pidzhak\Controller\seller\Customer',
                        'action' => 'index',
                    ),
                ),
            ),

            'order' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/order[/:action][/:id][/:measureTypeSelect]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Pidzhak\Controller\seller\Order',
                        'action' => 'index',
                    ),
                ),
            ),

            'orderclothes' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/orderclothes[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Pidzhak\Controller\seller\OrderClothes',
                        'action' => 'index',
                    ),
                ),
            ),

            'measure' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/measure[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Pidzhak\Controller\seller\Measure',
                        'action' => 'index',
                    ),
                ),
            ),

            'orderclothes-rest' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/restorderclothes',
                    'defaults' => array(
                        'controller' => 'Pidzhak\Controller\seller\OrderClothesRest',
                    ),
                ),
            ),

            'finance-rest' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/restfinance',
                    'defaults' => array(
                        'controller' => 'Pidzhak\Controller\seller\Finance',
                    ),
                ),
            ),

            'cert-rest' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/restcert',
                    'defaults' => array(
                        'controller' => 'Pidzhak\Controller\accountant\Cert',
                    ),
                ),
            ),

            'request-rest' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/restrequest',
                    'defaults' => array(
                        'controller' => 'Pidzhak\Controller\accountant\Request',
                    ),
                ),
            ),

            'task-rest' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/resttask',
                    'defaults' => array(
                        'controller' => 'Pidzhak\Controller\accountant\Task',
                    ),
                ),
            ),

            'order-rest' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/restorder',
                    'defaults' => array(
                        'controller' => 'Pidzhak\Controller\seller\OrderRest',
                    ),
                ),
            ),

            'customer-rest' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/restcustomer[/:id]',
                    'defaults' => array(
                        'controller' => 'Pidzhak\Controller\seller\CustomerRest',
                    ),
                ),
            ),

            'login' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/login',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Pidzhak\Controller',
                        'controller' => 'Pidzhak\Controller\Index',
                        'action' => 'login',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'process' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:action][/:accessTypeId]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'accessTypeId' => '[0-9]+',
                            ),
                            'defaults' => array(),
                        ),
                    ),
                ),
            ),


            'redactor' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/redactor[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Pidzhak\redactor\Index',
                        'action' => 'index',
                    ),
                ),
            ),

            'accountant' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/accountant[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Pidzhak\accountant\Index',
                        'action' => 'index',
                    ),
                ),
            ),

            'director' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/director[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Pidzhak\director\Index',
                        'action' => 'index',
                    ),
                ),
            ),

            'delivery' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/delivery[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Pidzhak\delivery\Index',
                        'action' => 'index',
                    ),
                ),
            ),

            'admin' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/admin[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Pidzhak\admin\Index',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
            ),

            'admin-rest' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/restadmin',
                    'defaults' => array(
                        'controller' => 'Pidzhak\admin\AdminRest',
                    ),
                ),
            ),

            'clients' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/clients[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Pidzhak\admin\Clients',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
            ),

            'tailors' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/tailors[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Pidzhak\admin\Tailors',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
            ),

            'tailor-rest' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/resttailor',
                    'defaults' => array(
                        'controller' => 'Pidzhak\admin\TailorRest',
                    ),
                ),
            ),

            'cycles' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/cycles[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Pidzhak\admin\Cycles',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
            ),

            'cycle-rest' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/restcycle',
                    'defaults' => array(
                        'controller' => 'Pidzhak\admin\CycleRest',
                    ),
                ),
            ),

            'fabrics' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/fabrics[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Pidzhak\admin\Fabrics',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
            ),

            'fabric-rest' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/restfabric',
                    'defaults' => array(
                        'controller' => 'Pidzhak\admin\FabricRest',
                    ),
                ),
            ),

            'styles' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/styles[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Pidzhak\admin\Styles',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
            ),

            'style-rest' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/reststyle',
                    'defaults' => array(
                        'controller' => 'Pidzhak\admin\StyleRest',
                    ),
                ),
            ),

            'prices' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/prices[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Pidzhak\admin\Prices',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
            ),

            'price-rest' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/restprice',
                    'defaults' => array(
                        'controller' => 'Pidzhak\admin\PriceRest',
                    ),
                ),
            ),

            'sms' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/sms[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Pidzhak\admin\Sms',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
            ),

            'sms-rest' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/restsms[/:id]',
                    'defaults' => array(
                        'controller' => 'Pidzhak\admin\SmsRest',
                    ),
                ),
            ),

            'penalties' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/penalties[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Pidzhak\admin\Penalties',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
            ),

            'penalty-rest' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/restpenalty',
                    'defaults' => array(
                        'controller' => 'Pidzhak\admin\PenaltyRest',
                    ),
                ),
            ),

            'suppliers' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/suppliers[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Pidzhak\admin\Suppliers',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
            ),

            'supplier-rest' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/restsupplier',
                    'defaults' => array(
                        'controller' => 'Pidzhak\admin\SupplierRest',
                    ),
                ),
            ),

            'connection-rest' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/restconnection',
                    'defaults' => array(
                        'controller' => 'Pidzhak\admin\ConnectionRest',
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
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy'
        ),
    ),
);