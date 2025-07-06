"use strict";
var whitelist = function () {
    var initIndex = function () {
        $('#whitelist-domain-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            order: [
                [0, 'desc']
            ],
            "columnDefs": [{
                "targets": 2,
                "orderable": false
            },
            {
                "targets": "_all",
                "createdCell": function (td, cellData, rowData, row, col) {
                    $(td).addClass('ml-5 p-4 pr-8 border border-t-0 rtl:border-l-0 border-gray-50 dark:border-zinc-600');
                }
            }
            ],
            ajax: {
                url: getDomainlist,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            },
            columns: [
                { data: 'email', class: "p-4 pr-8 border border-t-0  border-gray-50 dark:border-zinc-600" },
                { data: 'name', class: "p-4 pr-8 border border-t-0  border-gray-50 dark:border-zinc-600" },
                { data: 'actions', class: "p-4 pr-8 border border-t-0 border-l-0 rtl:border-l border-gray-50 dark:border-zinc-600" }
            ],
        });

        $('#whitelist-email-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            order: [
                [0, 'desc']
            ],
            "columnDefs": [{
                "targets": 4,
                "orderable": false
            },
            {
                "targets": "_all",
                "createdCell": function (td, cellData, rowData, row, col) {
                    $(td).addClass('ml-5 p-4 pr-8 border border-t-0 rtl:border-l-0 border-gray-50 dark:border-zinc-600');
                }
            }
            ],
            ajax: {
                url: getEmaillist,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            },
            columns: [
                { data: 'email', class: "p-4 pr-8 border border-t-0  border-gray-50 dark:border-zinc-600" },
                { data: 'first_name', class: "p-4 pr-8 border border-t-0  border-gray-50 dark:border-zinc-600" },
                { data: 'last_name', class: "p-4 pr-8 border border-t-0  border-gray-50 dark:border-zinc-600" },
                { data: 'created_at', class: "p-4 pr-8 border border-t-0  border-gray-50 dark:border-zinc-600" },
                { data: 'actions', class: "p-4 pr-8 border border-t-0 border-l-0 rtl:border-l border-gray-50 dark:border-zinc-600" }
            ],
        });

    };
    var createValidation = function () {
        $('#whitelist-table').validate({
            rules: {
                first_name: {
                    required: true,
                    maxlength: 255,
                },
                last_name: {
                    required: true,
                    maxlength: 255,
                },
                email: {
                    required: true,
                    email: true,
                },
                is_domain: {
                    required: true,
                },
            },
            messages: {
                first_name: {
                    required: "Please enter first name.",
                },
                last_name: {
                    required: "Please enter last name.",
                },
                email: {
                    required: "Please enter email.",
                },
                is_domain: {
                    required: "Please select is domain.",
                },
            },
            errorElement: "p",
            errorClass: "text-sm text-red-600",
            validClass: "text-green-600",
            highlight: function (element) {
                $(element).addClass('border-red-600');
            },
            unhighlight: function (element) {
                $(element).removeClass('border-red-600');
            },
            errorPlacement: function (error, element) {
                if (element.attr("name") === "is_domain") {
                    error.insertAfter(element.closest('div').parent());
                } else {
                    error.insertAfter(element);
                }
            }
        });
    };

    return {
        init: function () {
            initIndex();
        },
        create: function () {
            createValidation();
        },
        edit: function () {
            createValidation();
        },
    };
}();