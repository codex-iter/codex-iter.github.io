function ffwd_frontend_ajax(form_id, current_view, id, album_id, enter_or_back, type, srch_btn, title, sortByParam, load_more, image_onclick_action) {
	var page_number = jQuery("#page_number_" + current_view).val();
	var ffwd_load_more = jQuery("#ffwd_load_more_" + current_view).val();
	var ffwd_previous_album_ids = jQuery('#ffwd_previous_album_id_' + current_view).val();
	var ffwd_previous_album_page_numbers = jQuery('#album_page_number_' + current_view).val();
	var masonry_already_loaded = jQuery(".ffwd_masonry_thumb_cont_" + current_view + " img").length;
	if (typeof load_more == "undefined") {
		var load_more = false;
	}
	var post_data = {};
	if (album_id == 'back') { // Back from album.
		var album_page_number = ffwd_previous_album_page_numbers.split(",");
		page_number = album_page_number[0];
		jQuery('#album_page_number_' + current_view).val(ffwd_previous_album_page_numbers.replace(album_page_number[0] + ',', ''));
	}
	else if (enter_or_back != '') { // Enter album (not change the page).
		jQuery('#ffwd_previous_album_id_' + current_view).val(enter_or_back + ',' + ffwd_previous_album_ids);
		if (page_number) {
			jQuery('#album_page_number_' + current_view).val(page_number + ',' + ffwd_previous_album_page_numbers);
		}
		page_number = 1;
	}
	if (typeof title == "undefined") {
		var title = "";
	}
	if (typeof sortByParam == "undefined") {
		var sortByParam = jQuery(".bwg_order_" + current_view).val();
	}
	post_data["page_number_" + current_view] = page_number;
	post_data["ffwd_load_more_" + current_view] = ffwd_load_more;
	post_data["album_id_" + current_view] = album_id;
	post_data["ffwd_previous_album_id_" + current_view] = jQuery('#ffwd_previous_album_id_' + current_view).val();
	post_data["album_page_number_" + current_view] = jQuery('#album_page_number_' + current_view).val();
	post_data["type_" + current_view] = type;
	post_data["title_" + current_view] = title;
	post_data["sortImagesByValue_" + current_view] = sortByParam;
	// Loading.
	jQuery("#ffwd_ajax_loading_" + current_view).css('display', '');
	jQuery.post(
		window.location,
		post_data,
		function (data) {
			if (load_more) {
				var strr = jQuery(data).find('#' + id).html();
				jQuery('#' + id).append(strr);
				var str = jQuery(data).find('.ffwd_nav_cont_' + current_view).html();
				jQuery('.ffwd_nav_cont_' + current_view).html(str);
			}
			else {
				var str = jQuery(data).find('#' + form_id).html();
				jQuery('#' + form_id).html(str);
			}
		}
	).success(function (jqXHR, textStatus, errorThrown) {
		jQuery("#ffwd_ajax_loading_" + current_view).css('display', 'none');
		if (jQuery(".pagination-links_" + current_view).length) {
			jQuery("html, body").animate({scrollTop: jQuery('#' + form_id).offset().top - 150}, 500);
		}
		/* For all*/
		window["ffwd_document_ready_" + current_view]();
		/* For masonry view.*/
		if (id == "ffwd_masonry_thumbnails_" + current_view || id == "ffwd_album_masonry_" + current_view) {
			window["ffwd_masonry_ajax_" + current_view](masonry_already_loaded);
		}
	});
	return false;
}
/* For thumnail amd masonry view */
function ffwd_fill_likes_thumnail(id_object_id, ffwd, graph_url) {
	var ids_array_count=Math.ceil(id_object_id.length/50);
	var ids_array=[];
	var img_ids=[];
	for(var i = 0; i < ids_array_count; i++)
	{
		ids_array[i]=[];
		k=0;
		for(var j = i*50; j < (i+1)*50; j++ )
		{
			if(!id_object_id[j])
				break;

			ids_array[i][k]=[];
			var object_id = id_object_id[i]['from']+'_'+id_object_id[j]['object_id']


			ids_array[i][k]=object_id;
			img_ids[object_id]=id_object_id[j]['id'];
			k++;
		}

	}

	for(var i = 0; i < ids_array.length; i++)
	{

		ids=ids_array[i].join(',');

		url_for_cur_id_likes_thumb = graph_url.replace('{FB_ID}',''),

			graph_url_for_likes_thumb = url_for_cur_id_likes_thumb.replace('{EDGE}', '');
		graph_url_for_likes_thumb = graph_url_for_likes_thumb.replace('{FIELDS}', 'ids='+ids+'&fields=comments.summary(true).limit(0),likes.summary(true).limit(0)&');
		graph_url_for_likes_thumb = graph_url_for_likes_thumb.replace('{OTHER}', '');

		jQuery.getJSON(graph_url_for_likes_thumb,
			function(result)
			{

				for(var j in result)
				{

					ffwd_fill_likes_comments_thumb(result[j],ffwd,img_ids[j]);


				}

			}
		);


	}


}

function createCallback_thumbnail(id, ffwd, type) {
	return function (result) {
		do_something_with_data_thumbnail(result, id, ffwd, type);
	};
}

function ffwd_fill_likes_comments_thumb(result, ffwd,id) {

	var likes_count = (typeof result['likes']['summary'] != 'undefined') ? parseInt(result['likes']['summary']['total_count']) : '0';
	jQuery('#ffwd_likes_' + ffwd + '_' + id + ' span').html(likes_count);

	var comments_count = (typeof result['comments']['summary'] != 'undefined') ? parseInt(result['comments']['summary']['total_count']) : '0';
	jQuery('#ffwd_comments_' + ffwd + '_' + id + ' span').html(comments_count);

}

function do_something_with_data_thumbnail(result, id, ffwd, type) {
	switch (type) {
		case 'likes' :
			var likes_count = (typeof result['summary'] != 'undefined') ? parseInt(result['summary']['total_count']) : '0';
			jQuery('#ffwd_likes_' + ffwd + '_' + id + ' span').html(likes_count);
			break;
		case 'comments' :
			var comments_count = (typeof result['summary'] != 'undefined') ? parseInt(result['summary']['total_count']) : '0';
			jQuery('#ffwd_comments_' + ffwd + '_' + id + ' span').html(comments_count);
			break;
		default :
			console.log('error');
			break;
	}
}

