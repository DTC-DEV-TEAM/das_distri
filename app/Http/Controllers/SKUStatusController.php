<?php

namespace App\Http\Controllers;

use App\SKUStatus;
use Illuminate\Http\Request;

class SKUStatusController extends Controller
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
     * @param  \App\SKUStatus  $sKUStatus
     * @return \Illuminate\Http\Response
     */
    public function show(SKUStatus $sKUStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SKUStatus  $sKUStatus
     * @return \Illuminate\Http\Response
     */
    public function edit(SKUStatus $sKUStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SKUStatus  $sKUStatus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SKUStatus $sKUStatus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SKUStatus  $sKUStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy(SKUStatus $sKUStatus)
    {
        //
    }
}
