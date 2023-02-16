<?php

namespace App\Http\Controllers;

use App\PurchaseLine;
use Illuminate\Http\Request;

class PurchaseLineController extends Controller
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
     * @param  \App\PurchaseLine  $purchaseLine
     * @return \Illuminate\Http\Response
     */
    public function show(PurchaseLine $purchaseLine)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PurchaseLine  $purchaseLine
     * @return \Illuminate\Http\Response
     */
    public function edit(PurchaseLine $purchaseLine)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PurchaseLine  $purchaseLine
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PurchaseLine $purchaseLine)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PurchaseLine  $purchaseLine
     * @return \Illuminate\Http\Response
     */
    public function destroy(PurchaseLine $purchaseLine)
    {
        //
    }
}
