<?php
/**
 * Create your routes in here. The name is the lowercase name of the controller
 * without the controller part, the stuff after the hash is the method.
 * e.g. page#index -> OCA\KmaAssignWork\Controller\PageController->index()
 *
 * The controller class has to be registered in the application.php file since
 * it's instantiated in there
 */
return [
    'routes' => [
        # Page
	   ['name' => 'page#index', 'url' => '/', 'verb' => 'GET'],
	   ['name' => 'page#do_echo', 'url' => '/echo', 'verb' => 'POST'],

       #Work_Item
       ['name' => 'KmaWork#sayHi', 'url' => '/sayhi', 'verb' => 'GET'],
       ['name' => 'KmaWork#getAllKmaWork', 'url' => '/all_kma_work', 'verb' => 'GET'],
    //    ['name' => 'KmaWork#getKmaWork', 'url' => '/kma_work/{work_id}', 'verb' => 'GET'],
       ['name' => 'KmaWork#createKmaWork', 'url' => '/create_kma_work', 'verb' => 'POST'],

       #Task_Item
       ['name' => 'KmaTask#sayHi', 'url' => '/sayhii', 'verb' => 'GET'],
       ['name' => 'KmaTask#createKmaTask', 'url' => '/create_kma_task', 'verb' => 'POST'],
       ['name' => 'KmaTask#getAllKmaTask', 'url' => '/all_kma_task', 'verb' => 'GET'],

       #Level
       ['name' => 'KmaLevel#createKmaLevel', 'url' => '/create_kma_level', 'verb' => 'POST'],
       ['name' => 'KmaLevel#getAllKmaLevel', 'url' => '/all_kma_level', 'verb' => 'GET'],

       #Status
       ['name' => 'KmaStatus#createKmaStatus', 'url' => '/create_kma_status', 'verb' => 'POST'],
       ['name' => 'KmaStatus#getAllKmaStatus', 'url' => '/all_kma_status', 'verb' => 'GET'],

       #Comment
       ['name' => 'KmaCommnet#getAllTaskComments', 'url' => '/all_task_comments', 'verb' => 'GET'],
       ['name' => 'KmaCommnet#getKmaComment', 'url' => '/kma_comments/{comment_id}', 'verb' => 'GET'],
       ['name' => 'KmaCommnet#createKmaComment', 'url' => '/create_kma_comment', 'verb' => 'POST'],

       #Connection
       ['name' => 'KmaConnection#getAllKmaConnections', 'url' => '/all_connections', 'verb' => 'GET'],
       ['name' => 'KmaConnection#getKmaConnection', 'url' => '/kma_connection/{connection_id}', 'verb' => 'GET'],
       ['name' => 'KmaConnection#createKmaConnection', 'url' => '/create_kma_connection', 'verb' => 'POST'],

    ]
];
