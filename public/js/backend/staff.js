"use strict";
var staff = function () {
    var initIndex = function () {
        var table = $('#staff-table').DataTable({
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
                { data: 'name', class: "p-4 pr-8 border border-t-0  border-gray-50 dark:border-zinc-600" },
                { data: 'email', class: "p-4 pr-8 border border-t-0 border-l-0 border-gray-50 dark:border-zinc-600" },
                { data: 'contact', class: "p-4 pr-8 border border-t-0 border-l-0 border-gray-50 dark:border-zinc-600" },
                { data: 'status', class: "p-4 pr-8 border border-t-0 border-l-0 border-gray-50 dark:border-zinc-600" },
                { data: 'role', class: "p-4 pr-8 border border-t-0 border-l-0 border-gray-50 dark:border-zinc-600" },
                { data: 'actions', class: "p-4 pr-8 border border-t-0 border-l-0 rtl:border-l border-gray-50 dark:border-zinc-600" }
            ],
        });

        $(document).on('click', '.toggle-status', function () {
            var button = $(this);
            var userId = button.data('id');
            var newStatus = button.data('status') == '1' ? '0' : '1';

            Swal.fire({
                title: 'Are you sure?',
                text: `Do you want to ${newStatus == '1' ? 'activate' : 'block'} this user?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update it!',
                cancelButtonText: 'No, cancel!',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/staff/update-status/' + userId,
                        type: 'POST',
                        data: {
                            status: newStatus,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            button.data('status', newStatus);
                            button.text(newStatus == '1' ? 'Block' : 'Activate');
                            button.removeClass('bg-green-500 bg-red-500')
                                .addClass(newStatus == '1' ? 'bg-red-500' : 'bg-green-500');

                            Swal.fire(
                                'Updated!',
                                'The user status has been updated.',
                                'success'
                            );
                            table.ajax.reload();
                        },
                        error: function () {
                            Swal.fire(
                                'Error!',
                                'Unable to update the status. Please try again.',
                                'error'
                            );
                        }
                    });
                }
            });
        });


    };
    var createRoleValidation = function () {
        $.validator.addMethod("strongPassword", function (value, element) {
            return this.optional(element) || /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/.test(value);
        }, "Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.");

        $('#staff_create_form').validate({
            rules: {
                name: "required",
                email: {
                    required: true,
                    email: true,
                },
                contact: {
                    required: true,
                    number: true,
                    minlength: 10,
                    maxlength: 10,
                },
                role: "required",
                password: {
                    required: true,
                    strongPassword: true
                },
                confirm_password: {
                    required: true,
                    equalTo: "#password",
                },
            },
        });
    };
    var editRoleValidation = function () {
        $.validator.addMethod("strongPassword", function (value, element) {
            return this.optional(element) || /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/.test(value);
        }, "Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.");

        $('#staff_edit_form').validate({
            rules: {
                name: "required",
                email: {
                    required: true,
                    email: true,
                },
                contact: {
                    required: true,
                    number: true,
                    minlength: 10,
                    maxlength: 10,
                },
                role: "required",
                password: {
                    strongPassword: true
                },
                confirm_password: {
                    equalTo: "#password",
                },
            },
        });
    };

    return {
        init: function () {
            initIndex();
        },
        create: function () {
            createRoleValidation();
        },
        edit: function () {
            editRoleValidation();
        },
    };
}();