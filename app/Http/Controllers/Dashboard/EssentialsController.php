<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Shared\Permission;
use App\Models\Shared\Role;

class EssentialsController extends Controller
{
    public function rolesList()
    {
        try {
            $rows = Role::select('id', 'name')->get();
            return response()->json($rows);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], $exception->getCode());
        }
    }
    public function permissionsList()
    {
        try {
            $rows = Permission::select('id', 'name')->get();
            return response()->json($rows);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], $exception->getCode());
        }
    }
}