<?php
/**
 * Created by PhpStorm.
 * User: abu.andabekov
 * Date: 05.04.2015
 * Time: 14:30
 */

namespace Pidzhak;

use Pidzhak\Model\admin\Cycle;
use Pidzhak\Model\admin\CycleTable;
use Pidzhak\Model\admin\Fabric;
use Pidzhak\Model\admin\FabricTable;
use Pidzhak\Model\admin\Price;
use Pidzhak\Model\admin\PriceTable;
use Pidzhak\Model\admin\Sms;
use Pidzhak\Model\admin\SmsTable;
use Pidzhak\Model\admin\Style;
use Pidzhak\Model\admin\StyleTable;
use Pidzhak\Model\admin\User;
use Pidzhak\Model\admin\UserTable;

use Pidzhak\Model\AuthStorage;
use Pidzhak\Model\Seller\BodyMeasure;
use Pidzhak\Model\Seller\BodyMeasureTable;

use Pidzhak\Model\Seller\ClotherMeasure;
use Pidzhak\Model\Seller\ClotherMeasureTable;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use Pidzhak\Model\Seller\Order;
use Pidzhak\Model\Seller\OrderTable;
use Pidzhak\Model\Seller\OrderClothes;
use Pidzhak\Model\Seller\OrderClothesTable;
use Pidzhak\Model\Seller\Seller;
use Pidzhak\Model\Seller\SellerTable;
use Pidzhak\Model\Seller\Customer;
use Pidzhak\Model\Seller\CustomerTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;
use Zend\Authentication\AuthenticationService;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $this->initAcl($e);
        $e->getApplication()->getEventManager()->attach('route', array($this, 'checkAcl'));
    }

    public function initAcl(MvcEvent $e)
    {
        $acl = new Acl();

        $acl->addRole(new Role('nobody'))
            ->addRole(new Role('seller'))
            ->addRole(new Role('redactor'))
            ->addRole(new Role('accountant'))
            ->addRole(new Role('director'))
            ->addRole(new Role('delivery'))
            ->addRole(new Role('admin'));

        $acl->addResource(new Resource('home'))
            ->addResource(new Resource('pidzhak'))
            ->addResource(new Resource('seller'))
            ->addResource(new Resource('redactor'))
            ->addResource(new Resource('accountant'))
            ->addResource(new Resource('director'))
            ->addResource(new Resource('delivery'))
            ->addResource(new Resource('admin'))
            ->addResource(new Resource('login/process'))
            ->addResource(new Resource('customer'))
            ->addResource(new Resource('measure'))
            ->addResource(new Resource('customer-rest'))
			->addResource(new Resource('admin-rest'))
            ->addResource(new Resource('admin/add'))
			->addResource(new Resource('order'))
            ->addResource(new Resource('orderclothes'))
			->addResource(new Resource('orderclothes-rest'))
			->addResource(new Resource('order-rest'))
			->addResource(new Resource('clients'))
            ->addResource(new Resource('cycles'))
            ->addResource(new Resource('cycle-rest'))
            ->addResource(new Resource('fabrics'))
            ->addResource(new Resource('fabric-rest'))
            ->addResource(new Resource('styles'))
            ->addResource(new Resource('style-rest'))
            ->addResource(new Resource('prices'))
            ->addResource(new Resource('price-rest'))
            ->addResource(new Resource('sms'))
            ->addResource(new Resource('sms-rest'))
		;
        $acl->allow('nobody', 'home')->allow('nobody', 'pidzhak')
            ->allow('seller', 'seller')->allow('seller', 'customer')->allow('seller', 'measure')->allow('seller', 'customer-rest')->allow('seller', 'order')->allow('seller', 'orderclothes')->allow('seller', 'orderclothes-rest')->allow('seller', 'order-rest')
            ->allow('redactor', 'redactor')
            ->allow('accountant', 'accountant')
            ->allow('director', 'director')
            ->allow('delivery', 'delivery')
            ->allow('admin', 'admin')->allow('admin', 'admin-rest')->allow('admin', 'clients')->allow('admin', 'customer-rest')
            ->allow('admin', 'cycles')->allow('admin', 'cycle-rest')
            ->allow('admin', 'fabrics')->allow('admin', 'fabric-rest')
            ->allow('admin', 'styles')->allow('admin', 'style-rest')
            ->allow('admin', 'prices')->allow('admin', 'price-rest')
            ->allow('admin', 'sms')->allow('admin', 'sms-rest');

        $e->getViewModel()->acl = $acl;
    }

    public function checkAcl(MvcEvent $e)
    {

        $route = $e->getRouteMatch()->getMatchedRouteName();

        $authStorage = new AuthStorage();
        $userRole = $authStorage->getRole();

        if (!$e->getViewModel()->acl->isAllowed($userRole, $route) ) {
            $response = $e->getResponse();

            //location to page or what ever
            $response->getHeaders()->addHeaderLine('Location', $e->getRequest()->getBaseUrl() . '/404');
            $response->setStatusCode(303);
        }
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    // Autoload all classes from namespace 'Blog' from '/module/Blog/src/Blog'
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                )
            )
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(

            'factories'=>array(
//		        'Zend\Db\Adapter\Adapter'
//                                  => 'Zend\Db\Adapter\AdapterServiceFactory',

                'Pidzhak\Model\AuthStorage' => function ($sm) {
                    return new \Pidzhak\Model\AuthStorage('userstable'); // zf_tutorial
                },

                'AuthService' => function ($sm) {
                    $dbAdapter      = $sm->get('Zend\Db\Adapter\Adapter');
                    $dbTableAuthAdapter  = new DbTableAuthAdapter($dbAdapter, 'userstable','username','password');

                    $authService = new AuthenticationService();
                    $authService->setAdapter($dbTableAuthAdapter);
                    $authService->setStorage($sm->get('Pidzhak\Model\AuthStorage'));

                    return $authService;
                },
                'Pidzhak\Model\Seller\SellerTable' =>  function($sm) {
                    $tableGateway = $sm->get('SellerTableGateway');
                    $table = new SellerTable($tableGateway);
                    return $table;
                },
                'SellerTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Seller());
                    return new TableGateway('seller', $dbAdapter, null, $resultSetPrototype);
                },
                'Pidzhak\Model\Seller\CustomerTable' =>  function($sm) {
                    $tableGateway = $sm->get('CustomerTableGateway');
                    $table = new CustomerTable($tableGateway);
                    return $table;
                },
                'CustomerTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Customer());
                    return new TableGateway('customer', $dbAdapter, null, $resultSetPrototype);
                },
                'Pidzhak\Model\Seller\BodyMeasureTable' =>  function($sm) {
                    $tableGateway = $sm->get('BodyMeasureTableGateway');
                    $table = new BodyMeasureTable($tableGateway);
                    return $table;
                },
                'BodyMeasureTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new BodyMeasure());
                    return new TableGateway('bodymeasure', $dbAdapter, null, $resultSetPrototype);
                },
                'Pidzhak\Model\Seller\ClotherMeasureTable' =>  function($sm) {
                    $tableGateway = $sm->get('ClotherMeasureTableGateway');
                    $table = new ClotherMeasureTable($tableGateway);
                    return $table;
                },
                'ClotherMeasureTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new ClotherMeasure());
                    return new TableGateway('clothermeasure', $dbAdapter, null, $resultSetPrototype);
                },

                'Pidzhak\Model\admin\UserTable' =>  function($sm) {
                    $tableGateway = $sm->get('UserTableGateway');
                    $table = new UserTable($tableGateway);
                    return $table;
                },
                'UserTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new User());
                    return new TableGateway('userstable', $dbAdapter, null, $resultSetPrototype);
                },
                'Pidzhak\Model\admin\CycleTable' =>  function($sm) {
                    $tableGateway = $sm->get('CycleTableGateway');
                    $table = new CycleTable($tableGateway);
                    return $table;
                },
                'CycleTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Cycle());
                    return new TableGateway('cyclestable', $dbAdapter, null, $resultSetPrototype);
                },
                'Pidzhak\Model\admin\FabricTable' =>  function($sm) {
                    $tableGateway = $sm->get('FabricTableGateway');
                    $table = new FabricTable($tableGateway);
                    return $table;
                },
                'FabricTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Fabric());
                    return new TableGateway('fabricstable', $dbAdapter, null, $resultSetPrototype);
                },
                'Pidzhak\Model\admin\StyleTable' =>  function($sm) {
                    $tableGateway = $sm->get('StyleTableGateway');
                    $table = new StyleTable($tableGateway);
                    return $table;
                },
                'StyleTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Style());
                    return new TableGateway('stylestable', $dbAdapter, null, $resultSetPrototype);
                },
                'Pidzhak\Model\admin\PriceTable' =>  function($sm) {
                    $tableGateway = $sm->get('PriceTableGateway');
                    $table = new PriceTable($tableGateway);
                    return $table;
                },
                'PriceTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Price());
                    return new TableGateway('pricestable', $dbAdapter, null, $resultSetPrototype);
                },
                'Pidzhak\Model\admin\SmsTable' =>  function($sm) {
                    $tableGateway = $sm->get('SmsTableGateway');
                    $table = new SmsTable($tableGateway);
                    return $table;
                },
                'SmsTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Sms());
                    return new TableGateway('smsmessages', $dbAdapter, null, $resultSetPrototype);
                },

				'Pidzhak\Model\Seller\OrderTable' =>  function($sm) {
                    $tableGateway = $sm->get('OrderTableGateway');
                    $table = new OrderTable($tableGateway);
                    return $table;
                },
                'OrderTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Order());
                    return new TableGateway('ordertable', $dbAdapter, null, $resultSetPrototype);
                },
                'Pidzhak\Model\Seller\OrderClothesTable' =>  function($sm) {
                    $tableGateway = $sm->get('OrderClothesTableGateway');
                    $table = new OrderClothesTable($tableGateway);
                    return $table;
                },
                'OrderClothesTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new OrderClothes());
                    return new TableGateway('orderclothes', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
}