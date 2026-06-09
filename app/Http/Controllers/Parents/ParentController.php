<?php

namespace App\Http\Controllers\Parents;

use App\Exports\ParentImportTemplateExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImportParentsRequest;
use App\Http\Requests\QuickCreateParentRequest;
use App\Imports\ParentImport;
use App\Models\My_Parent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class ParentController extends Controller
{
    public function importForm()
    {
        return view('pages.Parents.import');
    }

    public function downloadTemplate()
    {
        return Excel::download(new ParentImportTemplateExport(), 'parent-import-template.xlsx');
    }

    public function import(ImportParentsRequest $request)
    {
        $import = new ParentImport();

        try {
            Excel::import($import, $request->file('file'));

            $failures = collect($import->failures())->map(function ($failure) {
                return sprintf('Row %d: %s', $failure->row(), implode(', ', $failure->errors()));
            })->toArray();

            if (!empty($failures) || method_exists($import, 'errors') && count($import->errors())) {
                return redirect()->back()->with('import_failures', $failures);
            }

            return redirect()->back()->with('success', 'Parents imported successfully.');
        } catch (\Exception $exception) {
            return redirect()->back()->withErrors(['file' => $exception->getMessage()]);
        }
    }

    /**
     * Quick create parent via AJAX
     */
    public function storeQuick(QuickCreateParentRequest $request)
    {
        try {
            $parent = new My_Parent();
            $parent->Name_Father = $request->input('Name_Father');
            $parent->Name_Mother = $request->input('Name_Mother');
            $parent->Job_Father = $request->input('Job_Father');
            $parent->Job_Mother = $request->input('Job_Mother');
            $parent->Phone_Father = $request->input('Phone_Father');
            $parent->Phone_Mother = $request->input('Phone_Mother');
            $parent->Address_Father = $request->input('Address_Father');
            $parent->National_ID_Father = $request->input('National_ID_Father');

            // Enforce default nationality (not shown in UI)
            $parent->Nationality_Father_id = 155;
            $parent->Nationality_Mother_id = 155;

            // Handle optional user account creation
            $createUser = $request->input('create_user') == '1' || $request->input('create_user') == 1 || $request->boolean('create_user');
            if ($createUser) {
                $parent->email = $request->input('email');
                $parent->password = Hash::make($request->input('password'));
            } else {
                $parent->email = null;
                $parent->password = null;
            }

            $parent->save();

            return response()->json([
                'success' => true,
                'message' => 'Parent created successfully.',
                'parent' => [
                    'id' => $parent->id,
                    'name' => $parent->Name_Father,
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create parent: ' . $e->getMessage()
            ], 500);
        }
    }
}
