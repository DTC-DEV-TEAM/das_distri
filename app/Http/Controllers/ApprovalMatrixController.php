<?php

namespace App\Http\Controllers;

use App\ApprovalMatrix;
use Illuminate\Http\Request;

class ApprovalMatrixController extends Controller
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
     * @param  \App\ApprovalMatrix  $approvalMatrix
     * @return \Illuminate\Http\Response
     */
    public function show(ApprovalMatrix $approvalMatrix)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ApprovalMatrix  $approvalMatrix
     * @return \Illuminate\Http\Response
     */
    public function edit(ApprovalMatrix $approvalMatrix)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ApprovalMatrix  $approvalMatrix
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ApprovalMatrix $approvalMatrix)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ApprovalMatrix  $approvalMatrix
     * @return \Illuminate\Http\Response
     */
    public function destroy(ApprovalMatrix $approvalMatrix)
    {
        //
    }
}
