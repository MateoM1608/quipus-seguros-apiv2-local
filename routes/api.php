<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

# Controllers for auth
use App\Http\Controllers\AuthApi\ResetPasswordController;
use App\Http\Controllers\AuthApi\AuthController;

# Controllers for user
use App\Http\Controllers\PermissionUserController;
use App\Http\Controllers\ProfileUserController;
use App\Http\Controllers\UserController;

# Controllers for Profile
use App\Http\Controllers\PermissionProfileController;
use App\Http\Controllers\ProfileController;

# Controllers for Modules
use App\Http\Controllers\ModuleController;

# Controllers for Clients
use App\Http\Controllers\SClientController;

# Controllers for Identification type
use App\Http\Controllers\GIdentificationTypeController;

# Controllers for Cities
use App\Http\Controllers\GCityController;

# Controllers for Policy
use App\Http\Controllers\SInsuranceCarrierController;
use App\Http\Controllers\SCommissionController;
use App\Http\Controllers\SPaymentController;
use App\Http\Controllers\GVendorController;
use App\Http\Controllers\SAgencyController;
use App\Http\Controllers\SBranchController;
use App\Http\Controllers\SPolicyController;
use App\Http\Controllers\SAnnexController;
use App\Http\Controllers\SClaimController;
use App\Http\Controllers\SRiskController;

# Controllers for Reports
use App\Http\Controllers\Reports\RCommissionReceivable;
use App\Http\Controllers\Reports\RExpirationController;
use App\Http\Controllers\Reports\RDashboardController;
use App\Http\Controllers\Reports\RPortfolioController;
use App\Http\Controllers\Reports\RProduction;

# Controllers for CRM
use App\Http\Controllers\CCaseStageController;
use App\Http\Controllers\CCaseAreaController;
use App\Http\Controllers\CCaseNoteController;
use App\Http\Controllers\CTypeCaseController;
use App\Http\Controllers\CCaseController;

# Controllers for Cargues
use App\Http\Controllers\DataUpload\IntegratorController;
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

