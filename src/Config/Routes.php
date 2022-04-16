<?php

/*
 * Datatables routes file.
 */
$routes->group('', ['namespace' => 'DatatablesBuilder\Controllers'], function ($routes) {
    // Login/out
    $routes->get('datatables/(:segment)', 'Datatables::genrate/$1');
});
