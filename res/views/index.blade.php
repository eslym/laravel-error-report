<!DOCTYPE html>
<html lang="{{app()->getLocale()}}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>{{__('err-reports::lang.title')}}</title>
</head>
<frameset cols="30%,*">
    <frame src="{{route('err-reports::list')}}?{{$query}}">
    <frame src="about:blank" name="details-view">
</frameset>
</html>