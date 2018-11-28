jQuery.ajaxSetup({
    url: wpf_ajax_obj.url,
    data:{
        referer: window.location.origin + window.location.pathname
    }
});

jQuery(document).ready(function ($) {
	var wpforo_wrap = $('#wpforo-wrap');
//	Like
    wpforo_wrap.on('click', '.wpforo-like', function () {
        $("#wpf-msg-box").hide();
        $('#wpforo-load').visible();
        var postid = $(this).data('postid'),
            that = $(this);
        $.ajax({
            type: 'POST',
            url: wpf_ajax_obj.url,
            data: {
                postid: postid,
                likestatus: 1,
                action: 'wpforo_like_ajax'
            }
        }).done(function (response) {
            try {
                response = $.parseJSON(response);
            } catch (e) {
                console.log(e);
            }
            if (response.stat === 1) {
                that.find('.wpforo-like-ico').removeClass('fa-thumbs-up').addClass('fa-thumbs-down');
                that.find('.wpforo-like-txt').text(' ' + wpforo_phrase('Unlike'));
                that.parents('.post-wrap').find('.bleft').html(response.likers);
                that.removeClass('wpforo-like').addClass('wpforo-unlike');
            }
            $('#wpforo-load').invisible();
            wpforo_notice_show(response.notice);
        });
    });
// unlike
    wpforo_wrap.on('click', '.wpforo-unlike', function () {
        $("#wpf-msg-box").hide();
        $('#wpforo-load').visible();
        var postid = $(this).data('postid'),
            that = $(this);
        $.ajax({
            type: 'POST',
            url: wpf_ajax_obj.url,
            data: {
                postid: postid,
                likestatus: 0,
                action: 'wpforo_like_ajax'
            }
        }).done(function (response) {
            try {
                response = $.parseJSON(response);
            } catch (e) {
                console.log(e);
            }
            if (response.stat === 1) {
                that.find('.wpforo-like-ico').removeClass('fa-thumbs-down').addClass('fa-thumbs-up');
                that.find('.wpforo-like-txt').text(' ' + wpforo_phrase('Like'));
                that.parents('.post-wrap').find('.bleft').html(response.likers);
                that.removeClass('wpforo-unlike').addClass('wpforo-like');
            }
            $('#wpforo-load').invisible();
            wpforo_notice_show(response.notice);
        });
    });


//	Vote
    wpforo_wrap.on('click', '.wpforo-voteup', function () {
        $("#wpf-msg-box").hide();
        $('#wpforo-load').visible();
        var type = $(this).data('type'),
            postid = $(this).data('postid'),
            that = $(this);
        $.ajax({
            type: 'POST',
            url: wpf_ajax_obj.url,
            data: {
                itemtype: type,
                postid: postid,
                votestatus: 'up',
                action: 'wpforo_vote_ajax'
            }
        }).done(function (response) {
            try {
                response = $.parseJSON(response);
            } catch (e) {
                console.log(e);
            }
            if (response.stat === 1) {
                var wpfvote_num = that.parents('.post-wrap').find('.wpfvote-num'),
                    count = wpfvote_num.text();
                wpfvote_num.text(++count).fadeIn();
            }
            $('#wpforo-load').invisible();
            wpforo_notice_show(response.notice);
        });
    });

    wpforo_wrap.on('click', '.wpforo-votedown', function () {
        $("#wpf-msg-box").hide();
        $('#wpforo-load').visible();
        var type = $(this).data('type'),
            postid = $(this).data('postid'),
            that = $(this);
        $.ajax({
            type: 'POST',
            url: wpf_ajax_obj.url,
            data: {
                itemtype: type,
                postid: postid,
                votestatus: 'down',
                action: 'wpforo_vote_ajax'
            }
        }).done(function (response) {
            try {
                response = $.parseJSON(response);
            } catch (e) {
                console.log(e);
            }
            if (response.stat === 1) {
                var wpfvote_num = that.parents('.post-wrap').find('.wpfvote-num'),
                    count = wpfvote_num.text();
                wpfvote_num.text(--count).fadeIn();
            }
            $('#wpforo-load').invisible();
            wpforo_notice_show(response.notice);
        });
    });


//	Answer
    wpforo_wrap.on('click', '.wpf-toggle-answer', function () {
        $("#wpf-msg-box").hide();
        $('#wpforo-load').visible();
        var postid = $(this).data('postid'),
            that = $(this);
        $.ajax({
            type: 'POST',
            url: wpf_ajax_obj.url,
            data: {
                postid: postid,
                answerstatus: 0,
                action: 'wpforo_answer_ajax'
            }
        }).done(function (response) {
            try {
                response = $.parseJSON(response);
            } catch (e) {
                console.log(e);
            }
            if (response.stat === 1) {
                that.removeClass('wpf-toggle-answer').addClass('wpf-toggle-not-answer');
            }
            $('#wpforo-load').invisible();
            wpforo_notice_show(response.notice);
        });
    });

    wpforo_wrap.on('click', '.wpf-toggle-not-answer', function () {
        $("#wpf-msg-box").hide();
        $('#wpforo-load').visible();
        var postid = $(this).data('postid'),
            that = $(this);
        $.ajax({
            type: 'POST',
            url: wpf_ajax_obj.url,
            data: {
                postid: postid,
                answerstatus: 1,
                action: 'wpforo_answer_ajax'
            }
        }).done(function (response) {
            try {
                response = $.parseJSON(response);
            } catch (e) {
                console.log(e);
            }
            if (response.stat === 1) {
                that.removeClass('wpf-toggle-not-answer').addClass('wpf-toggle-answer');
            }
            $('#wpforo-load').invisible();
            wpforo_notice_show(response.notice);
        });
    });


//	Quote
    wpforo_wrap.on('click', '.wpforo-quote', function () {
        $("#wpf-msg-box").hide();
        $("#wpforo-load").visible();
        var postid = $(this).data('postid');
        $("#wpf_postparentid").val( postid );
        $.ajax({
            type: 'POST',
            url: wpf_ajax_obj.url,
            data: {
                postid: postid,
                action: 'wpforo_quote_ajax'
            }
        }).done(function (response) {
            tinyMCE.activeEditor.setContent(response);

            var phrase = wpforo_phrase('Reply with quote');
            phrase = phrase.charAt(0).toUpperCase() + phrase.slice(1);
            $("#wpf-reply-form-title").html(phrase);

            $(".wpf-topic-sbs").show();
            $("#wpf-topic-sbs").prop("disabled", false);

            var wpf_formaction = $("#wpf_formaction"),
                wpf_formtopicid = $("#wpf_formtopicid"),
                wpf_formbutton = $("#wpf_formbutton"),
                wpf_title = $("#wpf_title");

            wpf_formaction.attr('name', 'post[action]');
            wpf_formtopicid.attr('name', 'post[topicid]');
            wpf_formbutton.attr('name', 'post[save]');
            wpf_formbutton.val(wpforo_phrase('Save'));
            wpf_title.attr('name', 'post[title]');
            wpf_title.val(wpforo_phrase('re') + ": " + wpf_title.attr('placeholder').replace(wpforo_phrase('re') + ": ", "").replace(wpforo_phrase('answer to') + ": ", ""));
            $('html, body').animate({scrollTop: $("#wpf-form-wrapper").offset().top}, 1000);

            tinymce.execCommand('mceFocus', false, 'postbody');
            tinyMCE.activeEditor.selection.select(tinyMCE.activeEditor.getBody(), true);
            tinyMCE.activeEditor.selection.collapse(false);
            $('#wpforo-load').invisible();
        });
    });

//	Report
    wpforo_wrap.on('click', '.wpforo-report', function(){
        var wpforo_load = $('#wpforo-load');
		$("#wpf-msg-box").hide();
        wpforo_load.visible();
        var postid = $(this).data('postid');
		$('#wpf_reportpostid').attr('value', postid);
		
		var dialog;
		var w = jQuery(window).width();
	    var h = jQuery(window).height();
	    var dialogWidth = 600;
	    var dialogHeight = 250;
	    H = ( dialogHeight < h ) ? dialogHeight : (h-40);
	    W = ( dialogWidth < w ) ? dialogWidth : (w-20);
		
		dialog = jQuery( "#wpf_reportdialog" ).dialog({
			create: function(event, ui) {
		        jQuery(event.target).parent().css('position', 'fixed');
		    },
			autoOpen: false,
		    height: H,
		    width: W,
		    modal: true,
			dialogClass:'wpforo-dialog wpforo-dialog-report'
		});
		
		dialog.dialog( "open" );
        wpforo_load.invisible();
	});

    $("#wpf_reportdialog").on('click', '#wpf_sendreport', function () {
        $("#wpf-msg-box").hide();
        $('#wpforo-load').visible();
        var postid = $('#wpf_reportpostid').attr('value');
        var messagecontent = $('#wpf_reportmessagecontent').attr('value');

        $.ajax({
            type: 'POST',
            url: wpf_ajax_obj.url,
            data: {
                postid: postid,
                reportmsg: messagecontent,
                action: 'wpforo_report_ajax'
            }
        }).done(function (response) {
            try {
                response = $.parseJSON(response);
            } catch (e) {
                console.log(e);
            }
            $("#wpf_reportdialog").dialog('close');
            $('#wpforo-load').invisible();
            wpforo_notice_show(response);
        });
    });
	
	
//	Sticky
    wpforo_wrap.on('click', '.wpforo-sticky', function () {
        $("#wpf-msg-box").hide();
        $('#wpforo-load').visible();
        var topicid = $(this).data('topicid'),
            that = $(this);

        $.ajax({
            type: 'POST',
            url: wpf_ajax_obj.url,
            data: {
                topicid: topicid,
                status: 'sticky',
                action: 'wpforo_sticky_ajax'
            }
        }).done(function (response) {
            try {
                response = $.parseJSON(response);
            } catch (e) {
                console.log(e);
            }
            if (response.stat === 1) {
                that.find('.wpforo-sticky-txt').text(' ' + wpforo_phrase('Unsticky'));
                that.removeClass('wpforo-sticky').addClass('wpforo-unsticky');
            }
            $('#wpforo-load').invisible();
            wpforo_notice_show(response.notice);
        });
    });


    wpforo_wrap.on('click', '.wpforo-unsticky', function () {
        $("#wpf-msg-box").hide();
        $('#wpforo-load').visible();
        var topicid = $(this).data('topicid'),
            that = $(this);

        $.ajax({
            type: 'POST',
            url: wpf_ajax_obj.url,
            data: {
                topicid: topicid,
                status: 'unsticky',
                action: 'wpforo_sticky_ajax'
            }
        }).done(function (response) {
            try {
                response = $.parseJSON(response);
            } catch (e) {
                console.log(e);
            }
            if (response.stat === 1) {
                that.find('.wpforo-sticky-txt').text(' ' + wpforo_phrase('Sticky'));
                that.removeClass('wpforo-unsticky').addClass('wpforo-sticky');
            }
            $('#wpforo-load').invisible();
            wpforo_notice_show(response.notice);
        });
    });

//	Approve
    wpforo_wrap.on('click','.wpforo-approve', function(){
        $("#wpf-msg-box").hide();  $('#wpforo-load').visible();
        var status_value = 'approve';
        var postid_value = $(this).attr('id');
        var postid = postid_value.replace("wpfapprove", "");

        $.ajax({
            type: 'POST',
            url: wpf_ajax_obj.url,
            data:{
                postid: postid,
                status: status_value,
                action: 'wpforo_approve_ajax'
            }
        }).done(function(response){
            if(response !== 0){
                $("#" + postid_value).removeClass('wpforo-approve').addClass('wpforo-unapprove');
                $("#approveicon" + postid).removeClass('fa-check').addClass('fa-exclamation-circle');
                $("#wpforo-wrap #post-" + postid + " .wpf-mod-message").hide();
                $("#wpforo-wrap .wpf-status-title").hide();
                $("#approvetext" + postid).text( ' ' + wpforo_phrase('Unapprove') );
            }
            $('#wpforo-load').invisible();
        });
    });

//	Unapprove
    wpforo_wrap.on('click','.wpforo-unapprove', function(){
        $("#wpf-msg-box").hide();  $('#wpforo-load').visible();
        var status_value = 'unapprove';
        var postid_value = $(this).attr('id');
        var postid = postid_value.replace("wpfapprove", "");

        $.ajax({
            type: 'POST',
            url: wpf_ajax_obj.url,
            data:{
                postid: postid,
                status: status_value,
                action: 'wpforo_approve_ajax'
            }
        }).done(function(response){
            if(response != 0){
                $("#" + postid_value).removeClass('wpforo-unapprove').addClass('wpforo-approve');
                $("#approveicon" + postid).removeClass('fa-exclamation-circle').addClass('fa-check');
                $('#wpforo-wrap #post-' + postid + ' .wpf-mod-message').visible();
                $('#wpforo-wrap .wpf-status-title').visible();
                $("#approvetext" + postid).text( ' ' + wpforo_phrase('Approve') );
            }
            $('#wpforo-load').invisible();
            location.reload();
        });
    });


//	Private
	wpforo_wrap.on('click','.wpforo-private', function(){
		$("#wpf-msg-box").hide();  $('#wpforo-load').visible();
		var status_value = 'private';
		var postid_value = $(this).attr('id');
		var postid = postid_value.replace("wpfprivate", "");
		
	   $.ajax({
	   		type: 'POST',
	   		url: wpf_ajax_obj.url,
	   		data:{
	   			postid: postid,
	   			status: status_value,
	   			action: 'wpforo_private_ajax'
	   		}
	   	}).done(function(response){
	   		if(response != 0){
		   		$("#" + postid_value).removeClass('wpforo-private').addClass('wpforo-public');
				$("#privateicon" + postid).removeClass('fa-eye-slash').addClass('fa-eye');
		   		$("#privatetext" + postid).text( ' ' + wpforo_phrase('Public') );
		   	}
	   		$('#wpforo-load').invisible();
	   	});
	});
	
	wpforo_wrap.on('click','.wpforo-public', function(){
		$("#wpf-msg-box").hide();  $('#wpforo-load').visible();
		var status_value = 'public';
		var postid_value = $(this).attr('id');
		var postid = postid_value.replace("wpfprivate", "");
		
	   $.ajax({
	   		type: 'POST',
	   		url: wpf_ajax_obj.url,
	   		data:{
	   			postid: postid,
	   			status: status_value,
	   			action: 'wpforo_private_ajax'
	   		}
	   	}).done(function(response){
	   		if(response != 0){
		   		$("#" + postid_value).removeClass('wpforo-public').addClass('wpforo-private');
				$("#privateicon" + postid).removeClass('fa-eye').addClass('fa-eye-slash');
		   		$("#privatetext" + postid).text( ' ' + wpforo_phrase('Private') );
		   	}
	   		$('#wpforo-load').invisible();
	   	});
	});
	
//	Solved
	wpforo_wrap.on('click','.wpforo-solved', function(){
		$("#wpf-msg-box").hide();  $('#wpforo-load').visible();
		var status_value = 'solved';
		var postid_value = $(this).attr('id');
		var postid = postid_value.replace("wpfsolved", "");
		
	   $.ajax({
	   		type: 'POST',
	   		url: wpf_ajax_obj.url,
	   		data:{
	   			postid: postid,
	   			status: status_value,
	   			action: 'wpforo_solved_ajax'
	   		}
	   	}).done(function(response){
	   		if(response != 0){
		   		$("#" + postid_value).removeClass('wpforo-solved').addClass('wpforo-unsolved');
		   		$("#solvedtext" + postid).text( ' ' + wpforo_phrase('Unsolved') );
		   	}
	   		$('#wpforo-load').invisible();
	   	});
	});
	
	wpforo_wrap.on('click','.wpforo-unsolved', function(){
		$("#wpf-msg-box").hide();  $('#wpforo-load').visible();
		var status_value = 'unsolved';
		var postid_value = $(this).attr('id');
		var postid = postid_value.replace("wpfsolved", "");
		
	   $.ajax({
	   		type: 'POST',
	   		url: wpf_ajax_obj.url,
	   		data:{
	   			postid: postid,
	   			status: status_value,
	   			action: 'wpforo_solved_ajax'
	   		}
	   	}).done(function(response){
	   		if(response != 0){
		   		$("#" + postid_value).removeClass('wpforo-unsolved').addClass('wpforo-solved');
		   		$("#solvedtext" + postid).text( ' ' + wpforo_phrase('Solved') );
		   	}
	   		$('#wpforo-load').invisible();
	   	});
	});
	
	
//	Close
	wpforo_wrap.on('click','.wpforo-close', function(){
		$("#wpf-msg-box").hide();  $('#wpforo-load').visible();
		var status_value = 'close';
		var postid_value = $(this).attr('id');
		var postid = postid_value.replace("wpfclose", "");
	   $.ajax({
	   		type: 'POST',
	   		url: wpf_ajax_obj.url,
	   		data:{
	   			postid: postid,
	   			status: status_value,
	   			action: 'wpforo_close_ajax'
	   		}
	   	}).done(function(response){
	   		if(response != 0){
		   		$("#" + postid_value).removeClass('wpforo-close').addClass('wpforo-open');
		   		$("#closeicon" + postid).removeClass('fa-lock').addClass('fa-unlock');
		   		$("#closetext" + postid).text( ' ' + wpforo_phrase('Open') );
		   		$("#wpf-form-wrapper").remove();
		   		$(".wpforo-reply").remove();
		   		$(".wpforo-quote").remove();
		   		$(".wpforo-edit").remove();
			}
	   		$('#wpforo-load').invisible();
	   	});
	});
	
	wpforo_wrap.on('click','.wpforo-open', function(){
		$("#wpf-msg-box").hide();  $('#wpforo-load').visible();
		var status_value = 'closed';
		var postid_value = $(this).attr('id');
		var postid = postid_value.replace("wpfclose", "");
	   $.ajax({
	   		type: 'POST',
	   		url: wpf_ajax_obj.url,
	   		data:{
	   			postid: postid,
	   			status: status_value,
	   			action: 'wpforo_close_ajax'
	   		}
	   	}).done(function(response){
	   		if(response != 0){
		   		/*$("#" + postid_value).removeClass('wpforo-open').addClass('wpforo-close');
		   		$("#closeicon" + postid).removeClass('fa-unlock').addClass('fa-lock');
		   		$("#closetext" + postid).text( ' ' + wpforo_phrase('Close') );*/
		   		window.location.assign(response);
		   	}
	   		$('#wpforo-load').invisible();
	   		
	   	});
	});
	
	
//	Edit
	wpforo_wrap.on('click','.wpforo-edit', function(){
		$("#wpf-msg-box").hide();  $('#wpforo-load').visible();
		
		var phrase = wpforo_phrase('Edit post');
		phrase = phrase.charAt(0).toUpperCase() + phrase.slice(1);
		$("#wpf-reply-form-title").html( phrase );
		var postid_value = $(this).attr('id');
		var is_topic = postid_value.indexOf("topic");
		
		if(is_topic == -1){
			var postid = postid_value.replace("wpfedit", "");
		}else{
			var postid = postid_value.replace("wpfedittopicpid", "");
		}
	   $.ajax({
	   		type: 'POST',
	   		url: wpf_ajax_obj.url,
	   		data: {
	   			postid: postid,
	   			action: 'wpforo_edit_ajax'
	   		}
	   	}).done(function(response){
	   		if(response != 0){
		   		try{
					response = $.parseJSON(response);
				}catch(e){
					console.log(e);
				}
		   		tinyMCE.activeEditor.setContent( response.body );
		   		$( ".wpf-topic-sbs" ).hide();
		   		$( "#wpf-topic-sbs" ).prop("disabled", true);
		   		$( "#wpf_formaction" ).val( 'edit' );
		   		$( "#wpf_formpostid" ).val( postid );
				$( "#wpf_formbutton" ).val( wpforo_phrase('Update') );
		   		$( 'html, body' ).animate({scrollTop: $("#wpf-form-wrapper").offset().top}, 1000);
		   		if(is_topic == -1){
                    $( ".wpf-topic-tags" ).hide();
		   			$( "#wpf_title").val( response.post_title );
					$( "#wpf_formaction" ).attr('name', 'post[action]');
					$( "#wpf_formbutton" ).attr('name', 'post[save]');
					$( "#wpf_formtopicid" ).attr('name', 'post[topicid]');
					$( "#wpf_title" ).attr('name', 'post[title]');
					if( $( "#wpf_user_name" ).length ) { $( "#wpf_user_name" ).attr('name', 'post[name]'); }
					if( $( "#wpf_user_email" ).length ) { $( "#wpf_user_email" ).attr('name', 'post[email]'); }
				}else{
					$( "#wpf_title").val( response.topic_title );
                    $( "#wpf_tags" ).val( response.topic_tags );
                    $( ".wpf-topic-tags" ).show();
					$( "#wpf_formaction" ).attr('name', 'topic[action]');
					$( "#wpf_formbutton" ).attr('name', 'topic[save]');
					$( "#wpf_formtopicid" ).attr('name', 'topic[topicid]');
					$( "#wpf_title" ).attr('name', 'topic[title]');
                    $( "#wpf_tags" ).attr('name', 'topic[tags]');
					if( $( "#wpf_user_name" ).length ) { $( "#wpf_user_name" ).attr('name', 'topic[name]'); }
					if( $( "#wpf_user_email" ).length ) { $( "#wpf_user_email" ).attr('name', 'topic[email]'); }
				}
				
	   			tinymce.execCommand('mceFocus',false,'postbody');
				tinyMCE.activeEditor.selection.select(tinyMCE.activeEditor.getBody(), true);
				tinyMCE.activeEditor.selection.collapse(false);
			}
			
   			$('#wpforo-load').invisible();
   			
	   	});
	});
	
	
//	Delete
	wpforo_wrap.on('click','.wpforo-delete', function(){
		$("#wpf-msg-box").hide();  $('#wpforo-load').visible();
		
		var ok = confirm(wpforo_ucwords( wpforo_phrase('are you sure you want to delete?') ));
		
		if (ok == true){
			var postid_value = $(this).attr('id');
			var is_topic = postid_value.indexOf("topic");
			
			if(is_topic == -1){
				var postid = postid_value.replace("wpfreplydelete", "");
				var status_value = 'reply';
			}else{
				var postid = postid_value.replace("wpftopicdelete", "");
				var status_value = 'topic';
			}
			
			var forumid = $("input[type='hidden']#wpf_parent").val();
			
		  	$.ajax({
		   		type: 'POST',
		   		url: wpf_ajax_obj.url,
		   		data:{
		   			forumid: forumid,
		   			postid: postid,
		   			status: status_value,
		   			action: 'wpforo_delete_ajax'
		   		}
		   	}).done(function(response){
		   		try{
					response = $.parseJSON(response);
				} catch (e) {
					console.log(e);
				}
		   		if( response.stat == 1 ){
					if(is_topic == -1){
						$('#post-'+response.postid).fadeOut().delay(200);
					}else{
						window.location.assign(response.location);	
					}
					$('#wpforo-load').invisible();
				}else{
					$('#wpforo-load').invisible();
				}
				
				wpforo_notice_show(response.notice);
		   	});
		}else{
			$('#wpforo-load').invisible();
		}
	});
	
	
//	Subscribe
	wpforo_wrap.on('click','.wpf-subscribe-forum, .wpf-subscribe-topic', function(){
		$("#wpf-msg-box").hide();  $('#wpforo-load').visible();
		var type = '';
		var status = 'subscribe';
		var clases = $(this).attr('class');
		
		if( clases.indexOf("wpf-subscribe-forum") > -1 ){
	    	type = 'forum';
		}
		if( clases.indexOf("wpf-subscribe-topic") > -1 ){
			type = 'topic';
		}
		
		var postid_value = $(this).attr('id');
		var itemid = postid_value.replace("wpfsubscribe-", "");
		
	   $.ajax({
	   		type: 'POST',
	   		url: wpf_ajax_obj.url,
	   		data: {
	   			itemid: itemid,
	   			type: type,
	   			status: status,
	   			action: 'wpforo_subscribe_ajax'
	   		}
	   	}).done(function(response){
	   		try{
			   	response = $.parseJSON(response);
			   } catch (e) {
			   	console.log(e);
			   }
	   		if( response.stat == 1 ){
	   			$("#wpfsubscribe-" + itemid).removeClass('wpf-subscribe-' + type).addClass('wpf-unsubscribe-' + type);
	   			$("#wpfsubscribe-" + itemid).text( ' ' + wpforo_phrase('Unsubscribe') );
	   			$('#wpforo-load').invisible();
			}else{
				$('#wpforo-load').invisible();
			}
			
			wpforo_notice_show(response.notice);
	   	});
	   	
	});
	
	wpforo_wrap.on('click','.wpf-unsubscribe-forum, .wpf-unsubscribe-topic', function(){
		$("#wpf-msg-box").hide();  $('#wpforo-load').visible();
		var type = '';
		var button_phrase = '';
		var status = 'unsubscribe';
		var clases = $(this).attr('class');
		if( clases.indexOf("wpf-unsubscribe-forum") > -1 ){
	    	type = 'forum';
	    	button_phrase = wpforo_ucwords( wpforo_phrase('Subscribe for new topics') );
		}
		if( clases.indexOf("wpf-unsubscribe-topic") > -1 ){
			type = 'topic';
			button_phrase = wpforo_ucwords( wpforo_phrase('Subscribe for new replies') );
		}
		var postid_value = $(this).attr('id');
		var itemid = postid_value.replace("wpfsubscribe-", "");
		
	   $.ajax({
	   		type: 'POST',
	   		url: wpf_ajax_obj.url,
	   		data: {
	   			itemid: itemid,
	   			type: type,
	   			status: status,
	   			action: 'wpforo_subscribe_ajax'
	   		}
	   	}).done(function(response){
	   		try{
			   	response = $.parseJSON(response);
			   } catch (e) {
			   	console.log(e);
			   }
	   		if( response.stat == 1 ){
	   			$("#wpfsubscribe-" + itemid).removeClass('wpf-unsubscribe-' + type).addClass('wpf-subscribe-' + type);
		   		$("#wpfsubscribe-" + itemid).text( ' ' + button_phrase );
		   		$('#wpforo-load').invisible();
			}else{
				$('#wpforo-load').invisible();
			}
			
			wpforo_notice_show(response.notice);
	   	});
	});

	wpforo_wrap.on('click', '.wpforo-tools', function () {
	    var tools = $('#wpf_moderation_tools');
	    if( tools.is(':visible') ){
            tools.slideUp(250, 'linear');
        }else{
            $("#wpf-msg-box").hide(); $('#wpforo-load').visible();
            tools.find('.wpf-tool-tabs .wpf-tool-tab').removeClass('wpf-tt-active');
            tools.find('.wpf-tool-tabs .wpf-tool-tab:first-child').addClass('wpf-tt-active');
			wpforo_topic_tools_tab_load();
        }
    });

	wpforo_wrap.on('click', '#wpf_moderation_tools .wpf-tool-tabs .wpf-tool-tab:not(.wpf-tt-active)', function () {
        $("#wpf-msg-box").hide();
        $(this).siblings('.wpf-tool-tab').removeClass('wpf-tt-active');
        if( !$(this).hasClass('wpf-tt-active') ) $(this).addClass('wpf-tt-active');
		wpforo_topic_tools_tab_load();
    });

    wpforo_topic_tools_tab_load();
});

