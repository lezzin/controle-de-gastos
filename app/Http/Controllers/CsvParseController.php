<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CsvParseController extends Controller
{
    public function parse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid file.'], 422);
        }

        $file = $request->file('csv_file');
        $handle = fopen($file->getPathname(), 'r');

        if (!$handle) {
            return response()->json(['error' => 'Could not read the file.'], 500);
        }

        $rows = [];
        while (($row = fgetcsv($handle, 1000, ';')) !== false) {
            $rows[] = $row;
        }
        fclose($handle);

        if (count($rows) < 7) {
            return response()->json(['error' => 'CSV does not contain enough rows.'], 422);
        }

        $headers = $rows[5];
        $dataRows = array_slice($rows, 6);

        $data = [];

        foreach ($dataRows as $row) {
            $assoc = [];

            foreach ($headers as $index => $header) {
                $assoc[$header] = $row[$index] ?? null;
            }

            $data[] = $assoc;
        }

        return response()->json([
            'headers' => $headers,
            'rows' => $data,
        ]);
    }
}
