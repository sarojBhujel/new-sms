<?php

namespace App\Http\Controllers\Classrooms;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClassrooms;
use App\Models\Classroom;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassroomController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        if (request()->ajax()) {
            $query = Classroom::with('grade');

            if ($gradeId = request()->input('grade_id')) {
                $query->where('Grade_id', $gradeId);
            }

            if (request()->has('search') && !empty(request()->input('search.value'))) {
                $search = request()->input('search.value');
                $query->where(function ($q) use ($search) {
                    $q->where('Name_Class', 'like', "%{$search}%")
                        ->orWhereHas('grade', function ($q2) use ($search) {
                            $q2->where('Name', 'like', "%{$search}%");
                        });
                });
            }

            $recordsTotal = Classroom::count();
            $recordsFiltered = $query->count();

            $columns = ['id', 'id', 'Name_Class', 'Grade_id', 'id'];
            $orderColumn = request()->input('order.0.column', 1);
            $orderDir = request()->input('order.0.dir', 'asc');

            if (isset($columns[$orderColumn])) {
                if ($columns[$orderColumn] === 'Grade_id') {
                    $query = Classroom::with('grade')
                        ->join('grades', 'grades.id', '=', 'classrooms.Grade_id')
                        ->select('classrooms.*')
                        ->orderBy('grades.Name', $orderDir);
                } else {
                    $query->orderBy($columns[$orderColumn], $orderDir);
                }
            }

            $start = request()->input('start', 0);
            $length = request()->input('length', 10);
            $data = $query->skip($start)->take($length)->get();

            return response()->json([
                'draw' => intval(request()->input('draw', 1)),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data,
            ]);
        }

        $data['grades'] = Grade::all();
        return view('pages.My_Classes.My_Classes', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(StoreClassrooms $request)
    {



        try {
            $List_Classes = $request->List_Classes;

            $validated = $request->validated();

            foreach ($List_Classes as $List_Class) {

                $My_Classes = new Classroom();

                $My_Classes->Name_Class = $List_Class['Name'];

                $My_Classes->Grade_id = $List_Class['Grade_id'];

                $My_Classes->save();
            }

            toastr()->success('Data has been saved successfully');
            return redirect()->route('Classrooms.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $Classroom = Classroom::findOrFail($id);
        return response()->json($Classroom);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $Classroom = Classroom::findOrFail($id);
        return response()->json($Classroom);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(StoreClassrooms $request, $id)
    {
        try {
            $validated = $request->validated();
            $Classroom = Classroom::findOrFail($id);

            $Classroom->update([
                'Name_Class' => $request->Name,
                'Grade_id' => $request->Grade_id,
            ]);

            if ($request->ajax()) {
                return response()->json(['message' => 'Classroom updated successfully.', 'classroom' => $Classroom]);
            }

            toastr()->success('Data has been updated successfully');
            return redirect()->route('Classrooms.index');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => $e->getMessage()], 422);
            }
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        try {
            Classroom::findOrFail($id)->delete();

            if ($request->ajax()) {
                return response()->json(['message' => 'Classroom deleted successfully.']);
            }

            toastr()->error('Data has been deleted successfully');
            return redirect()->route('Classrooms.index');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => $e->getMessage()], 422);
            }
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }


    public function delete_all(Request $request)
    {
        $delete_all_ids = explode(",",$request->delete_all_id);

        Classroom::whereIn('id',$delete_all_ids)->delete();

        toastr()->error(__('messages.Delete'),' ');
        return redirect()->route('Classrooms.index');
    }


    public function Filter_Classes(Request $request)
    {
        $Grades = Grade::all();
        $Search = Classroom::select('*')->where('Grade_id',$request->Grade_id)->get();
        return view('pages.My_Classes.My_Classes', compact('Grades'))->withDetails($Search);
    }
}
