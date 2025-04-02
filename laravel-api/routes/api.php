<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\ListContactController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\OpportunityController;
use App\Http\Controllers\PipelineController;
use App\Http\Controllers\TaskController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
// đăng nhập, đăng kí 
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
// đăng xuất
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});
// contacts
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('contacts', ContactController::class);
    Route::get('/contacts/export/excel', [ContactController::class, 'exportExcel']);
    Route::get('/contacts/export/json', [ContactController::class, 'exportJson']);
    Route::get('/contacts', [ContactController::class, 'index']);
});
//Manager 
// Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('managers', ManagerController::class);
//listcontact
Route::apiResource('list-contacts', ListContactController::class);
//tag
Route::apiResource('tags', TagController::class);
//Opportunity 
Route::get('/opportunities', [OpportunityController::class, 'index']);
Route::apiResource('opportunities', OpportunityController::class);
//edit và xoá nhièu opportunilti
Route::delete('opportunities-multi', [OpportunityController::class, 'destroyMultiple']);
Route::put('opportunities-multi', [OpportunityController::class, 'updateMultiple']);
//CRUD pipeline
Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('pipelines', PipelineController::class);
    Route::post('opportunities/{opportunity}/move', [PipelineController::class, 'moveOpportunity']);
});
Route::middleware(['auth:sanctum'])->group(function () {
    // Route cho Tag
    Route::apiResource('tags', TagController::class);

    // Route cho Opportunity
    Route::apiResource('opportunities', OpportunityController::class);
    Route::delete('opportunities-multi', [OpportunityController::class, 'destroyMultiple']);
    Route::put('opportunities-multi', [OpportunityController::class, 'updateMultiple']);

    // Route để gán và xóa tag
    Route::post('opportunities/{opportunity}/attach-tag', [OpportunityController::class, 'attachTag']);
    Route::post('opportunities/{opportunity}/detach-tag', [OpportunityController::class, 'detachTag']);
    Route::get('opportunities/filter-by-tag', [OpportunityController::class, 'filterByTag']);
    Route::get('opportunities/{opportunity}/related', [OpportunityController::class, 'getRelatedOpportunities']);
});

// crud tasks
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('tasks', TaskController::class);
});

//Filter tasks
Route::middleware('auth:sanctum')->group(function () {
    Route::get('filter', [TaskController::class, 'filter']);
});

