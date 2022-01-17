<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Shared\Country;
use App\Models\Shared\Permission;
use App\Models\Shared\Province;
use App\Models\Shared\Role;

class EssentialsController extends Controller
{
    public function rolesList()
    {
        try {
            $rows = Role::whereNotIn('id', [1, 2, 3, 4])->select('id', 'name')->get();
            return response()->json($rows);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 422);
        }
    }

    public function permissionsList()
    {
        try {
            $rows = Permission::select('id', 'name')->get();
            return response()->json($rows);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 422);
        }
    }

    public function countriesList()
    {
        try {
            $rows = Country::select('id', 'name')->get();
            return response()->json($rows);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 422);
        }
    }

    public function provincesList()
    {
        try {
            $rows = Province::select('id', 'name')->get();
            return response()->json($rows);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 422);
        }
    }
}