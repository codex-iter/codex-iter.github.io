var isPopUpOpened = false;
function ffwd_createpopup(url, current_view, width, height, duration, description, lifetime) {
	url = url.replace(/&#038;/g, '&');
	if (isPopUpOpened) { return };
	isPopUpOpened = true;
	if (ffwd_spider_hasalreadyreceivedpopup(description) || spider_isunsupporteduseragent()) {
		return;
	}
	jQuery("html").attr("style", "overflow:hidden !important;");
	jQuery("#spider_popup_loading_" + current_view).css({display: "block"});
	jQuery("#spider_popup_overlay_" + current_view).css({display: "block"});
	jQuery.get(url, function(data) {
		var popup = jQuery(
			'<div id="spider_popup_wrap" class="spider_popup_wrap" style="' +
			' width:' + width + 'px;' +
			' height:' + height + 'px;' +
			' margin-top:-' + height / 2 + 'px;' +
			' margin-left: -' + width / 2 + 'px; ">' +
			data +
			'</div>')
			.hide()
			.appendTo("body");
		ff_wd_spider_showpopup(description, lifetime, popup, duration);
	}).success(function(jqXHR, textStatus, errorThrown) {
		jQuery("#spider_popup_loading_" + current_view).css({display: "none !important;"});
	});
}

function ff_wd_spider_showpopup(description, lifetime, popup, duration) {
	isPopUpOpened = true;
	popup.show();
	ffwd_spider_receivedpopup(description, lifetime);
}

function ffwd_spider_hasalreadyreceivedpopup(description) {
	if (document.cookie.indexOf(description) > -1) {
		delete document.cookie[document.cookie.indexOf(description)];
	}
	return false;
}

function ffwd_spider_receivedpopup(description, lifetime) {
	var date = new Date();
	date.setDate(date.getDate() + lifetime);
	document.cookie = description + "=true;expires=" + date.toUTCString() + ";path=/";
}

function spider_isunsupporteduseragent() {
	return (!window.XMLHttpRequest);
}

function ffwd_destroypopup(duration) {
	if (document.getElementById("spider_popup_wrap") != null) {
		if (typeof jQuery().fullscreen !== 'undefined' && jQuery.isFunction(jQuery().fullscreen)) {
			if (jQuery.fullscreen.isFullScreen()) {
				jQuery.fullscreen.exit();
			}
		}
		setTimeout(function () {
			jQuery(".spider_popup_wrap").remove();
			jQuery(".spider_popup_loading").css({display: "none"});
			jQuery(".spider_popup_overlay").css({display: "none"});
			jQuery(document).off("keydown");
			jQuery("html").attr("style", "overflow:auto !important");
		}, 20);
	}
	isPopUpOpened = false;
	var isMobile = (/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()));
	var viewportmeta = document.querySelector('meta[name="viewport"]');
	if (isMobile && viewportmeta) {
		viewportmeta.content = 'width=device-width, initial-scale=1';
	}
	var scrrr = jQuery(document).scrollTop();
	window.location.hash = "";
	jQuery(document).scrollTop(scrrr);
	clearInterval(ffwd_playInterval);
}

// Set value by ID.
function spider_set_input_value(input_id, input_value) {
	if (document.getElementById(input_id)) {
		document.getElementById(input_id).value = input_value;
	}
}

function ffwd_get_passed_time_popup(time) {
	var today = new Date(),
		arr = time.split(/[^0-9]/);
	today = Date.parse(today) / 1000 - client_server_date_difference_popup;
	time = Date.UTC(arr[0],arr[1]-1,arr[2],arr[3],arr[4],arr[5] );
	time /= 1000;
	time = today - time;
	var tokens = {
		'year'   : '31536000',
		'month'  : '2592000',
		'week'   : '604800',
		'day'    : '86400',
		'hour'   : '3600',
		'minute' : '60',
		'second' : '1'
	};
	for (unit in tokens) {
		if (time < parseInt(tokens[unit])) continue;
		var numberOfUnits = Math.floor(time / parseInt(tokens[unit]));
		return numberOfUnits + ' ' + unit + ( ( numberOfUnits > 1 ) ? 's ago' : ' ago' ) ;
	}
}

function ffwd_time(object) {
	var date_format = ffwd_options["post_date_format"];
	if(object["type"] == "events")
		date_format = ffwd_options["event_date_format"];

	date = ffwd_set_timezone_format(ffwd_date_timezone_offset, date_format, object["created_time"]);
	return date;
}

function ffwd_set_timezone_format(timezone_offset, date_format, time) {
	var date = new Date(time);
	new_date_timezone = new Date( date.getTime() + (timezone_offset)* 3600 * 1000).toUTCString().replace( / GMT$/, "" );
	new_date = new Date(new_date_timezone);
	var year = new_date.getFullYear(),
		month = new_date.getMonth(),
		week_day = new_date.getDay(),
		month_day = new_date.getDate(),
		hour = new_date.getHours(),
		minute = new_date.getMinutes(),
		sec = new_date.getSeconds(),
		weekday= [
			"Sunday",
			"Monday",
			"Tuesday",
			"Wednesday",
			"Thursday",
			"Friday",
			"Saturday",
		],
		monthNames = [
			"January",
			"February",
			"March",
			"April",
			"May",
			"June",
			"July",
			"August",
			"September",
			"October",
			"November",
			"December"
		];
	switch(date_format) {
		case "ago" :
			format = ffwd_get_passed_time_popup(time);
			break;
		case "F j, Y, g:i a" :
			format = monthNames[month] + ' ' + month_day + ', ' + year + ', ' + formatAMPM(new_date);
			break;
		case "F j, Y" :
			format = monthNames[month] + ' ' + month_day + ', ' + year;
			break;
		case "l, F jS, Y" :
			format = weekday[week_day] + ', ' + monthNames[month] + ' ' + ordinal_suffix_of(month_day) + ', ' + year;
			break;
		case "l, F jS, Y, g:i a" :
			format = weekday[week_day] + ', ' + monthNames[month] + ' ' + ordinal_suffix_of(month_day) + ', ' + year + ', ' + formatAMPM(new_date);
			break;
		case "Y/m/d at g:i a" :
			format = year + '/' + ((month + 1) > 10 ? (month + 1) : "0" + (month + 1)) + '/' + (month_day > 10 ? month_day : "0" + month_day) + ' at ' + formatAMPM(new_date);
			break;
		default:
			format = ffwd_get_passed_time_popup(time);
			break;
	}
	return format;
}

