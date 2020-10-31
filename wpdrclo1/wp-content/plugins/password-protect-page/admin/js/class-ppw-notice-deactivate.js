(function ($) {
    'use strict';
    $(function () {
        $('a[aria-label="Deactivate Password Protect WordPress Lite"]').click(function () {
            var message = 'Please restore all your previously created passwords before deactivating the plugin to avoid all protected pages and posts becoming public.\nAre you sure you want to deactivate our PPWP Lite version now?';
            if (!confirm(message)) {
                return false;
            }
        });
    });
})(jQuery);
