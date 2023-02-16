<?php

namespace App\Http\Controllers;

use App\PurchaseApproval;
use Illuminate\Http\Request;

class PurchaseApprovalController extends Controller
{
    public function __construct() {
        // Register ENUM type
        DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PurchaseApproval  $purchaseApproval
     * @return \Illuminate\Http\Response
     */
    public function show(PurchaseApproval $purchaseApproval)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PurchaseApproval  $purchaseApproval
     * @return \Illuminate\Http\Response
     */
    public function edit(PurchaseApproval $purchaseApproval)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PurchaseApproval  $purchaseApproval
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PurchaseApproval $purchaseApproval)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PurchaseApproval  $purchaseApproval
     * @return \Illuminate\Http\Response
     */
    public function destroy(PurchaseApproval $purchaseApproval)
    {
        //
    }
}
