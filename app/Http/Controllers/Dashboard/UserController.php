<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Shared\User;

class UserController extends Controller
{
    public function index()
    {
        try {
            $rows = User::search(request('search'))->paginate(request('perPage'));
            return response()->json($rows);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], $exception->getCode());
        }
    }

    public function delete()
    {
        try {
            $row = User::findOrFail(request('id'));
            if (request('id') == 1 or $row->id == auth()->id()) return response()->json(['error' => 'This operation is not possible'], 403);
            $row->delete();
            return response()->json(null, 201);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], $exception->getCode());
        }
    }
}