"use strict";
var policyPage = function () {
    var initIndex = function () {
        $('#policy-page-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            order: [
                [0, 'desc']
            ],
            "columnDefs": [{
                "targets": 5,
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
                { data: 'id', class: "p-4 pr-8 border border-t-0  border-gray-50 dark:border-zinc-600" },
                { data: 'title', class: "p-4 pr-8 border border-t-0 border-l-0 border-gray-50 dark:border-zinc-600" },
                // { data: 'slug', class: "p-4 pr-8 border border-t-0 border-l-0 border-gray-50 dark:border-zinc-600" },
                { data: 'content', class: "p-4 pr-8 border border-t-0 border-l-0 border-gray-50 dark:border-zinc-600" },
                { data: 'policies_status', class: "p-4 pr-8 border border-t-0 border-l-0 border-gray-50 dark:border-zinc-600" },
                { data: 'created_at', class: "p-4 pr-8 border border-t-0 border-l-0 border-gray-50 dark:border-zinc-600" },
                { data: 'actions', class: "p-4 pr-8 border border-t-0 border-l-0 rtl:border-l border-gray-50 dark:border-zinc-600" }
            ],
        });
    };

    var editRoleValidation = function () {
        $('#policy-page-edit').validate({
            rules: {
                title: {
                    required: true,
                    maxlength: 255,
                },
                policies_status: {
                    required: true,
                },
                slug: {
                    required: true,
                    maxlength: 255,
                },
                content: {
                    required: true,
                },
            },
        });

        if (typeof CKEDITOR !== 'undefined') {
            CKEDITOR.replace('content');
        } else {
            console.error('CKEditor 4 script not loaded.');
        }
    };


    return {
        init: function () {
            initIndex();
        },
        edit: function () {
            editRoleValidation();
        },
    };
}();