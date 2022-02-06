<?php

/*
 * Datatables routes file.
 */
$routes->group('', ['namespace' => 'DatatablesBuilder\Controllers'], function ($routes) {
    // Login/out
    $routes->get('datatables/(:alpha)', 'Datatables::genrate/$1');
});