/* For album view */
function ffwd_fill_thum_srs_likes_compact_album(id_object_id, ffwd, graph_url, ffwd_album_info, image_onclick_action) {
	if (typeof id_object_id == 'object') {
		/*Album*/


		var ids_array_count=Math.ceil(id_object_id.length/50);
		var ids_array=[];
		var img_ids=[];
		for(var i = 0; i < ids_array_count; i++)
		{
			ids_array[i]=[];
			k=0;
			for(var j = i*50; j < (i+1)*50; j++ )
			{
				if(!id_object_id[j])
					break;

				ids_array[i][k]=[];
				var object_id = id_object_id[j]['object_id']


				ids_array[i][k]=object_id;
				img_ids[object_id]=id_object_id[j]['id'];
				k++;
			}

		}

		for(var i = 0; i < ids_array.length; i++)
		{

			ids=ids_array[i].join(',');

			url_for_cur_id_likes_thumb = graph_url.replace('{FB_ID}',''),

				graph_url_for_likes_thumb = url_for_cur_id_likes_thumb.replace('{EDGE}', 'photos');
			graph_url_for_likes_thumb = graph_url_for_likes_thumb.replace('{FIELDS}', 'ids='+ids+'&fields=source,width,height,count,link&');
			graph_url_for_likes_thumb = graph_url_for_likes_thumb.replace('{OTHER}', '');
			jQuery.getJSON(graph_url_for_likes_thumb,
				function(result)
				{

					for(var j in result)
					{

						ffwd_fill_likes_comments_c_album(result[j],ffwd,img_ids[j],ffwd_album_info, image_onclick_action);


					}

				}
			);


		}




	}
	else {
		/*Gallery*/
		var album_id = id_object_id,
			graph_url_for_album_photos = graph_url.replace('{EDGE}', 'photos'),
			url_for_album_photos = graph_url_for_album_photos.replace('{FB_ID}', album_id),
			url_for_album_photos = url_for_album_photos.replace('{FIELDS}', 'fields=images,link,from{picture,name,link}&');


		jQuery.getJSON(url_for_album_photos, createCallback_album('', ffwd, 'photos', graph_url, ffwd_album_info, image_onclick_action));

	}
}



function ffwd_fill_likes_comments_c_album(result,ffwd,id,ffwd_album_info, image_onclick_action)
{
	var height = (typeof result['data'][0] != 'undefined') ? result['data'][0]['height'] : 0,
		width = (typeof result['data'][0] != 'undefined') ? result['data'][0]['width'] : 0,
		image_thumb_width,
		image_thumb_height,
		resolution_w,
		resolution_h,
		count = (typeof result['data'] != 'undefined') ? result['data'].length : 0,
		scale;
	if (count) {
		if (width && height) {
			resolution_w = width;
			resolution_h = height;
			if (resolution_w != 0 && resolution_h != 0) {
				scale = Math.max(ffwd_album_info["album_thumb_width"] / resolution_w, ffwd_album_info["album_thumb_height"] / resolution_h);
				image_thumb_width = resolution_w * scale;
				image_thumb_height = resolution_h * scale;
			}
			else {
				image_thumb_width = ffwd_album_info["album_thumb_width"];
				image_thumb_height = ffwd_album_info["album_thumb_height"];
			}
		}
		else {
			image_thumb_width = ffwd_album_info["album_thumb_width"];
			image_thumb_height = ffwd_album_info["album_thumb_height"];
		}
		scale = Math.max(ffwd_album_info["album_thumb_width"] / image_thumb_width, image_thumb_height / image_thumb_height);
		image_thumb_width *= scale;
		image_thumb_height *= scale;
		thumb_left = (ffwd_album_info["album_thumb_width"] - image_thumb_width) / 2;
		thumb_top = (ffwd_album_info["album_thumb_height"] - image_thumb_height) / 2;

		jQuery('#ffwd_album_cover_' + id + '_' + ffwd).attr('src', result['data'][0]['source']).css({
			'width': image_thumb_width + 'px',
			'height': image_thumb_height + 'px',
			'margin-left': thumb_left + 'px',
			'margin-top': thumb_top + 'px',
		});
	}
	else {
		/*jQuery( "[ffwd_object_id='"+object_id+"']" ).remove();*/
	}
}


function createCallback_album(id, ffwd, type, graph_url, ffwd_album_info, image_onclick_action) {
	return function (result) {
		do_something_with_data_album(result, id, ffwd, type, graph_url, ffwd_album_info, image_onclick_action);
	};
}

function ffwd_fill_likes_comments_c_gallery(result,ffwd,id) {
	var likes_count = (typeof result['likes']['summary'] != 'undefined') ? parseInt(result['likes']['summary']['total_count']) : '0';
	jQuery('#ffwd_likes_' + id + '_' + ffwd + ' span').html(likes_count);


	var comments_count = (typeof result['comments']['summary'] != 'undefined') ? parseInt(result['comments']['summary']['total_count']) : '0';
	jQuery('#ffwd_comments_' + id + '_' + ffwd + ' span').html(comments_count);
}


