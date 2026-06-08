<?php

namespace App\Http\Controllers\FiscalYears;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFiscalYearRequest;
use App\Http\Requests\UpdateFiscalYearRequest;
use App\Models\FiscalYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FiscalYearController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = FiscalYear::query();

            if ($request->has('search') && !empty($request->input('search.value'))) {
                $search = $request->input('search.value');
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('start_date_np', 'like', "%{$search}%")
                      ->orWhere('end_date_np', 'like', "%{$search}%")
                      ->orWhere('start_date', 'like', "%{$search}%")
                      ->orWhere('end_date', 'like', "%{$search}%");
            }

            $recordsTotal = FiscalYear::count();
            $recordsFiltered = $query->count();

            $orderColumn = $request->input('order.0.column', 0);
            $orderDir = $request->input('order.0.dir', 'asc');
            $columns = ['id', 'name', 'start_date_np', 'end_date_np', 'start_date', 'end_date', 'status', 'id'];

            if (isset($columns[$orderColumn])) {
                $query->orderBy($columns[$orderColumn], $orderDir);
            }

            $start = $request->input('start', 0);
            $length = $request->input('length', 10);
            $data = $query->skip($start)->take($length)->get();

            return response()->json([
                'draw' => intval($request->input('draw', 1)),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data,
            ]);
        }

        return view('pages.FiscalYears.FiscalYears');
    }

    public function create()
    {
        return response()->json([]);
    }

    public function store(StoreFiscalYearRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                if ($request->boolean('status')) {
                    FiscalYear::where('status', true)->update(['status' => false]);
                }

                FiscalYear::create([
                    'name' => $request->name,
                    'start_date_np' => $request->start_date_np,
                    'end_date_np' => $request->end_date_np,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'status' => $request->boolean('status'),
                ]);
            });

            if ($request->ajax()) {
                return response()->json(['message' => 'Fiscal year created successfully.']);
            }

            toastr()->success('Fiscal year created successfully.');
            return redirect()->route('fiscal-years.index');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function show($id)
    {
        $fiscalYear = FiscalYear::findOrFail($id);
        return response()->json($fiscalYear);
    }

    public function edit($id)
    {
        $fiscalYear = FiscalYear::findOrFail($id);
        return response()->json($fiscalYear);
    }

    public function update(UpdateFiscalYearRequest $request, $id)
    {
        try {
            DB::transaction(function () use ($request, $id) {
                if ($request->boolean('status')) {
                    FiscalYear::where('status', true)->where('id', '!=', $id)->update(['status' => false]);
                }

                $fiscalYear = FiscalYear::findOrFail($id);
                $fiscalYear->update([
                    'name' => $request->name,
                    'start_date_np' => $request->start_date_np,
                    'end_date_np' => $request->end_date_np,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'status' => $request->boolean('status'),
                ]);
            });

            return response()->json(['message' => 'Fiscal year updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $fiscalYear = FiscalYear::findOrFail($id);
            $fiscalYear->delete();

            return response()->json(['message' => 'Fiscal year deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function activate(Request $request, $id)
    {
        try {
            DB::transaction(function () use ($id) {
                FiscalYear::where('status', true)->update(['status' => false]);
                $fiscalYear = FiscalYear::findOrFail($id);
                $fiscalYear->update(['status' => true]);
            });

            return response()->json(['message' => 'Fiscal year activated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
