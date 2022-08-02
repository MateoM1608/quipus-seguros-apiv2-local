<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use PDF;
use DB;

//FormRequest
use App\Http\Requests\Policy\SCommission\UpdateRequest;
use App\Http\Requests\Policy\SCommission\StoreRequest;

//Models
use App\Models\Policy\SCommission;

// Events
use App\Events\SCommissionEvent;

class SCommissionController extends Controller
{
    public function index(Request $request)
    {
        $data = SCommission::with(['sAnnex' => function ($query) use ($request) {
            //
        }])
            ->with(['gVendor' => function ($query) use ($request) {
                //
            }])

            ->where(function ($query) use ($request) {

                if (isset($request->commission_number)) {
                    $query->where('commission_number', 'like', '%' . $request->commission_number . '%');
                }

                if (isset($request->commissionDate)) {
                    $query->where('commission_date', 'like', '%' . $request->commission_date . '%');
                }

                if (isset($request->vendorId)) {
                    $query->where('g_vendor_id', 'like', '%' . $request->vendorId . '%');
                }

                if (isset($request->s_annex_id)) {
                    $query->where('s_annex_id', $request->s_annex_id);
                }
            });
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

        return response()->json($response);
    }

    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $commission = SCommission::create($request->all());

            event(new SCommissionEvent($commission));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        $commission = SCommission::with(['sAnnex', 'gVendor'])->find($commission->id);

        return response()->json($commission);
    }

    public function update(UpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $commission = SCommission::findOrFail($id);
            $commission->update($request->all());

            event(new SCommissionEvent($commission));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        return response()->json($commission);
    }

    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $commission = SCommission::withTrashed()->findOrFail($id);

            if ($request->force) {
                $commission->forceDelete();
            } else if ($commission->trashed()) {
                $commission->restore();
            } else {
                $commission->delete();
            }

            event(new SCommissionEvent($commission));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        return response()->json($commission);
    }

    public function bulkCommissionPaid(Request $request)
    {
        DB::beginTransaction();

        try {
            $optionsGarantia = [
                'orientation'   => 'portrait',
                'encoding'      => 'UTF-8',
                'header-html'   => null,
                'footer-html'   => null,
                'margin-top' => '25mm',
                'margin-bottom' => '20mm',
                'margin-right' => '10mm',
                'margin-left' => '18mm',
                'enable-javascript' => true,
                'enable-smart-shrinking' => true,
                'no-stop-slow-scripts' => true,
            ];
            $pdf = PDF::loadView('pdf.commissionPay');
            $pdf->setPaper('letter');
            $pdf->setOptions($optionsGarantia);

            $sequential = SCommission::groupBy('s_payroll_id')
            ->orderBy('s_payroll_id', 'desc')
            ->first('s_payroll_id');

            $sequential = $sequential->s_payroll_id? $sequential->s_payroll_id + 1 : 1;

            SCommission::whereIn('id', $request->commissionsId)
            ->update([
                'vendor_commission_paid' => 'Si',
                's_payroll_id' => $sequential,
                'payment_day' => Carbon::now()->format('Y-m-d H:m:s'),
            ]);

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }

        return $pdf->output();
    }
}