function do_something_with_data_album(result, id, ffwd, type, graph_url, ffwd_album_info, image_onclick_action) {

	switch (type) {
		case 'likes' :
		case 'comments' :
			var likes_count = (typeof result['likes']['summary'] != 'undefined') ? parseInt(result['likes']['summary']['total_count']) : '0';
			jQuery('#ffwd_likes_' + id + '_' + ffwd + ' span').html(likes_count);


			var comments_count = (typeof result['comments']['summary'] != 'undefined') ? parseInt(result['comments']['summary']['total_count']) : '0';
			jQuery('#ffwd_comments_' + id + '_' + ffwd + ' span').html(comments_count);
			break;
		case 'covers' :
			var height = (typeof result['data'][0] != 'undefined') ? result['data'][0]['height'] : 0,
				width = (typeof result['data'][0] != 'undefined') ? result['data'][0]['width'] : 0,
				image_thumb_width,
				image_thumb_height,
				resolution_w,
				resolution_h,
				count = (typeof result['data'] != 'undefined') ? result['data'].length : 0,
				scale;
			if (count) {
				if (width && height) {
					resolution_w = width;
					resolution_h = height;
					if (resolution_w != 0 && resolution_h != 0) {
						scale = Math.max(ffwd_album_info["album_thumb_width"] / resolution_w, ffwd_album_info["album_thumb_height"] / resolution_h);
						image_thumb_width = resolution_w * scale;
						image_thumb_height = resolution_h * scale;
					}
					else {
						image_thumb_width = ffwd_album_info["album_thumb_width"];
						image_thumb_height = ffwd_album_info["album_thumb_height"];
					}
				}
				else {
					image_thumb_width = ffwd_album_info["album_thumb_width"];
					image_thumb_height = ffwd_album_info["album_thumb_height"];
				}
				scale = Math.max(ffwd_album_info["album_thumb_width"] / image_thumb_width, image_thumb_height / image_thumb_height);
				image_thumb_width *= scale;
				image_thumb_height *= scale;
				thumb_left = (ffwd_album_info["album_thumb_width"] - image_thumb_width) / 2;
				thumb_top = (ffwd_album_info["album_thumb_height"] - image_thumb_height) / 2;

				jQuery('#ffwd_album_cover_' + id + '_' + ffwd).attr('src', result['data'][0]['source']).css({
					'width': image_thumb_width + 'px',
					'height': image_thumb_height + 'px',
					'margin-left': thumb_left + 'px',
					'margin-top': thumb_top + 'px',
				});
			}
			else {
				/*jQuery( "[ffwd_object_id='"+object_id+"']" ).remove();*/
			}
			break;
		case 'photos':
			var graph_url_for_likes = graph_url.replace('{EDGE}', ''),
				graph_url_for_likes = graph_url_for_likes.replace('{FIELDS}', ''),
				graph_url_for_comments = graph_url.replace('{EDGE}', ''),
				graph_url_for_comments = graph_url_for_comments.replace('{FIELDS}', '&fields=likes.summary(true).limit(0),comments.summary(true).limit(0)&');

			var data = result['data'];

			content = '';
			ffwd_album_info["data"] = [];
			curent_view = ffwd;


			var ids_array_count=Math.ceil(data.length/50);
			var ids_array=[];
			var img_ids=[];
			for(var j = 0; j < ids_array_count; j++)
			{
				ids_array[j]=[];
				k=0;
				for(var i = j*50; i < (j+1)*50; i++ )
				{
					if(!data[i])
						break;

					ids_array[j][k]=[];
					var object_id = data[i]['from']['id']+'_'+data[i]['id']


					ids_array[j][k]=object_id;
					img_ids[object_id]=i;
					k++;
					//////////////////////////////////////////////////////
					var row = {},
						images = data[i]['images'],
						image_obj_id = data[i]['id'];
					index_in_images = (images.length > 3) ? 2 : 0,
						image = data[i]['images'][index_in_images];
					row['id'] = i;
					row['object_id'] = image_obj_id;
					row['from'] = data[i]['from']['id'];
					ffwd_album_info["data"].push(row);

					var height = image['height'],
						width = image['width'],
						image_thumb_width,
						image_thumb_height,
						resolution_w,
						resolution_h,
						scale;

					if (typeof width != 'undefined' && typeof height != 'undefined') {
						resolution_w = width;
						resolution_h = height;
						if (resolution_w != 0 && resolution_h != 0) {
							scale = Math.max(ffwd_album_info["thumb_width"] / resolution_w, ffwd_album_info["thumb_height"] / resolution_h);
							image_thumb_width = resolution_w * scale;
							image_thumb_height = resolution_h * scale;
						}
						else {
							image_thumb_width = ffwd_album_info["thumb_width"];
							image_thumb_height = ffwd_album_info["thumb_height"];
						}
					}
					else {
						image_thumb_width = ffwd_album_info["thumb_width"];
						image_thumb_height = ffwd_album_info["thumb_height"];
					}

					scale = Math.max(ffwd_album_info["thumb_width"] / image_thumb_width, image_thumb_height / image_thumb_height);
					image_thumb_width *= scale;
					image_thumb_height *= scale;
					thumb_left = (ffwd_album_info["thumb_width"] - image_thumb_width) / 2;
					thumb_top = (ffwd_album_info["thumb_height"] - image_thumb_height) / 2;

					main_url = image.source;

					if (image_onclick_action == 'facebook') {
						main_url = data[i]['link'];
					}

					if (image_onclick_action == 'none') {
						main_url = '#';
					}


					content += '<a  class="ffwd_lightbox_' + curent_view + '" href="' + main_url + '" data-image-id="' + i + '" data-image-obj-id="' + image_obj_id + '" >' +
						'<div class="ffwd_standart_thumb_' + curent_view + '">' +
						'<div class="ffwd_standart_thumb_spun1_' + curent_view + '">' +
						'<div class="ffwd_standart_thumb_spun2_' + curent_view + '">' +
						'<img class="ffwd_standart_thumb_img_' + curent_view + '" style="width:' + image_thumb_width + 'px; height:' + image_thumb_height + 'px; margin-left: ' + thumb_left + 'px; margin-top: ' + thumb_top + 'px;" id="" src="' + image.source + '" alt="" />' +
						'<div class="ffwd_likes_comments_container_' + curent_view + '" >' +
						'<div class="ffwd_likes_comments_container_tab_' + curent_view + '" >' +
						'<div class="ffwd_likes_comments_' + curent_view + '" >' +
						'<div id="ffwd_likes_' + i + '_' + curent_view + '" class="ffwd_likes_' + curent_view + '">' +
						'<span></span>' +
						'</div>' +
						'<div id="ffwd_comments_' + i + '_' + curent_view + '" class="ffwd_comments_' + curent_view + '">' +
						'<span></span>' +
						'</div>' +
						'<div style="clear:both"></div>' +
						'</div>' +
						'</div>' +
						'</div>' +
						'</div>' +
						'</div>' +
						'</div>' +
						'</a>';

					var url_for_likes = graph_url_for_likes.replace('{FB_ID}', data[i]['from']['id']+'_'+data[i]['id']),
						url_for_comments = graph_url_for_comments.replace('{FB_ID}', data[i]['from']['id']+'_'+data[i]['id']);

					//jQuery.getJSON(url_for_likes, createCallback_album(i, ffwd, 'likes'));


					// jQuery.getJSON(url_for_comments, createCallback_album(i, ffwd, 'comments'));

				}

			}

			//console.log(img_ids);

			for(var i = 0; i < ids_array.length; i++)
			{

				ids=ids_array[i].join(',');



				var  graph_url_for_comments = graph_url.replace('{EDGE}', '');
				graph_url_for_comments=graph_url_for_comments.replace('{FB_ID}','')
				graph_url_for_comments = graph_url_for_comments.replace('{FIELDS}', '&ids='+ids+'&fields=likes.summary(true).limit(0),comments.summary(true).limit(0)&');



				jQuery.getJSON(graph_url_for_comments,
					function(result)
					{

						for(var j in result)
						{

							ffwd_fill_likes_comments_c_gallery(result[j],ffwd,img_ids[j]);
							//  console.log(result[j]);

						}

					}
				);


			}


			jQuery('#ffwd_gallery_' + curent_view).html(content);
			break;
		default :
			console.log('error');
			break;
	}
}
/* For Blog-style view */
function ffwd_get_passed_time(time) {
	var today = new Date(),
		arr = time.split(/[^0-9]/);
	today = Date.parse(today) / 1000 - client_server_date_difference;
	time = Date.UTC(arr[0], arr[1] - 1, arr[2], arr[3], arr[4], arr[5]);
	time /= 1000;
	time = today - time;

	var tokens = {
		'year' : '31536000',
		'month': '2592000',
		'week': '604800',
		'day': '86400',
		'hour': '3600',
		'minute': '60',
		'second': '1'
	};
	var tokens_s = {
		'year' : 'years',
		'month': 'months',
		'week': 'weeks',
		'day': 'days',
		'hour': 'hours',
		'minute': 'minutes',
		'second': 'seconds'
	};
	for (unit in tokens) {
		if (time < parseInt(tokens[unit])) continue;
		var numberOfUnits = Math.floor(time / parseInt(tokens[unit]));

		if(numberOfUnits > 1)
			return numberOfUnits + ' ' + ffwd_frontend_text[tokens_s[unit]] +  ' ago';

		return numberOfUnits + ' ' + ffwd_frontend_text[unit] + ' ago' ;
	}
}

