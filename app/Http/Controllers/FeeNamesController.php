<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFeeNames;
use App\Models\Classroom;
use App\Models\Fee;
use App\Models\FeeNames;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeeNamesController extends Controller
{
    const TYPE_MONTHLY = 'monthly';
    const TYPE_YEARLY = 'yearly';
    const TYPE_CUSTOM = 'custom';

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = FeeNames::query();

            if ($request->filled('search.value')) {
                $search = $request->input('search.value');
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('type', 'like', "%{$search}%");
                });
            }

            $recordsTotal = FeeNames::count();
            $recordsFiltered = $query->count();

            $orderColumn = $request->input('order.0.column', 0);
            $orderDir = $request->input('order.0.dir', 'asc');
            $columns = ['id', 'name', 'amount', 'type', 'id'];
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

        return view('pages.FeeName');
    }

    public function classes()
    {
        $classes = Classroom::with('grade')->get()->map(function ($classroom) {
            return [
                'id' => $classroom->id,
                'name' => $classroom->Name_Class,
                'grade_name' => optional($classroom->grade)->Name,
            ];
        });

        return response()->json($classes);
    }

    public function store(StoreFeeNames $request)
    {
        DB::transaction(function () use ($request, &$feeName) {
            $feeName = FeeNames::create([
                'name' => $request->name,
                'code' => $request->input('code'),
                'amount' => $request->amount,
                'type' => $request->type,
                'months' => $this->formatMonths($request),
                'description' => $request->input('description'),
                'active' => $request->input('active', true),
            ]);

            $this->syncClassFees($feeName, $request);
        });

        return response()->json(['message' => 'Fee setup created successfully.']);
    }

    public function show(FeeNames $feeName)
    {
        $feeName->load(['fees.classroom', 'fees.grade']);

        return response()->json([
            'id' => $feeName->id,
            'name' => $feeName->name,
            'code' => $feeName->code,
            'amount' => $feeName->amount,
            'type' => $feeName->type,
            'months' => $feeName->months ? explode(',', $feeName->months) : [],
            'description' => $feeName->description,
            'active' => $feeName->active,
            'fees' => $feeName->fees->map(function ($fee) {
                return [
                    'id' => $fee->id,
                    'title' => $fee->title,
                    'amount' => $fee->amount,
                    'month' => $fee->months,
                    'grade' => optional($fee->grade)->Name,
                    'classroom' => optional($fee->classroom)->Name_Class,
                    'remarks' => $fee->description,
                ];
            })->toArray(),
        ]);
    }

    public function edit(FeeNames $feeName)
    {
        $feeName->load(['fees']);

        return response()->json([
            'id' => $feeName->id,
            'name' => $feeName->name,
            'code' => $feeName->code,
            'amount' => $feeName->amount,
            'type' => $feeName->type,
            'months' => $feeName->months ? explode(',', $feeName->months) : [],
            'description' => $feeName->description,
            'active' => $feeName->active,
            'fees' => $feeName->fees->mapWithKeys(function ($fee) {
                return [$fee->Classroom_id => [
                    'amount' => $fee->amount,
                    'remarks' => $fee->description,
                ]];
            })->toArray(),
        ]);
    }

    public function update(StoreFeeNames $request, FeeNames $feeName)
    {
        DB::transaction(function () use ($request, $feeName) {
            $feeName->update([
                'name' => $request->name,
                'code' => $request->input('code'),
                'amount' => $request->amount,
                'type' => $request->type,
                'months' => $this->formatMonths($request),
                'description' => $request->input('description'),
                'active' => $request->input('active', true),
            ]);

            $this->syncClassFees($feeName, $request);
        });

        return response()->json(['message' => 'Fee setup updated successfully.']);
    }

    public function destroy(FeeNames $feeName)
    {
        $feeName->delete();

        return response()->json(['message' => 'Fee setup deleted successfully.']);
    }

    private function syncClassFees(FeeNames $feeName, Request $request)
    {
        $months = $this->formatMonths($request);
        $typeValue = $this->feeTypeValue($request->type);
        $classroomIds = [];

        foreach ($request->input('classroom_id', []) as $index => $classroomId) {
            $amount = $request->input('class_amount.' . $index);
            if ($amount === null || $amount === '') {
                continue;
            }

            $classroom = Classroom::find($classroomId);
            if (! $classroom) {
                continue;
            }

            $classroomIds[] = $classroomId;

            Fee::updateOrCreate(
                ['fee_name_id' => $feeName->id, 'Classroom_id' => $classroomId],
                [
                    'title' => $feeName->name,
                    'amount' => $amount,
                    'months' => $months,
                    'Grade_id' => $classroom->Grade_id,
                    'description' => $request->input('class_remarks.' . $index),
                    'Fee_type' => $typeValue,
                ]
            );
        }

        if (! empty($classroomIds)) {
            $feeName->fees()->whereNotIn('Classroom_id', $classroomIds)->delete();
        } else {
            $feeName->fees()->delete();
        }
    }

    private function formatMonths(Request $request)
    {
        if (! $request->has('months')) {
            return null;
        }

        return collect($request->input('months'))->filter()->implode(',');
    }

    private function feeTypeValue(string $type): int
    {
        return match ($type) {
            self::TYPE_YEARLY => 1,
            self::TYPE_CUSTOM => 2,
            default => 0,
        };
    }
}
