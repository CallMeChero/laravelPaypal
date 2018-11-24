<?php


Route::get('/', function () {
    return view('welcome');
});

Route::get('/execute-payment', 'PaymentController@storeInfo');
Route::post('/create-payment', 'PaymentController@createPayment');
