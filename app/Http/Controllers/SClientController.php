<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

//FormRequest
use App\Http\Requests\SClient\UpdateRequest;
use App\Http\Requests\SClient\StoreRequest;

//Models
use App\Models\SClient;

// Events
use App\Events\SClientEvent;

class SClientController extends Controller
{

    public function index(Request $request)
    {
        //\DB::enableQueryLog();
        $data = SClient::with(['gCity' => function ($query) use ($request) {
            if (isset($request->city)) {
                $query->where('description', $request->city); //REVISAR, no hace el filtro adecuado***
            }
        }])
            ->with(['gIdentificationType' => function ($query) use ($request) {
                //
            }])

            ->where(function ($query) use ($request) {

                if (isset($request->name)) {
                    $query->where('first_name', 'like', '%' . $request->name . '%');
                    $query->orWhere('last_name', 'like', '%' . $request->name . '%');
                }

                if (isset($request->identification)) {
                    $query->where('identification', 'like', '%' . $request->identification . '%');
                }
            })
            ->orderBy('first_name', 'asc');

        if ($request->trashed) {
            $data->withTrashed();
        }

        $response = [];

        if (isset($request->paginate) && $request->paginate == 1) {
            $data = $data->paginate($request->rows ?: 25, $request->colums ? $request->colums : ['*']);
            $response = [
                'total' => $data->total(),
                'data'  => $data->toArray()['data']
            ];
        } else {
            $response = [
                'total' => $data->count(),
                'data'  => $data->get($request->colums ? $request->colums : ['*'])
            ];
        }

        //dd(\DB::getQueryLog());

        return response()->json($response);
    }

    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $client = SClient::create($request->all());

            event(new SClientEvent($client));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        return response()->json($client);
    }

    public function update(UpdateRequest $request, SClient $id)
    {
        DB::beginTransaction();
        try {
            $client = SClient::findOrFail($id);
            $client->update($request->all());

            event(new SClientEvent($client));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        return response()->json($client);
    }

    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $client = SClient::withTrashed()->with(['gCity','gIdentificationType'])->findOrFail($id);

            if ($request->force) {
                $client->forceDelete();
            } else if ($client->trashed()) {
                $client->restore();
            } else {
                $client->delete();
            }

            event(new SClientEvent($client));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        return response()->json($client);
    }
}
