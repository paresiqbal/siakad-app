<?php

namespace App\Http\Controllers;

use App\Http\Requests\StaffDirRequest;
use App\Http\Resources\StaffDirResource;
use App\Models\StaffDirectory;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class StaffDirController extends Controller
{
    // create new staff
    public function create(StaffDirRequest $request): StaffDirResource
    {
        $data = $request->validated();

        // checl if a staff with the same name already exists
        if (StaffDirectory::where("name", $data["name"])->exists()) {
            throw new HttpResponseException(response()->json([
                "status" => "error",
                "errors" => [
                    "name" => ["Staff with this name already exists"],
                ],
            ], 400));
        }

        $staff = new StaffDirectory($data);
        $staff->save();

        return new StaffDirResource($staff);
    }

    // update staff
    public function update(StaffDirRequest $request, $id): StaffDirResource
    {
        $data = $request->validated();
        $staff = StaffDirectory::findOrFail($id);

        if (StaffDirectory::where("name", $data["name"])
            ->where("id", "<>", $id)
            ->exists()
        ) {
            throw new HttpResponseException(response()->json([
                "errors" => [
                    "name" => ["Another staff with this name already exists"],
                ],
            ], 400));
        }

        $staff->update($data);

        return new StaffDirResource($staff);
    }

    // show all staff
    public function index()
    {
        $staff = StaffDirectory::all();

        return response()->json([
            "status" => "success",
            "data" => StaffDirResource::collection($staff),
        ]);
    }

    // delete staff
    public function destroy($id)
    {
        $staff = StaffDirectory::findOrFail($id);
        $staff->delete();

        return response()->json([
            "status" => "success",
            "message" => "Staff deleted successfully",
        ]);
    }
}