Route::group(['prefix' => 'v1'], function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');

    Route::prefix('password')->group(function () {
        Route::post('/reset', [ResetPasswordController::class, 'notification'])->name('password-reset');
        Route::post('/change', [ResetPasswordController::class, 'changePassword'])->name('password-change');
    });

    Route::group(['middleware' => ['auth.jwt']], function () {
        Route::prefix('login')->group(function () {
            Route::post('/refresh', [AuthController::class, 'refresh'])->name('login-refresh');
            Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
            Route::post('/me', [AuthController::class, 'me'])->name('login-me');
        });

        Route::group(['middleware' => ['connection', 'authorization']], function () {

            /**
             * User
             */
            Route::prefix('user')->group(function () {
                Route::get('/', [UserController::class, 'index'])->name('user-consult');
                Route::post('/', [UserController::class, 'store'])->name('user-create');
                Route::put('/{user}', [UserController::class,'update'])->name('user-update');
                Route::delete('/{id}', [UserController::class,'destroy'])->name('user-delete');

                Route::prefix('password')->group(function () {
                    Route::put('/change', [UserController::class, 'changePassword'])->name('user-change-password');
                });

                Route::prefix('permission')->group(function () {
                    Route::get('/', [PermissionUserController::class,'index'])->name('user-permission-consult');
                    Route::post('/', [PermissionUserController::class,'store'])->name('user-permission-store');
                    Route::delete('/{permission}', [PermissionUserController::class,'destroy'])->name('user-permission-destroy');
                });
            });

            /**
             * Profiles
             */
            Route::prefix('profile')->group(function () {
                Route::get('/', [ProfileController::class, 'index'])->name('profile-consult');
                Route::post('/', [ProfileController::class, 'store'])->name('profile-store');
                Route::put('/{profile}', [ProfileController::class, 'update'])->name('profile-update');
                Route::delete('/{profile}', [ProfileController::class, 'destroy'])->name('profile-destroy');
                Route::prefix('permission-profile')->group(function () {
                    Route::get('/', [PermissionProfileController::class,'index'])->name('profile-permission-consult');
                    Route::post('/', [PermissionProfileController::class,'store'])->name('profile-permission-store');
                    Route::delete('/{permission}', [PermissionProfileController::class,'destroy'])->name('profile-permission-destroy');
                });
            });

            /**
             * Profiles users
             */
            Route::prefix('profileuser')->group(function () {
                Route::get('/', [ProfileUserController::class, 'index'])->name('profile-user-consult');
                Route::post('/', [ProfileUserController::class, 'store'])->name('profile-user-store');
                Route::delete('/{id}', [ProfileUserController::class, 'destroy'])->name('profile-user-update');
            });

            /**
             * Modules
             */
            Route::prefix('module')->group(function () {
                Route::get('/', [ModuleController::class, 'index'])->name('module-consult');
                Route::post('/', [ModuleController::class, 'store'])->name('module-store');
                Route::put('/{id}', [ModuleController::class, 'update'])->name('module-update');
                Route::delete('/{id}', [ModuleController::class, 'destroy'])->name('module-destroy');
            });

            /**
             * Cliente
             */
            Route::prefix('client')->group(function () {
                Route::get('/', [SClientController::class, 'index'])->name('client-consult');
                Route::post('/', [SClientController::class, 'store'])->name('client-store');
                Route::put('/{id}', [SClientController::class, 'update'])->name('client-update');
                Route::delete('/{id}', [SClientController::class, 'destroy'])->name('client-destroy');
            });

            /**
             * Identification type
             */
            Route::prefix('identificationType')->group(function () {
                Route::get('/', [GIdentificationTypeController::class, 'index'])->name('identification-type-consult');
            });

            /**
             * Country
             */
            Route::prefix('country')->group(function () {
                Route::get('/', [GCountryController::class, 'index'])->name('city-consult');
            });

            /**
             * City
             */
            Route::prefix('city')->group(function () {
                Route::get('/', [GCityController::class, 'index'])->name('city-consult');
            });

            /**
             * Policies
             */
            Route::prefix('policies')->group(function () {
                Route::get('/', [SPolicyController::class, 'index'])->name('policies-consult');
                Route::post('/', [SPolicyController::class, 'store'])->name('policies-store');
                Route::put('/{id}', [SPolicyController::class, 'update'])->name('policies-update');
                Route::delete('/{id}', [SPolicyController::class, 'destroy'])->name('policies-destroy');
            });

            /**
             * Agencies
             */
            Route::prefix('agency')->group(function () {
                Route::get('/', [SAgencyController::class, 'index'])->name('agency-consult');
                Route::post('/', [SAgencyController::class, 'store'])->name('agency-store');
                Route::put('/{id}', [SAgencyController::class, 'update'])->name('agency-update');
                Route::delete('/{id}', [SAgencyController::class, 'destroy'])->name('agency-destroy');
            });

            /**
             * Insurance carrier
             */
            Route::prefix('insuranceCarrier')->group(function () {
                Route::get('/', [SInsuranceCarrierController::class, 'index'])->name('insurance-carrier-consult');
                Route::post('/', [SInsuranceCarrierController::class, 'store'])->name('insurance-carrier-store');
                Route::put('/{id}', [SInsuranceCarrierController::class, 'update'])->name('insurance-carrier-update');
                Route::delete('/{id}', [SInsuranceCarrierController::class, 'destroy'])->name('insurance-carrier-destroy');
            });

            /**
             * Branch
             */
            Route::prefix('branch')->group(function () {
                Route::get('/', [SBranchController::class, 'index'])->name('branch-consult');
                Route::post('/', [SBranchController::class, 'store'])->name('branch-store');
                Route::put('/{id}', [SBranchController::class, 'update'])->name('branch-update');
                Route::delete('/{id}', [SBranchController::class, 'destroy'])->name('branch-destroy');
            });

            /**
             * Vendors
             */
            Route::prefix('vendor')->group(function () {
                Route::get('/', [GVendorController::class, 'index'])->name('vendor-consult');
                Route::post('/', [GVendorController::class, 'store'])->name('vendor-store');
                Route::put('/{id}', [GVendorController::class, 'update'])->name('vendor-update');
                Route::delete('/{id}', [GVendorController::class, 'destroy'])->name('vendor-destroy');
            });

            /**
             * Annex
             */
            Route::prefix('annex')->group(function () {
                Route::get('/', [SAnnexController::class, 'index'])->name('annex-consult');
                Route::post('/', [SAnnexController::class, 'store'])->name('annex-store');
                Route::put('/{id}', [SAnnexController::class, 'update'])->name('annex-update');
                Route::delete('/{id}', [SAnnexController::class, 'destroy'])->name('annex-destroy');
            });

            /**
             * Risk
             */
            Route::prefix('risk')->group(function () {
                Route::get('/', [SRiskController::class ,'index'])->name('risk-consult');
                Route::post('/', [SRiskController::class ,'store'])->name('risk-store');
                Route::put('/{id}', [SRiskController::class ,'update'])->name('risk-update');
                Route::delete('/{id}', [SRiskController::class ,'destroy'])->name('risk-destroy');
            });

            /**
             * Claim
             */
            Route::prefix('claim')->group(function () {
                Route::get('/', [SClaimController::class, 'index'])->name('claim-consult');
                Route::post('/', [SClaimController::class, 'store'])->name('claim-store');
                Route::put('/{id}', [SClaimController::class, 'update'])->name('claim-update');
                Route::delete('/{id}', [SClaimController::class, 'destroy'])->name('claim-destroy');
            });

            /**
             * Payment
             */
            Route::prefix('payment')->group(function () {
                Route::get('/', [SPaymentController::class, 'index'])->name('payment-consult');
                Route::post('/', [SPaymentController::class, 'store'])->name('payment-store');
                Route::put('/{id}', [SPaymentController::class, 'update'])->name('payment-update');
                Route::delete('/{id}', [SPaymentController::class, 'destroy'])->name('payment-destroy');
            });

            /**
             * Commission
             */
            Route::prefix('commission')->group(function () {
                Route::get('/', [SCommissionController::class, 'index'])->name('commission-consult');
                Route::post('/', [SCommissionController::class, 'store'])->name('commission-store');
                Route::put('/{id}', [SCommissionController::class, 'update'])->name('commission-update');
                Route::delete('/{id}', [SCommissionController::class, 'destroy'])->name('commission-destroy');
            });

            /**
             * Reports
             */
            Route::prefix('reports')->group(function () {
                Route::get('/commissionReceivable', [RCommissionReceivable::class, 'index'])->name('reports-commissionReceivable');
                Route::get('/expiration', [RExpirationController::class, 'index'])->name('reports-expiration');
                Route::get('/dashboard', [RDashboardController::class, 'index'])->name('reports-dashboard');
                Route::get('/portfolio', [RPortfolioController::class, 'index'])->name('reports-portfolio');
                Route::get('/production', [RProduction::class, 'index'])->name('reports-production');
            });

            /**
             * CRM
             */

            /**
             * Stages CRM
             */
            Route::prefix('stagesCrm')->group(function () {
                Route::get('/', [CCaseStageController::class, 'index'])->name('stages-crm-consult');
                Route::post('/', [CCaseStageController::class, 'store'])->name('stages-crm-store');
                Route::put('/{id}', [CCaseStageController::class, 'update'])->name('stages-crm-update');
                Route::delete('/{id}', [CCaseStageController::class, 'destroy'])->name('stages-crm-destroy');
            });

            /**
             * Areas CRM
             */
            Route::prefix('areasCrm')->group(function () {
                Route::get('/', [CCaseAreaController::class, 'index'])->name('areas-crm-consult');
                Route::post('/', [CCaseAreaController::class, 'store'])->name('areas-crm-store');
                Route::put('/{id}', [CCaseAreaController::class, 'update'])->name('areas-crm-update');
                Route::delete('/{id}', [CCaseAreaController::class, 'destroy'])->name('areas-crm-destroy');
            });


            Route::prefix('crm')->group(function () {

                /**
                 * Type Case
                 */
                Route::prefix('typeCases')->group(function () {
                    Route::get('/', [CTypeCaseController::class, 'index'])->name('type-cases-consult');
                });

                /**
                 * Cases
                 */
                Route::prefix('cases')->group(function () {
                    Route::get('/', [CCaseController::class, 'index'])->name('cases-consult');
                    Route::post('/', [CCaseController::class, 'store'])->name('cases-store');
                    Route::put('/{id}', [CCaseController::class, 'update'])->name('cases-update');
                    Route::delete('/{id}', [CCaseController::class, 'destroy'])->name('cases-destroy');
                });

                /**
                 * Notes
                 */
                Route::prefix('notes')->group(function () {
                    Route::get('/', [CCaseNoteController::class, 'index'])->name('notes-consult');
                    Route::post('/', [CCaseNoteController::class, 'store'])->name('notes-store');
                    Route::put('/{id}', [CCaseNoteController::class, 'update'])->name('notes-update');
                    Route::delete('/{id}', [CCaseNoteController::class, 'destroy'])->name('notes-destroy');
                });
            });

            Route::prefix('upload')->group(function () {
                Route::post('/', [IntegratorController::class, 'store'])->name('cargue-store');
            });
        });
    });
});
