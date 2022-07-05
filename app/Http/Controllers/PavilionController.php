<?php

namespace App\Http\Controllers;

use App\Models\Pavilion;
use App\Http\Requests\StorePavilionRequest;
use App\Http\Requests\UpdatePavilionRequest;

class PavilionController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePavilionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePavilionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pavilion  $pavilion
     * @return \Illuminate\Http\Response
     */
    public function show(Pavilion $pavilion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePavilionRequest  $request
     * @param  \App\Models\Pavilion  $pavilion
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePavilionRequest $request, Pavilion $pavilion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pavilion  $pavilion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pavilion $pavilion)
    {
        //
    }
}
