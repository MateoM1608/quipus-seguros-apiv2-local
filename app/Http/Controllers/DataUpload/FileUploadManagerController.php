<?php

namespace App\Http\Controllers\DataUpload;

use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

// Models
use App\Models\UploadedInformation;
use App\Models\User;

// Events
use App\Events\UploadedInformationEvent;

// Imports
use App\Imports\FileImport;

// Imports
use App\Exports\FileExport;
class FileUploadManagerController extends Controller
{
    public $data;
    public $connection;

    public function manager(Request $request)
    {
        $this->data = collect([]);

        try {
            $this->connect = $request->connection;
            $this->upload = UploadedInformation::on($request->connection)->find($request->id);

            switch ($this->upload->type) {
                case 'client':
                    $this->storeClient();
                    break;
                default:
                    # code...
                    break;
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function callHttp()
    {
        $user = User::find($this->upload->user_id);
        $token = \JWTAuth::fromUser($user);

        $this->upload->inserted_registry = 0;
        $this->upload->bad_records =  0;

        $this->data = $this->data->map(function($row, $key) use($token) {
            $response = Http::withToken($token)
            ->post(route($this->upload->type . '-store'), $row);

            switch ($response->status()) {
                case 200:
                    $this->upload->inserted_registry +=  1;
                    $row['status'] = 'Registro almacenado correctamente.';
                    break;
                case 422:
                    $this->upload->bad_records +=  1;
                    $row['status'] = implode(', ', call_user_func_array('array_merge', $response->json()['errors']));
                    break;
                default:
                    $this->upload->bad_records +=  1;
                    $row['status'] = 'Error al almacenar el registro.';
                    break;
            }

            event(new UploadedInformationEvent($this->connect, $this->upload));

            return $row;
        });
    }

    public function storeClient()
    {
        $collection = Excel::toCollection(new FileImport, $this->upload->path, 'arvixe');
        $clients =  $collection[0]->whereNotNull('cedulanit');

        $schema = [
            'identification' => 'cedulanit',
            'first_name' => 'nombres',
            'last_name' => 'apellidos',
            'birthday' => 'fecha_de_nacimiento',
            'adress' => 'direccion',
            'fix_phone' => 'telefono',
            'cel_phone' => 'celular',
            'email' => 'correoe',
            'g_city_id' => 'ciudad',
            'g_identification_type_id' => 'tipo_de_identificacion',
            'observations' => 'observaciones',
        ];

        foreach ($clients as $client) {
            $row = [];
            foreach ($schema as $key => $field) {
                $row[$key] = $client[$field];
            }
            $this->data->push($row);
        }

        $this->callHttp();

        $path = str_replace('xlsx','log.xlsx', $this->upload->path);

        $this->upload->log = $path;
        $this->upload->save();

        $headings = [
            'Cedula/Nit',
            'Nombres',
            'Apellidos',
            'Fecha de Nacimiento',
            'Direccion',
            'Telefono',
            'Celular',
            'CorreoE',
            'Ciudad',
            'Tipo de Identificacion',
            'Observaciones',
            'Status',
        ];

        Excel::store(new FileExport($this->data, $headings), $path, 'arvixe');
    }
}
