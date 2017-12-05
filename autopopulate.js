jQuery(document).ready(function($) {
console.log('auto');
    // Add default 'Select one'
    $( '#acf-field_59de553990f42' ).prepend( $('<option></option>').val('0').html('Select Year').attr({ selected: 'selected', disabled: 'disabled'}) );

    /**
     * Get vehicle_make option on select menu change
     *
     */
    $( '#acf-field_59de553990f42' ).change(function () {

        var selected_vehicle_year = ''; // Selected value

        // Get selected value
        $( '#acf-field_59de553990f42 option:selected' ).each(function() {
            selected_vehicle_year += $( this ).text();
        });
        $( '#acf-field_59de54d790f40' ).attr( 'disabled', 'disabled' );

        // If default is not selected get areas for selected year
        if( selected_vehicle_year != 'Select Year' ) {
            // Send AJAX request
            data = {
                action: 'pa_add_areas',
                pa_nonce: pa_vars.pa_nonce,
                vehicle_year: selected_vehicle_year,
            };

            // Get response and populate area select field
            $.post( ajaxurl, data, function(response) {

                if( response ){
                    // Disable 'Select Area' field until year is selected
                    $( '#acf-field_59de54d790f40' ).html( $('<option></option>').val('0').html('Select Model').attr({ selected: 'selected', disabled: 'disabled'}) );

                    // Add areas to select field options
                    $.each(response, function(val, text) {
                        $( '#acf-field_59de54d790f40' ).append( $('<option></option>').val(text).html(text) );
                    });

                    // Enable 'Select Area' field
                    $( '#acf-field_59de54d790f40' ).removeAttr( 'disabled' );
                };
            });
        }

    }).change();

});
