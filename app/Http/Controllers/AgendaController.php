<?php

namespace App\Http\Controllers;

use App\Http\Requests\AgendaRequest;
use App\Http\Resources\AgendaResource;
use App\Models\Agenda;
use Illuminate\Http\Exceptions\HttpResponseException;

class AgendaController extends Controller
{
    //create new agenda
    public function create(AgendaRequest $request): AgendaResource
    {
        $data = $request->validated();

        // Check if a agenda article with the same title already exists
        if (Agenda::where("title", $data["title"])->exists()) {
            throw new HttpResponseException(response()->json([
                "status" => "error",
                "errors" => [
                    "title" => ["Agenda with this title already exists"],
                ],
            ], 400));
        }

        $agenda = new Agenda($data);
        $agenda->save();

        return new AgendaResource($agenda);
    }
}