function formatAMPM(date) {
	var hours = date.getHours(),
		minutes = date.getMinutes(),
		ampm = hours >= 12 ? 'pm' : 'am';

	hours = hours % 12;
	hours = hours ? hours : 12;
	minutes = minutes < 10 ? '0'+minutes : minutes;
	var strTime = hours + ':' + minutes + ' ' + ampm;
	return strTime;
}

function ordinal_suffix_of(i) {
	var j = i % 10,
		k = i % 100;
	if (j == 1 && k != 11) {
		return i + "st";
	}
	if (j == 2 && k != 12) {
		return i + "nd";
	}
	if (j == 3 && k != 13) {
		return i + "rd";
	}
	return i + "th";
}

function ffwd_see_show_hide(string, type, row_type) {
	string_for_quality = string.replace(/&lt;br\/&gt;/g, "\n");
	string = string.replace(/&lt;br\/&gt;/g, " \n ");
	var new_string = string,
		hide_text_paragraph = '',
		length = string_for_quality.length;
	/*console.log(length); xndir@ ispanerin hatk chareri meja*/
	if(row_type == 'events')
		text_length = (typeof ffwd_options["event_desp_length"] != "undefined") ? ffwd_options["event_desp_length"] : 200;
	else
		text_length = (typeof ffwd_options["post_text_length"] != "undefined") ? ffwd_options["post_text_length"] : 200;

	if (length > text_length) {
		var stringCut = string_for_quality.substr(0, text_length);
		var last_whitespace_in_string_cut = stringCut.lastIndexOf(" ");
		last_whitespace_in_string_cut = (last_whitespace_in_string_cut == -1) ? 0 : last_whitespace_in_string_cut;
		var hide_text_length = length - last_whitespace_in_string_cut;

		stringCut = stringCut.substr(0, last_whitespace_in_string_cut)
		stringCut = stringCut.replace(/\n/g, " \n ");

		var hide_text = string_for_quality.substr(last_whitespace_in_string_cut, hide_text_length);
		hide_text = hide_text.replace(/\n/g, " \n ");
		hide_text_paragraph = ' <span style="display:none" class="ffwd_object_' + type + '_hide" >' + hide_text + ' </span>';
		new_string = stringCut + hide_text_paragraph + ' <span class="ffwd_more_dotes" > ... </span> <a href="" class="ffwd_see_more ffwd_see_more_' + type + '">See more</a>';
	}
	return new_string;
}

function ffwd_fill_tags(message, message_tags) {
	if(message_tags)
		var type = message_tags.constructor.name;
	if(type == "Array") {
		for(var j=0; j<message_tags.length; j++) {
			var tag_name = message_tags[j]["name"],
				tag_id = message_tags[j]["id"];
			message = message.replace(tag_name, '<a class="ffwd_message_tag" target="_blank" href="https://www.facebook.com/'+tag_id+'" >'+tag_name+'</a>');
		}
	}
	else if(type == "Object"){
		for(var x in message_tags) {
			var tag_name = message_tags[x]["0"]["name"],
				tag_id = message_tags[x]["0"]["id"];
			message = message.replace(tag_name, '<a class="ffwd_message_tag" target="_blank" href="https://www.facebook.com/'+tag_id+'" >'+tag_name+'</a>');
		}
	}
	return message;
}


function ffwd_testBrowser_cssTransitions() {
	return ffwd_testDom('Transition');
}

function ffwd_testBrowser_cssTransforms3d() {
	return ffwd_testDom('Perspective');
}

function ffwd_testDom(prop) {
	/* Browser vendor CSS prefixes.*/
	var browserVendors = ['', '-webkit-', '-moz-', '-ms-', '-o-', '-khtml-'];
	/* Browser vendor DOM prefixes.*/
	var domPrefixes = ['', 'Webkit', 'Moz', 'ms', 'O', 'Khtml'];
	var i = domPrefixes.length;
	while (i--) {
		if (typeof document.body.style[domPrefixes[i] + prop] !== 'undefined') {
			return true;
		}
	}
	return false;
}

