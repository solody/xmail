<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Console\Controller\Index' => 'Console\Controller\IndexController',
        ),
        'factories' => array(
            'Console\Controller\Address' => 'Console\Factory\AddressControllerFactory',
            'Console\Controller\Document' => 'Console\Factory\DocumentControllerFactory',
            'Console\Controller\Task' => 'Console\Factory\TaskControllerFactory',
        ),
    ),
    'router' => array(
        'routes' => array(
            'console' => array(
                'type'    => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/console',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'Console\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    // This route is a sane default when developing a module;
                    // as you solidify the routes for your module, however,
                    // you may want to remove it and replace it with more
                    // specific routes.
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]][/:page]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'page' => '[1-9][0-9]*',
                            ),
                            'defaults' => array(
                                'page'=>1,
                            ),
                        ),
                    ),
                    'document_edit' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/document/edit[/:document_id]',
                            'constraints' => array(
                                'document_id' => '[1-9][0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Document',
                                'action'     => 'edit',
                            ),
                        ),
                    ),
                    'task_edit' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/task/edit[/:task_id]',
                            'constraints' => array(
                                'task_id' => '[1-9][0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Task',
                                'action'     => 'edit',
                            ),
                        ),
                    ),
                    'queue' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/task/queue/:task_id[/:page]',
                            'constraints' => array(
                                'task_id' => '[1-9][0-9]*',
                                'page' => '[1-9][0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Task',
                                'action'     => 'queue',
                                'page'=>1,
                            ),
                        ),
                    ),
                    'queue_edit' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/task/queue/edit/:task_id',
                            'constraints' => array(
                                'task_id' => '[1-9][0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Task',
                                'action'     => 'queueedit',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'Console' => __DIR__ . '/../view',
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'Console\Service\AddressServiceInterface' => 'Console\Factory\AddressServiceFactory',
            'Console\Service\DocumentService' => 'Console\Factory\DocumentServiceFactory',
            'Console\Service\TaskService' => 'Console\Factory\TaskServiceFactory',
        )
    ),
);
