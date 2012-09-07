<?php

$slim_folder = '../Slim/';
require $slim_folder.'Slim/Slim.php';

$web2project_folder = '../web2project';
require_once $web2project_folder.'/base.php';
require_once W2P_BASE_DIR . '/includes/config.php';
require_once W2P_BASE_DIR . '/includes/main_functions.php';
require_once W2P_BASE_DIR . '/includes/db_adodb.php';

include 'functions.php';
include 'apiwrapper.class.php';

$app = new Slim(
            array('debug' => true)
        );

//TODO: figure out authentication
$GLOBALS['acl'] = new w2p_Mocks_Permissions();

/*
 * Sample: projects/283
 */
$app->get('/:module/:id', function($module, $id) {
    $classname = getClassName($module);
    $key = unPluralize($module).'_id';

    $obj = new $classname;
    $obj->load($id);

    if(is_null($obj->$key)) {
//TODO: set 404 header and return because the item wasn't found
    } else {
        $api = new APIWrapper($obj);
        echo $api->getObjectExport();
    }
});

/*
 * Sample: projects
 */
$app->post('/:module', function($module) {
//TODO: I hate using this global.. no solution offhand atm.
    global $app;

    $classname = getClassName($module);
    $allPostParams = $app->request()->post();

    $obj = new $classname;
    $obj->bind($allPostParams);
    $result = $obj->store();

    if ($result) {
//TODO: if success, return the 200 along with the new path
echo "success \n\n";
    } else {
//TODO: if failure, return the corresponding 400 along with the error messages
echo "fail \n\n";
    }
});

/*
 * Sample: projects/283
 */
$app->put('/:module/:id', function($module, $id) {
//TODO: I hate using this global.. no solution offhand atm.
    global $app;

    $classname = getClassName($module);
    $key = unPluralize($module).'_id';

    $allPostParams = $app->request()->post();

    $obj = new $classname;
    $obj->load($id);
    if(is_null($obj->$key)) {
//TODO: set 404 header and return because the item wasn't found
    } else {
        $obj->bind($allPostParams);
        $result = $obj->store();

        if ($result) {
//TODO: if success, return the 200 along with the new path
        } else {
//TODO: if failure, return the corresponding 401 or 404 along with the error messages
        }
    }

});

/*
 * Sample: projects/283
 */
$app->delete('/:module/:id', function($module, $id) {
    $classname = getClassName($module);

    $obj = new $classname;
    $result = $obj->delete($id);

    if ($result) {
//TODO: if success, return the 204 along with the new path
        echo "it worked! \n\n";
    } else {
        $errors = $obj->getError();

        if (isset($errors['noDeletePermission'])) {
//TODO: if failure, return the 401
        } else {
//TODO: if failure, return the corresponding 400 along with the error messages
        }
    }
});

/*
 * Sample: projects
 */
$app->options('/:module', function($module) {
    $classname = getClassName($module);

echo "this is an option request! \n\n";
//TODO: display the resource properties and/or interaction methods
});

$app->run();