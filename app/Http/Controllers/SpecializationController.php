<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSpecialization;
use App\Models\Specialization;
use App\Repository\SpecializationRepository;
use Illuminate\Http\Request;

class SpecializationController extends Controller
{
    protected $specializationRepository;

    public function __construct(SpecializationRepository $specializationRepository)
    {
        $this->specializationRepository = $specializationRepository;
    }

    public function index()
    {
        if (request()->ajax()) {
            $query = Specialization::query();
            
            // Search
            if (request()->has('search') && !empty(request()->input('search.value'))) {
                $search = request()->input('search.value');
                $query->where('Name', 'like', "%{$search}%");
                    //   ->orWhere('specialization_code', 'like', "%{$search}%")
                    //   ->orWhere('description', 'like', "%{$search}%");
            }

            $recordsTotal = Specialization::count();
            $recordsFiltered = $query->count();

            // Sorting
            $orderColumn = request()->input('order.0.column', 0);
            $orderDir = request()->input('order.0.dir', 'asc');
            $columns = ['id', 'Name'];
            if (isset($columns[$orderColumn])) {
                $query->orderBy($columns[$orderColumn], $orderDir);
            }

            // Pagination
            $start = request()->input('start', 0);
            $length = request()->input('length', 10);
            $data = $query->skip($start)->take($length)->get();

            return response()->json([
                'draw' => intval(request()->input('draw', 1)),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data
            ]);
        }

        $specializations = $this->specializationRepository->index();
        return view('Specializations.index', compact('specializations'));
    }

    public function create()
    {
        return view('Specializations.create');
    }

    public function store(StoreSpecialization $request)
    {
        try {
            $validated = $request->validated();

            Specialization::create([
                'Name' => $request->Name,
                // 'specialization_code' => $request->specialization_code,
                // 'description' => $request->description,
                // 'status' => $request->boolean('status'),
            ]);

            toastr()->success('Data has been saved successfully');
            return redirect()->route('specializations.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $specialization = $this->specializationRepository->show($id);
        return response()->json($specialization);
    }

    public function edit($id)
    {
        $specialization = $this->specializationRepository->edit($id);
        return response()->json($specialization);
    }

    public function update(StoreSpecialization $request, $id)
    {
        try {
            $validated = $request->validated();
            $specialization = Specialization::findOrFail($id);

            $specialization->update([
                'Name' => $request->Name,
                // 'specialization_code' => $request->specialization_code,
                // 'description' => $request->description,
                // 'status' => $request->boolean('status'),
            ]);

            return response()->json(['message' => 'Specialization updated successfully.', 'specialization' => $specialization]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function destroy($id)
    {
        try {
            Specialization::findOrFail($id)->delete();

            if (request()->ajax()) {
                return response()->json(['message' => 'Specialization deleted successfully.']);
            }

            toastr()->error('Data has been deleted successfully');
            return redirect()->route('specializations.index');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
