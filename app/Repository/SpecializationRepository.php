<?php

namespace App\Repository;

use App\Models\Specialization;
use Exception;

class SpecializationRepository implements SpecializationRepositoryInterface
{
    public function index()
    {
        return Specialization::all();
    }

    public function store($request)
    {
        try {
            Specialization::create([
                'specialization_name' => $request->specialization_name,
                'specialization_code' => $request->specialization_code,
                'description' => $request->description,
                'status' => $request->boolean('status'),
            ]);

            toastr()->success('Data has been saved successfully');
            return redirect()->route('specializations.index');
        } catch (Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        return Specialization::findOrFail($id);
    }

    public function edit($id)
    {
        return Specialization::findOrFail($id);
    }

    public function update($request, $id)
    {
        try {
            $specialization = Specialization::findOrFail($id);
            $specialization->update([
                'specialization_name' => $request->specialization_name,
                'specialization_code' => $request->specialization_code,
                'description' => $request->description,
                'status' => $request->boolean('status'),
            ]);

            toastr()->success('Data has been updated successfully');
            return redirect()->route('specializations.index');
        } catch (Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            Specialization::findOrFail($id)->delete();
            toastr()->error('Data has been deleted successfully');
            return redirect()->route('specializations.index');
        } catch (Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }
}