function ffwd_cube(tz, ntx, nty, nrx, nry, wrx, wry, current_image_class, next_image_class, direction) {
	/* If browser does not support 3d transforms/CSS transitions.*/
	if (!ffwd_testBrowser_cssTransitions()) {
		return ffwd_fallback(current_image_class, next_image_class, direction);
	}
	if (!ffwd_testBrowser_cssTransforms3d()) {
		return ffwd_fallback3d(current_image_class, next_image_class, direction);
	}
	ffwd_trans_in_progress = true;
	/* Set active thumbnail.*/
	jQuery(".ffwd_filmstrip_thumbnail").removeClass("ffwd_thumb_active").addClass("ffwd_thumb_deactive");
	jQuery("#ffwd_filmstrip_thumbnail_" + ffwd_current_key).removeClass("ffwd_thumb_deactive").addClass("ffwd_thumb_active");
	jQuery(".ffwd_slide_bg").css('perspective', 1000);
	jQuery(current_image_class).css({
		transform : 'translateZ(' + tz + 'px)',
		backfaceVisibility : 'hidden'
	});
	jQuery(next_image_class).css({
		opacity : 1,
		filter: 'Alpha(opacity=100)',
		backfaceVisibility : 'hidden',
		transform : 'translateY(' + nty + 'px) translateX(' + ntx + 'px) rotateY('+ nry +'deg) rotateX('+ nrx +'deg)'
	});
	jQuery(".ffwd_slider").css({
		transform: 'translateZ(-' + tz + 'px)',
		transformStyle: 'preserve-3d'
	});
	/* Execution steps.*/
	setTimeout(function () {
		jQuery(".ffwd_slider").css({
			transition: 'all ' + ffwd_transition_duration + 'ms ease-in-out',
			transform: 'translateZ(-' + tz + 'px) rotateX('+ wrx +'deg) rotateY('+ wry +'deg)'
		});
	}, 20);
	/* After transition.*/
	jQuery(".ffwd_slider").one('webkitTransitionEnd transitionend otransitionend oTransitionEnd mstransitionend', jQuery.proxy(ffwd_after_trans));
	function ffwd_after_trans() {
		jQuery(current_image_class).removeAttr('style');
		jQuery(next_image_class).removeAttr('style');
		jQuery(".ffwd_slider").removeAttr('style');
		jQuery(current_image_class).css({'opacity' : 0, filter: 'Alpha(opacity=0)', 'z-index': 1});
		jQuery(next_image_class).css({'opacity' : 1, filter: 'Alpha(opacity=100)', 'z-index' : 2});

		ffwd_trans_in_progress = false;
		jQuery(current_image_class).html('');

		/*First check main objects*/
		if (typeof ffwd_event_stack !== 'undefined' && ffwd_event_stack.length > 0) {
			ffwd_event_stack_for_attachments = [];
			key = ffwd_event_stack[0].split("-");
			ffwd_event_stack.shift();
			ffwd_change_image(key[0], key[1], data, true);
		}
		/*Then sub objects*/
		if (typeof ffwd_event_stack_for_attachments !== 'undefined' && ffwd_event_stack_for_attachments.length > 0) {
			var object = ffwd_event_stack_for_attachments[0];
			ffwd_event_stack_for_attachments.shift();
			ffwd_change_subattachment(object);
		}
	}
}
function ffwd_cubeH(current_image_class, next_image_class, direction) {
	/* Set to half of image width.*/
	var dimension = jQuery(current_image_class).width() / 2;
	if (direction == 'right') {
		ffwd_cube(dimension, dimension, 0, 0, 90, 0, -90, current_image_class, next_image_class, direction);
	}
	else if (direction == 'left') {
		ffwd_cube(dimension, -dimension, 0, 0, -90, 0, 90, current_image_class, next_image_class, direction);
	}
}
function ffwd_cubeV(current_image_class, next_image_class, direction) {
	/* Set to half of image height.*/
	var dimension = jQuery(current_image_class).height() / 2;
	/* If next slide.*/
	if (direction == 'right') {
		ffwd_cube(dimension, 0, -dimension, 90, 0, -90, 0, current_image_class, next_image_class, direction);
	}
	else if (direction == 'left') {
		ffwd_cube(dimension, 0, dimension, -90, 0, 90, 0, current_image_class, next_image_class, direction);
	}
}
/* For browsers that does not support transitions.*/
function ffwd_fallback(current_image_class, next_image_class, direction) {
	ffwd_fade(current_image_class, next_image_class, direction);
}
/* For browsers that support transitions, but not 3d transforms (only used if primary transition makes use of 3d-transforms).*/
function ffwd_fallback3d(current_image_class, next_image_class, direction) {
	ffwd_sliceV(current_image_class, next_image_class, direction);
}
function ffwd_none(current_image_class, next_image_class, direction) {
	jQuery(current_image_class).css({'opacity' : 0, 'z-index': 1});
	jQuery(next_image_class).css({'opacity' : 1, 'z-index' : 2});
	/* Set active thumbnail.*/
	jQuery(".ffwd_filmstrip_thumbnail").removeClass("ffwd_thumb_active").addClass("ffwd_thumb_deactive");
	jQuery("#ffwd_filmstrip_thumbnail_" + ffwd_current_key).removeClass("ffwd_thumb_deactive").addClass("ffwd_thumb_active");
	ffwd_trans_in_progress = false;
	jQuery(current_image_class).html('');
}
function ffwd_fade(current_image_class, next_image_class, direction) {
	/* Set active thumbnail.*/
	jQuery(".ffwd_filmstrip_thumbnail").removeClass("ffwd_thumb_active").addClass("ffwd_thumb_deactive");
	jQuery("#ffwd_filmstrip_thumbnail_" + ffwd_current_key).removeClass("ffwd_thumb_deactive").addClass("ffwd_thumb_active");
	if (ffwd_testBrowser_cssTransitions()) {
		jQuery(next_image_class).css('transition', 'opacity ' + ffwd_transition_duration + 'ms linear');
		jQuery(current_image_class).css({'opacity' : 0, 'z-index': 1});
		jQuery(next_image_class).css({'opacity' : 1, 'z-index' : 2});
	}
	else {
		jQuery(current_image_class).animate({'opacity' : 0, 'z-index' : 1}, ffwd_transition_duration);
		jQuery(next_image_class).animate({
			'opacity' : 1,
			'z-index': 2
		}, {
			duration: ffwd_transition_duration,
			complete: function () {

				ffwd_trans_in_progress = false;
				jQuery(current_image_class).html('');
			}
		});
		/* For IE.*/
		jQuery(current_image_class).fadeTo(ffwd_transition_duration, 0);
		jQuery(next_image_class).fadeTo(ffwd_transition_duration, 1);
	}
}
function ffwd_grid(cols, rows, ro, tx, ty, sc, op, current_image_class, next_image_class, direction) {
	/* If browser does not support CSS transitions.*/
	if (!ffwd_testBrowser_cssTransitions()) {
		return ffwd_fallback(current_image_class, next_image_class, direction);
	}
	ffwd_trans_in_progress = true;
	/* Set active thumbnail.*/
	jQuery(".ffwd_filmstrip_thumbnail").removeClass("ffwd_thumb_active").addClass("ffwd_thumb_deactive");
	jQuery("#ffwd_filmstrip_thumbnail_" + ffwd_current_key).removeClass("ffwd_thumb_deactive").addClass("ffwd_thumb_active");
	/* The time (in ms) added to/subtracted from the delay total for each new gridlet.*/
	var count = (ffwd_transition_duration) / (cols + rows);
	/* Gridlet creator (divisions of the image grid, positioned with background-images to replicate the look of an entire slide image when assembled)*/
	function ffwd_gridlet(width, height, top, img_top, left, img_left, src, imgWidth, imgHeight, c, r) {
		var delay = (c + r) * count;
		/* Return a gridlet elem with styles for specific transition.*/
		return jQuery('<div class="ffwd_gridlet" />').css({
			width : width,
			height : height,
			top : top,
			left : left,
			backgroundImage : 'url("' + src + '")',
			backgroundColor: jQuery(".spider_popup_wrap").css("background-color"),
			/*backgroundColor: 'rgba(0, 0, 0, 0)',*/
			backgroundRepeat: 'no-repeat',
			backgroundPosition : img_left + 'px ' + img_top + 'px',
			backgroundSize : imgWidth + 'px ' + imgHeight + 'px',
			transition : 'all ' + ffwd_transition_duration + 'ms ease-in-out ' + delay + 'ms',
			transform : 'none'
		});
	}
	/* Get the current slide's image.*/
	var cur_img = jQuery(current_image_class).find('img');
	/* Create a grid to hold the gridlets.*/
	var grid = jQuery('<div />').addClass('ffwd_grid');
	/* Prepend the grid to the next slide (i.e. so it's above the slide image).*/
	jQuery(current_image_class).prepend(grid);
	/* Vars to calculate positioning/size of gridlets.*/
	var cont = jQuery(".ffwd_slide_bg");
	var imgWidth = cur_img.width();
	var imgHeight = cur_img.height();
	var contWidth = cont.width(),
		contHeight = cont.height(),
		colWidth = Math.floor(contWidth / cols),
		rowHeight = Math.floor(contHeight / rows),
		colRemainder = contWidth - (cols * colWidth),
		colAdd = Math.ceil(colRemainder / cols),
		rowRemainder = contHeight - (rows * rowHeight),
		rowAdd = Math.ceil(rowRemainder / rows),
		leftDist = 0,
		img_leftDist = Math.ceil((jQuery(".ffwd_slide_bg").width() - cur_img.width()) / 2);
	var imgSrc = typeof cur_img.attr('src')=='undefined' ? '' :cur_img.attr('src');
	/* tx/ty args can be passed as 'auto'/'min-auto' (meaning use slide width/height or negative slide width/height).*/
	tx = tx === 'auto' ? contWidth : tx;
	tx = tx === 'min-auto' ? - contWidth : tx;
	ty = ty === 'auto' ? contHeight : ty;
	ty = ty === 'min-auto' ? - contHeight : ty;
	/* Loop through cols.*/
	for (var i = 0; i < cols; i++) {
		var topDist = 0,
			img_topDst = Math.floor((jQuery(".ffwd_slide_bg").height() - cur_img.height()) / 2),
			newColWidth = colWidth;
		/* If imgWidth (px) does not divide cleanly into the specified number of cols, adjust individual col widths to create correct total.*/
		if (colRemainder > 0) {
			var add = colRemainder >= colAdd ? colAdd : colRemainder;
			newColWidth += add;
			colRemainder -= add;
		}
		/* Nested loop to create row gridlets for each col.*/
		for (var j = 0; j < rows; j++)  {
			var newRowHeight = rowHeight,
				newRowRemainder = rowRemainder;
			/* If contHeight (px) does not divide cleanly into the specified number of rows, adjust individual row heights to create correct total.*/
			if (newRowRemainder > 0) {
				add = newRowRemainder >= rowAdd ? rowAdd : rowRemainder;
				newRowHeight += add;
				newRowRemainder -= add;
			}
			/* Create & append gridlet to grid.*/
			grid.append(ffwd_gridlet(newColWidth, newRowHeight, topDist, img_topDst, leftDist, img_leftDist, imgSrc, imgWidth, imgHeight, i, j));
			topDist += newRowHeight;
			img_topDst -= newRowHeight;
		}
		img_leftDist -= newColWidth;
		leftDist += newColWidth;
	}
	/* Set event listener on last gridlet to finish transitioning.*/
	var last_gridlet = grid.children().last();
	/* Show grid & hide the image it replaces.*/
	grid.show();
	cur_img.css('opacity', 0);
	/* Add identifying classes to corner gridlets (useful if applying border radius).*/
	grid.children().first().addClass('rs-top-left');
	grid.children().last().addClass('rs-bottom-right');
	grid.children().eq(rows - 1).addClass('rs-bottom-left');
	grid.children().eq(- rows).addClass('rs-top-right');
	/* Execution steps.*/
	setTimeout(function () {
		grid.children().css({
			opacity: op,
			transform: 'rotate('+ ro +'deg) translateX('+ tx +'px) translateY('+ ty +'px) scale('+ sc +')'
		});
	}, 30);
	jQuery(next_image_class).css('opacity', 1);
	/* After transition.*/
	jQuery(last_gridlet).one('webkitTransitionEnd transitionend otransitionend oTransitionEnd mstransitionend', jQuery.proxy(ffwd_after_trans));
	function ffwd_after_trans() {

		/*console.log('ffwd_after_transssss');*/

		jQuery(current_image_class).css({'opacity' : 0, 'z-index': 1});
		jQuery(next_image_class).css({'opacity' : 1, 'z-index' : 2});
		cur_img.css('opacity', 1);
		grid.remove();
		ffwd_trans_in_progress = false;
		jQuery(current_image_class).html('');

		/*First check main objects*/
		if (typeof ffwd_event_stack !== 'undefined' && ffwd_event_stack.length > 0) {
			/*console.log('gag');*/
			ffwd_event_stack_for_attachments = [];
			key = ffwd_event_stack[0].split("-");
			ffwd_event_stack.shift();
			ffwd_change_image(key[0], key[1], data, true);
		}
		/*Then sub objects*/
		if (typeof ffwd_event_stack_for_attachments !== 'undefined' && ffwd_event_stack_for_attachments.length > 0) {
			var object = ffwd_event_stack_for_attachments[0];
			ffwd_event_stack_for_attachments.shift();
			ffwd_change_subattachment(object);
		}
	}
}
function ffwd_sliceH(current_image_class, next_image_class, direction) {
	if (direction == 'right') {
		var translateX = 'min-auto';
	}
	else if (direction == 'left') {
		var translateX = 'auto';
	}
	ffwd_grid(1, 8, 0, translateX, 0, 1, 0, current_image_class, next_image_class, direction);
}
function ffwd_sliceV(current_image_class, next_image_class, direction) {
	if (direction == 'right') {
		var translateY = 'min-auto';
	}
	else if (direction == 'left') {
		var translateY = 'auto';
	}
	ffwd_grid(10, 1, 0, 0, translateY, 1, 0, current_image_class, next_image_class, direction);
}
function ffwd_slideV(current_image_class, next_image_class, direction) {
	if (direction == 'right') {
		var translateY = 'auto';
	}
	else if (direction == 'left') {
		var translateY = 'min-auto';
	}
	ffwd_grid(1, 1, 0, 0, translateY, 1, 1, current_image_class, next_image_class, direction);
}
function ffwd_slideH(current_image_class, next_image_class, direction) {
	if (direction == 'right') {
		var translateX = 'min-auto';
	}
	else if (direction == 'left') {
		var translateX = 'auto';
	}
	ffwd_grid(1, 1, 0, translateX, 0, 1, 1, current_image_class, next_image_class, direction);
}
function ffwd_scaleOut(current_image_class, next_image_class, direction) {
	ffwd_grid(1, 1, 0, 0, 0, 1.5, 0, current_image_class, next_image_class, direction);
}
function ffwd_scaleIn(current_image_class, next_image_class, direction) {
	ffwd_grid(1, 1, 0, 0, 0, 0.5, 0, current_image_class, next_image_class, direction);
}
function ffwd_blockScale(current_image_class, next_image_class, direction) {
	ffwd_grid(8, 6, 0, 0, 0, .6, 0, current_image_class, next_image_class, direction);
}
function ffwd_kaleidoscope(current_image_class, next_image_class, direction) {
	ffwd_grid(10, 8, 0, 0, 0, 1, 0, current_image_class, next_image_class, direction);
}
function ffwd_fan(current_image_class, next_image_class, direction) {
	if (direction == 'right') {
		var rotate = 45;
		var translateX = 100;
	}
	else if (direction == 'left') {
		var rotate = -45;
		var translateX = -100;
	}
	ffwd_grid(1, 10, rotate, translateX, 0, 1, 0, current_image_class, next_image_class, direction);
}
function ffwd_blindV(current_image_class, next_image_class, direction) {
	ffwd_grid(1, 8, 0, 0, 0, .7, 0, current_image_class, next_image_class);
}
function ffwd_blindH(current_image_class, next_image_class, direction) {
	ffwd_grid(10, 1, 0, 0, 0, .7, 0, current_image_class, next_image_class);
}
function ffwd_random(current_image_class, next_image_class, direction) {
	var anims = ['sliceH', 'sliceV', 'slideH', 'slideV', 'scaleOut', 'scaleIn', 'blockScale', 'kaleidoscope', 'fan', 'blindH', 'blindV'];
	/* Pick a random transition from the anims array.*/
	this["ffwd_" + anims[Math.floor(Math.random() * anims.length)]](current_image_class, next_image_class, direction);
}
function ffwd_reset_zoom() {
	var isMobile = (/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()));
	var viewportmeta = document.querySelector('meta[name="viewport"]');
	if (isMobile && viewportmeta) {
		viewportmeta.content = 'width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=0';
	}
}

