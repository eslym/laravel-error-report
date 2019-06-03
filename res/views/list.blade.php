<!DOCTYPE html>
<head>
    <title>{{__('err-reports::lang.title')}}</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.18/css/dataTables.semanticui.min.css" integrity="sha384-NWDSc00/CBkibLhoKVtYHuQj8VuJNbeHZDTpWhMKBFDPTLSgT2l3HSJjItrJl+B9" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.css" integrity="sha256-9mbkOfVho3ZPXfM7W8sV2SndrGDuh7wuyLjtsWeTI1Q=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.semanticui.min.css" integrity="sha384-Ntav57xESwsVryMdxDC3CG1VWvWNrxYxgKpQmZXtWwDV/ypfVa0kp4rhvraTFJJ1" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha384-vk5WoKIaW/vJyUAd9n/wmopsmNhiy+L2Z+SBxGYnUkunIxVxAv/UtMOhba/xskxh" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.js" integrity="sha256-t8GepnyPmw9t+foMh3mKNvcorqNHamSKtKRxxpUEgFI=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" integrity="sha384-r3v0/sXe5ocDydKBFcxP390rex2dEm9qN3Yv68S6uNX/F3b/RtMdGMUADZ8tabkz" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.18/js/dataTables.semanticui.min.js" integrity="sha384-5IYbSnFd6TeNKhOf8CO6LuJpN4IuBiaYwOsPv7CQsbF8sctyVeh7GU3OlfvFBW6n" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js" integrity="sha384-utW62Q5udTycRsqDMdQwjeaKASTAE2cf20juuz5yfC1n1hu8gBJ1Pn0oEzKIb8Gd" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.semanticui.min.js" integrity="sha384-fcF9eJURpHYoXg4EOQUt585TNKVmiahNP9nHmn0sgDvGd69x94o9IUheOiDsmeHc" crossorigin="anonymous"></script>
    <style>
        table.responsive{
            width: 100% !important;
        }
    </style>
    <script>
        jQuery.el = function (el){
            return $(document.createElement(el));
        };
        function drawAction(data,type,full,meta) {
            return $.el('div').append(
                $.el('div')
                    .data('id', full.id)
                    .addClass('ui tiny icon buttons')
                    .append(
                        $.el('button')
                            .append(
                                $.el('i')
                                    .addClass('eye icon'))
                            .attr('data-action', 'view')
                            .attr('data-id', full.id)
                            .addClass('ui green button'))
                    .append(
                        $.el('button')
                            .append(
                                $.el('i')
                                    .addClass('trash icon'))
                            .attr('data-action', 'delete')
                            .attr('data-id', full.id)
                            .addClass('ui red button'))
            ).html();
        }

        $.fn.dataTable.defaults.dom = "<'ui basic segment'<'ui stackable grid'<'row dt-table'<'sixteen wide column'tr>><'row'<'seven wide column'i><'right aligned nine wide column'p>>>>";

        /* Bootstrap paging button renderer */
        $.fn.dataTable.ext.renderer.pageButton.semanticUI = function ( settings, host, idx, buttons, page, pages ) {
            var api     = new $.fn.dataTable.Api( settings );
            var classes = settings.oClasses;
            var aria = settings.oLanguage.oAria.paginate || {};
            var btnDisplay, btnClass, counter=0;

            var attach = function( container, buttons ) {
                var i, ien, node, button;
                var clickHandler = function ( e ) {
                    e.preventDefault();
                    if ( !$(e.currentTarget).hasClass('disabled') && api.page() != e.data.action ) {
                        api.page( e.data.action ).draw( 'page' );
                    }
                };

                for ( i=0, ien=buttons.length ; i<ien ; i++ ) {
                    button = buttons[i];

                    if ( $.isArray( button ) ) {
                        attach( container, button );
                    }
                    else {
                        btnDisplay = '';
                        btnClass = '';

                        switch ( button ) {
                            case 'ellipsis':
                                btnDisplay = '<i class="ellipsis horizontal icon"></i>';
                                btnClass = 'disabled' + ' icon';
                                break;

                            case 'first':
                                btnDisplay = '<i class="angle double left icon"></i>';
                                btnClass = button + (page > 0 ?
                                    '' : ' disabled') + ' icon';
                                break;

                            case 'previous':
                                btnDisplay = '<i class="angle left icon"></i>';
                                btnClass = button + (page > 0 ?
                                    '' : ' disabled') + ' icon';
                                break;

                            case 'next':
                                btnDisplay = '<i class="angle right icon"></i>';
                                btnClass = button + (page < pages-1 ?
                                    '' : ' disabled') + ' icon';
                                break;

                            case 'last':
                                btnDisplay = '<i class="angle double right icon"></i>';
                                btnClass = button + (page < pages-1 ?
                                    '' : ' disabled') + ' icon';
                                break;

                            default:
                                btnDisplay = button + 1;
                                btnClass = page === button ?
                                    'active' : '';
                                break;
                        }

                        var tag = btnClass.indexOf( 'disabled' ) === -1 ?
                            'a' :
                            'div';

                        if ( btnDisplay ) {
                            node = $('<'+tag+'>', {
                                'class': classes.sPageButton+' '+btnClass,
                                'id': idx === 0 && typeof button === 'string' ?
                                    settings.sTableId +'_'+ button :
                                    null,
                                'href': '#',
                                'aria-controls': settings.sTableId,
                                'aria-label': aria[ button ],
                                'data-dt-idx': counter,
                                'tabindex': settings.iTabIndex
                            } )
                                .html( btnDisplay )
                                .appendTo( container );

                            settings.oApi._fnBindAction(
                                node, {action: button}, clickHandler
                            );

                            counter++;
                        }
                    }
                }
            };

            // IE9 throws an 'unknown error' if document.activeElement is used
            // inside an iframe or frame.
            var activeEl;

            try {
                // Because this approach is destroying and recreating the paging
                // elements, focus is lost on the select button which is bad for
                // accessibility. So we want to restore focus once the draw has
                // completed
                activeEl = $(host).find(document.activeElement).data('dt-idx');
            }
            catch (e) {}

            attach(
                $(host).empty().html('<div class="ui stackable pagination menu"/>').children(),
                buttons
            );

            if ( activeEl !== undefined ) {
                $(host).find( '[data-dt-idx='+activeEl+']' ).focus();
            }
        };
    </script>
