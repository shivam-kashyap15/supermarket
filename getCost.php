
<?php

require 'autoload.php';

$cart = new Cart();


$a = trim($_POST['a']);
$b = trim($_POST['b']);
$c = trim($_POST['c']);
$d = trim($_POST['d']);
$e = trim($_POST['e']);

if($a > 0) {
	$cart->addItem('A', $a);
}

if($b > 0) {
	$cart->addItem('B', $b);
}

if($c > 0) {
	$cart->addItem('C', $c);
}

if($d > 0) {
	$cart->addItem('D', $d);
}

if($e > 0) {
	$cart->addItem('E', $e);
}

$cartItems = $cart->getItems();

$checkout = new Checkout();

echo $checkout->calculateTotalPrice($cartItems['items']);
