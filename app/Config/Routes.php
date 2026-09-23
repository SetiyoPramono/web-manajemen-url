<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Home → redirect to login
$routes->get('/', 'AuthController::login');

// Auth Routes
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::attemptLogin');
$routes->get('logout', 'AuthController::logout');
$routes->get('blocked', 'AuthController::blocked');

$routes->get('register-card/(:segment)', 'AuthController::registerCard/$1');
$routes->post('register-card/(:segment)', 'AuthController::processRegisterCard/$1');

// Admin Routes (Protected by AuthFilter with 'admin' role)
$routes->group('admin', ['filter' => 'auth:admin'], static function ($routes) {
    $routes->get('/', 'AdminController::index');
    $routes->post('create', 'AdminController::createUrl');
    $routes->get('edit/(:num)', 'AdminController::editUrl/$1');
    $routes->post('update/(:num)', 'AdminController::updateUrl/$1');
    $routes->get('view/(:num)', 'AdminController::viewUrl/$1');
    $routes->post('delete/(:num)', 'AdminController::deleteUrl/$1');
    $routes->post('reset/(:num)', 'AdminController::resetUrl/$1');
    $routes->post('bulk-action', 'AdminController::bulkAction');
    $routes->get('settings', 'AdminController::settings');
    $routes->post('settings/update', 'AdminController::updateSettings');

    // Export/Import
    $routes->get('export', 'AdminController::exportCsv');
    $routes->post('import', 'AdminController::importCsv');

    // User Management
    $routes->get('users', 'AdminUsersController::index');
    $routes->post('users/create', 'AdminUsersController::create');
    $routes->post('users/delete/(:num)', 'AdminUsersController::delete/$1');

    // Blocked IPs Management
    $routes->get('blocked-ips', 'AdminBlockedIpsController::index');
    $routes->post('blocked-ips/block', 'AdminBlockedIpsController::block');
    $routes->post('blocked-ips/unblock/(:num)', 'AdminBlockedIpsController::unblock/$1');
    $routes->post('blocked-ips/toggle-permanent/(:num)', 'AdminBlockedIpsController::togglePermanent/$1');
    $routes->post('blocked-ips/delete/(:num)', 'AdminBlockedIpsController::delete/$1');
});

// Client Routes (Protected by AuthFilter with 'client' role)
$routes->group('client', ['filter' => 'auth:client'], static function ($routes) {
    $routes->get('/', 'ClientController::index');
    $routes->post('update', 'ClientController::updateUrl');
    $routes->post('update-review', 'ClientController::updateReviewCount');
});

// Shared API Routes (Protected for all authenticated users)
$routes->post('api/generate-review-link', 'ToolsController::generateReviewLink', ['filter' => 'auth']);

// Public CS Rotator API
$routes->get('api/cs-rotate', 'ToolsController::rotateCs');
$routes->post('api/cs-rotate', 'ToolsController::rotateCs');

// Slug Catch-All — MUST be LAST to avoid conflicting with above routes
$routes->get('(:any)', 'RedirectController::index/$1');
