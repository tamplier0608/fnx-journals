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
        'host' => 'db15.freehost.com.ua',
        'dbname' => 'vseoweb_journals',
        'username' => 'vseoweb_journals',
        'password' => 'K5xijeWPp',
        'charset' => 'utf8',
        'crypt_salt' => 'sdfksadks$$kmfasd$fsdfjkhl;k!',
    ),

);
