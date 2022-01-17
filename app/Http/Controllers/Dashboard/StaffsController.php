<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Shared\User;
use Laravel\Fortify\Rules\Password;

class StaffsController extends Controller
{
    public function index()
    {
        try {
            $rows = User::whereNotIn('role_id', [1, 2, 3, 4]);
            if (request('status') and !empty(request('status'))) {
                $rows = $rows->whereIn('status', request('status'));
            }
            if (request('dateRange') and !empty(request('dateRange'))) {
                $rows = $rows->whereBetween('created_at', request('dateRange'));
            }
            if (request('search') and !empty(request('search'))) {
                $rows = $rows->search(request('search'));
            }
            $rows = $rows->paginate(request('perPage'));
            return response()->json($rows);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }

    public function store()
    {
        $validate = request()->validate([
            'role_id' => ['required'],
            'first_name' => ['required', 'max:191'],
            'last_name' => ['required', 'max:191'],
            'mobile' => ['required', 'string', 'max:11', 'unique:common_database.users,mobile'],
            'email' => ['max:191', 'unique:common_database.users,email'],
            'status' => ['required'],
            'password' => ['required', 'string', new Password, 'confirmed']
        ]);
        if (!$validate) {
            return response()->json($validate, 422);
        }
        try {
            $row = User::create(request()->except('permissions'));
            $row->permissions()->sync(request('permissions'));
            return response()->json(null, 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }

    public function edit()
    {
        try {
            if (request('id') == 1 or request('id') == auth()->id()) return response()->json(['message' => 'This operation is not possible'], 403);
            $row = User::findOrFail(request('id'));
            return response()->json($row, 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }

    public function update()
    {
        if (request('id') == 1 or request('id') == auth()->id()) return response()->json(['message' => 'This operation is not possible'], 403);
        $row = User::findOrFail(request('id'));
        $validate = request()->validate([
            'role_id' => ['required'],
            'first_name' => ['required', 'max:191'],
            'last_name' => ['required', 'max:191'],
            'mobile' => ['required', 'string', 'max:11', 'unique:common_database.users,mobile,' . $row->id],
            'email' => ['max:191', 'unique:common_database.users,email,' . $row->id],
            'status' => ['required'],
            'password' => [new Password, 'confirmed']
        ]);
        if (!$validate) {
            return response()->json($validate, 422);
        }
        try {
            $row->update(request()->except('permissions'));
            $row->permissions()->sync(request('permissions'));
            return response()->json(null, 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }

    public function delete()
    {
        try {
            if (request('id') == 1 or request('id') == auth()->id()) return response()->json(['message' => 'This operation is not possible'], 403);
            $row = User::findOrFail(request('id'));
            $row->delete();
            return response()->json(null, 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }

    public function enable()
    {
        try {
            if (request('id') == 1 or request('id') == auth()->id()) return response()->json(['message' => 'This operation is not possible'], 403);
            $row = User::findOrFail(request('id'));
            $row->update(['status' => 'active']);
            return response()->json(null, 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }

    public function disable()
    {
        try {
            if (request('id') == 1 or request('id') == auth()->id()) return response()->json(['message' => 'This operation is not possible'], 403);
            $row = User::findOrFail(request('id'));
            $row->update(['status' => 'disable']);
            return response()->json(null, 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }

    public function selectedDelete()
    {
        try {
            User::whereIn('id', request('selected'))->whereNotIn('id', [1, auth()->id()])->delete();
            return response()->json(null, 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }

    public function selectedEnable()
    {
        try {
            User::whereIn('id', request('selected'))->whereNotIn('id', [1, auth()->id()])->update(['status' => 'active']);
            return response()->json(null, 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }

    public function selectedDisable()
    {
        try {
            User::whereIn('id', request('selected'))->whereNotIn('id', [1, auth()->id()])->update(['status' => 'disable']);
            return response()->json(null, 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }
}