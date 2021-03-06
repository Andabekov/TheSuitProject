<?php
/**
 * Created by PhpStorm.
 * User: abu.andabekov
 * Date: 05.04.2015
 * Time: 14:30
 */

namespace Pidzhak;

use Pidzhak\Model\accountant\CertificateTable;
use Pidzhak\Model\accountant\Request;
use Pidzhak\Model\accountant\RequestTable;
use Pidzhak\Model\accountant\Task;
use Pidzhak\Model\accountant\TaskTable;
use Pidzhak\Model\admin\ConnectionTable;
use Pidzhak\Model\admin\Connection;
use Pidzhak\Model\admin\Cycle;
use Pidzhak\Model\admin\CycleTable;
use Pidzhak\Model\admin\Fabric;
use Pidzhak\Model\admin\FabricTable;
use Pidzhak\Model\admin\Penalty;
use Pidzhak\Model\admin\PenaltyTable;
use Pidzhak\Model\admin\Price;
use Pidzhak\Model\admin\PriceTable;
use Pidzhak\Model\admin\Sms;
use Pidzhak\Model\admin\SmsTable;
use Pidzhak\Model\admin\Style;
use Pidzhak\Model\admin\StyleTable;
use Pidzhak\Model\admin\Supplier;
use Pidzhak\Model\admin\SupplierTable;
use Pidzhak\Model\admin\Tailor;
use Pidzhak\Model\admin\TailorTable;
use Pidzhak\Model\admin\User;
use Pidzhak\Model\admin\UserTable;

use Pidzhak\Model\AuthStorage;
use Pidzhak\Model\redactor\SystemCode;
use Pidzhak\Model\redactor\SystemCodeTable;
use Pidzhak\Model\seller\BodyMeasure;
use Pidzhak\Model\seller\BodyMeasureTable;

