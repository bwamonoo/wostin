<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


// System Routes (Routes for Migrations)
$routes->group('core', ['namespace' => 'App\Controllers\Core'], function ($routes) {
	$routes->get('run-migrations', 'Migrate::runMigrations');
	$routes->post('run-migrations', 'Migrate::runMigrations');
});


// Landing Page and User Dashboard Routes
$routes->get('/', 'Dashboard::index');
$routes->get('/dashboard', 'Dashboard::index', ['filter' => 'auth']);


// User Registration, Verification, Validation and Authentication Routes  
$routes->group('users', function ($routes) {
	$routes->get('register', 'Users::register', ['as' => 'users.register']);
	$routes->post('register', 'Users::register');

	$routes->get('verify-email', 'Users::verifyEmail');
	$routes->post('verify-email', 'Users::verifyEmail');
	$routes->get('resend-email-code', 'Users::resendEmailCode');

	$routes->get('verify-phone', 'Users::verifyPhone');
	$routes->post('verify-phone', 'Users::verifyPhone');
	$routes->get('resend-phone-code', 'Users::resendPhoneCode');

	$routes->get('login', 'Users::login', ['as' => 'users.login']);
	$routes->post('login', 'Users::login');
	$routes->get('logout', 'Users::logout', ['as' => 'users.logout']);

	$routes->get('profile', 'Users::profile', ['filter' => 'auth']);
	$routes->match(['get', 'post'], 'edit-profile', 'Users::edit_profile/$1', ['filter' => 'auth']);
});


// Routes for Schedule Management (User Side)
$routes->group('schedules', ['filter' => 'auth'], function ($routes) {
	$routes->get('/', 'Schedules::index', ['as' => 'schedules.index']);
	$routes->get('create', 'Schedules::create_schedule', ['as' => 'schedules.create_schedule']);
	$routes->post('create', 'Schedules::create_schedule');
	$routes->get('edit/(:any)', 'Schedules::edit_schedule/$1', ['as' => 'schedules.edit_schedule']);
	$routes->post('edit/(:any)', 'Schedules::edit_schedule/$1');
	$routes->get('details/(:num)', 'Schedules::details/$1', ['as' => 'schedules.details']);
	$routes->get('delete/(:num)', 'Schedules::delete/$1', ['as' => 'schedules.delete']);
});


// Payment Route
$routes->get('payment/confirm-payment', 'Schedules::confirm_payment', ['as' => 'schedules.confirm_payment', 'filter' => 'auth']);


// Admin Routes (for User and Schedule Management)
$routes->group('admin', ['namespace' => 'App\Controllers\Admin','filter' => [ 'admin']], function ($routes) {

	// Admin Dashboard and Reports
	$routes->get('dashboard', 'Admin::dashboard');
	$routes->get('reports', 'Admin::reports');
	
	// Schedule Management
	$routes->get('schedules', 'Admin::schedules');
	$routes->get('schedule-details/(:num)', 'Admin::schedule_details/$1');
	$routes->post('update-schedule-status/(:num)', 'Admin::update_schedule_status/$1');
	$routes->post('manage-schedule-status/(:num)', 'Admin::manage_schedule/$1');
	
	// User Management
	$routes->get('users', 'Admin::userManagement');
	$routes->get('deactivate-user/(:num)', 'Admin::deactivateUser/$1');
	$routes->get('reactivate-user/(:num)', 'Admin::reactivateUser/$1');
	$routes->get('user/(:num)', 'Admin::viewUser/$1');
	$routes->get('approve-schedule/(:num)', 'Admin::approveSchedule/$1');
	$routes->get('unapprove-schedule/(:num)', 'Admin::unapproveSchedule/$1');
	
	// Waste Type and Bin Size Management
	$routes->get('unavailability-management', 'Admin::unavailabilityManagement');
	$routes->post('add-unavailable-date', 'Admin::addUnavailableDate');
	$routes->get('delete-unavailable-date/(:num)', 'Admin::deleteUnavailableDate/$1');
	$routes->get('bin-waste-management', 'Admin::binWasteManagement');
	$routes->post('add-bin-size', 'Admin::addBinSize');
	$routes->get('delete-bin-size/(:num)', 'Admin::deleteBinSize/$1');
	$routes->post('add-waste-type', 'Admin::addWasteType');
	$routes->get('delete-waste-type/(:num)', 'Admin::deleteWasteType/$1');
});








