<?php

use App\Controllers\InvoicesController;
// use App\Controllers\LoginController;
// use App\Controllers\LogoutController;
// use App\Controllers\RegisterController;
use App\Router\Router;

// invoice
Router::get('/new-invoice', [InvoicesController::class, 'showForm']);
Router::get('/', [InvoicesController::class, 'showForm']);
Router::get('/invoices', [InvoicesController::class, 'getInvoices']);
Router::post('/invoices', [InvoicesController::class, 'createInvoice']);

// callback & redirect
Router::post('/callback-url', [InvoicesController::class, 'callbackUrl']);
Router::post('/return-url', [InvoicesController::class, 'returnUrl']);

// Follow-up
Router::post('/follow-refund', [InvoicesController::class, 'followRefund']);

// Own Form
Router::get('/ownform/new-payment', [InvoicesController::class, 'ownFormPayment']);


// Recurring
// Router::post('/follow-refund', [InvoicesController::class, 'followRefund']);


// Auth
// Router::get('/register', [RegisterController::class, 'showRegisterForm']);
// Router::post('/register', [RegisterController::class, 'createNewUser']);

// Router::get('/login', [LoginController::class, 'showLoginForm']);
// Router::post('/login', [LoginController::class, 'loginUser']);

// Router::post('/logout', [LogoutController::class, 'logout']);


?>