<?php
/**
 * Created by PhpStorm.
 * User: abu.andabekov
 * Date: 05.04.2015
 * Time: 14:30
 */

namespace Pidzhak;

use Pidzhak\Model\BodyMeasure;
use Pidzhak\Model\BodyMeasureTable;
use Zend\Mvc\ModuleRouteListener;
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

class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
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
            ),
        );
    }
}