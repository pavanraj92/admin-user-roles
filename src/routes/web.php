<?php

use Illuminate\Support\Facades\Route;
use admin\user_roles\Controllers\UserRoleManagerController;

Route::name('admin.')->middleware(['web','admin.auth'])->group(function () {  
    Route::resource('user_roles', UserRoleManagerController::class);
    Route::post('user_roles/updateStatus', [UserRoleManagerController::class, 'updateStatus'])->name('user_roles.updateStatus');

});
