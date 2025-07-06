"use strict";
var role = function () {
    var initIndex = function () {
        $('#role-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            order: [
                [0, 'desc']
            ],
            "columnDefs": [
                {
                    "targets": 4,
                    "orderable": false
                },
                {
                    "targets": 0,
                    "visible": false
                },
                {
                    "targets": "_all", // Apply to all columns
                    "createdCell": function (td, cellData, rowData, row, col) {
                        $(td).addClass('ml-5 p-4 pr-8 border border-t-0 rtl:border-l-0 border-gray-50 dark:border-zinc-600');
                    }
                }],
            ajax: {
                url: getlist,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            },
            columns: [
                { data: 'id', class: "p-4 pr-8 border border-t-0  border-gray-50 dark:border-zinc-600" },
                { data: 'name', class: "p-4 pr-8 border border-t-0  border-gray-50 dark:border-zinc-600" },
                { data: 'description', class: "p-4 pr-8 border border-t-0 border-l-0 border-gray-50 dark:border-zinc-600" },
                { data: 'created_date', class: "p-4 pr-8 border border-t-0 border-l-0 border-gray-50 dark:border-zinc-600" },
                { data: 'actions', class: "p-4 pr-8 border border-t-0 border-l-0 rtl:border-l border-gray-50 dark:border-zinc-600" }
            ],
        });
    };
    var createRoleValidation = function () {
        jQuery.validator.addMethod("checkboxRequired", function (value, element, params) {
            return $(params).filter(":checked").length > 0;
        }, "At least one permission must be selected.");
        jQuery.validator.addMethod("noSpace", function (value, element) {
            return value.indexOf(" ") < 0 && value != "";
        }, "Please enter valid name. Space is not allowed");
        $('#role_create_form').validate({
            rules: {
                name: {
                    required: true,
                    // noSpace: true,
                },
                'permission[]': {
                    checkboxRequired: '.permissions'
                },
                description: {
                    required: true,
                }
            },
            messages: {
                name: {
                    required: "Please enter name"
                },
                description: {
                    required: "Please enter description"
                },
                'permission[]': {
                    checkboxRequired: ""  // Leave the message empty to hide default message
                }
            },
            errorPlacement: function (error, element) {
                if (element.attr("name") === "permission[]") {
                    error.insertAfter("#role_create_form");
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function (event, validator) {
                if (validator.errorList.some(error => error.element.name === 'permission[]')) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'At least one permission must be selected.',
                        confirmButtonText: 'OK'
                    });
                }
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
    };

    var permissionSettingsForCrete = function () {
        $(document).on('change', '.permissions', function () {
            if ($(this).attr('id').startsWith('sub_permission_')) {
                var parentId = $(this).attr('id').replace('sub_permission_', '');
                var parentCheckbox = $('#permission_' + parentId + '');
                if ($(this).prop('checked')) {
                    parentCheckbox.prop('checked', true);
                }
                var allSubPermissionsUnchecked = $('.sub_permission_' + parentId + ':checked').length === 0;
                if (allSubPermissionsUnchecked) {
                    parentCheckbox.prop('checked', false);
                }
            } else {
                var subId = $(this).attr('id').replace('permission_', '');
                var subIdCheckbox = $('.sub_permission_' + subId + '');
                if (!$(this).prop('checked')) {
                    subIdCheckbox.prop('checked', false);
                } else {
                    subIdCheckbox.prop('checked', true);
                }
            }
        });
    }

    var editRoleValidation = function () {
        jQuery.validator.addMethod("checkboxRequired", function (value, element, params) {
            return $(params).filter(":checked").length > 0;
        }, "At least one permission must be selected.");
        jQuery.validator.addMethod("noSpace", function (value, element) {
            return value.indexOf(" ") < 0 && value != "";
        }, "Please enter valid name. Space is not allowed");
        $('#role_form_edit').validate({
            rules: {
                name: {
                    required: true,
                    // noSpace: true,
                },
                'permission[]': {
                    checkboxRequired: '.permissions'
                },
                description: {
                    required: true,
                }
            },
            messages: {
                name: {
                    required: "Please enter name"
                },
                description: {
                    required: "Please enter description"
                },
                'permission[]': {
                    checkboxRequired: ""  // Leave the message empty to hide default message
                }
            },
            errorPlacement: function (error, element) {
                if (element.attr("name") === "permission[]") {
                    error.insertAfter("#role_create_form");
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function (event, validator) {
                if (validator.errorList.some(error => error.element.name === 'permission[]')) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'At least one permission must be selected.',
                        confirmButtonText: 'OK'
                    });
                }
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
    };
    var permissionSettings = function () {

        $(document).on('change', '.permissions', function () {
            if ($(this).attr('id').startsWith('sub_permission_')) {
                var parentId = $(this).attr('id').replace('sub_permission_', '');
                var parentCheckbox = $('#permission_' + parentId + '');
                if ($(this).prop('checked')) {
                    parentCheckbox.prop('checked', true);
                }
                var allSubPermissionsUnchecked = $('.sub_permission_' + parentId + ':checked').length === 0;
                if (allSubPermissionsUnchecked) {
                    parentCheckbox.prop('checked', false);
                }
            } else {
                var subId = $(this).attr('id').replace('permission_', '');
                var subIdCheckbox = $('.sub_permission_' + subId + '');
                if (!$(this).prop('checked')) {
                    subIdCheckbox.prop('checked', false);
                } else {
                    subIdCheckbox.prop('checked', true);
                }
            }
        });
    }


    return {
        init: function () {
            initIndex();
        },
        create: function () {
            createRoleValidation();
            permissionSettingsForCrete();
        },
        edit: function () {
            editRoleValidation();
            permissionSettings();
        },
    };
}();