function ffwd_fill_likes_blog_style(id_object_id, ffwd, owner_info, ffwd_params, graph_url) {



	var ids_array_count=Math.ceil(id_object_id.length/50);
	var ids_array=[];
	var img_ids=[];
	for(var i = 0; i < ids_array_count; i++)
	{
		ids_array[i]=[];
		k=0;
		for(var j = i*50; j < (i+1)*50; j++ )
		{
			if(!id_object_id[j])
				break;

			ids_array[i][k]=[];
			var object_id = id_object_id[j]['object_id'].replace(id_object_id[j]['from'], owner_info['id'])


			ids_array[i][k]=object_id;
			img_ids[object_id]=id_object_id[j]['id'];
			k++;
		}

	}

	var reactions_array={};

/*	for(var i = 0; i < ids_array.length; i++)
	{

		ids=ids_array[i].join(',');

		url_for_cur_id_likes = graph_url.replace('{FB_ID}',''),
			graph_url_for_likes = url_for_cur_id_likes.replace('{EDGE}', '');
		graph_url_for_likes = graph_url_for_likes.replace('{FIELDS}', 'ids='+ids+'&fields=reactions.type(LIKE).limit(0).summary(true).as(like),reactions.type(LOVE).limit(0).summary(true).as(love),reactions.type(WOW).limit(0).summary(true).as(wow),reactions.type(HAHA).limit(0).summary(true).as(haha),reactions.type(SAD).limit(0).summary(true).as(sad),reactions.type(ANGRY).limit(0).summary(true).as(angry),reactions.limit(5).summary(true).as(total_reactions)&');
		graph_url_for_likes = graph_url_for_likes.replace('{OTHER}', 'summary=true');
		jQuery.getJSON(graph_url_for_likes,
			function(result)
			{

				for(var j in result)
				{

					ffwd_fill_likes(result[j],ffwd,img_ids[j]);

				}

			}
		);
	}*/


	for (var i = 0; i < id_object_id.length; i++) {


		/*For likes*/

		var object_id = id_object_id[i]['object_id'].replace(id_object_id[i]['from'], owner_info['id']);

		url_for_cur_id = graph_url.replace('{FB_ID}', object_id);
		graph_url_for_likes = url_for_cur_id.replace('{EDGE}', '');
		graph_url_for_likes = graph_url_for_likes.replace('{FIELDS}', 'fields=reactions.type(LIKE).limit(0).summary(true).as(like),reactions.type(LOVE).limit(0).summary(true).as(love),reactions.type(WOW).limit(0).summary(true).as(wow),reactions.type(HAHA).limit(0).summary(true).as(haha),reactions.type(SAD).limit(0).summary(true).as(sad),reactions.type(ANGRY).limit(0).summary(true).as(angry),reactions.limit(5).summary(true).as(total_reactions)&');
		graph_url_for_likes = graph_url_for_likes.replace('{OTHER}', 'summary=true');

		/*For comments*/




		var graph_url_for_comments = url_for_cur_id.replace('{EDGE}', 'comments');
		graph_url_for_comments = graph_url_for_comments.replace('{FIELDS}', 'fields=created_time,from,like_count,message,comment_count&');
		graph_url_for_comments = graph_url_for_comments.replace('{OTHER}', 'summary=true&filter=' + ffwd_params["comments_filter"] + '&order=' + ffwd_params["comments_order"] + '&limit=25');

		/*For future (attachment message_tags fields)*/
		/*console.log(graph_url_for_comments);*/
		/*For shares*/
		var graph_url_for_shares = url_for_cur_id.replace('{EDGE}', '');
		graph_url_for_shares = graph_url_for_shares.replace('{FIELDS}', 'fields=shares&');
		graph_url_for_shares = graph_url_for_shares.replace('{OTHER}', '');

		/*For attachments*/
		var graph_url_for_attachments = url_for_cur_id.replace('{EDGE}', 'attachments'),
			graph_url_for_attachments = graph_url_for_attachments.replace('{FIELDS}', '');
		graph_url_for_attachments = graph_url_for_attachments.replace('{OTHER}', '');

		/*For who post*/

		var url_for_who_post = graph_url.replace('{FB_ID}', id_object_id[i]['from']),

			graph_url_for_who_post = url_for_who_post.replace('{EDGE}', ''),
			graph_url_for_who_post = graph_url_for_who_post.replace('{FIELDS}', 'fields=picture,name,link&');
		graph_url_for_who_post = graph_url_for_who_post.replace('{OTHER}', '');

		if (id_object_id[i]['type'] != 'events') {
			//jQuery.getJSON(graph_url_for_likes, createCallback_blog_style(id_object_id[i]['id'], ffwd, 'likes'));
			//jQuery.getJSON(graph_url_for_shares, createCallback_blog_style(id_object_id[i]['id'], ffwd, 'shares'));


			//do_something_with_data_blog_style(reactions_array[object_id], id_object_id[i]['id'], ffwd, 'likes', "", ffwd_params, graph_url);

			if(!id_object_id[i]['shares'])
			{
				id_object_id[i]['shares']='{}';
			}

			do_something_with_data_blog_style(id_object_id[i]['shares'], id_object_id[i]['id'], ffwd, 'shares', "", ffwd_params, graph_url);

			// jQuery.getJSON(graph_url_for_attachments, createCallback_blog_style(id_object_id[i]['id'], ffwd, 'attachments', "", ffwd_params));


      if(id_object_id[i]['attachments'])
				do_something_with_data_blog_style(id_object_id[i]['attachments'], id_object_id[i]['id'], ffwd, 'attachments', "", ffwd_params, graph_url);

		}

		if(id_object_id[i]['comments']);
		do_something_with_data_blog_style(id_object_id[i]['comments'], id_object_id[i]['id'], ffwd, 'comments', "", ffwd_params, graph_url);


    // jQuery.getJSON(id_object_id[i]['comments'], createCallback_blog_style(id_object_id[i]['id'], ffwd, 'comments', "", ffwd_params, graph_url));
		//jQuery.getJSON(graph_url_for_who_post, createCallback_blog_style(id_object_id[i], ffwd, 'who_post', owner_info, ffwd_params));
		do_something_with_data_blog_style(id_object_id[i]['who_post'], id_object_id[i], ffwd, 'who_post', owner_info, ffwd_params, graph_url);



	}
}

