<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\ExcelData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ExcelController extends Controller
{
    public function handle(Request $request)
    {
        $sheets = [];
        $columns = [];
        $data = [];

        // Step 1: Upload Excel
        if ($request->hasFile('excelFile')) {
            $path = $request->file('excelFile')->store('excel_uploads');
            session(['excel_file' => $path]);

            $spreadsheet = IOFactory::load(Storage::path($path));
            $sheets = $spreadsheet->getSheetNames();
        }

        // Step 2: Select Sheet
        if ($request->has('selectedSheet') && session('excel_file')) {
            $spreadsheet = IOFactory::load(Storage::path(session('excel_file')));
            $worksheet = $spreadsheet->getSheetByName($request->selectedSheet);
            $columns = $worksheet->rangeToArray('A1:' . $worksheet->getHighestColumn() . '1')[0];
            session(['selected_sheet' => $request->selectedSheet]);
        }

        // Step 3: Select Column
        if ($request->has('selectedColumn') && session('excel_file') && session('selected_sheet')) {
            $spreadsheet = IOFactory::load(Storage::path(session('excel_file')));
            $worksheet = $spreadsheet->getSheetByName(session('selected_sheet'));
            $rows = $worksheet->toArray();

            $headerRow = $rows[0];
            $columnIndex = array_search($request->selectedColumn, $headerRow);

            if ($columnIndex !== false) {
                foreach ($rows as $key => $row) {
                    if ($key === 0) continue;
                    $data[] = $row[$columnIndex];
                }
                session(['selected_column' => $request->selectedColumn]);
            }
        }

        return Inertia::render('ExcelUpload', [
            'sheets' => $sheets,
            'columns' => $columns,
            'data' => $data,
        ]);
    }

    public function save()
    {
        if (session('excel_file') && session('selected_sheet') && session('selected_column')) {
            $spreadsheet = IOFactory::load(Storage::path(session('excel_file')));
            $worksheet = $spreadsheet->getSheetByName(session('selected_sheet'));
            $rows = $worksheet->toArray();

            $columnIndex = array_search(session('selected_column'), $rows[0]);

            if ($columnIndex !== false) {
                foreach ($rows as $key => $row) {
                    if ($key === 0) continue;
                    ExcelData::create(['value' => $row[$columnIndex]]);
                }
            }
        }

        return redirect()->route('excel.index')->with('success', 'Data saved successfully!');
    }
}
