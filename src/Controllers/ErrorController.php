<?php


namespace Eslym\ErrorReport\Controllers;

use Eslym\ErrorReport\Model\ErrorComment;
use Eslym\ErrorReport\Model\ErrorRecord;
use Eslym\ErrorReport\Model\ErrorReport;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\Facades\DataTables;

class ErrorController extends BaseController
{
    public function list(Request $request){
        if ($request->ajax()) {
            switch ($request->query->get('action')){
                case 'delete':
                    ErrorRecord::findOrFail($request->query->get('id'))
                        ->delete();
                    return Response::json('success');
                case 'view':
                    $record = ErrorRecord::findOrFail($request->query->get('id'));
                    return response()->json([
                        'reports' => $record
                            ->reports()
                            ->get(['id', 'created_at'])->map(function(ErrorReport $report){
                                return [
                                    'id' => $report->id,
                                    'created_at' => $report->created_at->diffForHumans(),
                                ];
                            }),
                        'comments' => $record
                            ->comments()
                            ->orderByDesc('created_at')
                            ->get(['email', 'created_at', 'content'])->map(function(ErrorComment $comment){
                                return [
                                    'email' => $comment->email,
                                    'content' => $comment->content,
                                    'created_at' => $comment->created_at->diffForHumans(),
                                ];
                            }),
                    ]);
                default:
                    $query = ErrorRecord::query()
                        ->leftJoin('error_comments AS c', 'error_records.id', '=', 'c.error_id')
                        ->groupBy('error_records.id')
                        ->select([
                            'error_records.*',
                            DB::raw('COUNT(c.id) AS comments')
                        ]);

                    foreach ($request->input('columns', []) as $col){
                        if($col['data'] == 'comments' && !empty($search = $col['search']['value'])){
                            $this->filterNumber($query, 'comments', $search, 'having');
                        }
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
        switch ($request->query->get('action')){
            case 'download':
                $report = ErrorReport::findOrFail($request->query->get('id'));
                return Response::download($report->content, $report->id.'.html');
            default:
                return Response::view('err-reports::list');
        }
    }

    protected function filterNumber(Builder $query, $column, $keyword, $method = 'where'){
        if(preg_match('/^\s*(<>|<=?|>=?|!?=)\s*(\d+)\s*$/', $keyword, $matches)){
            call_user_func([$query, $method], $column, $matches[1], $matches[2]);
            return;
        }
        if(preg_match('/^\s*(\d+)\s*-\s*(\d+)\s*$/', $keyword, $matches)){
            call_user_func([$query, $method.'Between'], $column, [$matches[1], $matches[2]]);
            return;
        }
        $keyword = str_replace('\\', '\\\\', $keyword);
        $keyword = str_replace('%', '\%', $keyword);
        call_user_func([$query, $method], $column, 'LIKE', "%$keyword%");
    }
}