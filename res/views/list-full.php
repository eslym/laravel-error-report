<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>Error Reports</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.18/css/dataTables.semanticui.min.css"
          integrity="sha384-NWDSc00/CBkibLhoKVtYHuQj8VuJNbeHZDTpWhMKBFDPTLSgT2l3HSJjItrJl+B9" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.css"
          integrity="sha256-9mbkOfVho3ZPXfM7W8sV2SndrGDuh7wuyLjtsWeTI1Q=" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.semanticui.min.css"
          integrity="sha384-Ntav57xESwsVryMdxDC3CG1VWvWNrxYxgKpQmZXtWwDV/ypfVa0kp4rhvraTFJJ1" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
            integrity="sha384-vk5WoKIaW/vJyUAd9n/wmopsmNhiy+L2Z+SBxGYnUkunIxVxAv/UtMOhba/xskxh"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.js"
            integrity="sha256-t8GepnyPmw9t+foMh3mKNvcorqNHamSKtKRxxpUEgFI=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"
            integrity="sha384-r3v0/sXe5ocDydKBFcxP390rex2dEm9qN3Yv68S6uNX/F3b/RtMdGMUADZ8tabkz"
            crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.18/js/dataTables.semanticui.min.js"
            integrity="sha384-5IYbSnFd6TeNKhOf8CO6LuJpN4IuBiaYwOsPv7CQsbF8sctyVeh7GU3OlfvFBW6n"
            crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"
            integrity="sha384-utW62Q5udTycRsqDMdQwjeaKASTAE2cf20juuz5yfC1n1hu8gBJ1Pn0oEzKIb8Gd"
            crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.semanticui.min.js"
            integrity="sha384-fcF9eJURpHYoXg4EOQUt585TNKVmiahNP9nHmn0sgDvGd69x94o9IUheOiDsmeHc"
            crossorigin="anonymous"></script>
    <style>
        table.responsive {
            width: 100% !important;
        }
    </style>
    <script>
        jQuery.el = function (el) {
            return $(document.createElement(el));
        };

        function drawAction(data, type, full, meta) {
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
        $.fn.dataTable.ext.renderer.pageButton.semanticUI = function (settings, host, idx, buttons, page, pages) {
            var api = new $.fn.dataTable.Api(settings);
            var classes = settings.oClasses;
            var aria = settings.oLanguage.oAria.paginate || {};
            var btnDisplay, btnClass, counter = 0;

            var attach = function (container, buttons) {
                var i, ien, node, button;
                var clickHandler = function (e) {
                    e.preventDefault();
                    if (!$(e.currentTarget).hasClass('disabled') && api.page() != e.data.action) {
                        api.page(e.data.action).draw('page');
                    }
                };

                for (i = 0, ien = buttons.length; i < ien; i++) {
                    button = buttons[i];

                    if ($.isArray(button)) {
                        attach(container, button);
                    } else {
                        btnDisplay = '';
                        btnClass = '';

                        switch (button) {
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
                                btnClass = button + (page < pages - 1 ?
                                    '' : ' disabled') + ' icon';
                                break;

                            case 'last':
                                btnDisplay = '<i class="angle double right icon"></i>';
                                btnClass = button + (page < pages - 1 ?
                                    '' : ' disabled') + ' icon';
                                break;

                            default:
                                btnDisplay = button + 1;
                                btnClass = page === button ?
                                    'active' : '';
                                break;
                        }

                        var tag = btnClass.indexOf('disabled') === -1 ?
                            'a' :
                            'div';

                        if (btnDisplay) {
                            node = $('<' + tag + '>', {
                                'class': classes.sPageButton + ' ' + btnClass,
                                'id': idx === 0 && typeof button === 'string' ?
                                    settings.sTableId + '_' + button :
                                    null,
                                'href': '#',
                                'aria-controls': settings.sTableId,
                                'aria-label': aria[button],
                                'data-dt-idx': counter,
                                'tabindex': settings.iTabIndex
                            })
                                .html(btnDisplay)
                                .appendTo(container);

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
            } catch (e) {
            }

            attach(
                $(host).empty().html('<div class="ui stackable pagination menu"/>').children(),
                buttons
            );

            if (activeEl !== undefined) {
                $(host).find('[data-dt-idx=' + activeEl + ']').focus();
            }
        };
    </script>
</head>
<body>
<div class="ui basic segment">
    <div class="ui form">
        Show
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
        entries
    </div>
</div>
<table class="table ui small definition celled responsive nowrap unstackable" id="dt">
    <thead>
    <tr>
        <th title=""></th>
        <th title="First Seen">First Seen</th>
        <th title="Last Seen">Last Seen</th>
        <th title="Report ID">Report ID</th>
        <th title="Site">Site</th>
        <th title="Error Type">Error Type</th>
        <th title="Error Count">Error Count</th>
        <th title="Comment Count">Comment Count</th>
        <th title=""></th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th></th>
        <th>
            <div class="ui fluid transparent input"><input placeholder="First Seen"/></div>
        </th>
        <th>
            <div class="ui fluid transparent input"><input placeholder="Last Seen"/></div>
        </th>
        <th>
            <div class="ui fluid transparent input"><input placeholder="Report ID"/></div>
        </th>
        <th>
            <div class="ui fluid transparent input"><input placeholder="Site"/></div>
        </th>
        <th>
            <div class="ui fluid transparent input"><input placeholder="Error Type"/></div>
        </th>
        <th>
            <div class="ui fluid transparent input"><input placeholder="Error Count"/></div>
        </th>
        <th>
            <div class="ui fluid transparent input"><input placeholder="Comment Count"/></div>
        </th>
        <th></th>
    </tr>
    </tfoot>
</table>
<script type="text/javascript">(function (window, $) {
    window.dt = $("#dt").DataTable({
        "serverSide": true,
        "processing": true,
        "ajax": "",
        "columns": [{
            "name": "is_console",
            "data": "is_console",
            "title": "",
            "orderable": false,
            "searchable": false,
            "render": function (data, type, full, meta) {
                return () => {
                    return data == "1" ? "<i class='ui terminal icon'></i>" : "<i class='ui file alternate outline icon'></i>";
                };
            },
            "className": "collapsing center aligned"
        }, {
            "name": "created_at",
            "data": "created_at",
            "title": "First Seen",
            "orderable": true,
            "searchable": true
        }, {
            "name": "updated_at",
            "data": "updated_at",
            "title": "Last Seen",
            "orderable": true,
            "searchable": true
        }, {
            "name": "id", "data": "id", "title": "Report ID", "render": function (data, type, full, meta) {
                return () => {
                    return '<code>' + data + '</code>';
                };
            }, "orderable": true, "searchable": true
        }, {
            "name": "site",
            "data": "site",
            "title": "Site",
            "orderable": true,
            "searchable": true
        }, {
            "name": "class",
            "data": "class",
            "title": "Error Type",
            "orderable": true,
            "searchable": true
        }, {
            "name": "counter",
            "data": "counter",
            "title": "Error Count",
            "className": "center aligned",
            "orderable": true,
            "searchable": true
        }, {
            "name": "comments",
            "data": "comments",
            "title": "Comment Count",
            "className": "center aligned",
            "orderable": true,
            "searchable": true
        }, {
            "defaultContent": "",
            "data": "action",
            "name": "action",
            "title": "",
            "render": function (data, type, full, meta) {
                return () => {
                    return drawAction(data, type, full, meta)
                };
            },
            "orderable": false,
            "searchable": false,
            "className": "collapsing"
        }],
        "order": [[2, "desc"]],
        "responsive": true,
        "language": {
            "decimal": "",
            "emptyTable": "No data available in table",
            "info": "Showing _START_ to _END_ of _TOTAL_ entries",
            "infoEmpty": "Showing 0 to 0 of 0 entries",
            "infoFiltered": "(filtered from _MAX_ total entries)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Show _MENU_ entries",
            "loadingRecords": "Loading...",
            "processing": "Processing...",
            "search": "Search:",
            "zeroRecords": "No matching records found",
            "aria": {
                "sortAscending": ": activate to sort column ascending",
                "sortDescending": ": activate to sort column descending"
            }
        }
    });
})(window, jQuery);
</script>

<div class="ui container">
    <div class="ui tiny modal" id="deleteModal">
        <div class="ui icon header">
            <i class="trash alternate icon"></i>
            Are you sure?
        </div>
        <div class="content">
            <p>You are about to delete report <code class="id"></code>, this action cannot be undone.</p>
        </div>
        <div class="actions">
            <div class="ui red inverted cancel inverted button">
                <i class="remove icon"></i>
                No
            </div>
            <div class="ui green ok button">
                <i class="checkmark icon"></i>
                Yes
            </div>
        </div>
    </div>
    <div class="ui small modal" id="viewModal">
        <div class="header">Error <span class="id"></span></div>
        <div class="content">
            <div class="ui segments">
                <div class="ui segment">
                    <h4>Sample Reports</h4>
                    <div class="ui placeholder">
                        <div class="line"></div>
                        <div class="line"></div>
                        <div class="line"></div>
                        <div class="line"></div>
                        <div class="line"></div>
                    </div>
                    <div class="actual reports">

                    </div>
                </div>
                <div class="ui segment">
                    <h4>Comments</h4>
                    <div class="ui placeholder">
                        <div class="line"></div>
                        <div class="line"></div>
                        <div class="line"></div>
                        <div class="line"></div>
                        <div class="line"></div>
                    </div>
                    <div class="actual comments">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    let select = function () {
        if (document.body.createTextRange) {
            const range = document.body.createTextRange();
            range.moveToElementText(this);
            range.select();
        } else if (window.getSelection) {
            const selection = window.getSelection();
            const range = document.createRange();
            range.selectNodeContents(this);
            selection.removeAllRanges();
            selection.addRange(range);
        } else {
            console.warn("Could not select text in node: Unsupported browser.");
        }
    };
    dt.columns().every(function () {
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
        onChange: (value) => {
            dt.page.len(value).draw();
        }
    }).find('input').val();
    dt.page.len(len);
    $('#dt').on('click', 'button[data-action=delete]', function () {
        let modal = $('#deleteModal')
            .attr('data-id', $(this).data('id'));
        modal.find('code.id')
            .text($(this).data('id'));
        modal.modal('show');
    }).on('click', 'button[data-action=view]', function () {
        let modal = $('#viewModal');
        modal.find('span.id').text($(this).data('id'));
        modal.find('.placeholder').show();
        modal.find('.actual').hide();
        modal.modal('show');
        $.getJSON(window.location, {
            action: 'view',
            id: $(this).data('id')
        }, (data) => {
            if (data.reports.length > 0) {
                let list = $.el('div')
                    .addClass('ui list');
                let a = document.createElement('a');
                a.href = window.location.href;
                $.each(data.reports, function (i, r) {
                    a.search = $.param({action: 'download', id: r.id});
                    list.append($.el('div').addClass('item')
                        .append($.el('i').addClass('file icon'))
                        .append($.el('div').addClass('content')
                            .append($.el('a').addClass('header').text(r.id).attr('href', a.href).attr('download', r.id + '.html'))
                            .append($.el('div').addClass('description').text(r.created_at))));
                });
                modal.find('.actual.reports')
                    .empty().append(list);
            } else {
                modal.find('.actual.reports')
                    .empty()
                    .append(
                        $.el('i')
                            .text('No Sample Reports'));
            }
            if (data.comments.length > 0) {
                let list = $.el('div').addClass('ui comments');
                $.each(data.comments, function (i, c) {
                    let text = $.el('div').addClass('text');
                    let lines = c.content.split("\n");
                    text.append($.el('p').html($.map(lines, (l) => {
                        return $.el('p').text(l).html();
                    }).join('<br/>')));
                    let author = $.el('a').addClass('author');
                    if (c.email) {
                        author.attr('href', 'mailto:' + c.email)
                            .text(c.email);
                    } else {
                        author.text('<Unknown>');
                    }
                    list.append($.el('div').addClass('comment')
                        .append($.el('div').addClass('avatar')
                            .append($.el('i').addClass('big user icon')))
                        .append($.el('div').addClass('content')
                            .append(author)
                            .append($.el('div').addClass('metadata')
                                .append($.el('div').addClass('date').text(c.created_at)))
                            .append(text)
                            .append($.el('div').addClass('actions'))));
                });
                modal.find('.actual.comments')
                    .empty().append(list);
            } else {
                modal.find('.actual.comments')
                    .empty()
                    .append(
                        $.el('i')
                            .text('No Comments'));
            }
            modal.find('.placeholder').hide();
            modal.find('.actual').show();
        });
    }).on('dblclick', 'td:nth-child(4)', select);
    $('#deleteModal').modal({
        approve: 'button.ok',
        cancel: 'button.cancel',
        onApprove: function () {
            $.getJSON(window.location, {
                action: 'delete',
                id: $(this).attr('data-id')
            }, function () {
                dt.draw();
            })
        }
    }).on('click', 'code.id', select);
</script>
</body>