<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', function () {
	return redirect()->to(base_url('auth'));
});
$routes->group('auth', ['namespace' => '\App\Controllers\Auth'], function ($routes) {
	$routes->get('', 'Login::index');
	$routes->get('login', 'Login::index');
	$routes->get('logout', 'Logout::index');
});

$routes->group('admin', ['filter' => 'auth', 'namespace' => '\App\Controllers\Admin'], function ($routes) {
	$routes->get('', 'Dashboard::index');
	$routes->group('', ['filter' => 'auth:1', 'namespace' => '\App\Controllers\Admin'], function ($routes) {
		$routes->get('admin', 'Admin::index');
	});
	$routes->get('dashboard', 'Dashboard::index');
	$routes->get('(:any)', 'Errors::show404');
});

$routes->group('api', ['namespace' => '\App\Controllers\Api'], function ($routes) {
	$routes->group('auth', ['namespace' => '\App\Controllers\Api\Auth'], function ($routes) {
		$routes->post('', 'Login::process');
		$routes->post('login', 'Login::process');
	});
	$routes->group('admin', ['filter' => 'auth_api', 'namespace' => '\App\Controllers\Api\Admin'], function ($routes) {
		$routes->group('admin', ['filter' => 'auth_api:1', 'namespace' => '\App\Controllers\Api\Admin'], function ($routes) {
			$routes->post('datatable', 'Admin::datatable_data');
			$routes->post('create', 'Admin::create');
			$routes->group('(:num)', function ($routes) {
				$routes->post('update', 'Admin::update/$1');
				$routes->post('delete', 'Admin::delete/$1');
			});
		});
	});
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
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
