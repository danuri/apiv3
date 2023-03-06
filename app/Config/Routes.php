<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('', 'Home::index', ['filter' => 'cors']);
$routes->get('/', 'Home::index', ['filter' => 'cors']);
$routes->post('setpasswd', 'Home::setpassword', ['filter' => 'cors']);
$routes->get('mobile/home', 'Mobile\Home::index', ['filter' => 'auth']);
$routes->get('mobile/home/absen', 'Mobile\Home::absen/$1', ['filter' => 'auth']);
$routes->get('mobile/home/absen/(:any)', 'Mobile\Home::absen/$1', ['filter' => 'auth']);
$routes->get('mobile/home/absens/(:any)/(:any)', 'Mobile\Home::absens/$1/$2', ['filter' => 'auth']);

$routes->post('mobile/auth/login', 'Mobile\Auth::login', ['filter' => 'cors']);
$routes->get('mobile/auth/test88', 'Mobile\Auth::test', ['filter' => 'cors']);

$routes->post('mobile/lckh/save', 'Mobile\Lckh::save', ['filter' => 'auth']);
$routes->get('mobile/lckh/index/(:any)/(:any)', 'Mobile\Lckh::index/$1/$2', ['filter' => 'auth']);
$routes->get('mobile/lckh/view/(:any)', 'Mobile\Lckh::view/$1', ['filter' => 'auth']);
$routes->get('mobile/lckh/delete/(:any)', 'Mobile\Lckh::delete/$1', ['filter' => 'auth']);


$routes->group("presensi", function ($routes) {
    $routes->get('months/(:num)/(:num)', 'Presensi::months/$1/$2');
    $routes->get('days/(:num)/(:num)/(:num)', 'Presensi::days/$1/$2/$3');
});
/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
