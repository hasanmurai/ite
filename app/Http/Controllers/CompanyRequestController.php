<?php

namespace App\Http\Controllers;

use App\Models\Company_Request;
use App\Http\Requests\StoreCompany_RequestRequest;
use App\Http\Requests\UpdateCompany_RequestRequest;

class CompanyRequestController extends Controller
{
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
     * @param  \App\Http\Requests\StoreCompany_RequestRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCompany_RequestRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company_Request  $company_Request
     * @return \Illuminate\Http\Response
     */
    public function show(Company_Request $company_Request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Company_Request  $company_Request
     * @return \Illuminate\Http\Response
     */
    public function edit(Company_Request $company_Request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCompany_RequestRequest  $request
     * @param  \App\Models\Company_Request  $company_Request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCompany_RequestRequest $request, Company_Request $company_Request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company_Request  $company_Request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company_Request $company_Request)
    {
        //
    }
}
