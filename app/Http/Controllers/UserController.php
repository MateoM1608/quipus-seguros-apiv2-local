<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use DB;

// FormRequest
use App\Http\Requests\Password\UpdateRequest as ValidatePasswordRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Requests\User\StoreRequest;

// Models
use App\Models\Permission;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $data = User::whereConnection(auth()->user()->connection)->orderBy('id', 'desc');

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

        return response()->json($response);
    }

    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = User::create($request->all());

            $data = [
                'link' => 'http://www.seguros.amauttasystems.com/pages/login',
                'user' => $user->name,
                'email' => $user->email,
                'password' => $request->password_real,
            ];

            Permission::insert([
                [
                    'user_id' => $user->id,
                    'module_id' => 1,
                    'actions' => '{"see": true, "edit": true, "create": true, "delete": true}',
                ],
                [
                    'user_id' => $user->id,
                    'module_id' => 2,
                    'actions' => '{"see": true, "edit": true, "create": true, "delete": true}',
                ],
                [
                    'user_id' => $user->id,
                    'module_id' => 3,
                    'actions' => '{"see": true, "edit": true, "create": true, "delete": true}',
                ],
                [
                    'user_id' => $user->id,
                    'module_id' => 4,
                    'actions' => '{"see": true, "edit": true, "create": true, "delete": true}',
                ],
                [
                    'user_id' => $user->id,
                    'module_id' => 5,
                    'actions' => '{"see": true, "edit": true, "create": true, "delete": true}',
                ],
                [
                    'user_id' => $user->id,
                    'module_id' => 6,
                    'actions' => '{"see": true, "edit": true, "create": true, "delete": true}',
                ],
                [
                    'user_id' => $user->id,
                    'module_id' => 7,
                    'actions' => '{"see": true, "edit": true, "create": true, "delete": true}',
                ],
                [
                    'user_id' => $user->id,
                    'module_id' => 22,
                    'actions' => '{"see": true, "edit": true, "create": true, "delete": true}',
                ],
                [
                    'user_id' => $user->id,
                    'module_id' => 27,
                    'actions' => '{"see": true, "edit": true, "create": true, "delete": true}',
                ],
            ]);

            \Mail::send('emails.user.create', $data, function ($message) use($user) {
                $message->from('noreply@amauttasystems.com', 'Quipus seguros');
                $message->to($user->email)->subject('Creación de usuario');
            });

            $redis = Redis::connection();
            $redis->publish('channel-vue-' . auth()->guard('api')->user()->id, json_encode([
                'evento' => 'USER',
                'datos' => $user
            ]));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json($th->getMessage(), 422);
        }


        return response()->json($user);
    }

    public function update(UpdateRequest $request, User $user)
    {
        DB::beginTransaction();
        try {
            $user->update($request->except(['password','connection']));

            $redis = Redis::connection();
            $redis->publish('channel-vue-' . auth()->guard('api')->user()->id, json_encode([
                'evento' => 'USER',
                'datos' => $user
            ]));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json($th->getMessage(), 422);
        }

        return response()->json($user);
    }

    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $user = User::withTrashed()->findOrFail($id);

            if ($request->force) {
                $user->forceDelete();
            } else if ($user->trashed()) {
                $user->restore();
            } else {
                $user->delete();
            }

            $redis = Redis::connection();
            $redis->publish('channel-vue-' . auth()->guard('api')->user()->id, json_encode([
                'evento' => 'USER',
                'datos' => $user
            ]));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json($th->getMessage(), 422);
        }


        return response()->json($user);
    }

    public function changePassword(ValidatePasswordRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = User::withTrashed()->findOrFail(auth()->guard('api')->user()->id);
            $user->update($request->only(['password']));
            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json($th->getMessage(), 422);
        }

        return response()->json(['message' => 'Cambio de contraseña realizado con exito.']);
    }
}
