<?php

namespace App\Http\Controllers\Admin\Approval;

use App\Http\Controllers\Controller;

class DaftarApprovalController extends Controller
{
    public function index()
    {
        return view('pages.admin.approval.daftar.index');
    }
}