/*Show hide subattachments cont*/
function show_hide_sub_attachments(pos) {
	/*Click from button*/
	if(!pos) {
		pos = (jQuery('.ffwd_sub_attachmenst_cont').css('top') == '0px') ? '-58px' : '0px';
	}
	jQuery('.ffwd_sub_attachmenst_cont').animate({
		top: pos,
	}, 250, "linear", function() {

	});
}

/* Change object info*/
function ffwd_change_info(key) {
	var object = data[key],
		name = jQuery('<div />').html(object['name']).text(),
		link = object['link'],
		message = ffwd_see_show_hide(object['message'], 'message', object['type']),
		message_tags = object['message_tags'],
		description = ffwd_see_show_hide(object['description'], 'description', object['type']),

		time = object['created_time'],
		place_name = '',
		place_id,
		class_name = (object["type"] != "events") ? ".ffwd_popup_from_time_post" : ".ffwd_popup_from_time_event";

	message = ffwd_fill_hashtags(message);
	message = ffwd_fill_tags(message, message_tags);
	description = ffwd_fill_hashtags(description);

	ffwd_fill_story_author_place(key);
	jQuery('.ffwd_object_name').html(name).attr('href', link);
	jQuery('.ffwd_object_messages').html(message);
	jQuery('.ffwd_object_description').html(description);
	jQuery(class_name).html(ffwd_time(object));
}

