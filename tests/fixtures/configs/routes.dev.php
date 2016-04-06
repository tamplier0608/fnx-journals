<?php

return array(
    'homepage' => array(
        'pattern' => '^/$',
        'uri' => 'index/index'
    ),
    
    'user_page' => array(
        'pattern' => 'user/([\d]+)',
        'uri' => 'user/show/$1'
    ),
    
    'show_page' => array(
        'pattern' => 'page/([\d]+)',
        'uri' => 'page/show/$1',
    ),

    'contacts' => array(
        'pattern' => 'contacts/form',
        'uri' => 'contacts'
    ),

    'error_controller' => array(
        'pattern' => 'error/404',
        'uri' => 'error/error'
    ),

    'articlse_list' => array(
        'pattern' => 'article-list',
        'uri' => 'index/articleList'
    ),

    'category_show' => array(
        'pattern' => 'category/([\d]+)/?',
        'uri' => 'category/show/$1'
    ),
);