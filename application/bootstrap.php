<?php

// setup database
require_once 'config.php'; 

// setup mvc
require_once 'core/model.php';
require_once 'core/view.php';
require_once 'core/controller.php';

// setup router
require_once 'core/route.php';

// show site
Route::start();

?>