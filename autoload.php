<?php
spl_autoload_register(function ($class_name) {
    include 'application/' . $class_name . '.php';
});

// require_once('application/Cart.php');
// require_once('application/Checkout.php');
// require_once('application/CostCalculator.php');
// require_once('application/Helper.php');
// require_once('application/ItemRules.php');
// require_once('application/Items.php');