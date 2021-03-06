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
            if (in_array(request('id'), [1, 2, 3, 4])) return response()->json(['message' => 'This operation is not possible'], 403);
            $row = Role::with('permissions')->findOrFail(request('id'));
            return response()->json($row, 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }

    public function update()
    {
        if (in_array(request('id'), [1, 2, 3, 4])) return response()->json(['message' => 'This operation is not possible'], 403);
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
            if (in_array(request('id'), [1, 2, 3, 4])) return response()->json(['message' => 'This operation is not possible'], 403);
            $row = Role::findOrFail(request('id'));
            $row->delete();
            return response()->json(null, 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }

    public function enable()
    {
        try {
            if (in_array(request('id'), [1, 2])) return response()->json(['message' => 'This operation is not possible'], 403);
            $row = Role::findOrFail(request('id'));
            $row->update(['status' => 'active']);
            return response()->json(null, 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }

    public function disable()
    {
        try {
            if (in_array(request('id'), [1, 2])) return response()->json(['message' => 'This operation is not possible'], 403);
            $row = Role::findOrFail(request('id'));
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