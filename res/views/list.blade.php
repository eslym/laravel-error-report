<!DOCTYPE html>
<html lang="{{app()->getLocale()}}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>{{__('err-reports::lang.title')}}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.css" integrity="sha256-9mbkOfVho3ZPXfM7W8sV2SndrGDuh7wuyLjtsWeTI1Q=" crossorigin="anonymous" />
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha384-vk5WoKIaW/vJyUAd9n/wmopsmNhiy+L2Z+SBxGYnUkunIxVxAv/UtMOhba/xskxh" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.js" integrity="sha256-t8GepnyPmw9t+foMh3mKNvcorqNHamSKtKRxxpUEgFI=" crossorigin="anonymous"></script>
    <style>
        body > form{
            position: fixed !important;
            padding: 20px;
            background: white;
            border-bottom: 1px lightgrey solid;
            top: 0;
            left: 0;
            right: 0;
            z-index: 9000;
        }
        body > div{
            padding: 150px 20px 20px;
        }
    </style>
</head>
<body>
<form class="ui form" id="search-form">
    <div class="field">
        <label for="search">{{__('err-reports::lang.search')}}</label>
        <div class="ui fluid icon input">
            <input id="search" name="search" placeholder="{{__('err-reports::lang.report_id')}}" value="{{request()->query('search')}}"/>
            <i class="circular search link icon"></i>
        </div>
    </div>
    <script>
        $('.link.icon').change(()=>{$('#search-form').submit();});
    </script>
</form>
<div>
    <div class="ui cards">
        @php($now = \Carbon\Carbon::now())
        @foreach($reports as $report)
            <div class="ui fluid card">
                <div class="content">
                    <div class="right floated mini ui image">
                        <i class="ui large {{$report->is_console ? 'terminal' : 'file alternate' }} middle aligned icon"></i>
                    </div>
                    <div class="header">
                        {{$report->class}}
                    </div>
                    <div class="meta">
                        {{$report->created_at->diffForHumans($now)}}
                        <div><i>{{$report->site}}</i></div>
                    </div>
                    <div class="description">
                        {{$report->id}}
                    </div>
                </div>
                <div class="extra content">
                    <div class="ui two buttons">
                        <a class="ui basic green button" href="{{route('err-reports::view', ['report'=>$report->id])}}" target="details-view">
                            {{__('err-reports::lang.details')}}
                        </a>
                        <a class="ui basic red button" href="{{route('err-reports::delete', ['report'=>$report->id])}}" target="_top">
                            {{__('err-reports::lang.delete')}}
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
</body>
</html>