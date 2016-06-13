<?php

$users = array();

$users[] = array('username' => 'Phillip7', 'password' => 'password1', 'rights' => 'user');
$users[] = array('username' => 'Micki', 'password' => 'password2', 'rights' => 'user');
$users[] = array('username' => 'Cathy', 'password' => 'password3', 'rights' => 'user');
$users[] = array('username' => 'Jane', 'password' => 'password4', 'rights' => 'user');
$users[] = array('username' => 'Matt', 'password' => 'password5', 'rights' => 'user');
$users[] = array('username' => 'Jake', 'password' => 'password6', 'rights' => 'admin');
$users[] = array('username' => 'Dave', 'password' => 'password7', 'rights' => 'admin');

$festivaldata = array();

$festivaldata[] = array('location' => 'Hyde Park', 'date' => '11/07/15', 'band' => 'Foo Fighters');
$festivaldata[] = array('location' => 'Blue Grass', 'date' => '22/09/15', 'band' => 'Portugal. The Man');
$festivaldata[] = array('location' => 'Death Fest', 'date' => '31/08/15', 'band' => 'Rise Against the Machine');
$festivaldata[] = array('location' => 'Bloodstock', 'date' => '02/07/15', 'band' => 'Arctic Monkeys');
$festivaldata[] = array('location' => 'Blast Off!', 'date' => '07/07/15', 'band' => 'Red Hot Chilli Peppers');

$mongo = new MongoClient();
$collection = $mongo->festival->users;
$collection->drop();
$collection->batchInsert($users);

$collection2 = $mongo->festival->festivaldata;
$collection2->drop();
$collection2->batchInsert($festivaldata)
?>