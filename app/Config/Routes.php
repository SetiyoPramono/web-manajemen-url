<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Home → redirect to login
$routes->get('/', 'AuthController::login');

// Auth Routes
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::attemptLogin');
$routes->get('logout', 'AuthController::logout');

// Admin Routes (Protected by AuthFilter with 'admin' role)
$routes->group('admin', ['filter' => 'auth:admin'], static function ($routes) {
    $routes->get('/', 'AdminController::index');
    $routes->post('create', 'AdminController::createUrl');
    $routes->get('edit/(:num)', 'AdminController::editUrl/$1');
    $routes->post('update/(:num)', 'AdminController::updateUrl/$1');
    $routes->get('view/(:num)', 'AdminController::viewUrl/$1');
    $routes->post('delete/(:num)', 'AdminController::deleteUrl/$1');
    $routes->get('settings', 'AdminController::settings');
    $routes->post('settings/update', 'AdminController::updateSettings');
});

// Client Routes (Protected by AuthFilter with 'client' role)
$routes->group('client', ['filter' => 'auth:client'], static function ($routes) {
    $routes->get('/', 'ClientController::index');
    $routes->post('update', 'ClientController::updateUrl');
});

// Slug Catch-All — MUST be LAST to avoid conflicting with above routes
$routes->get('(:any)', 'RedirectController::index/$1');