function createCallback_blog_style(id, ffwd, type, owner_info, ffwd_params, graph_url) {
	return function (result) {
		do_something_with_data_blog_style(result, id, ffwd, type, owner_info, ffwd_params, graph_url);
	};
}

function do_something_with_data_blog_style(result, id, ffwd, type, owner_info, ffwd_params, graph_url) {

	switch (type) {

		case 'comments' :

			var total_count = (result['data'].length < 25) ? result['data'].length : result['summary']['total_count'];
			jQuery('#ffwd_comments_count_' + ffwd + '_' + id).html(total_count);
			/*console.log(result);
			 console.log(result['data'].length);*/
			var more_comments = false,
				comments_exist = false;
			for (var i = 0, j = 1, z = 0; i < result['data'].length; i++, j++) {
				comments_exist = true;
				comment_id = result['data'][i]['id'];
				var display = 'display:block';
				if (j > 4) {
					display = 'display:none';
					more_comments = true;
					z++;
				}

/*
				var url_for_cur_id_comm_user_pic = graph_url.replace('{FB_ID}', result['data'][i]['from']['id']);
				url_for_cur_id_comm_user_pic = url_for_cur_id_comm_user_pic.replace('{EDGE}', '');
				url_for_cur_id_comm_user_pic = url_for_cur_id_comm_user_pic.replace('{FIELDS}', 'fields=picture&');
				url_for_cur_id_comm_user_pic = url_for_cur_id_comm_user_pic.replace('{OTHER}', '');
*/



				//var comment_author_pic = '<div style="float:left" id="ffwd_comment_author_pic_' + ffwd + '_' + id + '" class="ffwd_comment_author_pic_' + ffwd + '" > <img class="user_' + result['data'][i]['from']['id'] + '" src="https://graph.facebook.com/'+result['data'][i]['from']['id']+'/picture" > </div>',
				var comment_author_pic = '<span></span>',
					comment_author_name = '<span></span>',
					//comment_author_name = '<a id="ffwd_comment_author_name_' + ffwd + '_' + id + '" href="https://www.facebook.com/' + result['data'][i]['from']['id'] + '" class="ffwd_comment_author_name_' + ffwd + '" > ' + result['data'][i]['from']['name'] + ' </a>',
					comment_message = '<span id="ffwd_comment_message_' + ffwd + '_' + id + '" class="ffwd_comment_message_' + ffwd + '" > ' + result['data'][i]['message'] + ' </span>',
					comment_date = '<span id="ffwd_comment_date_' + ffwd + '_' + id + '" class="ffwd_comment_date_' + ffwd + '" > ' + ffwd_get_passed_time(result['data'][i]['created_time']) + '</span>',
					comment_likes_count = '<span id="ffwd_comment_likes_count_' + ffwd + '_' + id + '" class="ffwd_comment_likes_count_' + ffwd + '" > ' + result['data'][i]['like_count'] + ' </span>',
					comments_date_likes = '<div>' + comment_date + comment_likes_count + '</div>',
					comment_replies_cont = (ffwd_params["comments_filter"] == "toplevel" && ffwd_params["comments_replies"] == "1" && result['data'][i]['comment_count'] > 0) ? '<div class="ffwd_comment_replies_' + ffwd + '"><div id="ffwd_comment_replies_label_'+comment_id+'" class="ffwd_comment_replies_label_' + ffwd + '">' + result['data'][i]['comment_count'] +' '+ffwd_frontend_text.comment_reply+' </div><div class="ffwd_comment_replies_content_' + ffwd + '"></div></div>' : '',
					comment_div_cont = '<div class="ffwd_comment_content_' + ffwd + '" id="ffwd_comment_content_' + ffwd + '_' + id + '" >' + comment_author_name + comment_message + comments_date_likes + comment_replies_cont + '<div style="clear:both"></div></div>',
					comment = '<div id="ffwd_comment_' + ffwd + '_' + comment_id + '" class="ffwd_comment_' + ffwd + '" style="' + display + '">' + comment_author_pic + comment_div_cont + '<div style="clear:both" > </div></div>';

				jQuery('#ffwd_comments_content_' + id + '_' + ffwd + '').append(comment);
				(function (i) {
					jQuery('#ffwd_comment_replies_label_' + result['data'][i]['id']).bind("click", function () {

						ffwd_get_comments_replies(result['data'][i]['id'], graph_url, ffwd_params, ffwd);
					})
				}(i))


			}
			if (more_comments) {
				jQuery('#ffwd_comments_content_' + id + '_' + ffwd).append('<div class="ffwd_view_more_comments_cont_' + ffwd + '"> <a href="#" class="ffwd_view_more_comments" more_count="' + z + '"> <span>'+ffwd_frontend_text.view+' '+ z + ' '+ffwd_frontend_text.more_comments+'</span> <a> </div>');
			}
			if (!comments_exist) {
				jQuery('#ffwd_comments_content_' + id + '_' + ffwd).remove();
			}
			ffwd_blog_style_resize(ffwd_params, ffwd);
			break;
		case 'shares' :

			var shares_count = (result!= null && result['count'] != null) ? parseInt(result['count']) : '0';
			jQuery('#ffwd_shares_' + ffwd + '_' + id).html(shares_count);
			break;
		case 'attachments' :
			var src = '', length = 0, album_id = '';
			/*
			 erb story mej nshaca vor addes photos aranc albumi anun talu hetevabar @ngela timline -i mej
			 u avtomat et posti subattachmentsneri arkayutayn depqum kberi dranq, ISK ete nshvaca added photos to album esinch
			 hetevabar petqa albumi id-in vercnel araji media-i targeti urlic u pageID + albumid posti subattamentner@ cuyc tal!!!!!
			 */

			if (result) {
				/*If exists subattachments*/
				if (result['subattachments']) {
					length = result['subattachments']['data'].length;
					if (typeof result['subattachments']['data'][0]['media'] != "undefined") {
						src = result['subattachments']['data'][0]['media']['image']['src'];
					}
					/*First time add profile picture*/
					if (result['type'] == 'gallery') {
						src = result['subattachments']['data'][length - 1]['media']['image']['src'];
					}
				}
				else if (result['media']) {
					/* Check album containing this photo (compare title)
					 * If not Timeline photos or Profile Pictures so get photos from that album
					 */
					/*if (result['data'][0]['title'] != 'Timeline Photos' && result['data'][0]['title'] != 'Profile Pictures') {
					 album_id = result['data'][0]['url'].split("photos/");
					 if (typeof album_id[1] != 'undefined') {
					 album_id = album_id[1].split(".");
					 album_id = album_id[1];

					 }
					 }*/
					src = result['media']['image']['src'];
				}


				jQuery('#ffwd_blog_style_img_' + id + '_' + ffwd).attr('src', src);
				if (src == '')
					jQuery('#ffwd_blog_style_img_' + id + '_' + ffwd).remove();

			}
			ffwd_blog_style_resize(ffwd_params, ffwd);
			break;
		case 'who_post' :

			var who_post = result;

			var who_post_name_link = (ffwd_params['blog_style_author'] == "1") ? '<a class="ffwd_blog_style_object_from_name_' + ffwd + '" href="https://www.facebook.com/' + who_post['id'] + '" target="_blank">' + who_post['name'] + '</a>' : '',
				owner_name_link = '<a class="ffwd_blog_style_object_from_name_' + ffwd + '" href="https://www.facebook.com/' + owner_info['id'] + '" target="_blank">' + owner_info['name'] + '</a>',
				who_post_pic = '<img id="ffwd_user_pic_' + ffwd + '_' + id['id'] + '" class="ffwd_user_pic" src="' + who_post['picture']['data']['url'] + '" style="width:50px;height:50px;box-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);margin-top: 9px;">',
				place,
				full_place = '',
				story = id['story'].replace(/'/g, "&#039;"),
				who_post_index = story.indexOf(who_post['name']),
				owner_index = story.indexOf(owner_info['name']),
				place_name = '',
				place_id = '',
				story_tags = id['story_tags'],
				place_index = -1;

			if (id['place'] != null) {
				 /* place_id = id['place']['id'];
                if(id['place']['location']){
                var street = (ffwd_params['event_street'] == "1") ? ((typeof id['place']['location']['street'] != 'undefined') ? id['place']['location']['street'] : '') : '',
                    city = (ffwd_params['event_city'] == "1") ? ((typeof id['place']['location']['city'] != 'undefined') ? id['place']['location']['city'] : '') : '',
                    country = (ffwd_params['event_country'] == "1") ? ((typeof id['place']['location']['country'] != 'undefined') ? id['place']['location']['country'] : '') : '',
                    state = (ffwd_params['event_zip'] == "1") ? ((typeof id['place']['location']['state'] != 'undefined') ? id['place']['location']['state'] : '') : '',
                    zip = (ffwd_params['event_zip'] == "1") ? ((typeof id['place']['location']['zip'] != 'undefined') ? id['place']['location']['zip'] : '') : '',
                    latitude = (ffwd_params['event_map'] == "1") ? ((typeof id['place']['location']['latitude'] != 'undefined') ? id['place']['location']['latitude'] : '') : '',
                    longitude = (ffwd_params['event_map'] == "1") ? ((typeof id['place']['location']['longitude'] != 'undefined') ? id['place']['location']['longitude'] : '') : '';

                full_place = ((ffwd_params['event_street'] == "1") ? '<div class="ffwd_place_street_' + ffwd + '" >' + street + '</div> ' : '') +
                    ((ffwd_params['event_city'] == "1" || ffwd_params['event_zip'] == "1" || ffwd_params['event_country'] == "1" ) ? '<div class="ffwd_place_city_state_country_' + ffwd + '" >' + city + ' ' + state + ' ' + zip + ' ' + country + '</div>' : '') +
                    ((ffwd_params['event_map'] == "1") ? '<a class="ffwd_place_map_' + ffwd + '" style="text-decoration:underline" href="https://maps.google.com/maps?q=' + latitude + ',' + longitude + '" target="_blank">Map</a>' : '');
                }*/
                if(id['place']['name'] && ffwd_params['event_street'] == "1"){
					full_place =  '<div class="ffwd_place_city_state_country_' + ffwd + '" >' + id['place']['name'] + '</div>';
                }
			}
			// Who post
			if (who_post_index != -1) {
				story = story.replace(who_post['name'], who_post_name_link);
			}
			if (owner_index != -1) {
				story = story.replace(owner_info['name'], owner_name_link);
			}
			if (who_post_index == -1 && owner_index == -1) {
				story = who_post_name_link;
			}

			// With whom after was
			if (story_tags != null) {
				var type = story_tags.constructor.name;
				if (type == "Object") {
					for (var x in story_tags) {
						var story_tag_name = story_tags[x]["0"]["name"],
							story_tag_id = story_tags[x]["0"]["id"];
						with_name_index = story.indexOf(story_tag_name);
						if ((with_name_index != -1) && (story_tag_name != who_post['name']) && (story_tag_name != owner_info['name']) && (story_tag_id != place_id)) {
							story_tag_link = (/*ffwd_params['blog_style_with_whom'] == "1"*/true) ? '<a class="ffwd_blog_style_object_from_name_' + ffwd + '" href="https://www.facebook.com/' + story_tag_id + '" target="_blank">' + story_tag_name + '</a>' : '',
								story = story.replace(story_tag_name, story_tag_link);
						}
						else if (story_tag_id == place_id) {
							// Where after was
							place_index = 1;
							place = (ffwd_params['blog_style_place_name'] == "1") ? '<a class="ffwd_place_name_' + ffwd + '" href="https://www.facebook.com/' + story_tag_id + '" target="_blank">' + story_tag_name + '</a>' : '';
							story = story.replace("\u2014", "");
							story = story.replace(story_tag_name, place);
						}
					}
				}
				else if (type == "Array") {
					for (var j = 0; j < story_tags.length; j++) {
						if (typeof story_tags[j]["0"] != "undefined") {
							var story_tag_name = story_tags[j]["0"]["name"],
								story_tag_id = story_tags[j]["0"]["id"];
						} else {
							var story_tag_name = story_tags[j].name,
								story_tag_id = story_tags[j].id;
						}
						with_name_index = story.indexOf(story_tag_name);
						if ((with_name_index != -1) && (story_tag_name != who_post['name']) && (story_tag_name != owner_info['name']) && (story_tag_id != place_id)) {
							story_tag_link = (/*ffwd_params['blog_style_with_whom'] == "1"*/true) ? '<a class="ffwd_blog_style_object_from_name_' + ffwd + '" href="https://www.facebook.com/' + story_tag_id + '" target="_blank">' + story_tag_name + '</a>' : '',
								story = story.replace(story_tag_name, story_tag_link);
						}

						else if (story_tag_id == place_id) {
							// Where after was
							place_index = 1;
							place = (ffwd_params['blog_style_place_name'] == "1") ? '<a class="ffwd_place_name_' + ffwd + '" href="https://www.facebook.com/' + story_tag_id + '" target="_blank">' + story_tag_name + '</a>' : '';
							story = story.replace("\u2014", "");
							story = story.replace(story_tag_name, place);
						}
					}
				}
			}
			// Where after was
			if (ffwd_params['blog_style_place_name'] == "1"/* && typeof place != 'undefined' && place != ''*/) {
				if (id['type'] == 'events') {
					story += full_place;
				}
			}
			else {
				story = story.replace(/ at| in|/gi, "");
			}
			jQuery('#ffwd_blog_style_object_from_pic_' + ffwd + '_' + id['id']).html(who_post_pic).attr("href", who_post['link']);
			jQuery('#ffwd_blog_style_object_story_' + ffwd + '_' + id['id']).html(story);
			break;
		default :
			break;
	}
}


function ffwd_get_comments_replies(comment_id,graph_url,ffwd_params,ffwd) {

	var url_for_cur_id_comm_replies = graph_url.replace('{FB_ID}', comment_id);
	url_for_cur_id_comm_replies = url_for_cur_id_comm_replies.replace('{EDGE}', 'comments');
	url_for_cur_id_comm_replies = url_for_cur_id_comm_replies.replace('{FIELDS}', 'fields=parent,created_time,from,like_count,message&');
	url_for_cur_id_comm_replies = url_for_cur_id_comm_replies.replace('{OTHER}', '');
	jQuery.getJSON(url_for_cur_id_comm_replies, function (result) {
		for (var k = 0; k < result['data'].length; k++) {
			var parent_comm_id = result['data'][k]["parent"]["id"],
				comment_reply_id = result['data'][k]["id"];

			var url_for_cur_id_comm_rep_user_pic = graph_url.replace('{FB_ID}', result['data'][k]['from']['id']);
			url_for_cur_id_comm_rep_user_pic = url_for_cur_id_comm_rep_user_pic.replace('{EDGE}', '');
			url_for_cur_id_comm_rep_user_pic = url_for_cur_id_comm_rep_user_pic.replace('{FIELDS}', 'fields=picture&');
			url_for_cur_id_comm_rep_user_pic = url_for_cur_id_comm_rep_user_pic.replace('{OTHER}', '');

			var comment_reply_author_pic = '<div style="float:left" class="ffwd_comment_reply_author_pic_' + ffwd + '" > <img class="reply_user_' + result['data'][k]['from']['id'] + '" src="https://graph.facebook.com/'+result['data'][k]['from']['id']+'/picture" > </div>',
				comment_reply_author_name = '<a href="https://www.facebook.com/' + result['data'][k]['from']['id'] + '" class="ffwd_comment_reply_author_name_' + ffwd + '" > ' + result['data'][k]['from']['name'] + ' </a>',
				comment_reply_message = '<span class="ffwd_comment_reply_message_' + ffwd + '" > ' + result['data'][k]['message'] + ' </span>',
				comment_reply_date = '<span class="ffwd_comment_reply_date_' + ffwd + '" > ' + ffwd_get_passed_time(result['data'][k]['created_time']) + '</span>',
				comment_reply_likes_count = '<span class="ffwd_comment_reply_likes_count_' + ffwd + '" > ' + result['data'][k]['like_count'] + ' </span>',
				comments_reply_date_likes = '<div>' + comment_reply_date + comment_reply_likes_count + '</div>',
				comment_reply_div_cont = '<div class="ffwd_comment_reply_content_' + ffwd + '" >' + comment_reply_author_name + comment_reply_message + comments_reply_date_likes + '<div style="clear:both"></div></div>',
				comment_reply = '<div id="ffwd_comment_reply_' + ffwd + '_' + comment_reply_id + '" class="ffwd_comment_reply_' + ffwd + '">' + comment_reply_author_pic + comment_reply_div_cont + '<div style="clear:both" > </div></div>';
			jQuery('#ffwd_comment_' + ffwd + '_' + parent_comm_id + ' .ffwd_comment_replies_content_' + ffwd).append(comment_reply);

		}
		ffwd_blog_style_resize(ffwd_params, ffwd);
	});


}
function ffwd_fill_likes(result, ffwd, id) {
	var total_reactions_count = {}
	total_reactions_count[ffwd] = parseInt(result['total_reactions']['summary']['total_count']);
	var likes_count = {};
	likes_count[ffwd] = parseInt(result['like']['summary']['total_count']);
	var love_count = {};
	love_count[ffwd] = parseInt(result['love']['summary']['total_count']);
	var wow_count = {};
	wow_count[ffwd] = parseInt(result['wow']['summary']['total_count']);
	var haha_count = {};
	haha_count[ffwd] = parseInt(result['haha']['summary']['total_count']);
	var sad_count = {};
	sad_count[ffwd] = parseInt(result['sad']['summary']['total_count']);
	var angry_count = {};
	angry_count[ffwd] = parseInt(result['angry']['summary']['total_count']);

	jQuery('#ffwd_likes_' + ffwd + '_' + id).html(total_reactions_count[ffwd]);
	if (total_reactions_count[ffwd] >= 3  && result['total_reactions']['data'][0]) {
		var likes_some_names = '<div class="ffwd_like_name_cont_' + ffwd + '"> <a class="ffwd_like_name_' + ffwd + '" href="https://www.facebook.com/' + result['total_reactions']['data'][0]['id'] + '" target="_blank">' + result['total_reactions']['data'][0]['name'] + ' , </a><a class="ffwd_like_name_' + ffwd + '" href="https://www.facebook.com/' + result['total_reactions']['data'][1]['id'] + '" target="_blank">' + result['total_reactions']['data'][1]['name'] + ' </a></div>';
		var likes_count_last_part = '<div class="ffwd_almost_' + ffwd + '">  ' + ffwd_frontend_text.and + ' ' + (total_reactions_count[ffwd] - 2) + ' ' + ffwd_frontend_text.others + ' </div>';
	}
	else if (total_reactions_count[ffwd] == 2  && result['total_reactions']['data'][0]) {
		var likes_some_names = '<div class="ffwd_like_name_cont_' + ffwd + '"> <a class="ffwd_like_name_' + ffwd + '" href="https://www.facebook.com/' + result['total_reactions']['data'][0]['id'] + '" target="_blank">' + result['total_reactions']['data'][0]['name'] + ' , </a><a class="ffwd_like_name_' + ffwd + '" href="https://www.facebook.com/' + result['total_reactions']['data'][1]['id'] + '" target="_blank">' + result['total_reactions']['data'][1]['name'] + ' </a></div>';
		var likes_count_last_part = '';
	}
	else if (total_reactions_count[ffwd] == 1  && result['total_reactions']['data'][0]) {
		var likes_some_names = '<div class="ffwd_like_name_cont_' + ffwd + '"> <a class="ffwd_like_name_' + ffwd + '" href="https://www.facebook.com/' + result['total_reactions']['data'][0]['id'] + '" target="_blank">' + result['total_reactions']['data'][0]['name'] + '</a></div>';
		var likes_count_last_part = '';
	}
	else {
		var likes_some_names = '';
		var likes_count_last_part = '';
	}

	var reactions_icons = ''
	reactions_width = 0;
	if (likes_count[ffwd] != 0) {
		reactions_icons += '<span class="ffwd_react_icons_like_' + ffwd + '" title="Like"></span>';
		reactions_width += 40;
		jQuery('#ffwd_reactions_like_' + id + '_' + ffwd).show();
		jQuery('#ffwd_tootlip_text_like_' + id + '_' + ffwd).html('Like: ' + likes_count[ffwd]);
	}
	if (love_count[ffwd] != 0) {
		reactions_icons += '<span class="ffwd_react_icons_love_' + ffwd + '" title="Love"></span>';
		reactions_width += 40;
		jQuery('#ffwd_reactions_love_' + id + '_' + ffwd).show();
		jQuery('#ffwd_tootlip_text_love_' + id + '_' + ffwd).html('Love: ' + love_count[ffwd]);


	}
	if (haha_count[ffwd] != 0) {
		reactions_icons += '<span class="ffwd_react_icons_haha_' + ffwd + '" title="Haha"></span>';
		reactions_width += 40;
		jQuery('#ffwd_reactions_haha_' + id + '_' + ffwd).show();
		jQuery('#ffwd_tootlip_text_haha_' + id + '_' + ffwd).html('Haha: ' + haha_count[ffwd]);


	}
	if (wow_count[ffwd] != 0) {
		reactions_icons += '<span class="ffwd_react_icons_wow_' + ffwd + '" title="WOW"></span>';
		reactions_width += 40;
		jQuery('#ffwd_reactions_wow_' + id + '_' + ffwd).show();
		jQuery('#ffwd_tootlip_text_wow_' + id + '_' + ffwd).html('Wow: ' + wow_count[ffwd]);


	}
	if (sad_count[ffwd] != 0) {
		reactions_icons += '<span class="ffwd_react_icons_sad_' + ffwd + '" title="Sad"></span>';
		reactions_width += 40;
		jQuery('#ffwd_reactions_sad_' + id + '_' + ffwd).show();
		jQuery('#ffwd_tootlip_text_sad_' + id + '_' + ffwd).html('Sad: ' + sad_count[ffwd]);


	}
	if (angry_count[ffwd] != 0) {
		reactions_icons += '<span class="ffwd_react_icons_angry_' + ffwd + '" title="Angry"></span>';
		reactions_width += 40;
		jQuery('#ffwd_reactions_angry_' + id + '_' + ffwd).show();
		jQuery('#ffwd_tootlip_text_angry_' + id + '_' + ffwd).html('Angry: ' + angry_count[ffwd]);


	}

	jQuery('#ffwd_reacts_' + id + '_' + ffwd).css('width', reactions_width + 52 + 'px');

	if (total_reactions_count[ffwd] == 0)
		jQuery('#ffwd_reacts_' + id + '_' + ffwd).css('display', 'none');

	var likes_names_count = '<div class="ffwd_likes_names_' + ffwd + '">' + reactions_icons + likes_some_names + likes_count_last_part + ' </div><div style="clear:both" ></div>';


	if (total_reactions_count[ffwd])
		jQuery('#ffwd_likes_names_count_' + id + '_' + ffwd).html(likes_names_count);
	else
		jQuery('#ffwd_likes_names_count_' + id + '_' + ffwd).remove();

}


function ffwd_blog_style_resize(ffwd_params, ffwd) {
	var window_width = window.innerWidth,
		blogstyle_object = jQuery(".blog_style_object_container_" + ffwd),
		blogstyle_object_width = blogstyle_object.width(),
		blogstyle_comment_content = jQuery(".ffwd_comment_content_" + ffwd),
		blogstyle_comment_reply_content = jQuery(".ffwd_comment_reply_content_" + ffwd),
		blogstyle_comment_content_left_margin = (parseInt(blogstyle_comment_content.css("margin-left"))) ?
			parseInt(blogstyle_comment_content.css("margin-left")) : 0,
		comment_author_pic_width = (parseInt(jQuery(".ffwd_comment_author_pic_" + ffwd + " img").css("width"))) ?
			parseInt(jQuery(".ffwd_comment_author_pic_" + ffwd + " img").css("width")) : 0,
		comment_reply_author_pic_width = (parseInt(jQuery(".ffwd_comment_reply_author_pic_" + ffwd + " img").css("width"))) ?
			parseInt(jQuery(".ffwd_comment_reply_author_pic_" + ffwd + " img").css("width")) : 0,
		comment_content_padding = (parseInt(jQuery(".ffwd_comment_" + ffwd).css("padding-left"))) ?
			parseInt(jQuery(".ffwd_comment_" + ffwd).css("padding-left")) : 0,
		ffwd_blog_style_img = jQuery(".ffwd_blog_style_img_" + ffwd);
	/* Minus one for twenty fifteen theme :))*/
	max_width_for_comment_content = blogstyle_object_width -
		comment_author_pic_width -
		blogstyle_comment_content_left_margin -
		(2 * comment_content_padding) - 1,
		max_width_for_comment_reply_content = max_width_for_comment_content -
			comment_reply_author_pic_width -
			blogstyle_comment_content_left_margin -
			(2 * comment_content_padding) - 1;


	max_width_for_attachment = (ffwd_params["blog_style_view_type"] != "1" && window_width > 520) ?
	blogstyle_object_width * 0.55 : blogstyle_object_width;

	blogstyle_comment_content.css("max-width", max_width_for_comment_content);
	blogstyle_comment_reply_content.css("max-width", max_width_for_comment_reply_content);
	ffwd_blog_style_img.css("max-width", max_width_for_attachment);
}


/*
jQuery(window).ready(function () {
  jQuery.ajax({
    url: ffwd_frontend_text.ajax_url,
    data: {
      'action': 'ffwd_autoupdate',
		},
    type: 'get',
    success: function (response) {
      console.log(response);
    }

  })

})*/
