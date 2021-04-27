<?php

namespace App\Http\Controllers;
use DB;

use App\Models\Crm\COperationComment;
use Illuminate\Http\Request;

use App\Http\Requests\Crm\COperationComment\UpdateRequest;
use App\Http\Requests\Crm\COperationComment\StoreRequest;

class COperationCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = COperationComment::withTrashed()
        ->with(['COperation' => function ($query) use ($request) {  }])        
        ->where(function ($query) use ($request) {

            if (isset($request->comment_description)) {
                $query->where('comment_description', 'like', '%' . $request->comment_description . '%');
            }          
        });
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $operationComment = COperationComment::create($request->all());            
            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        /*
        $redis = Redis::connection();
        dd('fsfsfs------------>');
        $redis->publish('channel-vue-' . auth()->guard('api')->user()->id, json_encode([
            'evento' => 'CLIENT',
            'datos' => $client
        ]));
        */
        return response()->json($operationComment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Crm\COperationComment  $cOperationComment
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, COperationComment $id)
    {
        DB::beginTransaction();
        try {
            $id->update($request->all());
            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        /*
        $redis = Redis::connection();
        $redis->publish('channel-vue-' . auth()->guard('api')->user()->id, json_encode([
            'evento' => 'CLIENT',
            'datos' => $client
        ]));
            */
        return response()->json($id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Crm\COperationComment  $cOperationComment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $id = COperationComment::withTrashed()->findOrFail($id);

            if ($request->force) {
                $id->forceDelete();
            } else if ($id->trashed()) {
                $id->restore();
            } else {
                $id->delete();
            }

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        /*
        $redis = Redis::connection();
        $redis->publish('channel-vue-' . auth()->guard('api')->user()->id, json_encode([
            'evento' => 'CLIENT',
            'datos' => $clients
        ]));
        */

        return response()->json($id);
    }
}
