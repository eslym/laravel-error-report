<div class="ui right floated pagination menu">
    <a class="icon item" @if($paginator->currentPage() > 1) href="{{$paginator->url(1)}}" target="_top"  @endif>
        <i class="angle double left icon"></i>
    </a>
    <a class="icon item" @if($paginator->currentPage() > 1) href="{{$paginator->previousPageUrl()}}" target="_top" @endif>
        <i class="angle left icon"></i>
    </a>
    @php($start = min($paginator->currentPage() - 2, $paginator->lastPage() - 4))
    @php($start = $start < 1 ? 1 : $start)
    @php($end = min($start + 4, $paginator->lastPage()))
    @while($start <= $end)
        @if($start == $paginator->currentPage())
            <a class="item active">{{$start}}</a>
        @else
            <a class="item" href="{{$paginator->url($start)}}" target="_top">{{$start}}</a>
        @endif
        @php($start++)
    @endwhile
    <a class="icon item" @if($paginator->currentPage() < $paginator->lastPage()) href="{{$paginator->nextPageUrl()}}" target="_top" @endif>
        <i class="angle right icon"></i>
    </a>
    <a class="icon item" @if($paginator->currentPage() < $paginator->lastPage()) href="{{$paginator->url($paginator->lastPage())}}" target="_top" @endif>
        <i class="angle double right icon"></i>
    </a>
</div>