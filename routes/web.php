<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\CheckRolesContoller;
use App\Http\Controllers\Pages\PagesController;
use App\Http\Controllers\Directories\DirectoriesController;
use App\Http\Controllers\Dashboards\DashboardsController;
use App\Http\Controllers\HealthGuide\HealthGuidesController;
use App\Http\Controllers\Reports\ReportsController;
use App\Http\Controllers\Staffings\StaffingsController;
use App\Http\Controllers\Users\UsersController;
use App\Http\Controllers\Farmers\FarmersController;
use App\Http\Controllers\Feeds\CategoriesController;
use App\Http\Controllers\Feeds\IngredientsController;
use App\Http\Controllers\Feeds\SrpYearController;
use App\Http\Controllers\Reports\VisitRatingController;
use App\Http\Controllers\Admin\ConnectionController;
use App\Http\Controllers\UserManagement\UserManagementController;
use Illuminate\Http\RedirectResponse;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::group(['middleware' => ['auth:sanctum', 'verified']], function(){

    Route::get('/checkRole', [CheckRolesContoller::class, 'index'])->name('checkRole');
    Route::get('/getRoles', [CheckRolesContoller::class, 'getRoles'])->name('getRoles');
    
	// PCC Admin
	Route::group(['prefix' => 'Admin', 'as' => 'Admin.', 'middleware' => ['admin:Admin']], function(){

        // go back to previous page or request
        Route::get('/goback', function () {
            return redirect()->back();
        })->name('goback');
        // Change Password
        Route::get('/ChangePasswordPage', [UserManagementController::class, 'ChangePasswordPage'])->name('ChangePasswordPage');
        Route::post('/ChangePassword', [UserManagementController::class, 'ChangePassword'])->name('ChangePassword');
        
        // Custom function Returning Vue/Views
        Route::get('/', [DashboardsController::class, 'AdminDashboard'])->name('AdminDashboard');
        Route::get('/AdminFarmers', [FarmersController::class, 'Farmers'])->name('AdminFarmers');
        Route::get('/AdminStaff', [UserManagementController::class, 'Users'])->name('AdminStaff');
        Route::get('/AdminHealthGuide', [HealthGuidesController::class, 'HealthGuide'])->name('AdminHealthGuide');
        Route::get('/AdminDirectories', [DirectoriesController::class, 'Directories'])->name('AdminDirectories');
        Route::get('/AdminReports', [ReportsController::class, 'Reports'])->name('AdminReports');
        Route::get('/AdminReportsHealthVisit', [ReportsController::class, 'ReportsHealthVisit'])->name('AdminReportsHealthVisit');
        Route::get('/AdminCategories', [CategoriesController::class, 'Categories'])->name('AdminCategories');
        Route::get('/AdminPricing', [SrpYearController::class, 'SrpYears'])->name('AdminPricing');

        // Get Specific Symptoms or Details with in a System
        Route::get('/SpecificSystem', [HealthGuidesController::class, 'SpecificSystem'])->name('SpecificSystem');

        // Get Specific Farmer Details
        Route::get('/SpecificFarmer', [FarmersController::class, 'SpecificFarmer'])->name('SpecificFarmer');
        Route::post('/SaveEditedFarmerProfile', [FarmersController::class, 'SaveEditedFarmerProfile'])->name('SaveEditedFarmerProfile');
        Route::get('/DownloadFarmerList', [FarmersController::class, 'DownloadFarmerList'])->name('DownloadFarmerList');
        

        // Get Specific livestock Details and Visit logs
        Route::get('/PregnancyInfo', [FarmersController::class, 'PregnancyInfo'])->name('PregnancyInfo');
        Route::get('/SpecificLivestock', [FarmersController::class, 'SpecificLivestock'])->name('SpecificLivestock');
        Route::get('/DownloadLivestockList', [FarmersController::class, 'DownloadLivestockList'])->name('DownloadLivestockList');
        Route::post('/SaveCreatedLivestock', [FarmersController::class, 'SaveCreatedLivestock'])->name('SaveCreatedLivestock');
        
        // Get Specific Users Details
        Route::get('/SpecificStaff', [UserManagementController::class, 'SpecificStaff'])->name('SpecificStaff');
        Route::post('/CreateStaff', [UserManagementController::class, 'CreateStaff'])->name('CreateStaff');
        
        Route::get('/ViewProvinces', [UserManagementController::class, 'ViewProvinces'])->name('ViewProvinces');
        Route::get('/ViewCities', [UserManagementController::class, 'ViewCities'])->name('ViewCities');
        Route::get('/ViewBrgy', [UserManagementController::class, 'ViewBrgy'])->name('ViewBrgy');
        
        Route::get('/AssignUsers', [UserManagementController::class, 'AssignUsers'])->name('AssignUsers');
        Route::post('/SaveAssignedUsers', [UserManagementController::class, 'SaveAssignedUsers'])->name('SaveAssignedUsers');
        Route::post('/DirectSaveAssignedUsers', [UserManagementController::class, 'DirectSaveAssignedUsers'])->name('DirectSaveAssignedUsers');
        
        
        // Get all Symptoms List
        Route::get('/Symptoms', [HealthGuidesController::class, 'Symptoms'])->name('Symptoms');
        Route::post('/AddSymptomToHC', [HealthGuidesController::class, 'AddSymptomToHC'])->name('AddSymptomToHC');
        Route::post('/AddInterventionToHC', [HealthGuidesController::class, 'AddInterventionToHC'])->name('AddInterventionToHC');

        // Submits to saving edits for Health Content
        Route::post('/SaveEditedHC', [HealthGuidesController::class, 'SaveEditedHC'])->name('SaveEditedHC');
        Route::post('/SaveCreatedHC', [HealthGuidesController::class, 'SaveCreatedHC'])->name('SaveCreatedHC');
        
        // Submits to saving edits for Symptom Content
        Route::post('/SaveEditedSymptom', [HealthGuidesController::class, 'SaveEditedSymptom'])->name('SaveEditedSymptom');
        Route::post('/CreateSymptom', [HealthGuidesController::class, 'CreateSymptom'])->name('CreateSymptom');
        
        // Get Ingredients By CategoryId, Create and Edit for Ingredients
        Route::get('/AdminIngredients', [IngredientsController::class, 'IngredientsByCategory'])->name('AdminIngredients');
        Route::post('/CreateCategory', [CategoriesController::class, 'CreateCategory'])->name('CreateCategory');
        Route::post('/CreateIngredient', [IngredientsController::class, 'CreateIngredient'])->name('CreateIngredient');
        Route::post('/SaveEditedIngredient', [IngredientsController::class, 'SaveEditedIngredient'])->name('SaveEditedIngredient');
        

        // Get Price By Srp Year Id
        Route::get('/AdminPriceByYear', [SrpYearController::class, 'PriceByYear'])->name('AdminPriceByYear');
        Route::get('/DownloadPriceBySrp', [SrpYearController::class, 'DownloadPriceBySrp'])->name('DownloadPriceBySrp');
        Route::post('/UploadPriceBySrp', [SrpYearController::class, 'UploadPriceBySrp'])->name('UploadPriceBySrp');

        //CreateSrpYear with ingrededients
        Route::post('/CreateSrpYear', [SrpYearController::class, 'CreateSrpYear'])->name('CreateSrpYear');
        Route::post('/ActivateSrpYear', [SrpYearController::class, 'ActivateSrpYear'])->name('ActivateSrpYear');
        
        // Download Reports Health Visit
        Route::get('/DownloadTopHealthIssuesList', [ReportsController::class, 'DownloadTopHealthIssuesList'])->name('DownloadTopHealthIssuesList');

        
        // View Ratings
        Route::get('/AdminVisitRatings', [VisitRatingController::class, 'VisitRatings'])->name('AdminVisitRatings');

        //Config Related (ADMIN ONLY)
        Route::get('/AdminConnections', [ConnectionController::class, 'Connections'])->name('AdminConnections');
        Route::post('/CreateConnection', [ConnectionController::class, 'CreateConnection'])->name('CreateConnection');
        
        Route::get('/AdminConfig', [ConnectionController::class, 'AdminConfig'])->name('AdminConfig');
        Route::post('/SaveEditedSpecificConfig', [ConnectionController::class, 'SaveEditedSpecificConfig'])->name('SaveEditedSpecificConfig');
        
        //End of Config Related (ADMIN ONLY)


	});

    // PCC Vet
    Route::group(['prefix' => 'Vet', 'as' => 'Vet.', 'middleware' => ['vet:Vet']], function(){

        // go back to previous page or request
        Route::get('/goback', function () {
            return redirect()->back();
        })->name('goback');


        // Change Password
        Route::get('/ChangePasswordPage', [UserManagementController::class, 'ChangePasswordPage'])->name('ChangePasswordPage');
        Route::post('/ChangePassword', [UserManagementController::class, 'ChangePassword'])->name('ChangePassword');

        // Custom function Returning Vue/Views
        Route::get('/', [DashboardsController::class, 'VetDashboard'])->name('VetDashboard');

        /*
        |--------------------------------------------------------------------------
        | IHealth - Read Only
        |--------------------------------------------------------------------------
        */
        Route::get('/AdminHealthGuide', [HealthGuidesController::class, 'HealthGuide'])->name('AdminHealthGuide');
        // Get Specific Symptoms or Details with in a System
        Route::get('/SpecificSystem', [HealthGuidesController::class, 'SpecificSystem'])->name('SpecificSystem');
        // Get all Symptoms List
        Route::get('/Symptoms', [HealthGuidesController::class, 'Symptoms'])->name('Symptoms');
        
        /*
        |--------------------------------------------------------------------------
        | Farmer Data
        |--------------------------------------------------------------------------
        */
        Route::get('/AdminFarmers', [FarmersController::class, 'Farmers'])->name('AdminFarmers');
        // Get Specific Farmer Details
        Route::get('/SpecificFarmer', [FarmersController::class, 'SpecificFarmer'])->name('SpecificFarmer');
        Route::post('/SaveEditedFarmerProfile', [FarmersController::class, 'SaveEditedFarmerProfile'])->name('SaveEditedFarmerProfile');
        Route::get('/DownloadFarmerList', [FarmersController::class, 'DownloadFarmerList'])->name('DownloadFarmerList');

        // Get Specific livestock Details and Visit logs
        Route::get('/PregnancyInfo', [FarmersController::class, 'PregnancyInfo'])->name('PregnancyInfo');
        Route::get('/SpecificLivestock', [FarmersController::class, 'SpecificLivestock'])->name('SpecificLivestock');
        Route::get('/DownloadLivestockList', [FarmersController::class, 'DownloadLivestockList'])->name('DownloadLivestockList');
        Route::post('/SaveCreatedLivestock', [FarmersController::class, 'SaveCreatedLivestock'])->name('SaveCreatedLivestock');

        /*
        |--------------------------------------------------------------------------
        | User Management
        |--------------------------------------------------------------------------
        */
        Route::get('/AdminStaff', [UserManagementController::class, 'Users'])->name('AdminStaff');
        // Get Specific Users Details
        Route::get('/SpecificStaff', [UserManagementController::class, 'SpecificStaff'])->name('SpecificStaff');
        Route::get('/ViewProvinces', [UserManagementController::class, 'ViewProvinces'])->name('ViewProvinces');
        Route::get('/ViewCities', [UserManagementController::class, 'ViewCities'])->name('ViewCities');
        Route::get('/ViewBrgy', [UserManagementController::class, 'ViewBrgy'])->name('ViewBrgy');

        /*
        |--------------------------------------------------------------------------
        | IHealth
        |--------------------------------------------------------------------------
        */
        Route::get('/AdminHealthGuide', [HealthGuidesController::class, 'HealthGuide'])->name('AdminHealthGuide');
        // Get Specific Symptoms or Details with in a System
        Route::get('/SpecificSystem', [HealthGuidesController::class, 'SpecificSystem'])->name('SpecificSystem');
        // Get all Symptoms List
        Route::get('/Symptoms', [HealthGuidesController::class, 'Symptoms'])->name('Symptoms');
        Route::post('/AddSymptomToHC', [HealthGuidesController::class, 'AddSymptomToHC'])->name('AddSymptomToHC');
        Route::post('/AddInterventionToHC', [HealthGuidesController::class, 'AddInterventionToHC'])->name('AddInterventionToHC');

        /*
        |--------------------------------------------------------------------------
        | IFodder
        |--------------------------------------------------------------------------
        */
        Route::get('/AdminCategories', [CategoriesController::class, 'Categories'])->name('AdminCategories');
        Route::get('/AdminPricing', [SrpYearController::class, 'SrpYears'])->name('AdminPricing');
        // Get Ingredients By CategoryId, Create and Edit for Ingredients
        Route::get('/AdminIngredients', [IngredientsController::class, 'IngredientsByCategory'])->name('AdminIngredients');
        
        // Get Price By Srp Year Id
        Route::get('/AdminPriceByYear', [SrpYearController::class, 'PriceByYear'])->name('AdminPriceByYear');
        Route::get('/DownloadPriceBySrp', [SrpYearController::class, 'DownloadPriceBySrp'])->name('DownloadPriceBySrp');


        /*
        |--------------------------------------------------------------------------
        | Reports
        |--------------------------------------------------------------------------
        */
        Route::get('/AdminReports', [ReportsController::class, 'Reports'])->name('AdminReports');
        Route::get('/AdminReportsHealthVisit', [ReportsController::class, 'ReportsHealthVisit'])->name('AdminReportsHealthVisit');

        // Download Reports Health Visit
        Route::get('/DownloadTopHealthIssuesList', [ReportsController::class, 'DownloadTopHealthIssuesList'])->name('DownloadTopHealthIssuesList');

        // View Ratings
        Route::get('/AdminVisitRatings', [VisitRatingController::class, 'VisitRatings'])->name('AdminVisitRatings');

    });

    // PCC Vet Aide
    Route::group(['prefix' => 'VetAide', 'as' => 'VetAide.', 'middleware' => ['vet:VetAide']], function(){
        // go back to previous page or request
        Route::get('/goback', function () {
            return redirect()->back();
            })->name('goback');        
        

        // Change Password
        Route::get('/ChangePasswordPage', [UserManagementController::class, 'ChangePasswordPage'])->name('ChangePasswordPage');
        Route::post('/ChangePassword', [UserManagementController::class, 'ChangePassword'])->name('ChangePassword');

        // Custom function Returning Vue/Views
        Route::get('/', [DashboardsController::class, 'VetAideDashboard'])->name('VetAideDashboard');

        /*
        |--------------------------------------------------------------------------
        | Farmer Data
        |--------------------------------------------------------------------------
        */
        Route::get('/AdminFarmers', [FarmersController::class, 'Farmers'])->name('AdminFarmers');
        // Get Specific Farmer Details
        Route::get('/SpecificFarmer', [FarmersController::class, 'SpecificFarmer'])->name('SpecificFarmer');
        Route::post('/SaveEditedFarmerProfile', [FarmersController::class, 'SaveEditedFarmerProfile'])->name('SaveEditedFarmerProfile');
        Route::get('/DownloadFarmerList', [FarmersController::class, 'DownloadFarmerList'])->name('DownloadFarmerList');
        // Get Specific livestock Details and Visit logs
        Route::get('/PregnancyInfo', [FarmersController::class, 'PregnancyInfo'])->name('PregnancyInfo');
        Route::get('/SpecificLivestock', [FarmersController::class, 'SpecificLivestock'])->name('SpecificLivestock');
        Route::get('/DownloadLivestockList', [FarmersController::class, 'DownloadLivestockList'])->name('DownloadLivestockList');
        Route::post('/SaveCreatedLivestock', [FarmersController::class, 'SaveCreatedLivestock'])->name('SaveCreatedLivestock');
        
        /*
        |--------------------------------------------------------------------------
        | Reports
        |--------------------------------------------------------------------------
        */
        Route::get('/AdminReports', [ReportsController::class, 'Reports'])->name('AdminReports');
        Route::get('/AdminReportsHealthVisit', [ReportsController::class, 'ReportsHealthVisit'])->name('AdminReportsHealthVisit');

        /*
        |--------------------------------------------------------------------------
        | IHealth - Read Only
        |--------------------------------------------------------------------------
        */
        Route::get('/AdminHealthGuide', [HealthGuidesController::class, 'HealthGuide'])->name('AdminHealthGuide');
        // Get Specific Symptoms or Details with in a System
        Route::get('/SpecificSystem', [HealthGuidesController::class, 'SpecificSystem'])->name('SpecificSystem');
        // Get all Symptoms List
        Route::get('/Symptoms', [HealthGuidesController::class, 'Symptoms'])->name('Symptoms');
        
        /*
        |--------------------------------------------------------------------------
        | IFodder - Read Only
        |--------------------------------------------------------------------------
        */
        Route::get('/AdminCategories', [CategoriesController::class, 'Categories'])->name('AdminCategories');
        Route::get('/AdminPricing', [SrpYearController::class, 'SrpYears'])->name('AdminPricing');
        // Get Ingredients By CategoryId, Create and Edit for Ingredients
        Route::get('/AdminIngredients', [IngredientsController::class, 'IngredientsByCategory'])->name('AdminIngredients');
        
        // Get Price By Srp Year Id
        Route::get('/AdminPriceByYear', [SrpYearController::class, 'PriceByYear'])->name('AdminPriceByYear');
        Route::get('/DownloadPriceBySrp', [SrpYearController::class, 'DownloadPriceBySrp'])->name('DownloadPriceBySrp');

        /*
        |--------------------------------------------------------------------------
        | User Management
        |--------------------------------------------------------------------------
        */
        Route::get('/AdminStaff', [UserManagementController::class, 'Users'])->name('AdminStaff');
        // Get Specific Users Details
        Route::get('/SpecificStaff', [UserManagementController::class, 'SpecificStaff'])->name('SpecificStaff');
        Route::get('/ViewProvinces', [UserManagementController::class, 'ViewProvinces'])->name('ViewProvinces');
        Route::get('/ViewCities', [UserManagementController::class, 'ViewCities'])->name('ViewCities');
        Route::get('/ViewBrgy', [UserManagementController::class, 'ViewBrgy'])->name('ViewBrgy');

        /*
        |--------------------------------------------------------------------------
        | Reports
        |--------------------------------------------------------------------------
        */
        Route::get('/AdminReports', [ReportsController::class, 'Reports'])->name('AdminReports');
        Route::get('/AdminReportsHealthVisit', [ReportsController::class, 'ReportsHealthVisit'])->name('AdminReportsHealthVisit');

        // Download Reports Health Visit
        Route::get('/DownloadTopHealthIssuesList', [ReportsController::class, 'DownloadTopHealthIssuesList'])->name('DownloadTopHealthIssuesList');
        
        // View Ratings
        Route::get('/AdminVisitRatings', [VisitRatingController::class, 'VisitRatings'])->name('AdminVisitRatings');

    });

	// PCC IHealth Content Manager
	Route::group(['prefix' => 'IHealthContentManager', 'as' => 'IHealthContentManager.', 'middleware' => ['ihealthcontentmanager:IHealthContentManager']], function(){
        // go back to previous page or request
        Route::get('/goback', function () {
            return redirect()->back();
            })->name('goback');     
        

        // Change Password
        Route::get('/ChangePasswordPage', [UserManagementController::class, 'ChangePasswordPage'])->name('ChangePasswordPage');
        Route::post('/ChangePassword', [UserManagementController::class, 'ChangePassword'])->name('ChangePassword');

        // Custom function Returning Vue/Views
        Route::get('/', [DashboardsController::class, 'IHealthContentManagerDashboard'])->name('IHealthContentManagerDashboard');

        /*
        |--------------------------------------------------------------------------
        | IHealth - Read & Write
        |--------------------------------------------------------------------------
        */
        Route::get('/AdminHealthGuide', [HealthGuidesController::class, 'HealthGuide'])->name('AdminHealthGuide');
        // Get Specific Symptoms or Details with in a System
        Route::get('/SpecificSystem', [HealthGuidesController::class, 'SpecificSystem'])->name('SpecificSystem');
        // Get all Symptoms List
        Route::get('/Symptoms', [HealthGuidesController::class, 'Symptoms'])->name('Symptoms');
        Route::post('/AddSymptomToHC', [HealthGuidesController::class, 'AddSymptomToHC'])->name('AddSymptomToHC');
        Route::post('/AddInterventionToHC', [HealthGuidesController::class, 'AddInterventionToHC'])->name('AddInterventionToHC');

        // Submits to saving edits for Health Content
        Route::post('/SaveEditedHC', [HealthGuidesController::class, 'SaveEditedHC'])->name('SaveEditedHC');
        Route::post('/SaveCreatedHC', [HealthGuidesController::class, 'SaveCreatedHC'])->name('SaveCreatedHC');
        
        // Submits to saving edits for Symptom Content
        Route::post('/SaveEditedSymptom', [HealthGuidesController::class, 'SaveEditedSymptom'])->name('SaveEditedSymptom');
        Route::post('/CreateSymptom', [HealthGuidesController::class, 'CreateSymptom'])->name('CreateSymptom');

        /*
        |--------------------------------------------------------------------------
        | IFodder - Read Only
        |--------------------------------------------------------------------------
        */
        Route::get('/AdminCategories', [CategoriesController::class, 'Categories'])->name('AdminCategories');
        Route::get('/AdminPricing', [SrpYearController::class, 'SrpYears'])->name('AdminPricing');
        // Get Ingredients By CategoryId, Create and Edit for Ingredients
        Route::get('/AdminIngredients', [IngredientsController::class, 'IngredientsByCategory'])->name('AdminIngredients');
        
        // Get Price By Srp Year Id
        Route::get('/AdminPriceByYear', [SrpYearController::class, 'PriceByYear'])->name('AdminPriceByYear');
        Route::get('/DownloadPriceBySrp', [SrpYearController::class, 'DownloadPriceBySrp'])->name('DownloadPriceBySrp');

        /*
        |--------------------------------------------------------------------------
        | Farmer Data
        |--------------------------------------------------------------------------
        */
        Route::get('/AdminFarmers', [FarmersController::class, 'Farmers'])->name('AdminFarmers');
        // Get Specific Farmer Details
        Route::get('/SpecificFarmer', [FarmersController::class, 'SpecificFarmer'])->name('SpecificFarmer');
        Route::post('/SaveEditedFarmerProfile', [FarmersController::class, 'SaveEditedFarmerProfile'])->name('SaveEditedFarmerProfile');
        Route::get('/DownloadFarmerList', [FarmersController::class, 'DownloadFarmerList'])->name('DownloadFarmerList');
        // Get Specific livestock Details and Visit logs
        Route::get('/PregnancyInfo', [FarmersController::class, 'PregnancyInfo'])->name('PregnancyInfo');
        Route::get('/SpecificLivestock', [FarmersController::class, 'SpecificLivestock'])->name('SpecificLivestock');
        Route::get('/DownloadLivestockList', [FarmersController::class, 'DownloadLivestockList'])->name('DownloadLivestockList');
        Route::post('/SaveCreatedLivestock', [FarmersController::class, 'SaveCreatedLivestock'])->name('SaveCreatedLivestock');
        
        /*
        |--------------------------------------------------------------------------
        | User Management
        |--------------------------------------------------------------------------
        */
        Route::get('/AdminStaff', [UserManagementController::class, 'Users'])->name('AdminStaff');
        // Get Specific Users Details
        Route::get('/SpecificStaff', [UserManagementController::class, 'SpecificStaff'])->name('SpecificStaff');
        Route::get('/ViewProvinces', [UserManagementController::class, 'ViewProvinces'])->name('ViewProvinces');
        Route::get('/ViewCities', [UserManagementController::class, 'ViewCities'])->name('ViewCities');
        Route::get('/ViewBrgy', [UserManagementController::class, 'ViewBrgy'])->name('ViewBrgy');

        /*
        |--------------------------------------------------------------------------
        | Reports
        |--------------------------------------------------------------------------
        */
        Route::get('/AdminReports', [ReportsController::class, 'Reports'])->name('AdminReports');
        Route::get('/AdminReportsHealthVisit', [ReportsController::class, 'ReportsHealthVisit'])->name('AdminReportsHealthVisit');

        // Download Reports Health Visit
        Route::get('/DownloadTopHealthIssuesList', [ReportsController::class, 'DownloadTopHealthIssuesList'])->name('DownloadTopHealthIssuesList');
        
        // View Ratings
        Route::get('/AdminVisitRatings', [VisitRatingController::class, 'VisitRatings'])->name('AdminVisitRatings');

	});

	// PCC IFodder Content Manager
    Route::group(['prefix' => 'IFodderContentManager', 'as' => 'IFodderContentManager.', 'middleware' => ['ifoddercontentmanager:IFodderContentManager']], function(){
        // go back to previous page or request
        Route::get('/goback', function () {
            return redirect()->back();
            })->name('goback'); 
        

        // Change Password
        Route::get('/ChangePasswordPage', [UserManagementController::class, 'ChangePasswordPage'])->name('ChangePasswordPage');
        Route::post('/ChangePassword', [UserManagementController::class, 'ChangePassword'])->name('ChangePassword');
        
        Route::get('/', [DashboardsController::class, 'IFodderContentManagerDashboard'])->name('IFodderContentManagerDashboard');

        /*
        |--------------------------------------------------------------------------
        | IHealth - Read Only
        |--------------------------------------------------------------------------
        */
        Route::get('/AdminHealthGuide', [HealthGuidesController::class, 'HealthGuide'])->name('AdminHealthGuide');
        // Get Specific Symptoms or Details with in a System
        Route::get('/SpecificSystem', [HealthGuidesController::class, 'SpecificSystem'])->name('SpecificSystem');
        // Get all Symptoms List
        Route::get('/Symptoms', [HealthGuidesController::class, 'Symptoms'])->name('Symptoms');
        
        /*
        |--------------------------------------------------------------------------
        | IFodder - Read and Write
        |--------------------------------------------------------------------------
        */
        Route::get('/AdminCategories', [CategoriesController::class, 'Categories'])->name('AdminCategories');
        Route::get('/AdminPricing', [SrpYearController::class, 'SrpYears'])->name('AdminPricing');
        // Get Ingredients By CategoryId, Create and Edit for Ingredients
        Route::get('/AdminIngredients', [IngredientsController::class, 'IngredientsByCategory'])->name('AdminIngredients');
        Route::post('/CreateCategory', [CategoriesController::class, 'CreateCategory'])->name('CreateCategory');
        Route::post('/CreateIngredient', [IngredientsController::class, 'CreateIngredient'])->name('CreateIngredient');
        Route::post('/SaveEditedIngredient', [IngredientsController::class, 'SaveEditedIngredient'])->name('SaveEditedIngredient');
        // Get Price By Srp Year Id
        Route::get('/AdminPriceByYear', [SrpYearController::class, 'PriceByYear'])->name('AdminPriceByYear');
        Route::get('/DownloadPriceBySrp', [SrpYearController::class, 'DownloadPriceBySrp'])->name('DownloadPriceBySrp');
        Route::post('/UploadPriceBySrp', [SrpYearController::class, 'UploadPriceBySrp'])->name('UploadPriceBySrp');
        
        //CreateSrpYear with ingrededients
        Route::post('/CreateSrpYear', [SrpYearController::class, 'CreateSrpYear'])->name('CreateSrpYear');
        Route::post('/ActivateSrpYear', [SrpYearController::class, 'ActivateSrpYear'])->name('ActivateSrpYear'); 

        /*
        |--------------------------------------------------------------------------
        | Farmer Data
        |--------------------------------------------------------------------------
        */
        Route::get('/AdminFarmers', [FarmersController::class, 'Farmers'])->name('AdminFarmers');
        // Get Specific Farmer Details
        Route::get('/SpecificFarmer', [FarmersController::class, 'SpecificFarmer'])->name('SpecificFarmer');
        Route::post('/SaveEditedFarmerProfile', [FarmersController::class, 'SaveEditedFarmerProfile'])->name('SaveEditedFarmerProfile');
        Route::get('/DownloadFarmerList', [FarmersController::class, 'DownloadFarmerList'])->name('DownloadFarmerList');
        // Get Specific livestock Details and Visit logs
        Route::get('/PregnancyInfo', [FarmersController::class, 'PregnancyInfo'])->name('PregnancyInfo');
        Route::get('/SpecificLivestock', [FarmersController::class, 'SpecificLivestock'])->name('SpecificLivestock');
        Route::get('/DownloadLivestockList', [FarmersController::class, 'DownloadLivestockList'])->name('DownloadLivestockList');
        Route::post('/SaveCreatedLivestock', [FarmersController::class, 'SaveCreatedLivestock'])->name('SaveCreatedLivestock');
        
        /*
        |--------------------------------------------------------------------------
        | User Management
        |--------------------------------------------------------------------------
        */
        Route::get('/AdminStaff', [UserManagementController::class, 'Users'])->name('AdminStaff');
        // Get Specific Users Details
        Route::get('/SpecificStaff', [UserManagementController::class, 'SpecificStaff'])->name('SpecificStaff');
        Route::get('/ViewProvinces', [UserManagementController::class, 'ViewProvinces'])->name('ViewProvinces');
        Route::get('/ViewCities', [UserManagementController::class, 'ViewCities'])->name('ViewCities');
        Route::get('/ViewBrgy', [UserManagementController::class, 'ViewBrgy'])->name('ViewBrgy');

        /*
        |--------------------------------------------------------------------------
        | Reports
        |--------------------------------------------------------------------------
        */
        Route::get('/AdminReports', [ReportsController::class, 'Reports'])->name('AdminReports');
        Route::get('/AdminReportsHealthVisit', [ReportsController::class, 'ReportsHealthVisit'])->name('AdminReportsHealthVisit');

        // Download Reports Health Visit
        Route::get('/DownloadTopHealthIssuesList', [ReportsController::class, 'DownloadTopHealthIssuesList'])->name('DownloadTopHealthIssuesList');
        
        // View Ratings
        Route::get('/AdminVisitRatings', [VisitRatingController::class, 'VisitRatings'])->name('AdminVisitRatings');

	});

	// PCC Reports User
    Route::group(['prefix' => 'ReportsUser', 'as' => 'ReportsUser.', 'middleware' => ['reportsuser:ReportsUser']], function(){
        Route::get('/goback', function () {
            return redirect()->back();
            })->name('goback'); 


        // Change Password
        Route::get('/ChangePasswordPage', [UserManagementController::class, 'ChangePasswordPage'])->name('ChangePasswordPage');
        Route::post('/ChangePassword', [UserManagementController::class, 'ChangePassword'])->name('ChangePassword');
        
        // Custom function Returning Vue/Views
        Route::get('/', [DashboardsController::class, 'ReportsUserDashboard'])->name('ReportsUserDashboard');
                        
        /*
        |--------------------------------------------------------------------------
        | Farmer Data
        |--------------------------------------------------------------------------
        */
        Route::get('/AdminFarmers', [FarmersController::class, 'Farmers'])->name('AdminFarmers');
        // Get Specific Farmer Details
        Route::get('/SpecificFarmer', [FarmersController::class, 'SpecificFarmer'])->name('SpecificFarmer');
        Route::get('/SaveEditedFarmerProfile', [FarmersController::class, 'SaveEditedFarmerProfile'])->name('SaveEditedFarmerProfile');
        Route::get('/DownloadFarmerList', [FarmersController::class, 'DownloadFarmerList'])->name('DownloadFarmerList');
        // Get Specific livestock Details and Visit logs
        Route::get('/PregnancyInfo', [FarmersController::class, 'PregnancyInfo'])->name('PregnancyInfo');
        Route::get('/SpecificLivestock', [FarmersController::class, 'SpecificLivestock'])->name('SpecificLivestock');
        Route::get('/DownloadLivestockList', [FarmersController::class, 'DownloadLivestockList'])->name('DownloadLivestockList');
        Route::post('/SaveCreatedLivestock', [FarmersController::class, 'SaveCreatedLivestock'])->name('SaveCreatedLivestock');
        
        /*
        |--------------------------------------------------------------------------
        | User Management
        |--------------------------------------------------------------------------
        */
        Route::get('/AdminStaff', [UserManagementController::class, 'Users'])->name('AdminStaff');
        // Get Specific Users Details
        Route::get('/SpecificStaff', [UserManagementController::class, 'SpecificStaff'])->name('SpecificStaff');
        Route::get('/ViewProvinces', [UserManagementController::class, 'ViewProvinces'])->name('ViewProvinces');
        Route::get('/ViewCities', [UserManagementController::class, 'ViewCities'])->name('ViewCities');
        Route::get('/ViewBrgy', [UserManagementController::class, 'ViewBrgy'])->name('ViewBrgy');

        /*
        |--------------------------------------------------------------------------
        | IHealth - Read Only
        |--------------------------------------------------------------------------
        */
        Route::get('/AdminHealthGuide', [HealthGuidesController::class, 'HealthGuide'])->name('AdminHealthGuide');
        // Get Specific Symptoms or Details with in a System
        Route::get('/SpecificSystem', [HealthGuidesController::class, 'SpecificSystem'])->name('SpecificSystem');
        // Get all Symptoms List
        Route::get('/Symptoms', [HealthGuidesController::class, 'Symptoms'])->name('Symptoms');
                        
        
        /*
        |--------------------------------------------------------------------------
        | IFodder - Read Only
        |--------------------------------------------------------------------------
        */
        Route::get('/AdminCategories', [CategoriesController::class, 'Categories'])->name('AdminCategories');
        Route::get('/AdminPricing', [SrpYearController::class, 'SrpYears'])->name('AdminPricing');
        // Get Ingredients By CategoryId, Create and Edit for Ingredients
        Route::get('/AdminIngredients', [IngredientsController::class, 'IngredientsByCategory'])->name('AdminIngredients');
        
        // Get Price By Srp Year Id
        Route::get('/AdminPriceByYear', [SrpYearController::class, 'PriceByYear'])->name('AdminPriceByYear');
        Route::get('/DownloadPriceBySrp', [SrpYearController::class, 'DownloadPriceBySrp'])->name('DownloadPriceBySrp');


        /*
        |--------------------------------------------------------------------------
        | Reports
        |--------------------------------------------------------------------------
        */
        Route::get('/AdminReports', [ReportsController::class, 'Reports'])->name('AdminReports');
        Route::get('/AdminReportsHealthVisit', [ReportsController::class, 'ReportsHealthVisit'])->name('AdminReportsHealthVisit');

        // Download Reports Health Visit
        Route::get('/DownloadTopHealthIssuesList', [ReportsController::class, 'DownloadTopHealthIssuesList'])->name('DownloadTopHealthIssuesList');
        
        // View Ratings
        Route::get('/AdminVisitRatings', [VisitRatingController::class, 'VisitRatings'])->name('AdminVisitRatings');

	});

});
