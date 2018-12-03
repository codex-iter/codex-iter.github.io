;(function($) {

	var WPFormsBuilderLite = {

		/**
		 * Start the engine.
		 *
		 * @since 1.0.0
		 */
		init: function() {

			// Document ready
			$(document).ready(function() {
				WPFormsBuilderLite.ready();
			});

			WPFormsBuilderLite.bindUIActions();
		},

		/**
		 * Document ready.
		 *
		 * @since 1.0.0
		 */
		ready: function() {
		},

		/**
		 * Element bindings.
		 *
		 * @since 1.0.0
		 */
		bindUIActions: function() {

			// WPForms upgrade panels modal
			$(document).on('click', '#wpforms-panels-toggle button', function(e) {
				if ($(this).hasClass('upgrade-modal')){
					e.preventDefault();
					e.stopImmediatePropagation();
					WPFormsBuilderLite.upgradeModal($(this).text()+ ' panel');
				}
			});

			// WPForms upgrade field modal
			$(document).on('click', '.wpforms-add-fields-button', function(e) {
				if ($(this).hasClass('upgrade-modal')){
					e.preventDefault();
					e.stopImmediatePropagation();
					WPFormsBuilderLite.upgradeModal($(this).text()+ ' field');
				}
			});

			// WPForms upgrade providers modal
			$(document).on('click', '.wpforms-panel-sidebar-section', function(e) {
				if ($(this).hasClass('upgrade-modal')){
					e.preventDefault();
					e.stopImmediatePropagation();
					WPFormsBuilderLite.upgradeModal($(this).data('name'));
				}
			});

			// WPForms upgrade notifications/confirmation modeal
			$(document).on('click', '.wpforms-builder-settings-block-add', function(e) {
				if ($(this).hasClass('upgrade-modal')){
					e.preventDefault();
					e.stopImmediatePropagation();
					WPFormsBuilderLite.upgradeModal($(this).data('name'));
				}
			});

			// Warn users if they disable email notifications.
			$( document ).on( 'change', '#wpforms-panel-field-settings-notification_enable', function() {
				WPFormsBuilderLite.formBuilderNotificationAlert( $( this ).val() );
			});
		},

		/**
		 * Trigger modal for upgrade.
		 *
		 * @since 1.0.0
		 */
		upgradeModal: function(feature) {

			var message    = wpforms_builder_lite.upgrade_message.replace(/%name%/g, feature),
				upgradeURL = encodeURI( wpforms_builder_lite.upgrade_url + '&utm_content=' + feature.trim() );
			$.alert({
				title: feature+ ' ' + wpforms_builder_lite.upgrade_title,
				icon: 'fa fa-lock',
				content: message,
				boxWidth: '550px',
				onOpenBefore: function () {
					this.$btnc.after( '<div class="discount-note">' + wpforms_builder_lite.upgrade_bonus + wpforms_builder_lite.upgrade_doc + '</div>');
					this.$body.find( '.jconfirm-content' ).addClass( 'lite-upgrade' );
				},
				buttons: {
					confirm: {
						text: wpforms_builder_lite.upgrade_button,
						btnClass: 'btn-confirm',
						keys: ['enter'],
						action: function () {
							window.open( upgradeURL,'_blank' );
							$.alert({
								title: false,
								content: wpforms_builder_lite.upgrade_modal,
								icon: 'fa fa-info-circle',
								type: 'blue',
								boxWidth: '565px',
								buttons: {
									confirm: {
										text: wpforms_builder.ok,
										btnClass: 'btn-confirm',
										keys: [ 'enter' ]
									}
								}
							});
						}
					}
				}
			});
		},

		/**
		 * Warn users if they disable email notifications.
		 *
		 * @since 1.5.0
		 */
		formBuilderNotificationAlert: function( value ) {

			if ( '0' !== value ) {
				return;
			}

			$.alert({
				title: wpforms_builder.heads_up,
				content: wpforms_builder_lite.disable_notifications,
				backgroundDismiss: false,
				closeIcon: false,
				icon: 'fa fa-exclamation-circle',
				type: 'orange',
				buttons: {
					confirm: {
						text: wpforms_builder.ok,
						btnClass: 'btn-confirm',
						keys: [ 'enter' ]
					}
				}
			});
		}
	};

	WPFormsBuilderLite.init();

})(jQuery);
