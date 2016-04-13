<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that frontpage works');
$I->amOnUrl('http://dev.host/fnx-journals/');
$I->see('FNX Journals');