</head>
<body>
<div class="ui basic segment">
    <div class="ui form">
        {{ \Illuminate\Support\Str::before(__('err-reports::datatables.languages.lengthMenu'), '_MENU_') }}
        <div class="ui selection compact dropdown" id="len">
            <input type="hidden" name="len">
            <i class="dropdown icon"></i>
            <div class="text">10</div>
            <div class="menu">
                <div class="item" data-value="10">10</div>
                <div class="item" data-value="20">20</div>
                <div class="item" data-value="30">30</div>
                <div class="item" data-value="40">40</div>
                <div class="item" data-value="50">50</div>
            </div>
        </div>
        {{ \Illuminate\Support\Str::after(__('err-reports::datatables.languages.lengthMenu'), '_MENU_') }}
    </div>
</div>
{!! $html->table([], true, false) !!}
{!! $html->scripts() !!}
<div class="ui basic mini modal" id="deleteModal">
    <div class="ui icon header">
        <i class="trash alternative icon"></i>
        {{ __('err-reports::lang.delete.title') }}
    </div>
    <div class="content">
        <p>{!! __('err-reports::lang.delete.message', ['id' => '<span class="id"></span>']) !!}</p>
    </div>
    <div class="actions">
        <div class="ui red basic cancel inverted button">
            <i class="remove icon"></i>
            {{ __('err-reports::lang.no') }}
        </div>
        <div class="ui green ok inverted button">
            <i class="checkmark icon"></i>
            {{ __('err-reports::lang.yes') }}
        </div>
    </div>
</div>
<script>
    LaravelDataTables.dataTableBuilder.columns().every(function () {
        let col = this;
        this.search($('input', this.footer()).val());
        this.draw();
        $('input', this.footer()).on('keyup change', function () {
            if (col.search() !== this.value) {
                col.search(this.value).draw();
            }
        });
    });
    let len = $('#len.dropdown').dropdown({
        onChange: (value)=>{
            LaravelDataTables.dataTableBuilder.page.len(value).draw();
        }
    }).find('input').val();
    LaravelDataTables.dataTableBuilder.page.len(len);
    $('#dataTableBuilder').on('click', 'button[data-action=delete]', function(){
        let modal = $('#deleteModal')
            .attr('data-id', $(this).data('id'));
        modal.find('span.id')
            .text($(this).data('id'));
        modal.modal('show');
    });
    $('#deleteModal').modal({
        approve: 'button.ok',
        cancel: 'button.cancel',
        onApprove: function(){
            $.getJSON(window.location, {
                action: 'delete',
                id: $(this).data('id')
            }, function () {
                LaravelDataTables.dataTableBuilder.draw();
            })
        }
    });
</script>
</body>