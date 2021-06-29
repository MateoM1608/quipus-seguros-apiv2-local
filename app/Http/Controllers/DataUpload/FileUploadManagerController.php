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
    public $collection;
    public $connection;
    public $excelData;
    public $filterBy;
    public $headings;
    public $schema;
    public $data;

    public function manager(Request $request)
    {
        $this->data = collect([]);

        try {
            $this->connect = $request->connection;
            $this->upload = UploadedInformation::on($request->connection)->find($request->id);
            $this->collection = Excel::toCollection(new FileImport, $this->upload->path, 'arvixe');

            switch ($this->upload->type) {
                case 'client':
                    $this->storeClient();
                    break;
                case 'policies':
                    $this->storePolicies();
                    break;
                case 'insurance-carrier':
                    $this->storeInsuranceCarriers();
                    break;
            }

            $this->callHttp();

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function storeInsuranceCarriers()
    {
        $this->filterBy = 'cedulanit';

        $this->schema = [
            'insurance_carrier' => 'nombre_aseguradora',
            'identification' => 'cedulanit',
        ];

        $this->headings = [
            'NOMBRE ASEGURADORA ',
            'CEDULA/NIT',
            'Status',
        ];
    }


    public function storeClient()
    {
        $this->filterBy = 'cedulanit';

        $this->schema = [
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

        $this->headings = [
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
    }

    public function storePolicies()
    {
        $this->filterBy = 'numero_de_poliza';

        $this->schema = [
            's_branch_id' => 'nombre_ramo',
            's_agency_id' => 'nit_agencia',
            'policy_number' => 'numero_de_poliza',
            'insurance_carrier' => 'nit_aseguradora',
            'g_vendor_id' => 'cedulanit_vendedor',
            's_client_id' => 'cedulanit_cliente',
            'expedition_date' => 'inicio_vigencia',
            'payment_periodicity' => 'periodicidad',
        ];

        $this->headings = [
            'NOMBRE RAMO',
            'NIT AGENCIA',
            'NUMERO DE POLIZA',
            'NIT ASEGURADORA',
            'CEDULA/NIT VENDEDOR',
            'CEDULA/NIT CLIENTE',
            'INICIO VIGENCIA',
            'PERIODICIDAD',
            'Status',
        ];
    }

    public function callHttp()
    {
        $this->organizeData();

        $user = User::find($this->upload->user_id);
        $token = \JWTAuth::fromUser($user);

        $this->upload->inserted_registry = 0;
        $this->upload->bad_records =  0;

        $this->data = $this->data->map(function($row) use($token) {

            $data = $row;
            $data['manager_upload'] = true;

            $response = Http::withToken($token)
            ->post(route($this->upload->type . '-store'), $data);

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

        $path = str_replace('xlsx','log.xlsx', $this->upload->path);

        $this->upload->log = $path;
        $this->upload->save();

        Excel::store(new FileExport($this->data, $this->headings), $path, 'arvixe');
    }

    public function organizeData()
    {
        $this->excelData =  $this->collection[0]->whereNotNull($this->filterBy);

        foreach ($this->excelData as $data) {
            $row = [];
            foreach ($this->schema as $key => $field) {
                $row[$key] = trim($data[$field]);
            }
            $this->data->push($row);
        }
    }
}
