<?php


namespace Eslym\ErrorReport\Controllers;

use Eslym\ErrorReport\Model\ErrorReport;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class ErrorController extends BaseController
{
    public function index(Request $request){
        return response()->view('err-reports::index', ['query' => http_build_query($request->query->all())]);
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
                $query->orWhere('class', 'like', "%$word%");
                $query->orWhere('site', 'like', "%$word%");
            });
        }
        $query->orderBy('created_at', 'desc');
        $reports = $query->paginate(15, ['id', 'class', 'site', 'is_console', 'created_at']);
        $reports->appends($request->query->all());
        $reports->withPath(route('err-reports::index'));
        $query = http_build_query($request->query->all());
        return response()->view('err-reports::list', compact('reports', 'query'));
    }

    public function view($report){
        $report = ErrorReport::findOrFail($report, ['content']);
        return response($report->content, 200);
    }

    public function delete(Request $request, $report){
        $report = ErrorReport::findOrFail($report, ['id']);
        $report->delete();
        return redirect()->route('err-reports::index', $request->query->all());
    }
}