<?php

namespace App\Http\Controllers;

use App\Http\Requests\VendorRequest;
use App\Http\Resources\VendorResource;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        $ven = VendorResource::collection(
            Vendor::where('sch_id', $user->sch_id)
            ->where('sch_id', $user->sch_id)
            ->get()
        );

        return [
            'status' => 'true',
            'message' => 'Vendors List',
            'data' => $ven
        ];
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VendorRequest $request)
    {
        $request->validated($request->all());

        $vendorcode = Str::random(15);

        $user = Auth::user();

        $vend = Vendor::create([
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
            'vendor_code' => $vendorcode,
            'vendor_type' => $request->vendor_type,
            'initial_balance' => $request->initial_balance,
            'vendor_name' => $request->vendor_name,
            'company_name' => $request->company_name,
            'contact_address' => $request->contact_address,
            'contact_person' => $request->contact_person,
            'contact_phone' => $request->contact_phone,
            'email_address' => $request->email_address,
        ]);

        return [
            "status" => 'true',
            "message" => 'Vendor Added Successfully',
            "data" => $vend
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