/*Function for fill hashtags*/
function ffwd_fill_hashtags(str) {
	str = str.replace(/ \n /g, " <br> ");
	str = str.split(' ');
	for(var i=0; i<str.length; i++) {
		if(str[i].indexOf('#') !== -1) {
			var hashtag = str[i].replace('#', '<a class="ffwd_hashtag" target="_blank" href="https://www.facebook.com/hashtag/');
			var word = str[i].split('#');
			word = '#' + word[1];
			hashtag += '">' + word + '</a>';
			str[i] = hashtag;
		}
	}
	str = str.join(' ');
	return str;
}

/*See less see more*/
function ffwd_see_less_more(that, more_less, type) {
	var display = (more_less == 'more') ? 'inline' : 'none',
		display_dotes = (more_less == 'more') ? 'none' : 'inline',
		less_more = (more_less == 'more') ? 'less' : 'more';

	that.parent().parent().find('.ffwd_object_'+type+'_hide').css('display', display);
	that.html('See ' + less_more).removeClass('ffwd_see_' + more_less + '_' + type).addClass('ffwd_see_' + less_more + '_' + type);
	that.parent().find('.ffwd_more_dotes').css('display', display_dotes);
}

/*Function for comments and likes cont*/
function ffwd_fill_likes_comments(key) {
	if(ffwd_content_type == "specific")
	{
		var url_for_cur_id = popup_graph_url.replace('{FB_ID}', data[key]['from']+'_'+data[key]['object_id']);
		/*For likes*/
		var graph_url_for_likes = url_for_cur_id.replace('{EDGE}', '');
		graph_url_for_likes = graph_url_for_likes.replace('{FIELDS}', 'fields=likes.summary(true){id,name},comments.summary(true){created_time,from,like_count,message,comment_count}&');
		graph_url_for_likes = graph_url_for_likes.replace('{OTHER}', 'summary=true');
		jQuery.getJSON(graph_url_for_likes, function(result) {
			var likes_count = (typeof result['likes']['summary'] != 'undefined') ? parseInt(result['likes']['summary']['total_count']) : 0;
			jQuery('#ffwd_likes').html(likes_count);
			if(likes_count >= 3) {
				var likes_some_names = '<div class="ffwd_likes_name_cont"> <a class="ffwd_likes_name" href="https://www.facebook.com/'+result['likes']['data'][0]['id']+'" target="_blank">' + result['likes']['data'][0]['name'] + ' , </a><a class="ffwd_likes_name" href="https://www.facebook.com/'+result['likes']['data'][1]['id']+'" target="_blank">' + result['likes']['data'][1]['name'] +'</a></div>',
					likes_count_last_part = '<div class="ffwd_likes_count_last_part" > and ' + (likes_count - 2)  +' others like this </div>';
			}
			else if(likes_count == 2 ) {
				var likes_some_names = '<div> <a class="ffwd_likes_name" href="https://www.facebook.com/'+result['likes']['data'][0]['id']+'" target="_blank">' + result['likes']['data'][0]['name'] + ' , </a> , <a class="ffwd_likes_name" href="https://www.facebook.com/'+result['likes']['data'][1]['id']+'" target="_blank">' + result['likes']['data'][1]['name'] +'</a></div>',
					likes_count_last_part = '';
			}
			else if(likes_count == 1 ) {
				var likes_some_names = '<div> <a class="ffwd_likes_name" href="https://www.facebook.com/'+result['likes']['data'][0]['id']+'" target="_blank">' + result['likes']['data'][0]['name'] + '</a></div>',
					likes_count_last_part = '';
			}
			else {
				var likes_some_names = '',
					likes_count_last_part = '';
			}
			var likes_names_count = '<div class="ffwd_likes_names" > '+likes_some_names+likes_count_last_part+' </div><div style="clear:both" ></div>';
			if(likes_count > 0)
				jQuery('#ffwd_likes_names_count').html(likes_names_count).css("display", "block");
			else
				jQuery('#ffwd_likes_names_count').css("display", "none");


			var	total_count = (result['comments']['data'].length < 25) ? result['comments']['data'].length : result['comments']['summary']['total_count'];
			jQuery('#ffwd_comments_count').html(total_count);
			var more_comments = false;
			jQuery('#ffwd_comments_content').html('');
			for(var i=0, j=1, z=0; i<result['comments']['data'].length; i++,j++) {
				var display = 'display:block',
					comment_id = result['comments']['data'][i]['id'];
				if(j > 4) {
					display = 'display:none';
					more_comments = true;
					z++;
				}

/*
				var url_for_cur_id_comm_user_pic = popup_graph_url.replace('{FB_ID}', result['comments']['data'][i]['from']['id']);
				url_for_cur_id_comm_user_pic = url_for_cur_id_comm_user_pic.replace('{EDGE}', '');
				url_for_cur_id_comm_user_pic = url_for_cur_id_comm_user_pic.replace('{FIELDS}', '&fields=picture&');
				url_for_cur_id_comm_user_pic = url_for_cur_id_comm_user_pic.replace('{OTHER}', '');
*/


				var comment_author_pic = '<span></span>',
				//var comment_author_pic = '<div style="float:left" class="ffwd_comment_author_pic" > <img class="user_'+result['comments']['data'][i]['from']['id']+'" style="width:32px;height:32px" src="https://graph.facebook.com/'+result['comments']['data'][i]['from']['id']+'/picture" > </div>',
					comment_author_name = '<span></span>',
				//	comment_author_name = '<a class="ffwd_comment_author_name" href="https://www.facebook.com/'+result['comments']['data'][i]['from']['id']+'" target="_blank"> '+result['comments']['data'][i]['from']['name']+' </a>',
					comment_message = '<span class="ffwd_comment_message" > '+result['comments']['data'][i]['message']+' </span>',
					comment_date = '<span class="ffwd_comment_date" > '+ffwd_get_passed_time_popup(result['comments']['data'][i]['created_time'])+'</span>',
					comment_likes_count = '<span class="ffwd_comment_likes_count"> '+result['comments']['data'][i]['like_count']+' </span>',
					comment_date_likes = '<div class="ffwd_comment_date_likes">'+ comment_date + comment_likes_count + '</div>',
					comment_replies_cont = (ffwd_options["comments_filter"] == "toplevel" && ffwd_options["comments_replies"] == "1" && result['comments']['data'][i]['comment_count'] > 0) ? '<div class="ffwd_comment_replies"><div id="ffwd_comment_replies_popup_label_' + result['comments']['data'][i]['id']+'" class="ffwd_comment_replies_label">'+result['comments']['data'][i]['comment_count']+' Reply</div><div class="ffwd_comment_replies_content"></div></div>' : '',
					comment_div_cont = '<div class="ffwd_comment_content" >'+comment_author_name+comment_message+comment_date_likes+comment_replies_cont+'<div style="clear:both"></div></div>',
					comment = '<div id="ffwd_single_comment_'+comment_id+'" style="'+display+'" class="ffwd_single_comment">' + comment_author_pic + comment_div_cont + '<div style="clear:both" > </div></div>';
				jQuery('#ffwd_comments_content').append(comment);
				/*jQuery.getJSON(url_for_cur_id_comm_user_pic, function(result) {
				 jQuery('.user_'+result['id']+'').attr('src', result['picture']['data']['url']);
				 });*/
				if(ffwd_options["comments_filter"] == "toplevel" && ffwd_options["comments_replies"] == "1")
				{


					(function (i) {

						jQuery('#ffwd_comment_replies_popup_label_' + result['comments']['data'][i]['id']).bind("click", function () {

							ffwd_get_comments_replies_popup(result['comments']['data'][i]['id'], popup_graph_url);
						})
					}(i))

				}
			}
			if(more_comments) {
				jQuery('#ffwd_comments_content').append('<div class="ffwd_view_more_comments_cont"> <a href="#" class="ffwd_view_more_comments" more_count="'+z+'"> <span> View '+z+' more comments </span> <a> </div>');
			}
			;




		});
		console.log(graph_url_for_likes);
		return;
	}


	var url_for_cur_id = popup_graph_url.replace('{FB_ID}', data[key]['object_id']);
	/*For likes*/
	var graph_url_for_likes = url_for_cur_id.replace('{EDGE}', 'likes');
	graph_url_for_likes = graph_url_for_likes.replace('{FIELDS}', 'fields=id,name&');
	graph_url_for_likes = graph_url_for_likes.replace('{OTHER}', 'summary=true');
	/*For comments*/
	var graph_url_for_comments = url_for_cur_id.replace('{EDGE}', 'comments');
	graph_url_for_comments = graph_url_for_comments.replace('{FIELDS}', 'fields=created_time,from,like_count,message,comment_count&');
	graph_url_for_comments = graph_url_for_comments.replace('{OTHER}', 'summary=true&filter='+ffwd_options["comments_filter"]+'&order='+ffwd_options["comments_order"]+'&limit=25');
	/*console.log(graph_url_for_comments)*/
	/*For shares*/
	var graph_url_for_shares = url_for_cur_id.replace('{EDGE}', '');
	graph_url_for_shares = graph_url_for_shares.replace('{FIELDS}', 'fields=shares&');
	graph_url_for_shares = graph_url_for_shares.replace('{OTHER}', '');
	jQuery.getJSON(graph_url_for_likes, function(result) {
		var likes_count = (typeof result['summary'] != 'undefined') ? parseInt(result['summary']['total_count']) : 0;
		jQuery('#ffwd_likes').html(likes_count);
		/*if(likes_count >= 3) {
			var likes_some_names = '<div class="ffwd_likes_name_cont"> <a class="ffwd_likes_name" href="https://www.facebook.com/'+result['data'][0]['id']+'" target="_blank">' + result['data'][0]['name'] + ' , </a><a class="ffwd_likes_name" href="https://www.facebook.com/'+result['data'][1]['id']+'" target="_blank">' + result['data'][1]['name'] +'</a></div>',
				likes_count_last_part = '<div class="ffwd_likes_count_last_part" > and ' + (likes_count - 2)  +' others like this </div>';
		}
		else if(likes_count == 2 ) {
			var likes_some_names = '<div> <a class="ffwd_likes_name" href="https://www.facebook.com/'+result['data'][0]['id']+'" target="_blank">' + result['data'][0]['name'] + ' , </a> , <a class="ffwd_likes_name" href="https://www.facebook.com/'+result['data'][1]['id']+'" target="_blank">' + result['data'][1]['name'] +'</a></div>',
				likes_count_last_part = '';
		}
		else if(likes_count == 1 ) {
			var likes_some_names = '<div> <a class="ffwd_likes_name" href="https://www.facebook.com/'+result['data'][0]['id']+'" target="_blank">' + result['data'][0]['name'] + '</a></div>',
				likes_count_last_part = '';
		}
		else {
			var likes_some_names = '',
				likes_count_last_part = '';
		}*/


        var likes_some_names = '',
            likes_count_last_part = '';

        var likes_names_count = '<div class="ffwd_likes_names" > '+likes_some_names+likes_count_last_part+' </div><div style="clear:both" ></div>';
		if(likes_count > 0)
			jQuery('#ffwd_likes_names_count').html(likes_names_count).css("display", "block");
		else
			jQuery('#ffwd_likes_names_count').css("display", "none");
	});
	/* For shares */
	if(ffwd_content_type == "timeline") {
		var shares_count = (typeof data[key]['shares'] != 'undefined') ? parseInt(data[key]['count']) : '0';
		jQuery('#ffwd_shares').html(shares_count);
	}
	/* For comments */

	var	total_count = (data[key]['comments']['data'].length < 25) ? data[key]['comments']['data'].length : data[key]['comments']['summary']['total_count'];
	jQuery('#ffwd_comments_count').html(total_count);
	var more_comments = false;
	jQuery('#ffwd_comments_content').html('');
	for(var i=0, j=1, z=0; i<data[key]['comments']['data'].length; i++,j++) {
		var display = 'display:block',
			comment_id = data[key]['comments']['data'][i]['id'];
		if(j > 4) {
			display = 'display:none';
			more_comments = true;
			z++;
		}

/*
		var url_for_cur_id_comm_user_pic = popup_graph_url.replace('{FB_ID}', data[key]['comments']['data'][i]['from']['id']);
		url_for_cur_id_comm_user_pic = url_for_cur_id_comm_user_pic.replace('{EDGE}', '');
		url_for_cur_id_comm_user_pic = url_for_cur_id_comm_user_pic.replace('{FIELDS}', '&fields=picture&');
		url_for_cur_id_comm_user_pic = url_for_cur_id_comm_user_pic.replace('{OTHER}', '');

*/

		//var comment_author_pic = '<div style="float:left" class="ffwd_comment_author_pic" > <img class="user_'+data[key]['comments']['data'][i]['from']['id']+'" style="width:32px;height:32px" src="https://graph.facebook.com/'+data[key]['comments']['data'][i]['from']['id']+'/picture" > </div>',
		var comment_author_pic = '<span></span>',
			//comment_author_name = '<a class="ffwd_comment_author_name" href="https://www.facebook.com/'+data[key]['comments']['data'][i]['from']['id']+'" target="_blank"> '+data[key]['comments']['data'][i]['from']['name']+' </a>',
			comment_author_name = '<span></span>',
			comment_message = '<span class="ffwd_comment_message" > '+data[key]['comments']['data'][i]['message']+' </span>',
			comment_date = '<span class="ffwd_comment_date" > '+ffwd_get_passed_time_popup(data[key]['comments']['data'][i]['created_time'])+'</span>',
			comment_likes_count = '<span class="ffwd_comment_likes_count"> '+data[key]['comments']['data'][i]['like_count']+' </span>',
			comment_date_likes = '<div class="ffwd_comment_date_likes">'+ comment_date + comment_likes_count + '</div>',
			comment_replies_cont = (ffwd_options["comments_filter"] == "toplevel" && ffwd_options["comments_replies"] == "1" && data[key]['comments']['data'][i]['comment_count'] > 0) ? '<div class="ffwd_comment_replies"><div id="ffwd_comment_replies_popup_label_' + data[key]['comments']['data'][i]['id']+'" class="ffwd_comment_replies_label">'+data[key]['comments']['data'][i]['comment_count']+' Reply</div><div class="ffwd_comment_replies_content"></div></div>' : '',
			comment_div_cont = '<div class="ffwd_comment_content" >'+comment_author_name+comment_message+comment_date_likes+comment_replies_cont+'<div style="clear:both"></div></div>',
			comment = '<div id="ffwd_single_comment_'+comment_id+'" style="'+display+'" class="ffwd_single_comment">' + comment_author_pic + comment_div_cont + '<div style="clear:both" > </div></div>';
		jQuery('#ffwd_comments_content').append(comment);
		/*jQuery.getJSON(url_for_cur_id_comm_user_pic, function(result) {
		 jQuery('.user_'+result['id']+'').attr('src', result['picture']['data']['url']);
		 });*/
		if(ffwd_options["comments_filter"] == "toplevel" && ffwd_options["comments_replies"] == "1")
		{


			(function (i) {

				jQuery('#ffwd_comment_replies_popup_label_' + data[key]['comments']['data'][i]['id']).bind("click", function () {

					ffwd_get_comments_replies_popup(data[key]['comments']['data'][i]['id'], popup_graph_url);
				})
			}(i))

		}
	}
	if(more_comments) {
		jQuery('#ffwd_comments_content').append('<div class="ffwd_view_more_comments_cont"> <a href="#" class="ffwd_view_more_comments" more_count="'+z+'"> <span> View '+z+' more comments </span> <a> </div>');
	}
	;
}