function wpforo_ucwords (str) {
    return (str + '').replace(/^([a-z])|\s+([a-z])/, function ($1) {
        return $1.toUpperCase();
    });
}

function wpforo_topic_tools_tab_load() {
    var active_tab = jQuery('#wpf_moderation_tools').find('.wpf-tool-tab.wpf-tt-active');
    if( active_tab.length ){
        var active_tab_id = active_tab.attr('id');
        if( active_tab_id ){
            jQuery("#wpf-msg-box").hide();
            jQuery('#wpf_tool_tab_content_wrap').html('<i class="fas fa-spinner fa-spin wpf-icon-spinner"></i>');
            jQuery.ajax({
                type: 'POST',
                data: {
                    active_tab_id: active_tab_id,
                    action: 'wpforo_active_tab_content_ajax'
                }
            }).done(function(response){
                if( response ){
                    jQuery('#wpf_tool_tab_content_wrap').html(response);
                    jQuery('#wpf_moderation_tools').slideDown(400, 'linear');
                }
                jQuery('#wpforo-load').invisible();
            });
        }
    }
}

jQuery(document).ready(function ($) {
    $('#wpf_tags').suggest(
        window.ajaxurl + "?action=wpforo_tag_search",
        {   multiple:true,
            multipleSep: ",",
            resultsClass: 'wpf_ac_results',
            selectClass: 'wpf_ac_over',
            matchClass: 'wpf_ac_match',
            onSelect: function() {}
        }
    );
    $('.wpf_ac_results').blur(function() {
        $('#wpf_tags').removeClass( 'wpf-ac-loading' );
    });
    $('#wpf_tags').blur(function() {
        $(this).removeClass( 'wpf-ac-loading' );
    });
    $('#wpf_tags').keydown(
        function ( e ) {
            var loading; clearTimeout(loading);
            var tags = $('#wpf_tags').val();
            if( tags.length >= 1 ){
                switch(e.keyCode) {
                    case 38:  // up
                    case 40:  // down
                    case 8:   // backspace
                    case 9:   // tab
                    case 13:  // return
                    case 27:  // escape
                    case 32:  // space
                    case 188: // comma
                        $(this).removeClass( 'wpf-ac-loading' ); break;
                    default:
                        $(this).addClass( 'wpf-ac-loading' );
                }
            }
            loading = setTimeout(function() { $('#wpf_tags').removeClass( 'wpf-ac-loading' ); }, 1000);
        }
    );
});

