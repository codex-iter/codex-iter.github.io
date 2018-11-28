(function($) {
	window.fbps = {

		init: function() {
			this.events();
		},

		events: function() {
			var self = this;

			$('.fbps-list').on('change', 'input[type=checkbox]', function() {
				var checked = $(this).prop('checked');
				if(checked) {
					var checkbox = $(this).parents('li').clone();
					$('#import-form ul').prepend(checkbox);
				} else {
					$('#import-form').find('li[data-id='+$(this).val()+']').remove();
				}
			});

			$('#fbps-page-input').keypress(function(e) {
				if(e.keyCode === 13) { // pressed return
					$('#fbps-load-albums').trigger('click');
				}
			});

			$('#import-form').on('change', 'input[type=checkbox]', function() {
				var checked = $(this).prop('checked');
				if(!checked) {
					$(this).parents('li').remove();
					$('li[data-id='+$(this).val()+'] input[type=checkbox]').prop('checked', false);
				}
			});

			$('#import-form').on('click', '#import-button', function(e) {
				e.preventDefault();
				$('#import-form ul li').each(function() {
					$(this).append('<span class="spinner fbps-spinner" />');
					$(this).find('.spinner').show();
					var album_id = $(this).find('input[type=checkbox]').val();
					self.facebook_import(album_id, $(this), $('#fbps-wp-images').prop('checked'));
				});
			});

			$('.fbps-options code').zclip({
				path: '/wp-content/plugins/fb-photo-sync/js/ZeroClipboard.swf',
				copy: function() {
					return $(this).text();
				}
			});

			$('.fbps-options code').click(function() {
				$(this).next('small').remove();
				$(this).after(' <small>copied!</small>');
				var $copied = $(this).next('small');
				setTimeout(function() {
					$copied.fadeOut(500, function() {
						$(this).remove();
					});
				}, 2000);
			});

			$('#fbps-album-list').on('click', '.delete-album', function(e) {
				e.preventDefault();
				var title = $(this).parents('li').find('h3').text();
				if(confirm('Are you sure you want to permanently delete "'+title+'"?')) {
					var data = {
						action: 'fbps_delete_album',
						nonce: $('#nonce').val(),
						id: $(this).parents('li').data().id
					};
					$.post(ajaxurl, data, function(r) {
						if(r.success) {
							$('li[data-id='+r.data.id+']').fadeOut(500, function() {
								$(this).remove();
							});
						}
					});
				}
			});

			$('#fbps-album-list').on('click', '.sync-album', function(e) {
				e.preventDefault();
				var $this = $(this);
				$this.parents('.fbps-options').find('p:first-child').append('<span style="float: left" class="spinner fbps-spinner" />');
				$this.parents('li').find('.spinner').show();
				var album_id = $this.parents('li').data().id;
				self.facebook_import(album_id, $this.parents('li'), $this.parents('.fbps-options').find('.fbps-wp-photos').prop('checked'));
			});

			$(document.body).on('click', '#fbps-load-albums', function(e) {
				e.preventDefault();
				var page_id = $('#fbps-page-input').val();
				FB.api('/'+page_id+'?fields=albums{id,cover_photo,name,count}', function(r) {
					if(r.error) {
						var error = r.error.message || 'Sorry, something went wrong.';
						alert(error);
					}
					if(r.albums) {
						self.albums = [];
						self.get_albums(r.albums);
					}
				});
			});

			$(document.body).on('click', '#fbps-show-albums', function(e) {
				e.preventDefault();
				FB.api('/me/albums?fields=id,cover_photo,name,count', function(r) {
					if(r.error) {
						var error = r.error.message || 'Sorry, something went wrong.';
						alert(error);
					}
					if(r.data) {
						self.albums = [];
						self.get_albums(r);
					}
				});
			});

			$('#fbps-app-id-submit').click(function(e) {
				e.preventDefault();
				var app_id = $('#fbps-app-id').val();
				if ($.trim(app_id) === '') {
					alert('You need to enter your Facebook App ID');
					return;
				}
				var data = {
					action: 'fbps_save_app',
					nonce: $('#nonce').val(),
					id: $('#fbps-app-id').val()
				};
				$.post(ajaxurl, data, function(r) {
					if(r.success) {
						location.reload();
					} else {
						alert('Sorry, something is not correct.');
					}
				});
			});

			$('#fbps-app-id').keypress(function(e) {
				if (e.keyCode === 13) { // pressed return
					$('#fbps-app-id-submit').trigger('click');
				}
			});

			$('#fbps-app-id-remove').click(function(e) {
				e.preventDefault();
				var data = {
					action: 'fbps_remove_app',
					nonce: $('#nonce').val(),
				};
				$.post(ajaxurl, data, function(r) {
					if(r.success) {
						location.reload();
					} else {
						alert('Sorry, something is not correct.');
					}
				});
			});
		},

		get_albums: function(albums) {
			var self = this,
				album_list = '',
				count = '',
				description = '';

			self.albums = self.albums.concat(albums.data);

			if (self.test_obj(albums, 'paging.next')) {
				FB.api(albums.paging.next, function(r) {
					self.get_albums(r);
				});
			} else {
				var albums = self.albums;
				for(var i = 0; i < albums.length; i++) {
					var album = albums[i];
					if (album.count) {
						count = ' (<span class="fbps-counter"><span>0</span> of </span>'+album.count+' photos)';
					}
					if (album.description) {
						description = album.description;
					}
					album_list += '<li data-id="'+album.id+'" title="'+description+'"><label><input type="checkbox" value="'+album.id+'" /> '+album.name+count+'</li>';
				}
				$('#fbps-page-album-list').html(album_list);
			}
		},

		facebook_import: function(album_id, $parent, wp_photos) {
			var self = this;
			$parent.find('.fbps-counter').show();
			var album = {
				items: []
			};
			FB.api('/'+album_id, { fields: 'id,name,cover_photo' }, function(r) {
				album.id = r.id;
				album.name = r.name;
				album.cover_photo = r.cover_photo;
				FB.api('/'+album_id+'/photos', function(r) {
					console.log(r);
					var response = r; // has paging in object
					var data = r.data;
					self.build_album_items(response, album, data, $parent, wp_photos);
				});
			});
		},

		build_album_items: function(response, album, data, $parent, wp_photos) {
			var self = this,
				j = 0; // for async

			for(var i = 0; i < data.length; i++) {
				FB.api('/'+data[i].id+'?fields=images,name,picture,link', function(r) {
					var item = {
						id: r.id,
						photos: r.images,
						link: r.link,
						name: r.name,
						picture: r.picture,
						show: true
					};
					// /album-id/picture endpoint is busted, so need to do this.
					if (album.cover_photo.id === r.id) {
						album.picture = r.picture;
					}
					album.items.push(item);
					if(j+1 === data.length) {
						// since call is async, need to wait until last is done
						self.ajax_save(response, album, $parent, wp_photos, 0);
					}
					j++; // for async
				});
			}
		},

		items_paging: function(url, album, $parent, wp_photos) {
			var self = this;
			$.getJSON(url, function(r) {
				if(r) {
					var items = r.data,
						new_album = jQuery.extend(true, {}, album);

					new_album.items = [];
					self.build_album_items(r, new_album, items, $parent, wp_photos);
				}
			});
		},

		ajax_save: function(r, album, $parent, wp_photos, item) {
			var self = this,
				album_str = self.get_album_item(album, item), // import one photo at a time
				data = {
					action: 'fbps_save_album',
					nonce: $('#nonce').val(),
					album: album_str,
					wp_photos: wp_photos
				};

			$.post(ajaxurl, data, function(d) {
				if(d.error) {
					alert('There was an issue with this album.');
					return;
				}
				++item;
				if(typeof album.items[item] !== 'undefined') {
					var counter = parseInt($parent.find('.fbps-counter span').html(), 10);
					$parent.find('.fbps-counter span').html(++counter);
					return self.ajax_save(r, album, $parent, wp_photos, item);
				}
				if (self.test_obj(r, 'photos.paging.next')) {
					self.items_paging(r.photos.paging.next, album, $parent, wp_photos);
				} else if (self.test_obj(r, 'paging.next')) {
					self.items_paging(r.paging.next, album, $parent, wp_photos);
				} else {
					$parent.find('.spinner').remove();
					$parent.find('.fbps-counter').hide();
					$parent.find('.fbps-counter span').html('0');
				}
			});
		},

		get_album_item: function(album, item) {
			var new_album = jQuery.extend(true, {}, album);
				new_item = album.items[item];

			 new_album.items = [];
			 new_album.items.push(new_item);
			 return JSON.stringify(new_album);
		},

		test_obj: function(obj, prop) {
			var parts = prop.split('.');
			for(var i = 0, l = parts.length; i < l; i++) {
					var part = parts[i];
					if(obj !== null && typeof obj === "object" && part in obj) {
						obj = obj[part];
					}
					else {
						return false;
					}
			}
			return true;
		}

	};
	window.fbps.init();
})(jQuery);
