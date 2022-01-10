<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Shared\FilemanagerFile;
use App\Models\Shared\FilemanagerFolder;
use Illuminate\Support\Str;

class FilemanagerController extends Controller
{
    public function index()
    {
        try {
            $filemanager = auth()->user()->filemanager;
            $folders = $filemanager->folders;
            $files = $filemanager->folders()->first()->files;
            return response()->json([
                'folders' => $folders,
                'files' => $files
            ]);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], $exception->getCode());
        }
    }

    public function folder()
    {
        try {
            $folder = FilemanagerFolder::findOrFail(request('id'));
            $files = $folder->files;
            return response()->json([
                'folder' => $folder,
                'files' => $files
            ]);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], $exception->getCode());
        }
    }

    public function folderCreate()
    {
        try {
            FilemanagerFolder::create([
                'filemanager_id' => auth()->user()->filemanager->id,
                'user_id' => auth()->id(),
                'src' => auth()->user()->filemanager->src . '/' . makeHash(request('name')),
                'name' => request('name'),
            ]);
            return response()->json(null, 201);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], $exception->getCode());
        }
    }

    public function folderDelete()
    {
        try {
            $folder = FilemanagerFolder::findOrFail(request('id'));
            if ($folder->name == 'public') abort(403);
            $folder->delete();
            return response()->json(null, 201);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], $exception->getCode());
        }
    }

    public function fileUpload($folder_id)
    {
        try {
            $folder = FilemanagerFolder::findOrFail($folder_id);
            $fileExtension = request()->file('file')->getClientOriginalExtension();
            $fileSize = bytesToHuman(request()->file('file')->getSize());
            $folderName = Str::replace('/storage/', '', $folder->src);
            $fileName = md5(uniqid(rand(), true)) . '.' . $fileExtension;
            $filePath = request()->file('file')->storeAs($folderName, $fileName, 'public');
            FilemanagerFile::create([
                'filemanager_id' => auth()->user()->filemanager->id,
                'filemanager_folder_id' => $folder->id,
                'user_id' => auth()->id(),
                'src' => '/storage/' . $filePath,
                'name' => request('filename'),
                'extension' => $fileExtension,
                'size' => $fileSize,
            ]);
            return response()->json(null, 201);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], $exception->getCode());
        }
    }

    public function fileDelete()
    {
        try {
            FilemanagerFile::findOrFail(request('id'))->delete();
            return response()->json(null, 201);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], $exception->getCode());
        }
    }
}