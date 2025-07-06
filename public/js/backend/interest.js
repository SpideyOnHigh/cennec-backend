"use strict";
var interest = function () {
    var initIndex = function () {
        $('#interest-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            order: [
                [0, 'desc']
            ],
            "columnDefs": [{
                "targets": [1, 3, 6],
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
                url: getlist,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            },
            columns: [
                { data: 'name', class: "p-4 pr-8 border border-t-0  border-gray-50 dark:border-zinc-600" },
                { data: 'color', class: "p-4 pr-8 border border-t-0  border-gray-50 dark:border-zinc-600" },
                { data: 'category', class: "p-4 pr-8 border border-t-0 border-l-0 border-gray-50 dark:border-zinc-600" },
                { data: 'description_link', class: "p-4 pr-8 border border-t-0 border-l-0 border-gray-50 dark:border-zinc-600" },
                { data: 'sponsor_id', class: "p-4 pr-8 border border-t-0 border-l-0 border-gray-50 dark:border-zinc-600" },
                { data: 'created_at', class: "p-4 pr-8 border border-t-0 border-l-0 border-gray-50 dark:border-zinc-600" },
                { data: 'actions', class: "p-4 pr-8 border border-t-0 border-l-0 rtl:border-l border-gray-50 dark:border-zinc-600" }
            ],
        });
    };
    var createValidation = function () {
        $('#interest_create_form').validate({
            rules: {
                interest_name: {
                    required: true,
                    minlength: 2
                },
                interest_color: {
                    required: true
                },
                sponsor_id: {
                    required: true
                },
                interest_category_id: {
                    required: true
                },
                description_link: {
                    // required: true,
                    url: true
                }
            },
            messages: {
                interest_name: {
                    required: "Please enter an interest name.",
                    minlength: "The name must be at least 2 characters long."
                },
                interest_color: {
                    required: "Please select a color."
                },
                sponsor_id: {
                    required: "Please select a sponsor."
                },
                interest_category_id: {
                    required: "Please select a category."
                },
                description_link: {
                    required: "Please enter a description link.",
                    url: "Please enter a valid URL."
                }
            },
            errorElement: "p",
            errorClass: "text-sm text-red-600",
            validClass: "text-green-600",
            highlight: function (element) {
                $(element).addClass('border-red-600');
            },
            unhighlight: function (element) {
                $(element).removeClass('border-red-600');
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