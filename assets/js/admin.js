jQuery(document).ready(function($){
    // Confirm before saving link attributes
    $('form#link-attributes-form').on('submit', function(e){
        var $clickedButton = $(document.activeElement); 
        if ($clickedButton.attr('name') === 'lm_save_attributes_submit') {
            if (!confirm(lmAdmin.confirmSave)) {
                e.preventDefault(); 
            }
        }
    });

    // Event handlers for Unlink and Replace URL buttons have been removed.
});