"use strict";

var email = function () {

    var validateForm = function () {
        $('#email-form').validate({
            rules: {
                first_name: {
                    required: true,
                    minlength: 2
                },
                email: {
                    required: true,
                    email: true
                }
            },
            messages: {
                first_name: {
                    required: "Please enter your first name",
                    minlength: "Your first name must be at least 2 characters long"
                },
                email: {
                    required: "Please enter your email address",
                    email: "Please enter a valid email address"
                }
            },
            errorClass: 'text-red-500 text-sm', // Tailwind CSS class for error messages
            errorElement: 'span',
            highlight: function (element, errorClass) {
                $(element).addClass('border-red-500').removeClass('border-gray-300');
            },
            unhighlight: function (element, errorClass) {
                $(element).removeClass('border-red-500').addClass('border-gray-300');
            },
            errorPlacement: function (error, element) {
                error.insertAfter(element); // Place error message after the input field
            },
            submitHandler: function (form) {
                // Trigger form submission if valid
                $(form).find('button[type="submit"]').trigger('click');
            }
        });
    };

    var bindEvents = function () {
        $('#email-form').on('submit', function (event) {
            event.preventDefault();
            const clickedButton = $(this).find('button[type="submit"]:focus');
            const buttonValue = clickedButton.val();

            const form = $(this);
            const formData = form.serialize() + '&template=' + encodeURIComponent(buttonValue);

            clickedButton.prop('disabled', true);
            clickedButton.addClass('relative');

            const spinner = $('<div class="spinner absolute inset-0 m-auto"></div>');
            clickedButton.append(spinner);

            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: formData,
                success: function (response) {
                    $('#response-container').val(`{
   template: "${response.template}",
   success: "${response.success}"
}`);
                },
                complete: function () {
                    clickedButton.prop('disabled', false);
                    clickedButton.removeClass('relative');
                    clickedButton.find('.spinner').remove();
                }
            });
        });
    };

    return {
        init: function () {
            validateForm();
            bindEvents();
        }
    };
}();
