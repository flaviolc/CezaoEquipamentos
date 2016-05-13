<?php

/**
 * Módulo Admin
 *
 * @link      http://...
 * @copyright Copyright (c) 2016 Disciplina de Desenvolvimento com Frameworks
 * @license   Private
 */

namespace Ecommerce;

return array(
    'router' => array(
        'routes' => array(
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'ecommerce' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/ecommerce',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Ecommerce\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                        'child_routes' => array(//permite mandar dados pela url
                            'wildcard' => array(
                                'type' => 'Wildcard'
                            ),
                        ),
                    ),
                ),
            ),
//Paginator original
//            'paginator' => array(
//                'type' => 'segment',
//                'options' => array(
//                    'route' => 'index[page/:page]',
//                    'defaults' => array(
//                        'page' => 1,
//                    ),
//                ),
//            ),
            //Paginator Controller Marcas
            'marcas' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/ecommerce/marcas/index[page/:page]',
                    //'route' => '/ecommerce/marcas/',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Ecommerce\Controller',
                        'controller' => 'Marcas',
                        'action' => 'index',
                        'page' => 1,
                    ),
                ),
            ),
            'categorias' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/ecommerce/categorias/index[page/:page]',
                    //'route' => '/ecommerce/marcas/',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Ecommerce\Controller',
                        'controller' => 'Categorias',
                        'action' => 'index',
                        'page' => 1,
                    ),
                ),
            ),
            'produtos' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/ecommerce/produtos/index[page/:page]',
                    //'route' => '/ecommerce/marcas/',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Ecommerce\Controller',
                        'controller' => 'Produtos',
                        'action' => 'index',
                        'page' => 1,
                    ),
                ),
            ),
            'tipoEndereco' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/ecommerce/tipo-enderecos/index[page/:page]',
                    //'route' => '/ecommerce/marcas/',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Ecommerce\Controller',
                        'controller' => 'TipoEnderecos',
                        'action' => 'index',
                        'page' => 1,
                    ),
                ),
            ),
            'subCategoria' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/ecommerce/sub-categorias/index[page/:page]',
                    //'route' => '/ecommerce/marcas/',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Ecommerce\Controller',
                        'controller' => 'SubCategorias',
                        'action' => 'index',
                        'page' => 1,
                    ),
                ),
            ),
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            'application_entities' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/Ecommerce/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Ecommerce\Entity' => 'application_entities'
                )
            ))),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'factories' => array(
            'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Ecommerce\Controller\Index' => Controller\IndexController::class,
            'Ecommerce\Controller\Categorias' => Controller\CategoriasController::class,
            'Ecommerce\Controller\Produtos' => Controller\ProdutosController::class,
            'Ecommerce\Controller\Marcas' => Controller\MarcasController::class,
            'Ecommerce\Controller\TipoEnderecos' => Controller\TipoEnderecosController::class,
            'Ecommerce\Controller\SubCategorias' => Controller\SubCategoriasController::class,
            'Ecommerce\Controller\Enderecos' => Controller\EnderecosController::class,
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'admin/index/index' => __DIR__ . '/../view/admin/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
);
