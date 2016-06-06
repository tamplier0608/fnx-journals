<?php

return array(
    
    'application_path' => DOCUMENT_ROOT . '/app/',
    'source_path' => DOCUMENT_ROOT . '/src/',

    'bootstrap' => array(
        'file' => 'Bootstrap.php',
        'main_path' => DOCUMENT_ROOT . '/app/'
    ),

    'layout' => array(
        'path' => DOCUMENT_ROOT . '/app/Resources/views/',
        'template' => 'main.phtml'
    ),

    'view' => array(
        'path' => DOCUMENT_ROOT . '/src/AppBundle/Resources/views/'
    ),

    'assets' => array(
        'path' => DOCUMENT_ROOT . '/assets/'
    ),

    'db' => array(
        'adapter' => 'mysql',
        'host' => 'localhost',
        'dbname' => 'fnx_journals',
        'username' => 'webuser',
        'password' => 'webuserpassword',
        'charset' => 'utf8',
        'crypt_salt' => 'sdfksadks$$kmfasd$fsdfjkhl;k!',
    ),

);
