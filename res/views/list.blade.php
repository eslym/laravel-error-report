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
    <script src="https://cdn.datatables.net/fixedheader/3.1.5/js/dataTables.fixedHeader.min.js" integrity="sha384-e/L88frG01/c0HybKKVKr3UAZP3l78l0013aBen/0iP4jkA/koXYOWSQ7YP2bETZ" crossorigin="anonymous"></script>
    <style>
        table.responsive{
            width: 100% !important;
        }
    </style>
    <script>
        function drawAction(data,type,full,meta) {
            return $(document.createElement('div')).append(
                $(document.createElement('div'))
                    .data('id', full.id)
                    .addClass('ui tiny icon buttons')
                    .append(
                        $(document.createElement('button'))
                            .append(
                                $(document.createElement('i'))
                                    .addClass('eye icon')
                            ).addClass('ui green button')
                    ).append(
                        $(document.createElement('button'))
                            .append(
                                $(document.createElement('i'))
                                    .addClass('comment icon')
                            ).addClass('ui blue button')
                    ).append(
                        $(document.createElement('button'))
                            .append(
                                $(document.createElement('i'))
                                    .addClass('trash icon')
                            ).addClass('ui red button')
                    )
            ).html();
        }

        $.fn.dataTable.ext.classes.sProcessing = "dataTables_processing ui dimmer text loader";
        $.fn.dataTable.defaults.dom = "<'ui basic segment'r<'ui stackable grid'<'row'<'eight wide column'l><'right aligned eight wide column'f>><'row dt-table'<'sixteen wide column't>><'row'<'seven wide column'i><'right aligned nine wide column'p>>>>";

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
{!! $html->table([], true, false) !!}
{!! $html->scripts() !!}
<script>
    LaravelDataTables.dataTableBuilder.columns().every(function () {
        let col = this;
        this.search($('input', this.footer()).val());
        this.draw();
        $('input', this.footer()).on('keyup change', function () {
            if (col.search() !== this.value) {
                col.search(this.value)
                    .draw();
            }
        });
    });
</script>
</body>