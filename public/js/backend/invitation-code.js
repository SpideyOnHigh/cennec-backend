"use strict";

var invitationCode = function () {
    var initIndex = function () {
        var table = $('#invitation-code-table').DataTable({
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
                { data: 'sponsor_id', class: "p-4 pr-8 border border-t-0 border-l-0 border-gray-50 dark:border-zinc-600" },
                { data: 'email', class: "p-4 pr-8 border border-t-0 border-l-0 border-gray-50 dark:border-zinc-600" },
                { data: 'code', class: "p-4 pr-8 border border-t-0 border-l-0 border-gray-50 dark:border-zinc-600" },
                { data: 'expires', class: "p-4 pr-8 border border-t-0 border-l-0 border-gray-50 dark:border-zinc-600" },
                { data: 'total', class: "p-4 pr-8 border border-t-0 border-l-0 border-gray-50 dark:border-zinc-600" },
                { data: 'used', class: "p-4 pr-8 border border-t-0 border-l-0 border-gray-50 dark:border-zinc-600" },
                { data: 'description', class: "p-4 pr-8 border border-t-0 border-l-0 border-gray-50 dark:border-zinc-600" },
                { data: 'created_at', class: "p-4 pr-8 border border-t-0 border-l-0 border-gray-50 dark:border-zinc-600" },
            ],
            "createdRow": function (row, data, dataIndex) {
                $('td:eq(0)', row).addClass('p-4 pr-8 border border-t-0 rtl:border-l-0 border-gray-50 dark:border-zinc-600');
                $('td:last', row).addClass('p-4 pr-8 border border-t-0 border-l-0 rtl:border-l border-gray-50 dark:border-zinc-600');
            }
        });
        table.buttons().container()
            .appendTo('#invitation-code-table-buttons_wrapper .col-md-6:eq(0)');

        $(".dataTables_length select").addClass('form-select form-select-sm');
    };

    var createOperatorValidation = function () {
        var today = new Date().toISOString().split('T')[0];
        $('#expiration_date').val(today);
        $.validator.addMethod("dateValidation", function (value, element) {
            var selectedDate = value;
            var selectedDateObject = new Date(selectedDate);
            var currentDate = new Date();
            return selectedDateObject >= currentDate;
        }, "Expiry date must be greater than the current date.");
        $('.invitation-code-create-form').validate({
            rules: {
                code: {
                    required: true,
                    number: true,
                    minlength: 8,
                    maxlength: 8,
                },
                max_user_allow: {
                    required: true,
                    number: true,
                },
                comment: {
                    required: true,
                    maxlength: 255,
                },
                sponsor_id: {
                    required: true,
                },
                expiration: "dateValidation",
            },
        });
    };


    return {
        init: function () {
            initIndex();
        },
        create: function () {
            createOperatorValidation();
        },
    };
}();