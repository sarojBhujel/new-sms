<?php

namespace App\Http\Controllers\Parents;

use App\Exports\ParentImportTemplateExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImportParentsRequest;
use App\Imports\ParentImport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
}
