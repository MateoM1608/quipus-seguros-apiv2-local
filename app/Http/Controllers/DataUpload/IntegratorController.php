<?php

namespace App\Http\Controllers\DataUpload;

use App\Http\Controllers\Controller;
use Storage;
use File;
use DB;


// FormRequest
use App\Http\Requests\Cargues\StoreRequest;

// Models
use App\Models\UploadedInformation;

// Imports
use App\Imports\FileImport;

// Jobs
use App\Jobs\FileUploadManagerJob;

class IntegratorController extends Controller
{

    public function store(StoreRequest $request)
    {

        DB::beginTransaction();

        try {
            switch ($request->type) {
                case 'client':
                        $where = 'cedulanit';
                    break;
                case 'policies':
                        $where = 'numero_de_poliza';
                    break;
                case 'insurance-carrier':
                        $where = 'cedulanit';
                    break;
            }

            $data = (new FileImport)->toCollection($request->file);

            $path = 'cargues/' . auth('api')->user()->connection . '/' . date_format(now(), 'Y') . '/' . date_format(now(), 'm') . '/' . $request->type . '/' . $request->type .'_' . date_format(now(), 'Hisu') . '.xlsx';

            $uploaded = UploadedInformation::create([
                'nit' => auth('api')->user()->connection,
                'type' => $request->type,
                'path' => $path,
                'total_records' => $data[0]->whereNotNull($where)->count(),
                'user_id' => auth('api')->user()->id,
            ]);

            Storage::disk('arvixe')->put($path, File::get($request->file));

            FileUploadManagerJob::dispatch(auth('api')->user()->connection, $uploaded->id)->onQueue('upload');

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json($th->getMessage(), 422);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }
    }
}
