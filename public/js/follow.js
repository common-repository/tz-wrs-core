$jq =jQuery.noConflict();

/*
 * Validate Email Address
 */
var emailValidation = function(email) {

    $jq("#frm_msg").html("");
    $jq("#frm_msg").removeAttr("class");

    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;

    if(email == '' || (!emailReg.test(email))){
        $jq("#frm_msg").html("Please enter valid email");
        $jq("#frm_msg").addClass("error");
        return false;
    }else{
        return true;
    }

};

$jq(document).ready(function(){
    // bind event handlers when the page loads.
    bindButtonClick();
});

function bindButtonClick(){
    /*
     * Make initial subscription to service
     */
    $jq(".logged_out").on("mouseenter",function(){
        $jq(this).val("Login");
        
    });
    $jq(".logged_out").on("mouseout",function(){
        $jq(this).val("Follow");        
    });
	
    $jq(".dj_following").hover(function(){
        $jq(".dj_following:hover").val("Unfollow");
    });
    $jq(".dj_following").on("mouseout",function(){
        $jq(".dj_following").val("Following");        
    });


	$jq(document).on("click", '.dj_follow', function() { 
        var activeObject = $jq(this);
		var currid = jQuery("#wp-admin-bar-the_team span").data('currid');
		updateFollow(activeObject,currid);
	});

	$jq(document).on("click", '.dj_following', function() { 
        var activeObject = $jq(this);
		var currid = jQuery("#wp-admin-bar-the_team span").data('currid');
		updateUnFollow(activeObject,currid);
	});
	$jq(document).on("click", '#loadFollowees.hiding', function() { 
        var activeObject = $jq(this);
		var currAuthor = $jq(this).attr("data-author");
		var currid = jQuery("#wp-admin-bar-the_team span").data('currid');
		showFollowees(activeObject,currAuthor,currid);
	});
	$jq(document).on("click", '#loadFollowees.showing', function() { 
        var activeObject = $jq(this);
		var currAuthor = $jq(this).attr("data-author");
		var currid = jQuery("#wp-admin-bar-the_team span").data('currid');
		hideFollowees(activeObject,currAuthor,currid);
	});
	$jq(document).on("click", '#loadFollowers.hiding', function() { 
        var activeObject = $jq(this);
		var currAuthor = $jq(this).attr("data-author");
		var currid = jQuery("#wp-admin-bar-the_team span").data('currid');
		showFollowers(activeObject,currAuthor,currid);
	});
	$jq(document).on("click", '#loadFollowers.showing', function() { 
        var activeObject = $jq(this);
		var currAuthor = $jq(this).attr("data-author");
		var currid = jQuery("#wp-admin-bar-the_team span").data('currid');
		hideFollowers(activeObject,currAuthor,currid);
	});
}

function showFollowers(activeObject,currAuthor,currid){
	jQuery.ajax({
		type: "post",
		url: ajaxData.ajaxUrl,
		data : {
			action : 'tzwrs_get_followers',
			author_id : activeObject.attr("data-author"),
			nonce : ajaxData.ajaxNonce,
			url : ajaxData.currentURL,
			currid : currid
		},
		success: function(message){
			var result = eval('(' + message + ')');
			if ( result.followers != '' ) {
				jQuery("#loadFollowers").removeClass("hiding").addClass("showing");
				jQuery("#loadFollowees").removeClass("showing").addClass("hiding");
				jQuery(".followers").html( result.followers ).fadeIn();
			}
		}
	});
}

function hideFollowers(activeObject,currAuthor,currid){
	jQuery("#loadFollowers.showing").removeClass("showing").addClass("hiding");
	jQuery(".followers").fadeOut();
}

function hideFollowees(activeObject,currAuthor,currid){
	jQuery("#loadFollowees.showing").removeClass("showing").addClass("hiding");
	jQuery(".followers").fadeOut();
}

function showFollowees(activeObject,currAuthor,currid){
	//var email = $jq("#user_email").val();
	jQuery.ajax({
		type: "post",
		url: ajaxData.ajaxUrl,
		data : {
			action : 'tzwrs_get_followees',
			author_id : activeObject.attr("data-author"),
			nonce : ajaxData.ajaxNonce,
			url : ajaxData.currentURL,
			currid : currid
		},
		success: function(message){
			var result = eval('(' + message + ')');
			if ( result.followees != '' ) {
				jQuery("#loadFollowees").removeClass("hiding").addClass("showing");
				jQuery("#loadFollowers").removeClass("showing").addClass("hiding");
				jQuery(".followers").html( result.followees ).fadeIn();
			}
		}
	});
}

/*
* Unfollow single author
*/

function updateUnFollow(activeObject,currid) {
	jQuery.ajax({
		url: ajaxData.ajaxUrl,
		type: 'post',
		data : {
			action : 'tzwrs_unfollow_wrs_djs',
			author_id : activeObject.attr("data-author"),
			nonce : ajaxData.ajaxNonce,
			url : ajaxData.currentURL,
			currid : currid
		},
		beforeSend: function() {
			activeObject.prop("disabled", true);
		},
		success: function(message){
			var result = eval('(' + message + ')');
			if(result.status == 'success' ){
				activeObject.prop("disabled", false);
				jQuery(".dj_follow_case input." + activeObject.attr("data-author")).val("Follow");
				jQuery(".dj_follow_case input." + activeObject.attr("data-author")).removeClass("following").removeClass("dj_following").addClass("dj_follow");
			}
			jQuery(".followers_text").html( result.followers_count );
			bindButtonClick();
		}
	});
}

/*
 * Follow a single author
 */
function updateFollow(activeObject,currid) {

	jQuery.ajax({
		url : ajaxData.ajaxUrl,
		type : 'post',
		data : {
			action : 'tzwrs_follow_wrs_djs',
			author_id : activeObject.attr("data-author"),
			nonce : ajaxData.ajaxNonce,
			url : ajaxData.currentURL,
			currid : currid
		},
		beforeSend: function() {
			activeObject.prop("disabled", true);
		},
		success : function( message ) {
			var result = eval('(' + message + ')');
			if(result.status == 'success' ){
				activeObject.prop("disabled", false);
				jQuery(".dj_follow_case input." + activeObject.attr("data-author")).val("Following");
				jQuery(".dj_follow_case input." + activeObject.attr("data-author")).removeClass("dj_follow").addClass("dj_following").addClass("following");
			}
			jQuery(".followers_text").html( result.followers_count );
			bindButtonClick();
		}
	});
}
    

$jq(document).ready(function(){

    $jq("#subscribeAuthors").on("click",function(){

        var email = $jq("#user_email").val();
        if(emailValidation(email)){
  
            jQuery.ajax({
                type: "post",
                url: ajaxData.ajaxUrl,
                data: "action=subscribe_to_wp_authors&nonce="+ajaxData.ajaxNonce+"&url="+ajaxData.currentURL+"&email="+email,
                success: function(message){
                    var result = eval('(' + message + ')');
                    if(result.error){
                        $jq("#frm_msg").html(result.error);
                        $jq("#frm_msg").addClass("error");
                    }
                    if(result.success){
                        $jq("#frm_msg").html(result.success);
                        $jq("#frm_msg").addClass("success");
                    }
                }
            });
        }
    });

    /*
    * Go to login page
    */
	$jq(document).on("click", '.logged_out', function() {
		window.location.href = $jq(this).data('url');
    });
	
});
