<?php


namespace Eslym\ErrorReport\Controllers;

use Eslym\ErrorReport\Model\ErrorRecord;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Contracts\DataTable;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder as HtmlBuilder;

class ErrorController extends BaseController
{
    public function list(Request $request, HtmlBuilder $html){
        app()->setLocale('zh-CN');

        $columns = $this->columns();

        if ($request->ajax()) {
            switch ($request->query->get('action')){
                case 'delete':
                    ErrorRecord::findOrFail($request->query->get('id'))
                        ->delete();
                    return response()->json('success');
                case 'view':
                    $record = ErrorRecord::findOrFail($request->query->get('id'));
                    return response()->json([
                        'reports' => $record
                            ->reports()
                            ->get(['id', 'created_at']),
                        'comments' => $record
                            ->comments()
                            ->orderByDesc('created_at')
                            ->get(['email', 'created_at', 'content'])
                    ]);
                default:
                    $query = ErrorRecord::query()
                        ->leftJoin('error_comments AS c', 'error_records.id', '=', 'c.error_id')
                        ->groupBy('error_records.id')
                        ->select([
                            'error_records.*',
                            DB::raw('COUNT(c.id) AS comments')
                        ]);

                    $comments_index = $columns->pluck('data')
                        ->search('comments');

                    if(!empty($search = $request->input("columns.$comments_index.search.value"))){
                        $this->filterNumber($query, 'comments', $search, 'having');
                    }

                    return Datatables::eloquent($query)
                        ->filterColumn('comments', function(Builder $query, $keyword){
                        })
                        ->filterColumn('counter', function(Builder $query, $keyword){
                            $this->filterNumber($query, 'counter', $keyword);
                        })
                        ->make(true);
            }
        }

        $html->columns($columns->toArray());
        $html->addAction([
            'title' => '',
            'render' => '()=>{return drawAction(data,type,full,meta)}',
            'className' => 'collapsing',
        ]);
        $html->orderBy(1, 'desc');

        $html->addTableClass(['ui', 'small', 'definition', 'celled', 'table', 'responsive', 'nowrap', 'unstackable']);
        $html->parameters([
            'responsive' => true,
            'language' => __('err-reports::datatables.languages'),
        ]);

        return response()->view('err-reports::list', compact('html'));
    }

    protected function columns(){
        return collect([
            [
                "data" => "is_console",
                "name" => "is_console",
                "title" => '',
                "orderable" => false,
                'searchable' => false,
                'render' => '()=>{return data == "1" ? "<i class=\'ui terminal icon\'></i>" : "<i class=\'ui file alternate outline icon\'></i>";}',
                'className' => 'collapsing',
            ],
            [
                "data" => "created_at",
                "name" => "created_at",
                "title" => __('err-reports::datatables.created_at'),
                "footer" => '<div class="ui fluid transparent input"><input placeholder="'.e(__('err-reports::datatables.created_at')).'"/></div>',
            ],
            [
                "data" => "id",
                "name" => "id",
                "title" =>__('err-reports::datatables.id'),
                "footer" => '<div class="ui fluid transparent input"><input placeholder="'.e(__('err-reports::datatables.id')).'"/></div>',
            ],
            [
                "data" => "site",
                "name" => "site",
                "title" => __('err-reports::datatables.site'),
                "footer" => '<div class="ui fluid transparent input"><input placeholder="'.e(__('err-reports::datatables.site')).'"/></div>',
            ],
            [
                "data" => "class",
                "name" => "class",
                "title" => __('err-reports::datatables.class'),
                "footer" => '<div class="ui fluid transparent input"><input placeholder="'.e(__('err-reports::datatables.class')).'"/></div>',
            ],
            [
                "data" => "counter",
                "name" => "counter",
                "title" => __('err-reports::datatables.counter'),
                "footer" => '<div class="ui fluid transparent input"><input placeholder="'.e(__('err-reports::datatables.counter')).'"/></div>',
            ],
            [
                "data" => "comments",
                "name" => "comments",
                "title" => __('err-reports::datatables.comments'),
                "footer" => '<div class="ui fluid transparent input"><input placeholder="'.e(__('err-reports::datatables.comments')).'"/></div>',
            ]
        ]);
    }

    protected function filterNumber(Builder $query, $column, $keyword, $method = 'where'){
        if(preg_match('/^\s*(<>|<|>|=|!=)\s*(\d+)\s*$/', $keyword, $matches)){
            call_user_func([$query, $method], $column, $matches[1], $matches[2]);
            return;
        }
        if(preg_match('/^\s*(\d+)\s*-\s*(\d+)\s*$/', $keyword, $matches)){
            call_user_func([$query, $method.'Between'], $column, [$matches[1], $matches[2]]);
            return;
        }
        $keyword = str_replace('%', '\%', $keyword);
        call_user_func([$query, $method], $column, 'LIKE', "%$keyword%");
    }
}