<!DOCTYPE html>
<head>
    <title>{{__('err-reports::lang.title')}}</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.18/css/dataTables.semanticui.min.css" integrity="sha384-NWDSc00/CBkibLhoKVtYHuQj8VuJNbeHZDTpWhMKBFDPTLSgT2l3HSJjItrJl+B9" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.css" integrity="sha256-9mbkOfVho3ZPXfM7W8sV2SndrGDuh7wuyLjtsWeTI1Q=" crossorigin="anonymous" />
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha384-vk5WoKIaW/vJyUAd9n/wmopsmNhiy+L2Z+SBxGYnUkunIxVxAv/UtMOhba/xskxh" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.js" integrity="sha256-t8GepnyPmw9t+foMh3mKNvcorqNHamSKtKRxxpUEgFI=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" integrity="sha384-r3v0/sXe5ocDydKBFcxP390rex2dEm9qN3Yv68S6uNX/F3b/RtMdGMUADZ8tabkz" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.18/js/dataTables.semanticui.min.js" integrity="sha384-5IYbSnFd6TeNKhOf8CO6LuJpN4IuBiaYwOsPv7CQsbF8sctyVeh7GU3OlfvFBW6n" crossorigin="anonymous"></script>
</head>
<body>
@php($html->addTableClass(['ui', 'celled', 'table']))
{!! $html->table() !!}
{!! $html->scripts() !!}
</body>