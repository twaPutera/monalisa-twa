<?php

namespace App\Http\Controllers\Admin\UserManagement;

use App\Imports\UserImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\User\UserQueryServices;
use App\Services\User\UserCommandServices;
use App\Http\Requests\User\UserStoreRequest;
use App\Services\User\UserDatatableServices;
use App\Http\Requests\User\UserImportRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Requests\UserChangePasswordRequest;

class UserController extends Controller
{
    protected $userCommandServices;
    protected $userQueryServices;
    protected $userDatatableServices;

    public function __construct(
        UserCommandServices $userCommandServices,
        UserQueryServices $userQueryServices,
        UserDatatableServices $userDatatableServices
    ) {
        $this->userCommandServices = $userCommandServices;
        $this->userQueryServices = $userQueryServices;
        $this->userDatatableServices = $userDatatableServices;
    }

    public function index()
    {
        return view('pages.admin.user-management.user.index');
    }

    public function store(UserStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $this->userCommandServices->store($request);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User has been created successfully',
                'data' => $data,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function show($id)
    {
        try {
            $data = $this->userQueryServices->findById($id);

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function update(UserUpdateRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $request->id = $id;
            $data = $this->userCommandServices->update($request, $id);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User has been updated successfully',
                'data' => $data,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function delete($id)
    {
        try {
            $data = $this->userCommandServices->delete($id);

            return response()->json([
                'success' => true,
                'message' => 'User has been deleted successfully',
            ]);
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function changePassword(UserChangePasswordRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $data = $this->userCommandServices->changePassword($request, $id);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Password has been changed successfully',
                'data' => $data,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function datatable(Request $request)
    {
        $datatable = $this->userDatatableServices->datatable($request);

        return $datatable;
    }

    public function import(UserImportRequest $request)
    {
        try {
            DB::beginTransaction();
            Excel::import(new UserImport, $request->file('file'));
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User has been imported successfully',
            ], 200);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $th) {
            DB::rollback();
            $failures = $th->failures();
            $errors = [];
            foreach ($failures as $failure) {
                $errors[] = [
                    'row' => $failure->row(),
                    'attribute' => $failure->attribute(),
                    'errors' => $failure->errors(),
                    'values' => $failure->values(),
                ];
            }
            return response()->json([
                'success' => false,
                'form' => 'import',
                'message' => $th->getMessage(),
                'errors' => $errors,
            ], 400);
        } catch (\Throwable $th) {
            // throw $th;
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function downloadTemplateImport()
    {
        $path = public_path('template-import/template-import-user-up.xlsx');
        return response()->download($path);
    }

    public function getDataUserSelect2(Request $request)
    {
        try {
            $data = $this->userQueryServices->getDataUserSelect2($request);
            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
