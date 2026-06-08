<?php


namespace App\Repository;


use App\Models\Fee;
use App\Models\FiscalYear;
use App\Models\Grade;

class FeesRepository implements FeesRepositoryInterface
{

    public function index()
    {

        $fees = Fee::all();
        $Grades = Grade::all();
        return view('pages.Fees.index', compact('fees', 'Grades'));
    }

    public function create()
    {

        $Grades = Grade::all();
        return view('pages.Fees.add', compact('Grades'));
    }

    public function edit($id)
    {

        $fee = Fee::findorfail($id);
        $Grades = Grade::all();
        return view('pages.Fees.edit', compact('fee', 'Grades'));
    }


    public function store($request)
    {
        try {
            $activeFiscalYear = FiscalYear::requireActive();

            $fees = new Fee();
            $fees->title = $request->title;
            $fees->amount  = $request->amount;
            $fees->Grade_id  = $request->Grade_id;
            $fees->Classroom_id  = $request->Classroom_id;
            $fees->description  = $request->description;
            $fees->year  = $request->year;
            $fees->Fee_type  = $request->Fee_type;
            $fees->active_fiscal_year_id = $activeFiscalYear->id;
            $fees->save();
            
            toastr()->success('Data has been saved successfully');
            return redirect()->route('Fees.create');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function update($request)
    {
        try {
            $fees = Fee::findorfail($request->id);
            $fees->title = $request->title;
            $fees->amount  = $request->amount;
            $fees->Grade_id  = $request->Grade_id;
            $fees->Classroom_id  = $request->Classroom_id;
            $fees->description  = $request->description;
            $fees->year  = $request->year;
            $fees->Fee_type  = $request->Fee_type;
            $fees->save();
            toastr()->success('Data has been Update successfully');
            return redirect()->route('Fees.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy($request)
    {
        try {
            Fee::destroy($request->id);
            toastr()->error('Data has been Deleted successfully');
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
