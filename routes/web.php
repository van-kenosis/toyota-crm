<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\VehicleReservationController;
use App\Http\Controllers\VehicleReleasesController;
use App\Http\Controllers\VehicleInventoryController;


//LOGIN
Route::get('/', [LoginController::class, 'index']);
Route::post('/login', [LoginController::class, 'login'])->name("login.user");
Route::post('/logout', [LoginController::class, 'logout'])->name("logout");

//DASHBOARD
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

//LEADS
Route::get('/leads', [LeadController::class, 'index'])->name('leads');;
Route::post('/leads', [LeadController::class, 'store'])->name('leads.store');
Route::get('/leads/individual/list', [LeadController::class, 'individualList'])->name('leads.individual.list');
Route::get('/leads/fleet/list', [LeadController::class, 'fleetList'])->name('leads.fleet.list');
Route::get('/leads/company/list', [LeadController::class, 'companyList'])->name('leads.company.list');
Route::get('/leads/government/list', [LeadController::class, 'governmentList'])->name('leads.government.list');
Route::post('/leads/processing', [LeadController::class, 'processing'])->name('leads.processing');
Route::delete('/leads/destroy', [LeadController::class, 'destroy'])->name('leads.destroy');
Route::get('/getProvince', [LeadController::class, 'getProvince'])->name('leads.getProvince');
Route::get('/getUnit', [LeadController::class, 'getUnit'])->name('leads.getUnit');
Route::get('/getInquiryType', [LeadController::class, 'getInquiryType'])->name('leads.getInquiryType');
Route::get('/leads/get-variants-and-colors', [LeadController::class, 'getVariantsAndColors'])->name('leads.getVariantsAndColors');
Route::get('/leads/get-variants', [LeadController::class, 'getVariants'])->name('leads.getVariants');
Route::get('/leads/get-colors', [LeadController::class, 'getColor'])->name('leads.getColor');
Route::get('leads/edit/{id}', [LeadController::class, 'edit'])->name('leads.edit');
Route::post('/leads/update/{id}', [LeadController::class, 'update'])->name('leads.update');
Route::post('/leads/updateRemarks/', [LeadController::class, 'updateRemarks'])->name('leads.updateRemarks');


// APPLICATION
Route::get('/application', [ApplicationController::class, 'index'])->name('application');
Route::get('/list/pending', [ApplicationController::class, 'list_pending'])->name('application.pending');
Route::get('/list/approved', [ApplicationController::class, 'list_approved'])->name('application.approved');
Route::get('/list/cancel', [ApplicationController::class, 'list_cancel'])->name('application.cancel');
Route::get('/list/cash', [ApplicationController::class, 'list_cash'])->name('application.cash');
Route::post('/application/store', [ApplicationController::class, 'store'])->name('application.store');
Route::get('application/edit/{id}', [ApplicationController::class, 'edit'])->name('application.edit');
Route::post('/application/update/{id}', [ApplicationController::class, 'update'])->name('application.update');
Route::get('/getBanks', [ApplicationController::class, 'getBanks'])->name('application.getBanks');
Route::get('/getStatus', [ApplicationController::class, 'getStatus'])->name('application.getStatus');
Route::post('/application/processing', [ApplicationController::class, 'processing'])->name('application.processing');
Route::post('/application/cancel', [ApplicationController::class, 'cancel'])->name('application.status.cancel');
Route::post('/application/store/banks', [ApplicationController::class, 'updateBanks'])->name('application.store.banks');
Route::get('/application/banks/{id}', [ApplicationController::class, 'getApplicationBanks'])->name('application.banks.get');
Route::post('/application/banks/approval/{id}', [ApplicationController::class, 'updateBankApproval'])->name('application.banks.approval');



// VEHICLE RESERVATION
Route::get('vehicle-reservation', [VehicleReservationController::class, 'index'])->name('vehicle.reservation');
Route::get('vehicle-reservation/units/list', [VehicleReservationController::class, 'availableUnitsList'])->name('vehicle.reservation.units.list');
Route::get('vehicle-reservation/pending/list', [VehicleReservationController::class, 'list_pending'])->name('vehicle.reservation.pending.list');
Route::get('vehicle-reservation/list', [VehicleReservationController::class, 'list_reserved'])->name('vehicle.reservation.list');
Route::get('vehicle-reservation/getReservedCount', [VehicleReservationController::class, 'getReservedCount'])->name('vehicle.reservation.getReservedCount');
Route::get('vehicle-reservation/reservationPerTeam', [VehicleReservationController::class, 'reservationPerTeam'])->name('vehicle.reservation.reservationPerTeam');
Route::post('vehicle-reservation/processing_pending', [VehicleReservationController::class, 'processing_pending'])->name('vehicle.reservation.processing_pending');
Route::post('vehicle-reservation/processing_reserved', [VehicleReservationController::class, 'processing_reserved'])->name('vehicle.reservation.processing_reserved');
Route::get('get-cs-number/{id}', [VehicleReservationController::class, 'getCSNumberByVehicleId'])->name('get-cs-number');
Route::post('vehicle/reservation/addCSNumber', [VehicleReservationController::class, 'addCSNumber'])->name('vehicle.reservation.addCSNumber');


// VEHICLE RELEASES
Route::get('vehicle-releases', [VehicleReleasesController::class, 'index'])->name('vehicle.releases');
Route::get('vehicle-releases/pending/list', [VehicleReleasesController::class, 'list_pending_for_release'])->name('vehicle.releases.pending.list');
Route::get('vehicle-releases/released/list', [VehicleReleasesController::class, 'list_release'])->name('vehicle.releases.list');
Route::get('vehicle-releases/releasedUnitsList', [VehicleReleasesController::class, 'releasedUnitsList'])->name('vehicle.releases.units.list');
Route::get('vehicle-releases/releasedPerTeam', [VehicleReleasesController::class, 'releasedPerTeam'])->name('vehicle.releases.releasedPerTeam');
Route::get('vehicle-releases/getReleasedCount', [VehicleReleasesController::class, 'getReleasedCount'])->name('vehicle.releases.getReleasedCount');
Route::post('vehicle-releases/processing', [VehicleReleasesController::class, 'processing'])->name('vehicle.releases.processing');

// VEHICLE INVENTORY
Route::get('vehicle-inventory', [VehicleInventoryController::class, 'index'])->name('vehicle.inventory');
Route::get('vehicle-inventory/list', [VehicleInventoryController::class, 'inventoryList'])->name('vehicle.inventory.list');
Route::get('vehicle-inventory/getTotalInventory', [VehicleInventoryController::class, 'getTotalInventory'])->name('vehicle.inventory.getTotalInventory');
