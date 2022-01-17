<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Shared\Province;

class ProvincesController extends Controller
{
    public function index()
    {
        try {
            $rows = Province::query();
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
            'country_id' => ['required'],
            'name' => ['required', 'max:191'],
            'status' => ['required']
        ]);
        if (!$validate) {
            return response()->json($validate, 422);
        }
        try {
            Province::create(request()->all());
            return response()->json(null, 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }

    public function edit()
    {
        try {
            $row = Province::findOrFail(request('id'));
            return response()->json($row, 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }

    public function update()
    {
        $row = Province::findOrFail(request('id'));
        $validate = request()->validate([
            'country_id' => ['required'],
            'name' => ['required', 'max:191'],
            'status' => ['required']
        ]);
        if (!$validate) {
            return response()->json($validate, 422);
        }
        try {
            $row->update(request()->all());
            return response()->json(null, 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }

    public function delete()
    {
        try {
            $row = Province::findOrFail(request('id'));
            $row->delete();
            return response()->json(null, 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }

    public function enable()
    {
        try {
            $row = Province::findOrFail(request('id'));
            $row->update(['status' => 'active']);
            return response()->json(null, 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }

    public function disable()
    {
        try {
            $row = Province::findOrFail(request('id'));
            $row->update(['status' => 'disable']);
            return response()->json(null, 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }

    public function selectedDelete()
    {
        try {
            Province::whereIn('id', request('selected'))->delete();
            return response()->json(null, 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }

    public function selectedEnable()
    {
        try {
            Province::whereIn('id', request('selected'))->update(['status' => 'active']);
            return response()->json(null, 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }

    public function selectedDisable()
    {
        try {
            Province::whereIn('id', request('selected'))->update(['status' => 'disable']);
            return response()->json(null, 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }
}