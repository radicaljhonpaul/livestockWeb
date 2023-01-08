<?php

use App\Http\Controllers\API\DiagnosisController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\HealthGuide;
use App\Http\Controllers\API\FeedManagementController;
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\LogoutController;
use App\Http\Controllers\API\RegistrationController;
use App\Http\Controllers\API\FarmerController;
use App\Http\Controllers\API\UserDirectoryController;
use App\Http\Controllers\API\SmsController;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
// Route::middleware('auth:sanctum')->post('/logout', function(){
//     return request()->user()->currentAccessToken()->delete();
// });

Route::post('/login', [LoginController::class, 'login']);
// Route::post('/registration', [RegistrationController::class, 'registration']);

// For API that needs authenticated middleware - auth:sanctum
Route::group(['middleware' => ['auth:sanctum']], function($route){

    // Farmers Info API
    $route->post('/Farmers/synchedUp', [FarmerController::class, 'synched_up']);
    $route->post('/Farmers/livestock/pregnancySynchedUp', [FarmerController::class, 'livestockpregnancy_synchedUp']);
    
    // Diagnosis API
    $route->post('/DiagnosisLogs/synchedUp', [DiagnosisController::class, 'synched_up']);
    $route->post('/DiagnosisLogs/synchedUpMultiple', [DiagnosisController::class, 'synched_up_multiple']);
    $route->get('/DiagnosisLogs/syncDown', [DiagnosisController::class, 'diagnosisLog_syncDown']);
    $route->get('/DiagnosisLogs/syncDownPaginated', [DiagnosisController::class, 'diagnosisLog_syncDownPaginated']);
    
    
    // Media Sync Up API
    $route->post('/DiagnosisLogs/mediaSyncUp', [DiagnosisController::class, 'diagnosisLog_media_syncUp']);
    $route->post('/DiagnosisLogs/mediaSyncUpWithDeletedFiles', [DiagnosisController::class, 'diagnosisLog_media_syncUpWithDeletedFiles']);
    $route->get('/DiagnosisLogs/mediaCheckFileSize', [DiagnosisController::class, 'diagnosisLog_media_CheckFileSize']);
    $route->get('/DiagnosisLogs/MediaSyncDown', [DiagnosisController::class, 'download_DiagnosisLog_media_syncDown']);
    $route->get('/DiagnosisLogs/MediaSyncDownURL', [DiagnosisController::class, 'download_DiagnosisLog_media_syncDown_URL']);
    
    //common farmer api calls
    $route->get("/getFarmersAssigned", [FarmerController::class, 'getFarmersAssigned']);    
    $route->post("/getSpecificFarmerAssigned", [FarmerController::class, 'getSpecificFarmerAssigned']);
    $route->get("/getFarmersAssignedPaginated", [FarmerController::class, 'getFarmersAssignedPaginated']);   
    $route->get("/getAdminLevelsAssignedPaginated", [FarmerController::class, 'getAdminLevelsAssignedPaginated']); 

    //common user api calls
    $route->get("/getAreaUsers", [UserDirectoryController::class, 'getAreaUsers']); 

    //feeding logs api calls
    $route->get("/getFeedingLogsAssigned", [FeedManagementController::class, 'getFeedingLogsAssigned']);    
    $route->get("/getFeedingLogsAssignedPaginated", [FeedManagementController::class, 'getFeedingLogsAssignedPaginated']); 
    $route->post("/syncUpFeedingLogs", [FeedManagementController::class, 'syncUpFeedingLogs']); 

    $route->get('/user', [LoginController::class, 'user']);
    $route->post('/logout', [LogoutController::class, 'logout']);


    //CSRS
    $route->post("/setFarmerConsentViaSms", [SmsController::class, 'setFarmerConsentViaSms']);
    $route->post("/setHealthRatingViaSms", [SmsController::class, 'setHealthRatingViaSms']);



});

/*
|--------------------------------------------------------------------------
| iHealth API ROUTES
|--------------------------------------------------------------------------
*/

// Fetch all the Systems->HC->Symptoms
Route::get("/systems",[HealthGuide::class,'Systems']);

// Fetch a Specific Systems | Params: System ID
Route::post("/specificSystem",[HealthGuide::class,'SpecificSystem']);

// Fetch HC Media | Params: HC Media ID
Route::post("/specificHCmedia",[HealthGuide::class,'SpecificHCMedia']);

// Fetch HC Media Zipped
Route::get("/downloadHCMediaZipped",[HealthGuide::class,'DownloadHCMediaZipped']);

// Fetch Symptoms Media Zipped
Route::get("/downloadSymptomMediaZipped",[HealthGuide::class,'DownloadSymptomMediaZipped']);



// Fetch Symptom Media | Params: Symptom Media ID
Route::get("/specificSymptomMedia",[HealthGuide::class,'SpecificHCMedia']);


/*
|--------------------------------------------------------------------------
| iFeed API ROUTES
|--------------------------------------------------------------------------
*/

//Feed Management related APIs
Route::get("/srpyears", [FeedManagementController::class, 'srpyears']);

//Fetch ingredients with price by SRF | Param: srp id
Route::get("/ingredientsbysrp/{srp_id}", [FeedManagementController::class, 'listBySrp']);


/*
|--------------------------------------------------------------------------
| Customer Satisfaction Rating Service
|--------------------------------------------------------------------------
*/
Route::get("/createConnectionToken", [SmsController::class, 'createConnectionToken']);