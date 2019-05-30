<?php


namespace Eslym\ErrorReport\Controllers;

use Eslym\ErrorReport\Model\ErrorRecord;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;

class ErrorController extends BaseController
{
    public function list(Request $request, Builder $html){
        $columns = collect([
            [
                "data" => "id",
                "name" => "id",
                "title" => "Id",
                "orderable" => true,
                "searchable" => true,
                "attributes" => [],
            ],
            [
                "data" => "site",
                "name" => "site",
                "title" => "Site",
                "orderable" => true,
                "searchable" => true,
                "attributes" => [],
            ],
            [
                "data" => "class",
                "name" => "class",
                "title" => "Class",
                "orderable" => true,
                "searchable" => true,
                "attributes" => [],
            ],
            [
                "data" => "created_at",
                "name" => "created_at",
                "title" => "Created At",
                "orderable" => true,
                "searchable" => true,
                "attributes" => [],
            ],
            [
                "data" => "is_console",
                "name" => "is_console",
                "title" => "Environment",
                "orderable" => true,
                "searchable" => true,
                "attributes" => [],
            ]
        ]);

        if ($request->ajax()) {
            return Datatables::eloquent(ErrorRecord::select($columns->pluck('data')->toArray()))
                ->make(true);
        }

        $html->columns($columns->toArray());

        return response()->view('err-reports::list', compact('html'));
    }

    public function view($report){
        return response($report->content, 200);
    }

    public function delete(Request $request, $report){
        $report = ErrorRecord::findOrFail($report, ['id']);
        $report->delete();
        return redirect()->route('err-reports::index', $request->query->all());
    }
}