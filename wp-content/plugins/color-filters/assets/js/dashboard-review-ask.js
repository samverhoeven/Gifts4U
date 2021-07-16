jQuery( document ).ready( function( $ ) {
	jQuery( '.ewd-uwcf-main-dashboard-review-ask' ).css( 'display', 'block' );

  jQuery(document).on( 'click', '.ewd-uwcf-main-dashboard-review-ask .notice-dismiss', function( event ) {
    var data = 'ask_review_time=7&action=ewd_uwcf_hide_review_ask';
    jQuery.post( ajaxurl, data, function() {} );
  });

	jQuery( '.ewd-uwcf-review-ask-yes' ).on( 'click', function() {
		jQuery( '.ewd-uwcf-review-ask-feedback-text' ).removeClass( 'ewd-uwcf-hidden' );
		jQuery( '.ewd-uwcf-review-ask-starting-text' ).addClass( 'ewd-uwcf-hidden' );

		jQuery( '.ewd-uwcf-review-ask-no-thanks' ).removeClass( 'ewd-uwcf-hidden' );
		jQuery( '.ewd-uwcf-review-ask-review' ).removeClass( 'ewd-uwcf-hidden' );

		jQuery( '.ewd-uwcf-review-ask-not-really' ).addClass( 'ewd-uwcf-hidden' );
		jQuery( '.ewd-uwcf-review-ask-yes' ).addClass( 'ewd-uwcf-hidden' );

		var data = 'ask_review_time=7&action=ewd_uwcf_hide_review_ask';
        jQuery.post( ajaxurl, data, function() {} );
	});

	jQuery( '.ewd-uwcf-review-ask-not-really' ).on( 'click', function() {
		jQuery( '.ewd-uwcf-review-ask-review-text' ).removeClass( 'ewd-uwcf-hidden' );
		jQuery( '.ewd-uwcf-review-ask-starting-text' ).addClass( 'ewd-uwcf-hidden' );

		jQuery( '.ewd-uwcf-review-ask-feedback-form' ).removeClass( 'ewd-uwcf-hidden' );
		jQuery( '.ewd-uwcf-review-ask-actions' ).addClass( 'ewd-uwcf-hidden' );

		var data = 'ask_review_time=1000&action=ewd_uwcf_hide_review_ask';
        jQuery.post( ajaxurl, data, function() {} );
	});

	jQuery( '.ewd-uwcf-review-ask-no-thanks' ).on( 'click', function() {
		var data = 'ask_review_time=1000&action=ewd_uwcf_hide_review_ask';
        jQuery.post( ajaxurl, data, function() {} );

        jQuery( '.ewd-uwcf-main-dashboard-review-ask' ).css( 'display', 'none' );
	});

	jQuery( '.ewd-uwcf-review-ask-review' ).on( 'click', function() {
		jQuery( '.ewd-uwcf-review-ask-feedback-text' ).addClass( 'ewd-uwcf-hidden' );
		jQuery( '.ewd-uwcf-review-ask-thank-you-text' ).removeClass( 'ewd-uwcf-hidden' );

		var data = 'ask_review_time=1000&action=ewd_uwcf_hide_review_ask';
        jQuery.post( ajaxurl, data, function() {} );
	});

	jQuery( '.ewd-uwcf-review-ask-send-feedback' ).on( 'click', function() {
		var feedback = jQuery( '.ewd-uwcf-review-ask-feedback-explanation textarea' ).val();
		var email_address = jQuery( '.ewd-uwcf-review-ask-feedback-explanation input[name="feedback_email_address"]' ).val();
		var data = 'feedback=' + feedback + '&email_address=' + email_address + '&action=ewd_uwcf_send_feedback';
        jQuery.post( ajaxurl, data, function() {} );

        var data = 'ask_review_time=1000&action=ewd_uwcf_hide_review_ask';
        jQuery.post( ajaxurl, data, function() {} );

        jQuery( '.ewd-uwcf-review-ask-feedback-form' ).addClass( 'ewd-uwcf-hidden' );
        jQuery( '.ewd-uwcf-review-ask-review-text' ).addClass( 'ewd-uwcf-hidden' );
        jQuery( '.ewd-uwcf-review-ask-thank-you-text' ).removeClass( 'ewd-uwcf-hidden' );
	});
});