use Pidzhak\Model\accountant\Certificate;
use Pidzhak\Model\seller\ClotherMeasure;
use Pidzhak\Model\seller\ClotherMeasureTable;
use Pidzhak\Model\seller\FinanceOperations;
use Pidzhak\Model\seller\FinanceOperationsTable;
use Pidzhak\Model\seller\PhoneCall;
use Pidzhak\Model\seller\PhoneCallTable;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use Pidzhak\Model\seller\Order;
use Pidzhak\Model\seller\OrderTable;
use Pidzhak\Model\seller\OrderClothes;
use Pidzhak\Model\seller\OrderClothesTable;
use Pidzhak\Model\redactor\OrderClothes as OrderClothesEn;
use Pidzhak\Model\redactor\OrderClothesTable as OrderClothesTableEn;
use Pidzhak\Model\seller\Customer;
use Pidzhak\Model\seller\CustomerTable;
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
            ->addResource(new Resource('pidzhak/tracking'))
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
			->addResource(new Resource('order'))
            ->addResource(new Resource('orderclothes'))
			->addResource(new Resource('orderclothes-rest'))
			->addResource(new Resource('finance-rest'))
			->addResource(new Resource('cert-rest'))
			->addResource(new Resource('request-rest'))
			->addResource(new Resource('task-rest'))
			->addResource(new Resource('order-rest'))
			->addResource(new Resource('clients'))
            ->addResource(new Resource('cycles'))
            ->addResource(new Resource('cycle-rest'))
            ->addResource(new Resource('tailors'))
            ->addResource(new Resource('tailor-rest'))
            ->addResource(new Resource('fabrics'))
            ->addResource(new Resource('fabric-rest'))
            ->addResource(new Resource('styles'))
            ->addResource(new Resource('style-rest'))
            ->addResource(new Resource('prices'))
            ->addResource(new Resource('price-rest'))
            ->addResource(new Resource('sms'))
            ->addResource(new Resource('sms-rest'))
            ->addResource(new Resource('penalties'))
            ->addResource(new Resource('penalty-rest'))
            ->addResource(new Resource('suppliers'))
            ->addResource(new Resource('supplier-rest'))
            ->addResource(new Resource('connection-rest'))
		;

        $acl->allow('nobody', 'home')
            ->allow('nobody', 'pidzhak')
            ->allow('nobody', 'pidzhak/tracking')
            ->allow('seller', 'seller')->allow('seller', 'customer')->allow('seller', 'measure')->allow('seller', 'customer-rest')->allow('seller', 'orderclothes')
            ->allow('seller', 'orderclothes-rest')
            ->allow('seller', 'finance-rest')
            ->allow('seller', 'order')
            ->allow('seller', 'order-rest')
            ->allow('seller', 'cycle-rest')
            ->allow('seller', 'fabric-rest')
            ->allow('seller', 'cert-rest')
            ->allow('seller', 'request-rest')
            ->allow('seller', 'task-rest')

            ->allow('redactor', 'redactor')
            ->allow('redactor', 'cycle-rest')
            ->allow('redactor', 'order')
            ->allow('redactor', 'order-rest')
            ->allow('redactor', 'orderclothes')
            ->allow('redactor', 'orderclothes-rest')
            ->allow('redactor', 'request-rest')
            ->allow('redactor', 'task-rest')

            ->allow('delivery', 'orderclothes-rest')

            ->allow('accountant', 'accountant')
            ->allow('accountant', 'finance-rest')
            ->allow('accountant', 'cert-rest')
            ->allow('accountant', 'request-rest')
            ->allow('accountant', 'task-rest')
            ->allow('director', 'director')
            ->allow('delivery', 'delivery')
            ->allow('admin', 'admin')->allow('admin', 'admin-rest')->allow('admin', 'clients')->allow('admin', 'customer-rest')
            ->allow('admin', 'cycles')->allow('admin', 'cycle-rest')
            ->allow('admin', 'tailors')->allow('admin', 'tailor-rest')
            ->allow('admin', 'fabrics')->allow('admin', 'fabric-rest')
            ->allow('admin', 'styles')->allow('admin', 'style-rest')
            ->allow('admin', 'prices')->allow('admin', 'price-rest')
            ->allow('admin', 'sms')->allow('admin', 'sms-rest')
            ->allow('admin', 'penalties')->allow('admin', 'penalty-rest')
            ->allow('admin', 'suppliers')->allow('admin', 'supplier-rest')
            ->allow('admin', 'connection-rest')
        ;

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
                    $dbTableAuthAdapter  = new DbTableAuthAdapter($dbAdapter, 'userstable','username','password', 'MD5(?)');

                    $authService = new AuthenticationService();
                    $authService->setAdapter($dbTableAuthAdapter);
                    $authService->setStorage($sm->get('Pidzhak\Model\AuthStorage'));

                    return $authService;
                },
                'Pidzhak\Model\seller\CustomerTable' =>  function($sm) {
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
                'Pidzhak\Model\seller\BodyMeasureTable' =>  function($sm) {
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
                'Pidzhak\Model\seller\ClotherMeasureTable' =>  function($sm) {
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
                'Pidzhak\Model\admin\TailorTable' =>  function($sm) {
                    $tableGateway = $sm->get('TailorTableGateway');
                    $table = new TailorTable($tableGateway);
                    return $table;
                },
                'TailorTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Tailor());
                    return new TableGateway('tailorstable', $dbAdapter, null, $resultSetPrototype);
                },

				'Pidzhak\Model\seller\OrderTable' =>  function($sm) {
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
                'Pidzhak\Model\seller\OrderClothesTable' =>  function($sm) {
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
                'Pidzhak\Model\redactor\SystemCodeTable' =>  function($sm) {
                    $tableGateway = $sm->get('SystemCodeTableGateway');
                    $table = new SystemCodeTable($tableGateway);
                    return $table;
                },
                'SystemCodeTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new SystemCode());
                    return new TableGateway('orderclothsystemcodes', $dbAdapter, null, $resultSetPrototype);
                },
                'Pidzhak\Model\seller\OrderClothesTableEn' =>  function($sm) {
                    $tableGateway = $sm->get('OrderClothesTableGatewayEn');
                    $table = new OrderClothesTableEn($tableGateway);
                    return $table;
                },
                'OrderClothesTableGatewayEn' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new OrderClothesEn());
                    return new TableGateway('orderclothredactor', $dbAdapter, null, $resultSetPrototype);
                },
                'Pidzhak\Model\seller\FinanceOperationsTable' =>  function($sm) {
                    $tableGateway = $sm->get('FinanceOperationsTableGateway');
                    $table = new FinanceOperationsTable($tableGateway);
                    return $table;
                },
                'FinanceOperationsTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new FinanceOperations());
                    return new TableGateway('accounting', $dbAdapter, null, $resultSetPrototype);
                },
                'Pidzhak\Model\accountant\CertificateTable' =>  function($sm) {
                    $tableGateway = $sm->get('CertificateTableGateway');
                    $table = new CertificateTable($tableGateway);
                    return $table;
                },
                'CertificateTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Certificate());
                    return new TableGateway('certificates', $dbAdapter, null, $resultSetPrototype);
                },
                'Pidzhak\Model\accountant\RequestTable' =>  function($sm) {
                    $tableGateway = $sm->get('RequestTableGateway');
                    $table = new RequestTable($tableGateway);
                    return $table;
                },
                'RequestTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Request());
                    return new TableGateway('requests', $dbAdapter, null, $resultSetPrototype);
                },

                'Pidzhak\Model\accountant\TaskTable' =>  function($sm) {
                    $tableGateway = $sm->get('TaskTableGateway');
                    $table = new TaskTable($tableGateway);
                    return $table;
                },
                'TaskTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Task());
                    return new TableGateway('tasks', $dbAdapter, null, $resultSetPrototype);
                },

                'Pidzhak\Model\seller\PhoneCallTable' =>  function($sm) {
                    $tableGateway = $sm->get('PhoneCallTableGateway');
                    $table = new PhoneCallTable($tableGateway);
                    return $table;
                },
                'PhoneCallTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new PhoneCall());
                    return new TableGateway('phonecalls', $dbAdapter, null, $resultSetPrototype);
                },
                'Pidzhak\Model\admin\PenaltyTable' =>  function($sm) {
                    $tableGateway = $sm->get('PenaltyTableGateway');
                    $table = new PenaltyTable($tableGateway);
                    return $table;
                },
                'PenaltyTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Penalty());
                    return new TableGateway('penalties', $dbAdapter, null, $resultSetPrototype);
                },

                'Pidzhak\Model\admin\SupplierTable' =>  function($sm) {
                    $tableGateway = $sm->get('SupplierTableGateway');
                    $table = new SupplierTable($tableGateway);
                    return $table;
                },
                'SupplierTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Supplier());
                    return new TableGateway('fabric_suppliers', $dbAdapter, null, $resultSetPrototype);
                },
                'Pidzhak\Model\admin\ConnectionTable' =>  function($sm) {
                    $tableGateway = $sm->get('ConnectionTableGateway');
                    $table = new ConnectionTable($tableGateway);
                    return $table;
                },
                'ConnectionTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Connection());
                    return new TableGateway('supplier_fabric_class', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
}