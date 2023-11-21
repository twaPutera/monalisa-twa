<?php

namespace App\Http\Controllers\Admin\Approval;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Approval\ApprovalQueryServices;
use App\Services\Approval\ApprovalCommandServices;
use App\Services\Approval\ApprovalDatatableServices;

class ApprovalController extends Controller
{
    protected $approvalQueryServices;
    protected $approvalCommandServices;
    protected $approvalDatatableServices;

    public function __construct(
        ApprovalQueryServices $approvalQueryServices,
        ApprovalCommandServices $approvalCommandServices,
        ApprovalDatatableServices $approvalDatatableServices
    ) {
        $this->approvalQueryServices = $approvalQueryServices;
        $this->approvalCommandServices = $approvalCommandServices;
        $this->approvalDatatableServices = $approvalDatatableServices;
    }

    public function datatable(Request $request)
    {
        return $this->approvalDatatableServices->datatable($request);
    }
}
