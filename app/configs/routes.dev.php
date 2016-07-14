<?php

return array(
    'homepage' => array(
        'pattern' => '^/$',
        'uri' => 'index/index'
    ),

    'show_article' => array(
        'pattern' => 'article/([\d]+)/?',
        'uri' => 'index/showArticle/$1'
    ),

    'author_list' => array(
        'pattern' => 'authors/?',
        'uri' => 'index/authorList'
    ),

    'author_page' => array(
        'pattern' => 'author/([\d]+)/?',
        'uri' => 'index/authorPage/$1'
    ),
    
    'category_page' => array(
        'pattern' => 'category/([\d]+)/?',
        'uri' => 'index/categoryPage/$1'
    ),

    'tag_page' => array(
        'pattern' => 'tag/([\d+])/?',
        'uri' => 'index/tagPage/$1'
    ),

    'login' => array(
        'pattern' => 'login/?',
        'uri' => 'user/login'
    ),

    'logout' => array(
        'pattern' => 'logout/?',
        'uri' => 'user/logout'
    ),

    'user_collection' => array(
        'pattern' => 'user/collection/?',
        'uri' => 'user/collection'
    ),

    'buy_article' => array(
        'pattern' => 'user/buy/([\d+])/?',
        'uri' => 'user/buy/$1'
    ),

    'add_comment' => array(
        'pattern' => 'comment/new/?',
        'uri' => 'index/addComment'
    ),

    'load_comments' => array(
        'pattern' => 'comment/load/?',
        'uri' => 'index/loadComments'
    ),

    # for test purposes
    'user_setmoney' => array(
        'pattern' => 'user/setmoney/([\d+])/?',
        'uri' => 'user/setmoney/$1'
    ),

    'user_clean_collection' => array(
        'pattern' => 'user/clean/collection/?',
        'uri' => 'user/cleanCollection'
    ),
);