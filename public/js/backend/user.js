"use strict";

var operator = function () {
    var initIndex = function () {
        var table = $('#user-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            order: [
                [0, 'desc']
            ],
            "columnDefs": [{
                "targets": 7,
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
                { data: 'username', class: "p-4 pr-8 border border-t-0 border-l-0 border-gray-50 dark:border-zinc-600" },
                { data: 'joined', class: "p-4 pr-8 border border-t-0 border-l-0 border-gray-50 dark:border-zinc-600" },
                // { data: 'name' , class: "p-4 pr-8 border border-t-0 border-l-0 border-gray-50 dark:border-zinc-600" },
                { data: 'email', class: "p-4 pr-8 border border-t-0 border-l-0 border-gray-50 dark:border-zinc-600" },
                { data: 'dob', class: "p-4 pr-8 border border-t-0 border-l-0 border-gray-50 dark:border-zinc-600" },
                { data: 'gender', class: "p-4 pr-8 border border-t-0 border-l-0 border-gray-50 dark:border-zinc-600" },
                { data: 'status', class: "p-4 pr-8 border border-t-0 border-l-0 border-gray-50 dark:border-zinc-600" },
                // { data: 'password' , class: "p-4 pr-8 border border-t-0 border-l-0 border-gray-50 dark:border-zinc-600" },
                { data: 'actions', class: "p-4 pr-8 border border-t-0 border-l-0 rtl:border-l border-gray-50 dark:border-zinc-600" }
            ],
            "createdRow": function (row, data, dataIndex) {
                $('td:eq(0)', row).addClass('p-4 pr-8 border border-t-0 rtl:border-l-0 border-gray-50 dark:border-zinc-600');
                $('td:last', row).addClass('p-4 pr-8 border border-t-0 border-l-0 rtl:border-l border-gray-50 dark:border-zinc-600');
            }
        });
        table.buttons().container()
            .appendTo('#operator-table-buttons_wrapper .col-md-6:eq(0)');

        $(".dataTables_length select").addClass('form-select form-select-sm');

        $('#user-table').on('click', '.reset-password', function () {
            var email = $(this).data('email');
            var id = $(this).data('id');

            Swal.fire({
                title: 'Confirm Password Reset',
                text: `Email for reset will be sent to ${email}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, reset it!',
                cancelButtonText: 'Cancel',
                preConfirm: () => {
                    Swal.showLoading();
                    return $.ajax({
                        url: '/forgot-password',
                        method: 'POST',
                        data: {
                            email: email,
                            id: id,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        Swal.fire(
                            'Sent!',
                            'Password reset email sent successfully.',
                            'success'
                        );
                        $('#resetPasswordModal').modal('hide');
                    }).fail(function (xhr) {
                        Swal.fire(
                            'Error!',
                            'An error occurred while sending the password reset email.',
                            'error'
                        );
                    });
                }
            });
        });

    };


    var createOperatorValidation = function () {
        $.validator.addMethod("dateValidation", function (value, element) {
            var selectedDate = value;
            var selectedDateObject = new Date(selectedDate);
            var currentDate = new Date();
            return selectedDateObject > currentDate;
        }, "Expiry date must be greater than the current date.");
        $('.operator-create-form').validate({
            rules: {
                name: "required",
                username: "required",
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
                address: "required",
                password: {
                    required: true,
                },
                confirm_password: {
                    required: true,
                    equalTo: "#password",
                },
            },
            messages: {
                name: "Please enter name",
                username: "Please enter username",
                email: {
                    required: "Please enter email",
                    email: "Please enter valid email",
                },
                contact: {
                    required: "Please enter Contact",
                    number: "Please enter valid contact",
                    minlength: "Contact requires 10 digits",
                    maxlength: "Contact requires 10 digits"
                },
                address: "Please enter address",
                password: "Please enter password",
                confirm_password: {
                    required: "Please confirm password",
                    equalTo: "Password does not matched",
                },
            }
        });
    };
    var editOperatorValidation = function () {
        $.validator.addMethod("strongPassword", function (value, element) {
            return this.optional(element) || /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/.test(value);
        }, "Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.");
        $.validator.addMethod("dateValidation", function (value, element) {
            var selectedDate = value;
            var selectedDateObject = new Date(selectedDate);
            var currentDate = new Date();
            return selectedDateObject > currentDate;
        }, "Expiry date must be greater than the current date.");

        $('#operator_form_edit').validate({
            rules: {
                email: {
                    required: true,
                    email: true,
                },
                location: {
                    required: true,
                    maxlength: 191,
                },
                contact: {
                    required: true,
                    number: true,
                    minlength: 10,
                    maxlength: 10,
                },
                username: {
                    required: true,
                    maxlength: 191,
                },
                password: {
                    required: {
                        depends: function (element) {
                            return $('.ckb-password').is(':checked');
                        }
                    },
                    minlength: {
                        depends: function (element) {
                            return $('.ckb-password').is(':checked');
                        },
                        param: 8,
                    },
                    strongPassword: true
                },
                confirm_password: {
                    required: {
                        depends: function (element) {
                            return $('.ckb-password').is(':checked');
                        }
                    },
                    equalTo: {
                        depends: function (element) {
                            return $('.ckb-password').is(':checked');
                        },
                        param: "#password",
                        message: "Passwords do not match."
                    }
                }
            },
            messages: {
                email: {
                    required: "Please enter email",
                    email: "Please enter valid email",
                },
                contact: {
                    required: "Please enter Contact",
                    number: "Please enter valid contact",
                    minlength: "Contact requires 10 digits",
                    maxlength: "Contact requires 10 digits"
                },
                expiry_date: {
                    required: "Please select expiry date",
                },
                location: {
                    required: "Please enter location",
                },
                username: {
                    required: "Please enter username",
                },
                status: "Please enter status",
                role: {
                    required: "Please select role"
                },
            }
        });
    };
    var changePassword = function () {
        $(document).on('change', '.ckb-password', function () {
            $('.password-div').toggleClass('hidden', !$(this).prop('checked'));
        });
    };

    var initMultiSelect = function () {
        $('.multi-select-nas').select2({
            placeholder: '---Select NAS---',
            closeOnSelect: false,
            allowClear: true,
            tags: false,
            width: '100%',
            minimumResultsForSearch: -1
        });
        $("#select_btn_nas").on('click', function () {
            if ($(this).hasClass('select-all')) {
                if ($('.multi-select-nas').find('option').length !== 0) {
                    $('.multi-select-nas').find('option').prop('selected', true).trigger('change');
                    $(this).toggleClass("select-all unselect-all");
                    $(this).text('Unselect All');
                }
            } else {
                $('.multi-select-nas').find('option').prop('selected', false).trigger('change');
                $(this).toggleClass("select-all unselect-all");
                $(this).text('Select All');
            }
        });


        $('.multi-select-service').select2({
            placeholder: '---Select Services---',
            closeOnSelect: false,
            allowClear: true,
            tags: false,
            width: '100%',
            minimumResultsForSearch: -1
        });

        $("#select_btn_service").on('click', function () {
            if ($(this).hasClass('select-all')) {
                if ($('.multi-select-service').find('option').length !== 0) {
                    $('.multi-select-service').find('option').prop('selected', true).trigger('change');
                    $(this).toggleClass("select-all unselect-all");
                    $(this).text('Unselect All');
                }
            } else {
                $('.multi-select-service').find('option').prop('selected', false).trigger('change');
                $(this).toggleClass("select-all unselect-all");
                $(this).text('Select All');
            }
        });
    }

    return {
        init: function () {
            initIndex();
        },
        create: function () {
            createOperatorValidation();
        },
        edit: function () {
            editOperatorValidation();
            changePassword();
            initMultiSelect();
        },
    };
}();