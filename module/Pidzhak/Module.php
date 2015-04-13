<?php
/**
 * Created by PhpStorm.
 * User: abu.andabekov
 * Date: 05.04.2015
 * Time: 14:30
 */

namespace Pidzhak;

use Pidzhak\Model\AuthStorage;use Pidzhak\Model\BodyMeasure;
use Pidzhak\Model\BodyMeasureTable;
use Pidzhak\Model\ClotherMeasure;
use Pidzhak\Model\ClotherMeasureTable;use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use Pidzhak\Model\Seller;
use Pidzhak\Model\SellerTable;
use Pidzhak\Model\Customer;
use Pidzhak\Model\CustomerTable;
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
        ;

        $acl->allow('nobody', 'home')->allow('nobody', 'pidzhak')
            ->allow('seller', 'seller')
            ->allow('redactor', 'redactor')
            ->allow('accountant', 'accountant')
            ->allow('director', 'director')
            ->allow('delivery', 'delivery')
            ->allow('admin', 'admin');

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
                'Pidzhak\Model\SellerTable' =>  function($sm) {
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
                'Pidzhak\Model\CustomerTable' =>  function($sm) {
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
                'Pidzhak\Model\BodyMeasureTable' =>  function($sm) {
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
                'Pidzhak\Model\ClotherMeasureTable' =>  function($sm) {
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
            ),
        );
    }
}