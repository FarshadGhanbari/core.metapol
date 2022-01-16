<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Shared\Role;

class RolesController extends Controller
{
    public function index()
    {
        try {
            $rows = Role::whereNotIn('id', [1, 2]);
            if (request('status') and !empty(request('status'))) {
                $rows = $rows->whereIn('status', request('status'));
            }
            if (request('dateRange') and !empty(request('dateRange'))) {
                $rows = $rows->whereBetween('created_at', request('dateRange'));
            }
            $rows = $rows->search(request('search'))->paginate(request('perPage'));
            return response()->json($rows);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }

    public function store()
    {
        $validate = request()->validate([
            'name' => ['required', 'max:191'],
            'status' => ['required']
        ]);
        if (!$validate) {
            return response()->json($validate, 422);
        }
        try {
            $row = Role::create(request()->except('permissions'));
            $row->permissions()->sync(request('permissions'));
            return response()->json(null, 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }

    public function edit()
    {
        try {
            $row = Role::with('permissions')->findOrFail(request('id'));
            return response()->json($row, 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }

    public function update()
    {
        $row = Role::findOrFail(request('id'));
        $validate = request()->validate([
            'name' => ['required', 'max:191'],
            'status' => ['required']
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
            $row = Role::findOrFail(request('id'));
            if (in_array(request('id'), [1, 2, 3, 4])) return response()->json(['message' => 'This operation is not possible'], 403);
            $row->delete();
            return response()->json(null, 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }

    public function enable()
    {
        try {
            $row = Role::findOrFail(request('id'));
            if (in_array(request('id'), [1, 2])) return response()->json(['message' => 'This operation is not possible'], 403);
            $row->update(['status' => 'active']);
            return response()->json(null, 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }

    public function disable()
    {
        try {
            $row = Role::findOrFail(request('id'));
            if (in_array(request('id'), [1, 2])) return response()->json(['message' => 'This operation is not possible'], 403);
            $row->update(['status' => 'disable']);
            return response()->json(null, 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }

    public function selectedDelete()
    {
        try {
            Role::whereIn('id', request('selected'))->whereNotIn('id', [1, 2, 3, 4])->delete();
            return response()->json(null, 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }

    public function selectedEnable()
    {
        try {
            Role::whereIn('id', request('selected'))->whereNotIn('id', [1, 2])->update(['status' => 'active']);
            return response()->json(null, 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }

    public function selectedDisable()
    {
        try {
            Role::whereIn('id', request('selected'))->whereNotIn('id', [1, 2])->update(['status' => 'disable']);
            return response()->json(null, 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }
}