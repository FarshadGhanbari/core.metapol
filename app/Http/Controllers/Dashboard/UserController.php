<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shared\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $rows = User::search($request->search)->latest()->paginate($request->perPage);
        return response()->json($rows);
    }
}