<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CustomQueryController extends Controller
{

    public function index()
{
    
}

public function showForm()
{
    $saved_queries = \App\SavedQuery::where('user_id', auth()->id())
        ->where('saved','=',1)
        ->orderBy('id', 'desc')
        ->get();

    $data = $this->getSchemaData();
    $data['saved_queries'] = $saved_queries;

    return view('custom_query', $data);
}



public function saveQuery(Request $request)
{

    $request->validate(['sql' => 'required|string']);
    $sql = trim($request->input('sql'));

    if(auth()->user()->email != 'it@propack.me'){
            // Trim and remove trailing semicolon
        $sql = trim($sql, " \t\n\r\0\x0B;");

        // Allow only single SELECT statement — disallow stacked/multiple queries
        if (!preg_match('/^select\s.+\sfrom\s.+$/i', $sql) || preg_match('/;\s*(select|update|delete|insert|drop|alter)/i', $sql)) {
            return back()->withInput()->with('error', 'Only single SELECT queries are allowed.');
        }
    }

    // List of tables to forbid
    $forbiddenTables = ['users', 'settings', 'basic_settings']; // add any other forbidden table names here

    // Extract table names from SQL (simple regex, adjust if needed)
    preg_match_all('/from\s+([`"]?)(\w+)\1/i', $sql, $matches);
    $tablesInQuery = $matches[2] ?? [];

    // Normalize table names to lowercase
    $tablesInQuery = array_map('strtolower', $tablesInQuery);

    // Check if any forbidden tables are in query
    foreach ($tablesInQuery as $table) {
        if (in_array($table, $forbiddenTables)) {
            return back()->withInput()->with('error', "Queries on the table '{$table}' are forbidden.");
        }
    }

    try {
        $results = DB::select(DB::raw($sql));
        $resultsArray = json_decode(json_encode($results), true);

        $exist = \App\SavedQuery::where('id','=',auth()->user()->id)->where('body','like','%'.$sql.'%')->value('id');
        if($exist > 0){

        }else{
            $saved_qeury = new \App\SavedQuery;
            $saved_qeury->user_id = auth()->user()->id;
            $saved_qeury->body = $sql;
            $saved_qeury->saved = 1;
            $saved_qeury->created_by = auth()->user()->name;
            $saved_qeury->save();
        }
   
        return back()->withInput()->with('results', $resultsArray);
    } catch (\Exception $e) {
        return back()->withInput()->with('error', $e->getMessage());
    }
}


public function runQuery(Request $request)
{
    $request->validate(['sql' => 'required|string']);
    $sql = trim($request->input('sql'));
    if(auth()->user()->email != 'charbelkabbouchi@gmail.com'){
            // Trim and remove trailing semicolon
        $sql = trim($sql, " \t\n\r\0\x0B;");

        // Allow only single SELECT statement — disallow stacked/multiple queries
        if (!preg_match('/^select\s.+\sfrom\s.+$/i', $sql) || preg_match('/;\s*(select|update|delete|insert|drop|alter)/i', $sql)) {
            return back()->withInput()->with('error', 'Only single SELECT queries are allowed.');
        }
    }
    // List of tables to forbid
    $forbiddenTables = ['users', 'settings', 'basic_settings']; // add any other forbidden table names here

    // Extract table names from SQL (simple regex, adjust if needed)
    preg_match_all('/from\s+([`"]?)(\w+)\1/i', $sql, $matches);
    $tablesInQuery = $matches[2] ?? [];

    // Normalize table names to lowercase
    $tablesInQuery = array_map('strtolower', $tablesInQuery);

    // Check if any forbidden tables are in query
    foreach ($tablesInQuery as $table) {
        if (in_array($table, $forbiddenTables)) {
            return back()->withInput()->with('error', "Queries on the table '{$table}' are forbidden.");
        }
    }

    try {
        $results = DB::select(DB::raw($sql));
        $resultsArray = json_decode(json_encode($results), true);
        $exist = \App\SavedQuery::where('id','=',auth()->user()->id)->where('body','like','%'.$sql.'%')->value('id');
        if($exist > 0){

        }else{
            $saved_qeury = new \App\SavedQuery;
            $saved_qeury->user_id = auth()->user()->id;
            $saved_qeury->body = $sql;
            $saved_qeury->saved = 0;
            $saved_qeury->created_by = auth()->user()->name;
            $saved_qeury->save();
        }
   
        return back()->withInput()->with('results', $resultsArray);

    } catch (\Exception $e) {
        return back()->withInput()->with('error', $e->getMessage());
    }
}


public function deleteQuery(Request $request,$id)
{
        $saved_qeury_delete =  \App\SavedQuery::findorfail($id);
        $saved_qeury_delete->delete();
     return redirect()->back();
}
 
private function getSchemaData(): array
{
    $database = DB::getDatabaseName();

    // Get all user tables in the current MySQL database
    $tablesRaw = DB::select("
        SELECT TABLE_NAME 
        FROM INFORMATION_SCHEMA.TABLES 
        WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA = ?
        ORDER BY TABLE_NAME
    ", [$database]);

    $tableList = array_map(function ($t) {
        return $t->TABLE_NAME;
    }, $tablesRaw);

    $tableColumns = [];
    foreach ($tableList as $table) {
        $columns = DB::select("
            SELECT COLUMN_NAME
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?
            ORDER BY ORDINAL_POSITION
        ", [$database, $table]);

        // Flatten to just column names if desired
        $tableColumns[$table] = array_map(function ($col) {
            return $col->COLUMN_NAME;
        }, $columns);
    }

    return [
        'tableList' => $tableList,
        'tableColumns' => $tableColumns,
    ];
}

    public function showForm1()
    {
        return view('custom_query');
    }

    public function runQuery1(Request $request)
    {
        $request->validate([
            'sql' => 'required|string',
        ]);

        $sql = trim($request->input('sql'));

        // Only allow SELECT queries
        if (!preg_match('/^select\s/i', $sql)) {
            return redirect()->back()->withInput()->with('error', 'Only SELECT queries are allowed.');
        }

        try {
            $results = DB::select(DB::raw($sql));
            // Convert stdClass to array
            $resultsArray = json_decode(json_encode($results), true);

            return redirect()->back()->withInput()->with('results', $resultsArray);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }


    public function export(Request $request): StreamedResponse
{
    $request->validate([
        'sql' => 'required|string',
    ]);

    $sql = trim($request->input('sql'));

    if (!preg_match('/^select\s/i', $sql)) {
        abort(403, 'Only SELECT queries are allowed.');
    }

    try {
        $results = DB::select(DB::raw($sql));
        $resultsArray = json_decode(json_encode($results), true);

        if (empty($resultsArray)) {
            return back()->with('error', 'No data to export.');
        }

        $headers = array_keys($resultsArray[0]);

        $callback = function () use ($resultsArray, $headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);

            foreach ($resultsArray as $row) {
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->streamDownload($callback, 'query_results.csv', [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="query_results.csv"',
        ]);
    } catch (\Exception $e) {
        return back()->with('error', $e->getMessage());
    }
}

}
