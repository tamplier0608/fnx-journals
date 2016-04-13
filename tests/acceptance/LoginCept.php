<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that frontpage works');
$I->amOnUrl('http://dev.host/fnx-journals/');
$I->see('FNX Journals');

$I->expectTo('see login page');
$I->click('Log in');
$I->see('Username');
$I->see('Password');
$I->see('', '.login');

$I->
$I->fillField('Username', 'test-user1');
$I->click('Sign in');
$I->seeCurrentUrlEquals('/fnx-journals/login');
$I->see('Error! Incorrect input data.');

$I->fillField('Username', 'test-user1');
$I->fillField('Password', 'test-user2');
$I->click('Sign in');
$I->seeCurrentUrlEquals('/fnx-journals/login');
$I->see('Error! Credentials is not valid.');

$I->fillField('Username', 'test-user1');
$I->fillField('Password', 'test-user1');
$I->click('Sign in');
$I->expectTo('redirect to home page');
$I->seeCurrentUrlEquals('/fnx-journals/');

$I->seeInDatabase('orders', array('customerId' => 1));