<?php


namespace Eslym\ErrorReport\Controllers;

use Eslym\ErrorReport\Model\ErrorReport;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class ErrorController extends BaseController
{
    public function index(){
        return response()->view('err-reports::index');
    }

    public function list(Request $request){
        $query = ErrorReport::query();
        $search = $request->query('search');
        $search = preg_split('/\s+/', $search);
        foreach ($search as $word){
            $word = str_replace('\\', '\\\\', $word);
            $word = str_replace('%', '\\%', $word);
            $query->where(function(Builder $query) use ($word){
                $query->where('id', 'like', "%$word%");
                $query->orWhere('site', 'like', "%$word%");
            });
        }
        $query->orderBy('created_at', 'desc');
        $reports = $query->get(['id', 'class', 'site', 'is_console', 'created_at']);
        return response()->view('err-reports::list', compact('reports'));
    }

    public function view(ErrorReport $report){
        return response($report->content, 200);
    }

    public function delete(ErrorReport $report){
        $report->delete();
        return redirect()->route('err-reports::index');
    }
}