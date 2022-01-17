<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Shared\Country;

class CountriesController extends Controller
{
    public function index()
    {
        try {
            $rows = Country::query();
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
            Country::create(request()->all());
            return response()->json(null, 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }

    public function edit()
    {
        try {
            $row = Country::findOrFail(request('id'));
            return response()->json($row, 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }

    public function update()
    {
        $row = Country::findOrFail(request('id'));
        $validate = request()->validate([
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
            $row = Country::findOrFail(request('id'));
            $row->delete();
            return response()->json(null, 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }

    public function enable()
    {
        try {
            $row = Country::findOrFail(request('id'));
            $row->update(['status' => 'active']);
            return response()->json(null, 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }

    public function disable()
    {
        try {
            $row = Country::findOrFail(request('id'));
            $row->update(['status' => 'disable']);
            return response()->json(null, 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }

    public function selectedDelete()
    {
        try {
            Country::whereIn('id', request('selected'))->delete();
            return response()->json(null, 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }

    public function selectedEnable()
    {
        try {
            Country::whereIn('id', request('selected'))->update(['status' => 'active']);
            return response()->json(null, 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }

    public function selectedDisable()
    {
        try {
            Country::whereIn('id', request('selected'))->update(['status' => 'disable']);
            return response()->json(null, 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }
}