function ffwd_get_comments_replies_popup(comment_id,popup_graph_url) {
	var url_for_cur_id_comm_replies = popup_graph_url.replace('{FB_ID}', comment_id);
	url_for_cur_id_comm_replies = url_for_cur_id_comm_replies.replace('{EDGE}', 'comments');
	url_for_cur_id_comm_replies = url_for_cur_id_comm_replies.replace('{FIELDS}', 'fields=parent,created_time,from,like_count,message&');
	url_for_cur_id_comm_replies = url_for_cur_id_comm_replies.replace('{OTHER}', '');
	jQuery.getJSON(url_for_cur_id_comm_replies, function(result) {
		for (var k = 0; k < result['data'].length; k++) {
			var parent_comm_id = comment_id,
				comment_reply_id = result['data'][k]["id"];

			var url_for_cur_id_comm_rep_user_pic = popup_graph_url.replace('{FB_ID}', result['data'][k]['from']['id']);
			url_for_cur_id_comm_rep_user_pic = url_for_cur_id_comm_rep_user_pic.replace('{EDGE}', '');
			url_for_cur_id_comm_rep_user_pic = url_for_cur_id_comm_rep_user_pic.replace('{FIELDS}', 'fields=picture&');
			url_for_cur_id_comm_rep_user_pic = url_for_cur_id_comm_rep_user_pic.replace('{OTHER}', '');

			var comment_reply_author_pic = '<div class="ffwd_comment_reply_author_pic" style="float:left" > <img class="reply_user_' + result['data'][k]['from']['id'] + '" style="width:25px;height:25px" src="https://graph.facebook.com/' + result['data'][k]['from']['id'] + '/picture" > </div>',
				comment_reply_author_name = '<a class="ffwd_comment_reply_author_name" href="https://www.facebook.com/' + result['data'][k]['from']['id'] + '" > ' + result['data'][k]['from']['name'] + ' </a>',
				comment_reply_message = '<span class="ffwd_comment_reply_message" > ' + result['data'][k]['message'] + ' </span>',
				comment_reply_date = '<span class="ffwd_comment_reply_date" > ' + ffwd_get_passed_time_popup(result['data'][k]['created_time']) + '</span>',
				comment_reply_likes_count = '<span class="ffwd_comment_reply_likes_count" > ' + result['data'][k]['like_count'] + ' </span>',
				comment_reply_date_likes = '<div class="ffwd_comment_reply_date_likes">' + comment_reply_date + comment_reply_likes_count + '</div>',
				comment_reply_div_cont = '<div class="ffwd_comment_reply_content" >' + comment_reply_author_name + comment_reply_message + comment_reply_date_likes + '<div style="clear:both"></div></div>',
				comment_reply = '<div class="ffwd_comment_reply" id="ffwd_comment_reply_' + comment_reply_id + '" >' + comment_reply_author_pic + comment_reply_div_cont + '<div style="clear:both" > </div></div>';

			jQuery('#ffwd_single_comment_' + parent_comm_id + ' .ffwd_comment_replies_content').append(comment_reply);
			/*jQuery.getJSON(url_for_cur_id_comm_rep_user_pic, function(result) {
			 jQuery('.reply_user_'+result['id']+'').attr('src', result['picture']['data']['url']);
			 });*/
		}
	})

}