<?php

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
// /* 
Route::get('/', function () {
    return redirect('admin/login');
    //return view('welcome');
});

Route::get('/admin/items/item-created','AdminItemsController@getItemsCreatedAPI')->name('itemscreate.API');
Route::get('/admin/items/item-updated','AdminItemsController@getItemsUpdatedAPI')->name('itemsupdate.API');
Route::get('/admin/pullout_requests/send-request-notification','AdminPulloutRequestsController@sendRequestNotification')->name('pullout.sendRequestNotification');

Route::group(['middleware' => ['web']], function() {
    //import sample template
    Route::get('/admin/service_details/import-template','AdminServiceDetailsController@importTemplate');
    Route::get('/admin/problem_details/import-template','AdminProblemDetailsController@importTemplate');
    Route::get('/admin/items/import-template','AdminItemsController@importTemplate');
    Route::get('/admin/items/inventory-upload','AdminItemsController@uploadInventory');
    Route::get('/admin/items/upload-inventory-template','AdminItemsController@uploadTemplate');
    Route::post('/admin/items/upload-inventory','AdminItemsController@inventoryUpload')->name('upload.inventory');
    //Route::get('/admin/items/item-created','AdminItemsController@getItemsCreatedAPI')->name('itemscreate.API');
    //Route::get('/admin/items/item-updated','AdminItemsController@getItemsUpdatedAPI')->name('itemsupdate.API');
    Route::get('/admin/items/export-excel','AdminItemsController@customExport');
    Route::get('/admin/items/skulegend-upload','AdminItemsController@uploadSKULegend');
    Route::get('/admin/items/upload-skulegend-template','AdminItemsController@uploadSKULegendTemplate');
    Route::post('/admin/items/upload-skulegend','AdminItemsController@SKULegendUpload')->name('upload.skulegend');
    //import templates
    Route::get('/admin/paths/import-template','AdminPathsController@importTemplate');
    Route::get('/admin/stores/import-template','AdminStoresController@importTemplate');
    Route::get('/admin/store_types/import-template','AdminStoreTypesController@importTemplate');
    Route::get('/admin/reasons/import-template','AdminReasonsController@importTemplate');
    Route::get('/admin/approval_matrix/import-template','AdminApprovalMatrixController@importTemplate');
    //returns
    //level1
    Route::get('/admin/scheduling/ReturnsSchedulingEdit/{id}','AdminReturnsHeaderController@ReturnsSchedulingEdit'); 
    Route::get('/admin/scheduling/ReturnsSchedulingDetail/{id}','AdminReturnsHeaderController@ReturnsSchedulingDetail'); 
    Route::post('/admin/scheduling/item-search','AdminReturnsHeaderController@itemSearch')->name('scheduling.item.search');
    Route::get('/admin/scheduling/ReturnsCloseRejectEdit/{id}','AdminReturnsHeaderController@ReturnsCloseRejectEdit'); 
    Route::get('/admin/scheduling/GetExtractReturnsScheduling','AdminReturnsHeaderController@GetExtractReturnsScheduling')->name('GetExtractReturnsScheduling');
    Route::get('/sendmail', 'AdminReturnsHeaderController@sendmail');
    Route::get('/admin/scheduling/ReturnsPulloutPrint/{id}','AdminReturnsHeaderController@ReturnsPulloutPrint'); 
    Route::get('admin/scheduling/ReturnPulloutUpdateONL','AdminReturnsHeaderController@ReturnPulloutUpdateONL');
    Route::get('/admin/scheduling/ReturnsTaggingEdit/{id}','AdminReturnsHeaderController@ReturnsTaggingEdit');
    
    Route::get('admin/scheduling/ReturnPulloutUpdateONLDTD','AdminReturnsHeaderController@ReturnPulloutUpdateONLDTD');
    
    Route::get('/admin/scheduling/ReturnsDeliveryEdit/{id}','AdminReturnsHeaderController@ReturnsDeliveryEdit'); 
    
    Route::get('/admin/scheduling/ReturnsDetail/{id}','AdminReturnsHeaderController@ReturnsDetail');
   
    //level2
    Route::get('/admin/returns_tagging/ReturnsTaggingEdit/{id}','AdminReturnsHeader1Controller@ReturnsTaggingEdit'); 
    Route::get('/admin/returns_tagging/ReturnsTaggingDetail/{id}','AdminReturnsHeader1Controller@ReturnsTaggingDetail'); 
    Route::get('/admin/returns_tagging/GetExtractReturnsTagging','AdminReturnsHeader1Controller@GetExtractReturnsTagging')->name('GetExtractReturnsTagging');
    
    //level3
    Route::get('/admin/returns_diagnosing/ReturnsDiagnosingEdit/{id}','AdminReturnsDiagnosingController@ReturnsDiagnosingEdit'); 
    Route::get('/admin/returns_diagnosing/ReturnsDiagnosingDetail/{id}','AdminReturnsDiagnosingController@ReturnsDiagnosingDetail'); 
    Route::get('/admin/returns_diagnosing/ReturnsSORReceivingEdit/{id}','AdminReturnsDiagnosingController@ReturnsSORReceivingEdit'); 
    Route::get('/admin/returns_diagnosing/TechLeadECOMM/{id}','AdminReturnsDiagnosingController@TechLeadECOMM');
    Route::get('/admin/returns_diagnosing/ReturnsReturnFormPrint/{id}','AdminReturnsDiagnosingController@ReturnsReturnFormPrint');
    Route::get('admin/returns_diagnosing/FormRejectUpdateStatus','AdminReturnsDiagnosingController@FormRejectUpdateStatus');
    Route::get('admin/returns_diagnosing/FormRepairUpdateStatus','AdminReturnsDiagnosingController@FormRepairUpdateStatus');
    Route::get('/admin/returns_diagnosing/GetExtractReturnsDiagnosing','AdminReturnsDiagnosingController@GetExtractReturnsDiagnosing')->name('GetExtractReturnsDiagnosing');


    Route::get('/admin/returns_diagnosing/GetExtractReturnsDiagnosingSC','AdminReturnsDiagnosingController@GetExtractReturnsDiagnosingSC')->name('GetExtractReturnsDiagnosingSC');

    Route::get('/admin/returns_diagnosing/ReturnsDiagnosingEditSC/{id}','AdminReturnsDiagnosingController@ReturnsDiagnosingEditSC'); 
    Route::get('/admin/returns_diagnosing/ReturnsReturnFormPrintSC/{id}','AdminReturnsDiagnosingController@ReturnsReturnFormPrintSC'); 
    Route::get('admin/returns_diagnosing/FormRejectUpdateStatusSC','AdminReturnsDiagnosingController@FormRejectUpdateStatusSC');
    Route::get('admin/returns_diagnosing/FormRepairUpdateStatusSC','AdminReturnsDiagnosingController@FormRepairUpdateStatusSC');
    //level4
    Route::get('/admin/returns_crf/ReturnsCRFPrint/{id}','AdminReturnsCrfController@ReturnsCRFPrint'); 
    Route::get('admin/returns_crf/CRFUpdateStatus','AdminReturnsCrfController@CRFUpdateStatus');
    Route::get('/admin/returns_crf/ReturnsClosingEdit/{id}','AdminReturnsCrfController@ReturnsClosingEdit'); 
    Route::get('/admin/returns_crf/GetExtractReturnsCRF','AdminReturnsCrfController@GetExtractReturnsCRF')->name('GetExtractReturnsCRF');

    //level5
    Route::get('/admin/returns_sor/ReturnsSOREdit/{id}','AdminReturnsSorController@ReturnsSOREdit'); 
    Route::get('/admin/returns_sor/GetExtractReturnsSOR','AdminReturnsSorController@GetExtractReturnsSOR')->name('GetExtractReturnsSOR');
    Route::get('/admin/returns_sor/ViewSOR/{id}','AdminReturnsSorController@ViewSOR'); 
    //history
    Route::get('/admin/returns_history/ReturnsHistoryDetail/{id}','AdminReturnsHistoryController@ReturnsHistoryDetail');
    Route::get('/admin/returns_history/ReturnsReturnFormPrint/{id}','AdminReturnsHistoryController@ReturnsReturnFormPrint'); 
    Route::get('/admin/returns_history/ReturnsCRFPrint/{id}','AdminReturnsHistoryController@ReturnsCRFPrint');   
    Route::get('/admin/returns_history/ReturnsHistoryEdit/{id}','AdminReturnsHistoryController@ReturnsHistoryEdit');   
    
    Route::get('/admin/returns_history/EditHistoryEcomm/{id}','AdminReturnsHistoryController@EditHistoryEcomm');  
    
    //export
    Route::get('/admin/returns_history/GetExtractReturns','AdminReturnsHistoryController@GetExtractReturns')->name('GetExtractReturns');
    
    Route::get('/admin/returns_history/GetExtractReturnsSC','AdminReturnsHistoryController@GetExtractReturnsSC')->name('GetExtractReturnsSC');

    //retailscheduling
    Route::get('/admin/returns_retail_scheduling/ReturnsSchedulingRetailEdit/{id}','AdminReturnsRetailSchedulingController@ReturnsSchedulingRetailEdit'); 
    Route::get('/admin/returns_retail_scheduling/ReturnsPulloutPrint/{id}','AdminReturnsRetailSchedulingController@ReturnsPulloutPrint'); 
    Route::get('admin/returns_retail_scheduling/ReturnPulloutUpdate','AdminReturnsRetailSchedulingController@ReturnPulloutUpdate');
    
    Route::get('/admin/returns_retail_scheduling/ReturnsDeliveryEditRTL/{id}','AdminReturnsRetailSchedulingController@ReturnsDeliveryEditRTL'); 

    Route::get('/admin/returns_retail_scheduling/GetExtractSchedulingReturnsRTL','AdminReturnsRetailSchedulingController@GetExtractSchedulingReturnsRTL')->name('GetExtractSchedulingReturnsRTL');
    
    //retaildiagnosing
    Route::get('/admin/retail_return_diagnosing/ReturnsDiagnosingRTLEdit/{id}','AdminRetailReturnDiagnosingController@ReturnsDiagnosingRTLEdit');
    Route::get('/admin/retail_return_diagnosing/ReturnsReturnFormPrintRTL/{id}','AdminRetailReturnDiagnosingController@ReturnsReturnFormPrintRTL');  
    Route::get('/admin/retail_return_diagnosing/TechLeadRTL/{id}','AdminRetailReturnDiagnosingController@TechLeadRTL');
    Route::get('/admin/retail_return_diagnosing/FormRejectUpdateStatusRTL','AdminRetailReturnDiagnosingController@FormRejectUpdateStatusRTL'); 
    Route::get('/admin/retail_return_diagnosing/FormRepairUpdateStatusRTL','AdminRetailReturnDiagnosingController@FormRepairUpdateStatusRTL'); 
    
    Route::get('/admin/retail_return_diagnosing/GetExtractDiagnosingReturnsRTL','AdminRetailReturnDiagnosingController@GetExtractDiagnosingReturnsRTL')->name('GetExtractDiagnosingReturnsRTL');
    Route::get('/admin/retail_return_diagnosing/GetExtractDiagnosingReturnsRTLSC','AdminRetailReturnDiagnosingController@GetExtractDiagnosingReturnsRTLSC')->name('GetExtractDiagnosingReturnsRTLSC');


    Route::get('/admin/retail_return_diagnosing/ReturnsDiagnosingRTLEditSC/{id}','AdminRetailReturnDiagnosingController@ReturnsDiagnosingRTLEditSC');
    Route::get('/admin/retail_return_diagnosing/ReturnsReturnFormPrintRTLSC/{id}','AdminRetailReturnDiagnosingController@ReturnsReturnFormPrintRTLSC');  
    Route::get('/admin/retail_return_diagnosing/FormRejectUpdateStatusRTLSC','AdminRetailReturnDiagnosingController@FormRejectUpdateStatusRTLSC'); 
    Route::get('/admin/retail_return_diagnosing/FormRepairUpdateStatusRTLSC','AdminRetailReturnDiagnosingController@FormRepairUpdateStatusRTLSC'); 
    
    
    //retailcrf
    Route::get('/admin/retail_return_crf/ReturnsCRFPrintRTL/{id}','AdminRetailReturnCrfController@ReturnsCRFPrintRTL');
    Route::get('admin/retail_return_crf/CRFUpdateStatusRTL','AdminRetailReturnCrfController@CRFUpdateStatusRTL');
    Route::get('/admin/retail_return_crf/ReturnsClosingEditRTL/{id}','AdminRetailReturnCrfController@ReturnsClosingEditRTL');
    Route::get('/admin/retail_return_crf/GetExtractCRFReturnsRTL','AdminRetailReturnCrfController@GetExtractCRFReturnsRTL')->name('GetExtractCRFReturnsRTL');
   
    Route::get('/admin/retail_return_crf/ReturnsClosingEditReplaceRTL/{id}','AdminRetailReturnCrfController@ReturnsClosingEditReplaceRTL');
    
    //retailsor
    Route::get('/admin/retail_return_sor/ReturnsSOREditRTL/{id}','AdminRetailReturnSorController@ReturnsSOREditRTL');
    Route::get('/admin/retail_return_sor/GetExtractSORReturnsRTL','AdminRetailReturnSorController@GetExtractSORReturnsRTL')->name('GetExtractSORReturnsRTL');
    Route::get('/admin/retail_return_sor/ViewSORRTL/{id}','AdminRetailReturnSorController@ViewSORRTL');
    //retailclosing
    Route::get('/admin/retail_return_closing/ReturnsClosingEditRTLOPS/{id}','AdminRetailReturnClosingController@ReturnsClosingEditRTLOPS');
    Route::get('/admin/retail_return_closing/GetExtractClosingReturnsRTL','AdminRetailReturnClosingController@GetExtractClosingReturnsRTL')->name('GetExtractClosingReturnsRTL');

    
    //retailhistory
    Route::get('/admin/retail_return_history/ReturnsHistoryDetailRTL/{id}','AdminRetailReturnHistoryController@ReturnsHistoryDetailRTL');

    Route::get('/admin/retail_return_history/ReturnsPulloutPrint/{id}','AdminRetailReturnHistoryController@ReturnsPulloutPrint');   
    Route::get('/admin/retail_return_history/ReturnsCRFPrintRTL/{id}','AdminRetailReturnHistoryController@ReturnsCRFPrintRTL');   
    Route::get('/admin/retail_return_history/ReturnsReturnFormPrintRTL/{id}','AdminRetailReturnHistoryController@ReturnsReturnFormPrintRTL');   
    
    Route::get('/admin/retail_return_history/GetExtractReturnsRTL','AdminRetailReturnHistoryController@GetExtractReturnsRTL')->name('GetExtractReturnsRTL');
    
    Route::get('/admin/retail_return_history/GetExtractReturnsRTLSC','AdminRetailReturnHistoryController@GetExtractReturnsRTLSC')->name('GetExtractReturnsRTLSC');

    Route::get('/admin/retail_return_history/ReturnsHistoryEdit/{id}','AdminRetailReturnHistoryController@ReturnsHistoryEdit');  
    
    //retailtagging
    Route::get('/admin/retail_for_verification/ReturnsTaggingRTLEdit/{id}','AdminRetailForVerificationController@ReturnsTaggingRTLEdit'); 
    Route::get('/admin/retail_for_verification/GetExtractReturnsTaggingRTL','AdminRetailForVerificationController@GetExtractReturnsTaggingRTL')->name('GetExtractReturnsTaggingRTL');
    
    //retailcreatingcrf
    Route::get('/admin/to_create_crf/ReturnsCreateEditRTL/{id}','AdminToCreateCrfController@ReturnsCreateEditRTL');
    Route::get('/admin/to_create_crf/GetExtractCreatedCRFReturnsRTL','AdminToCreateCrfController@GetExtractCreatedCRFReturnsRTL')->name('GetExtractCreatedCRFReturnsRTL');

    //ecommcreatingcrf
    Route::get('/admin/to_create_crf_ecomm/ReturnsCreateEditEcomm/{id}','AdminToCreateCrfEcommController@ReturnsCreateEditEcomm');

    //receiving
    Route::get('/admin/for_receiving_returns/ReturnsReceivingEdit/{id}','AdminForReceivingReturnsController@ReturnsReceivingEdit');
    Route::get('/admin/for_receiving_returns/GetExtractReturnsReceiving','AdminForReceivingReturnsController@GetExtractReturnsReceiving')->name('GetExtractReturnsReceiving');
    
    Route::get('/admin/for_receiving_returns/ReturnsSRRPrint/{id}','AdminForReceivingReturnsController@ReturnsSRRPrint');
    Route::get('admin/for_receiving_returns/ReturnsSRRUpdate','AdminForReceivingReturnsController@ReturnsSRRUpdate');
    
    //to close
    Route::get('/admin/returns_to_close/ReturnsCloseRejectEdit/{id}','AdminReturnsToCloseController@ReturnsCloseRejectEdit');
    Route::get('/admin/returns_to_close/GetExtractReturnsScheduling','AdminReturnsToCloseController@GetExtractReturnsScheduling')->name('GetExtractReturnsScheduling');  
   
    Route::get('/admin/returns_to_close/ReturnsDetailClose/{id}','AdminReturnsToCloseController@ReturnsDetailClose');
       
    //import
    Route::get('admin/scheduling/import-excel', 'AdminReturnsHeaderController@importPage');
    Route::get('/admin/scheduling/import-template', 'AdminReturnsHeaderController@importTemplate');
    Route::post('admin/scheduling/import-items','AdminReturnsHeaderController@importExcel')->name('upload.createitems');


    //script
    Route::post('/admin/scheduling/stores', 'AdminReturnsHeaderController@stores');
    Route::post('/admin/scheduling/backend_stores', 'AdminReturnsHeaderController@backend_stores');  
    Route::post('/admin/scheduling/branch_drop_off', 'AdminReturnsHeaderController@branch_drop_off'); 
    
    Route::post('/admin/scheduling/branch_change', 'AdminReturnsHeaderController@branch_change');  

    Route::post('/admin/retail_for_verification/backend_stores', 'AdminRetailForVerificationController@backend_stores');  
    Route::post('/admin/retail_for_verification/branch_drop_off', 'AdminRetailForVerificationController@branch_drop_off'); 

    Route::post('/admin/retail_for_verification/branch_change', 'AdminRetailForVerificationController@branch_change');  
    
    //to receive ecomm
    
    Route::get('/admin/to_receive_ecomm/ReturnsToReceiveEdit/{id}','AdminToReceiveEcommController@ReturnsToReceiveEdit');
    Route::get('/admin/to_receive_ecomm/GetExtractReturnsToReceiveSC','AdminToReceiveEcommController@GetExtractReturnsToReceiveSC')->name('GetExtractReturnsToReceiveSC');  
    
    Route::get('/admin/to_receive_ecomm/ReturnsReceivingSC/{id}','AdminToReceiveEcommController@ReturnsReceivingSC');
    
    Route::get('/admin/to_receive_ecomm/ReturnsSRRPrintSC/{id}','AdminToReceiveEcommController@ReturnsSRRPrintSC');
    
    Route::get('admin/to_receive_ecomm/ReturnsSRRUpdateSC','AdminToReceiveEcommController@ReturnsSRRUpdateSC');
        
    Route::get('/admin/to_receive_ecomm/ReturnsDiagnosingEditSC/{id}','AdminToReceiveEcommController@ReturnsDiagnosingEditSC');
    Route::get('/admin/to_receive_ecomm/ReturnsSORReceivingEditSC/{id}','AdminToReceiveEcommController@ReturnsSORReceivingEditSC');
    Route::get('/admin/to_receive_ecomm/ReturnsReturnFormPrintSC/{id}','AdminToReceiveEcommController@ReturnsReturnFormPrintSC');
    

    Route::get('/admin/to_receive_ecomm/ToReceiveEcomm/{id}','AdminToReceiveEcommController@ToReceiveEcomm'); 



    //to receive rtl
    Route::get('/admin/to_receive_retail/ReturnsToReceiveEditRTL/{id}','AdminToReceiveRetailController@ReturnsToReceiveEditRTL');
    Route::get('/admin/to_receive_retail/GetExtractReturnsToReceiveSCRTL','AdminToReceiveRetailController@GetExtractReturnsToReceiveSCRTL')->name('GetExtractReturnsToReceiveSCRTL');  
      
    Route::get('/admin/to_receive_retail/ReturnsDiagnosingRTLEditSC/{id}','AdminToReceiveRetailController@ReturnsDiagnosingRTLEditSC');
     Route::get('/admin/to_receive_retail/ReturnsReturnFormPrintRTLSC/{id}','AdminToReceiveRetailController@ReturnsReturnFormPrintRTLSC');
     
    Route::get('/admin/to_receive_retail/ReturnsSRRPrintSC/{id}','AdminToReceiveRetailController@ReturnsSRRPrintSC');
    
    Route::get('/admin/to_receive_retail/ReturnsSRRUpdateSCRTL','AdminToReceiveRetailController@ReturnsSRRUpdateSCRTL');

    Route::get('/admin/to_receive_retail/ToReceiveRTL/{id}','AdminToReceiveRetailController@ToReceiveRTL'); 

    Route::get('/admin/to_receive_retail/ToReceiveSCRTL/{id}','AdminToReceiveRetailController@ToReceiveSCRTL');

     
    //shipbackecomm
    Route::get('/admin/to_ship_back_ecomm/ReturnsReturnFormPrint/{id}','AdminToShipBackEcommController@ReturnsReturnFormPrint'); 
    
    //shipbackrtl
    Route::get('/admin/to_ship_back_rtl/ReturnsReturnFormPrintRTL/{id}','AdminToShipBackRtlController@ReturnsReturnFormPrintRTL'); 
    
    
    Route::get('/admin/retail_for_verification/ReturnsSRRPrint/{id}','AdminRetailForVerificationController@ReturnsSRRPrint');
    Route::get('admin/retail_for_verification/ReturnsSRRUpdateRTL','AdminRetailForVerificationController@ReturnsSRRUpdateRTL');
    
    Route::get('/admin/retail_for_verification/ReturnsSRRPrintForRTL/{id}','AdminRetailForVerificationController@ReturnsSRRPrintForRTL');
        
        
    Route::get('/admin/clear-view', function() {
        Artisan::call('view:clear');
        return "View cache is cleared!";
    });
    Route::get('/admin/users/useraccount-upload','AdminCmsUsersController@uploadUserAccount');
    Route::get('/admin/users/upload-useraccount-template','AdminCmsUsersController@uploadUserAccountTemplate');
    Route::post('/admin/users/upload-useraccount','AdminCmsUsersController@userAccountUpload')->name('upload.useraccount');
    
    Route::post('/admin/retail_return_request/ReturnRequestProcess','AdminRetailReturnRequestController@ReturnRequestProcess'); 
    Route::post('/admin/retail_return_request/stores','AdminRetailReturnRequestController@stores'); 
    Route::post('/admin/retail_return_request/backend_stores','AdminRetailReturnRequestController@backend_stores'); 
    
    Route::get('/admin/retail_return_request/GetExtractReturnsCreated','AdminRetailReturnRequestController@GetExtractReturnsCreated')->name('GetExtractReturnsCreated');

    //distriverify
    Route::get('/admin/distri_to_verify/ReturnsTaggingDISTRIEdit/{id}','AdminDistriToVerifyController@ReturnsTaggingDISTRIEdit'); 
    Route::get('/admin/distri_to_verify/GetExtractReturnsTaggingRTL','AdminDistriToVerifyController@GetExtractReturnsTaggingRTL')->name('GetExtractReturnsTaggingRTL');
    Route::post('/admin/distri_to_verify/backend_stores', 'AdminDistriToVerifyController@backend_stores');  
    Route::post('/admin/distri_to_verify/branch_drop_off', 'AdminDistriToVerifyController@branch_drop_off'); 
    Route::post('/admin/distri_to_verify/branch_change', 'AdminDistriToVerifyController@branch_change'); 
    Route::get('/admin/distri_to_verify/ReturnsSRRPrint/{id}','AdminDistriToVerifyController@ReturnsSRRPrint');
    Route::get('admin/distri_to_verify/ReturnsSRRUpdateDISTRI','AdminDistriToVerifyController@ReturnsSRRUpdateDISTRI');
    Route::get('/admin/distri_to_verify/ReturnsSRRPrintForDISTRI/{id}','AdminDistriToVerifyController@ReturnsSRRPrintForDISTRI');
    Route::get('/admin/distri_to_verify/ReturnsSchedulingEdit/{id}','AdminDistriToVerifyController@ReturnsSchedulingEdit'); 
    Route::post('/admin/distri_for_verification/backend_stores', 'AdminDistriToVerifyController@backend_stores');  
    Route::post('/admin/distri_for_verification/branch_change', 'AdminDistriToVerifyController@branch_change');  
    Route::post('/admin/distri_for_verification/branch_drop_off', 'AdminDistriToVerifyController@branch_drop_off');  


    //distrihistory
    Route::get('/admin/distri_return_history/ReturnsHistoryDetailDISTRI/{id}','AdminDistriReturnHistoryController@ReturnsHistoryDetailDISTRI');
    Route::get('/admin/distri_return_history/ReturnsPulloutPrint/{id}','AdminDistriReturnHistoryController@ReturnsPulloutPrint');   
    Route::get('/admin/distri_return_history/ReturnsCRFPrintDISTRI/{id}','AdminDistriReturnHistoryController@ReturnsCRFPrintDISTRI');   
    Route::get('/admin/distri_return_history/ReturnsReturnFormPrintDISTRI/{id}','AdminDistriReturnHistoryController@ReturnsReturnFormPrintDISTRI');     
    Route::get('/admin/distri_return_history/GetExtractReturnsDISTRI','AdminDistriReturnHistoryController@GetExtractReturnsDISTRI')->name('GetExtractReturnsDISTRI');
    Route::get('/admin/distri_return_history/GetExtractReturnsDISTRISC','AdminDistriReturnHistoryController@GetExtractReturnsDISTRISC')->name('GetExtractReturnsDISTRISC');
    Route::get('/admin/distri_return_history/ReturnsHistoryEdit/{id}','AdminDistriReturnHistoryController@ReturnsHistoryEdit');  
    //distrischeduling
    Route::get('/admin/returns_distri_scheduling/ReturnsSchedulingDISTRIEdit/{id}','AdminReturnsDistriSchedulingController@ReturnsSchedulingDISTRIEdit'); 
    Route::get('/admin/returns_distri_scheduling/ReturnsPulloutPrint/{id}','AdminReturnsDistriSchedulingController@ReturnsPulloutPrint'); 
    Route::get('admin/returns_distri_scheduling/ReturnPulloutUpdate','AdminReturnsDistriSchedulingController@ReturnPulloutUpdate');
    Route::get('/admin/returns_distri_scheduling/ReturnsDeliveryEditDISTRI/{id}','AdminReturnsDistriSchedulingController@ReturnsDeliveryEditDISTRI'); 
    Route::get('/admin/returns_distri_scheduling/GetExtractSchedulingReturnsDISTRI','AdminReturnsDistriSchedulingController@GetExtractSchedulingReturnsDISTRI')->name('GetExtractSchedulingReturnsDISTRI');
    //distridiagnosing
    Route::get('/admin/distri_return_diagnosing/ReturnsDiagnosingDISTRIEdit/{id}','AdminDistriReturnDiagnosingController@ReturnsDiagnosingDISTRIEdit');
    Route::get('/admin/distri_return_diagnosing/ReturnsReturnFormPrintDISTRI/{id}','AdminDistriReturnDiagnosingController@ReturnsReturnFormPrintDISTRI');  
    Route::get('/admin/distri_return_diagnosing/TechLeadDISTRI/{id}','AdminDistriReturnDiagnosingController@ReturnsReturnFormPrintDISTRI');  
    Route::get('/admin/distri_return_diagnosing/FormRejectUpdateStatusDISTRI','AdminDistriReturnDiagnosingController@FormRejectUpdateStatusDISTRI'); 
    Route::get('/admin/distri_return_diagnosing/FormRepairUpdateStatusDISTRI','AdminDistriReturnDiagnosingController@FormRepairUpdateStatusDISTRI');  
    Route::get('/admin/distri_return_diagnosing/GetExtractDiagnosingReturnsDISTRI','AdminDistriReturnDiagnosingController@GetExtractDiagnosingReturnsDISTRI')->name('GetExtractDiagnosingReturnsDISTRI');
    Route::get('/admin/distri_return_diagnosing/GetExtractDiagnosingReturnsDISTRISC','AdminDistriReturnDiagnosingController@GetExtractDiagnosingReturnsDISTRISC')->name('GetExtractDiagnosingReturnsDISTRISC');
    Route::get('/admin/distri_return_diagnosing/ReturnsDiagnosingDISTRIEditSC/{id}','AdminDistriReturnDiagnosingController@ReturnsDiagnosingDISTRIEditSC');
    Route::get('/admin/distri_return_diagnosing/ReturnsReturnFormPrintDISTRISC/{id}','AdminDistriReturnDiagnosingController@ReturnsReturnFormPrintDISTRISC');  
    Route::get('/admin/distri_return_diagnosing/FormRejectUpdateStatusDISTRISC','AdminDistriReturnDiagnosingController@FormRejectUpdateStatusDISTRISC'); 
    Route::get('/admin/distri_return_diagnosing/FormRepairUpdateStatusDISTRISC','AdminDistriReturnDiagnosingController@FormRepairUpdateStatusDISTRISC'); 
    //distritoshipback
    Route::get('/admin/to_ship_back_distri/ReturnsReturnFormPrintDISTRI/{id}','AdminToShipBackDistriController@ReturnsReturnFormPrintDISTRI'); 
    //tocloseDISTRI
    Route::get('/admin/to_close_distri/ReturnsClosingEditDISTRIOPS/{id}','AdminToCloseDistriController@ReturnsClosingEditDISTRIOPS');
    Route::get('/admin/to_close_distri/GetExtractClosingReturnsDISTRI','AdminToCloseDistriController@GetExtractClosingReturnsDISTRI')->name('GetExtractClosingReturnsDISTRI');
    //tocreatecrfDISTRI
    Route::get('/admin/crf_to_create_distri/ReturnsCreateEditDISTRI/{id}','AdminCrfToCreateDistriController@ReturnsCreateEditDISTRI');
    Route::get('/admin/crf_to_create_distri/GetExtractCreatedCRFReturnsDISTRI','AdminCrfToCreateDistriController@GetExtractCreatedCRFReturnsDISTRI')->name('GetExtractCreatedCRFReturnsDISTRI');

    //toSORDISTRI
    Route::get('/admin/to_sor_distri/ReturnsSOREditDISTRI/{id}','AdminToSorDistriController@ReturnsSOREditDISTRI');
    Route::get('/admin/to_sor_distri/GetExtractSORReturnsDISTRI','AdminToSorDistriController@GetExtractSORReturnsRTL')->name('GetExtractSORReturnsDISTRI');
    Route::get('/admin/to_sor_distri/ViewSORDISTRI/{id}','AdminToSorDistriController@ViewSORDISTRI');
    //toreceiveDISTRI
    Route::get('/admin/to_receive_distri/ReturnsToReceiveEditDISTRI/{id}','AdminToReceiveDistriController@ReturnsToReceiveEditDISTRI');
    Route::get('/admin/to_receive_distri/GetExtractReturnsToReceiveSCDISTRI','AdminToReceiveDistriController@GetExtractReturnsToReceiveSCDISTRI')->name('GetExtractReturnsToReceiveSCDISTRI');  
    Route::get('/admin/to_receive_distri/ReturnsDiagnosingDISTRIEditSC/{id}','AdminToReceiveDistriController@ReturnsDiagnosingDISTRIEditSC');
    Route::get('/admin/to_receive_distri/ReturnsReturnFormPrintDISTRISC/{id}','AdminToReceiveDistriController@ReturnsReturnFormPrintDISTRISC');
    Route::get('/admin/to_receive_distri/ReturnsSRRPrintSC/{id}','AdminToReceiveDistriController@ReturnsSRRPrintSC');
    Route::get('/admin/to_receive_distri/ReturnsSRRPrint/{id}','AdminToReceiveDistriController@ReturnsSRRPrint');
    Route::get('/admin/to_receive_distri/ReturnsSchedulingDISTRIEdit/{id}','AdminDistriToVerifyController@ReturnsSchedulingDISTRIEdit');
    Route::get('/admin/to_receive_distri/ReturnsSRRUpdateSCDISTRI','AdminToReceiveDistriController@ReturnsSRRUpdateSCDISTRI');
    Route::get('/admin/to_receive_distri/ToReceiveDistri/{id}','AdminToReceiveDistriController@ToReceiveDistri');
    
    
    Route::get('/admin/returns_to_close/ReturnsClosingEdit/{id}','AdminReturnsToCloseController@ReturnsClosingEdit');


    Route::get('/admin/returns_to_close/ReturnsClosingEdit/{id}','AdminReturnsToCloseController@ReturnsClosingEdit');
    
    Route::get('admin/retail_return_history/import-retail', 'AdminRetailReturnHistoryController@importPage');

    Route::post('admin/retail_return_history/import-retails','AdminRetailReturnHistoryController@importExcel')->name('upload.importRTL');



    Route::get('/admin/scheduling/ReturnsSchedulingEdit/{id}','AdminReturnsHeaderController@ReturnsSchedulingEdit'); 
    
    Route::get('admin/returns_history/import-ecomm', 'AdminReturnsHistoryController@importPage');

    Route::post('admin/returns_history/import-ecomms','AdminReturnsHistoryController@importExcel')->name('upload.importECOMM');

    // Step that can reuse, no editing of data
    // Turnover Process
    Route::post('/admin/to_receive_retail/ReturnsToReceiveEditRTL/turnover','AdminToReceiveRetailController@toTurnOverProcess')->name('turnover');
    Route::get('/admin/to_receive_retail/ReturnsToReceiveEditRTL/custom_reference_number/{ref_number}/{module_mainpath}','AdminToReceiveRetailController@returnReferenceNumber')->name('custom_reference_number');
    // For Warranty Claim
    Route::post('/admin/to_receive_retail/ReturnsDiagnosingRTLEdit/for_warranty_claim','AdminRetailReturnDiagnosingController@forWarrantyClaim')->name('for_warranty_claim');
    Route::get('/admin/to_receive_retail/ReturnsDiagnosingRTLEdit/custom_reference_number/{ref_number}/{module_mainpath}','AdminRetailReturnDiagnosingController@returnReferenceNumber')->name('fwc_custom_reference_number');
    
});