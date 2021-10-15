<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('nuevocrm', function () {
    $newCase = [
        'case' => '12',
        'note' => 'Se requiere cotizar con otras aseguradoras',
        'url' => 'https://www.google.com',
        'creator_case' => 'Santiago Giraldo'
    ];
    return view('emails.crm.responsible', $newCase);
});
