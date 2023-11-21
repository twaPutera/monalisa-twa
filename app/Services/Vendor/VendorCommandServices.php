<?php

namespace App\Services\Vendor;

use App\Models\Vendor;
use App\Http\Requests\Vendor\VendorStoreRequest;
use App\Http\Requests\Vendor\VendorUpdateRequest;

class VendorCommandServices
{
    public function store(VendorStoreRequest $request)
    {
        $request->validated();

        $vendor = new Vendor;
        $vendor->kode_vendor = $request->kode_vendor;
        $vendor->nama_vendor = $request->nama_vendor;
        $vendor->save();
    }

    public function update(VendorUpdateRequest $request, $id)
    {
        $request->validated();

        $vendor = Vendor::findOrFail($id);
        $vendor->kode_vendor = $request->kode_vendor;
        $vendor->nama_vendor = $request->nama_vendor;
        $vendor->save();
    }

    public function destroy($id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->delete();
    }
}
