<div class="space-y-4 mb-2">
    @if (session('success'))
        <div class="px-5 py-[9px] flex items-center bg-green-50 border border-green-100 rounded alert-dismissible" id="alert-success">
            <i class="text-lg text-green-700 align-middle bx bx-check ltr:mr-2 rtl:ml-2"></i>
            <p class="text-green-700">{{ session('success') }}</p>
            <button class="text-lg text-green-400 alert-close ltr:ml-auto rtl:mr-auto" id="closeAlertButton-success"><i class="mdi mdi-close"></i></button>
        </div>
    @endif
    @if (session('status'))
        <div class="px-5 py-[9px] flex items-center bg-green-50 border border-green-100 rounded alert-dismissible" id="alert-status">
            <i class="text-lg text-green-700 align-middle bx bx-check ltr:mr-2 rtl:ml-2"></i>
            <p class="text-green-700">{{ session('status') }}</p>
            <button class="text-lg text-green-400 alert-close ltr:ml-auto rtl:mr-auto" id="closeAlertButton-status"><i class="mdi mdi-close"></i></button>
        </div>
    @endif
    @if (session('error'))
        <div class="px-5 py-[9px] flex items-center bg-red-50 border border-red-100 rounded alert-dismissible" id="alert-error">
            <i class="text-lg text-red-700 align-middle mdi mdi-block-helper ltr:mr-2 rtl:ml-2"></i>
            <p class="text-red-700">{{ session('error') }}</p>
            <button class="text-lg text-red-400 alert-close ltr:ml-auto rtl:mr-auto" id="closeAlertButton-error"><i class="mdi mdi-close"></i></button>
        </div>
    @endif
    @if (session('info'))
        <div class="px-5 py-[9px] flex items-center bg-violet-50 border border-violet-100 rounded alert-dismissible" id="alert-info">
            <i class="text-lg align-middle mdi mdi-check-all ltr:mr-2 rtl:ml-2 text-violet-700"></i>
            <p class="text-violet-700">{{ session('info') }}</p>
            <button class="text-lg alert-close ltr:ml-auto rtl:mr-auto text-violet-400" id="closeAlertButton-info"><i class="mdi mdi-close"></i></button>
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function closeAlert(alertId) {
            var alert = document.getElementById(alertId);
            if (alert) {
                var closeButton = alert.querySelector('.alert-close');
                if (closeButton) {
                    closeButton.click();
                }
            }
        }

        setTimeout(function() {
            closeAlert('alert-success');
        }, 5000);
        
        setTimeout(function() {
            closeAlert('alert-status');
        }, 5000);

        setTimeout(function() {
            closeAlert('alert-error');
        }, 5000);

        setTimeout(function() {
            closeAlert('alert-info');
        }, 5000);
    });
</script>