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
        $columns = $this->columns();

        if ($request->ajax()) {
            return Datatables::eloquent(ErrorRecord::select($columns->pluck('data')->toArray()))
                ->make(true);
        }

        $html->columns($columns->toArray());
        $html->orderBy(1, 'desc');

        return response()->view('err-reports::list', compact('html'));
    }

    public function columns(){
        return collect([
            [
                "data" => "is_console",
                "name" => "is_console",
                "title" => '',
                "orderable" => false,
                'searchable' => false,
                'render' => '()=>{return data == "1" ? "<i class=\'ui terminal icon\'></i>" : "<i class=\'ui file alternate outline icon\'></i>";}',
            ],
            [
                "data" => "created_at",
                "name" => "created_at",
                "title" => __('err-reports::datatables.created_at'),
                "footer" => '<div class="ui mini fluid input"><input placeholder="'.e(__('err-reports::datatables.created_at')).'"/></div>',
            ],
            [
                "data" => "id",
                "name" => "id",
                "title" =>__('err-reports::datatables.id'),
                "footer" => '<div class="ui mini fluid input"><input placeholder="'.e(__('err-reports::datatables.id')).'"/></div>',
            ],
            [
                "data" => "site",
                "name" => "site",
                "title" => __('err-reports::datatables.site'),
                "footer" => '<div class="ui mini fluid input"><input placeholder="'.e(__('err-reports::datatables.site')).'"/></div>',
            ],
            [
                "data" => "class",
                "name" => "class",
                "title" => __('err-reports::datatables.class'),
                "footer" => '<div class="ui mini fluid input"><input placeholder="'.e(__('err-reports::datatables.class')).'"/></div>',
            ]
        ]);
    }
}