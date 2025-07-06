"use strict";

var operator = function () {
    var initIndex = function () {
        var table = $('#operator-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            order: [
                [0, 'desc']
            ],
            ajax: {
                url: getlist,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            },
            columns: [
                { data: 'user', class: "p-4 pr-8 border border-t-0  border-gray-50 dark:border-zinc-600" },
                { data: 'reported_on', class: "p-4 pr-8 border border-t-0 border-l-0 border-gray-50 dark:border-zinc-600" },
                { data: 'reported_by', class: "p-4 pr-8 border border-t-0 border-l-0 border-gray-50 dark:border-zinc-600" },
                { data: 'reason', class: "p-4 pr-8 border border-t-0 border-l-0 border-gray-50 dark:border-zinc-600" },
            ],
            "createdRow": function (row, data, dataIndex) {
                $('td:eq(0)', row).addClass('p-4 pr-8 border border-t-0 rtl:border-l-0 border-gray-50 dark:border-zinc-600');
                $('td:last', row).addClass('p-4 pr-8 border border-t-0 border-l-0 rtl:border-l border-gray-50 dark:border-zinc-600');
            }
        });
        table.buttons().container()
            .appendTo('#operator-table-buttons_wrapper .col-md-6:eq(0)');

        $(".dataTables_length select").addClass('form-select form-select-sm');
    };


    return {
        init: function () {
            initIndex();
        }
    };
}();