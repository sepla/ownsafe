// Copyright (C) 2015 Sebastian Plaza
// This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
// This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty
// of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
// You should have received a copy of the GNU General Public License along with this program. 
// If not, see http://www.gnu.org/licenses/. 

var clipboardCounter = "";
var start_clipboardCounter = 30;
var clipboardCount=start_clipboardCounter;
var clearClipboardTxt = "";
var popUpCloserTimerS = 2000;
var popUpCloserTimerL = 3000;

//<!-- ----- content ----- -->
$(document).on('pageinit', '#content', function(){
	if (!isApp) $("#contentfooter").css("visibility","hidden");
});

$(document).on('pageshow', '#content', function(){
	
	if (isApp) {

		cordova.plugins.notification.local.schedule({
   		 	title: "OwnSafe",
   		 	text: loggedinTxt,
   		 	actions: [{
        		id: 'logoutact',
        		type: 'button',
	       		title: logoutTxt}],
  		  	foreground: true
		});
		
		cordova.plugins.notification.local.on('logoutact', function (notification, eopts) { logout();});
	}


	if (sessionStorage.getItem("lastlogin")!=="") {
		$('#content_lastlogin').text(sessionStorage.getItem("lastlogin"));
	}

	if (sessionStorage.getItem("loginfailure")!=="") {
		$('#content_loginfailure').text(sessionStorage.getItem("loginfailure"));
		if (sessionStorage.getItem("loginfailure")>0) {
			$('#content_loginfailure_label1').show();
			$('#content_loginfailure_label2').show();
			$('#content_loginfailure').show();
		}
	}
  
	if (sessionStorage.getItem("lastfailedlogin")!=="") {

		$('#content_lastfailedlogin').text(sessionStorage.getItem("lastfailedlogin"));
		if (sessionStorage.getItem("loginfailure")>0) {
			$('#content_lastfailedlogin').css('color', 'red');
			$('#content_lastfailedlogin_label').css('color', 'red');
		}
	}

	try {
	// decrypt
		var key = sessionStorage.getItem("k");
		//console.log("content key: "+key);

		//console.log("Items:"+$( ".entry" ).length);
		$( ".entry" ).each(function( index ) {
			try {
				var iv = $(this).attr('ivvalue');
				var id = $(this).attr('id');
				//console.log("ID: "+id+" TXT:"+$(this).text());
				var prefix = "U2FsdGV";
				if ($(this).text().slice(0, prefix.length) == prefix) {
					var bytes = CryptoJS.AES.decrypt($(this).text().toString(), key, { iv: iv });
					var plaintext = "not encrypted";
					try {
						plaintext = bytes.toString(CryptoJS.enc.Utf8);
					} catch (e) {console.log(e);}
					if (!plaintext) plaintext = "not encrypted";
					//console.log("ID"+id+": "+plaintext);
					$(this).text(plaintext);
				} else {
					//console.log("Unexpected prefix for id "+id+". Expected "+prefix+" - got "+$(this).text().slice(0, prefix.length));
				}
			} catch (e) {
				console.log("Sorry, something has gone wrong");
				console.log(e);
			}
		});

		// sort
		var mylist = $('#ullist');
		try{
			var listitems = mylist.children('li').get();
			listitems.sort(function(a, b) {
				return $(a).text().toUpperCase().localeCompare($(b).text().toUpperCase());
			});
			$.each(listitems, function(idx, itm) { mylist.append(itm); });
		} catch (e) {
			console.log("Sorry, something has gone wrong");
			console.log(e);
		}

		mylist.listview( "refresh" );
		$(".logout").text(count);
		$('#waitForDecode').hide();
		mylist.fadeIn(250);
		if (!isApp) {
			$('#myFilter').focus();
			setTimeout(checkButtonPosition, 300);
		}
	} catch(err) {
		console.log("Sorry, something has gone wrong");
    alert(err.message);
		$("#general_error").popup(("open"), { transition: 'pop' });
		console.log(err.message);
		$('#waitForDecode').hide();
	}
});

function checkButtonPosition() {
	if (!isApp) {
		if ($("#contentfooter").length!=0) {
			try {
					//var docViewTop = $(window).scrollTop();
		    //var docViewBottom = docViewTop + $(window).height();
		    //var elemTop = $("#contentfooter").offset().top;
		    //var docBottom = $(document).scrollTop() + window.innerHeight;
		    //console.log("1 ET:"+elemTop+" UUL:"+ullistBottom);
		    var elemTop = $("#contentfooter").offset().top;
		    if ($("#contentfooter").css("position")=="fixed") {
			    elemTop = $("#contentfooter").offset().top - $("#contentfooter").height();
			  } 
			  //else console.log("War relativ");
	 				var ullistBottom = ($("#ullist").offset().top+$("#ullist").height());
	//        console.log("2 ET:"+elemTop+" UUL:"+ullistBottom);
	//        console.log("DVT:"+docViewTop+" DVB:"+docViewBottom+" ET:"+elemTop+" EB:"+elemBottom+" DB:"+docBottom+" UUL:"+ullistBottom);
					if(ullistBottom > elemTop) {
						$("#contentfooter").css("position", "fixed");
					} else {
						$("#contentfooter").css("position", "relative");
					}
			} catch(err) {console.log(err);}	
			if ($("#contentfooter").css("visibility")=="hidden")	
			$("#contentfooter").css({opacity: 0, visibility: "visible"}).animate({opacity: 1}, 'fast');
		}
	} else {
		setTimeout(checkButtonPosition, 250);
	}
}

//$(document).on('focus', "#myFilter",function(event) {
//	$(".ui-footer").css("position", "relative");
//	$(".ui-footer").css("bottom", "");
//	console.log("focus");
//});

//$(document).on('blur', "#myFilter",function(event) {
//	$(".ui-footer").css("position", "fixed");
//	$(".ui-footer").css("bottom", "0");
//	console.log("blur");
//});


$(document).on('click', "#menu_content",function(event) {
	$.mobile.changePage("#content");
});

//<!-- ----- addentry ----- -->
$(document).on('pageshow', '#addentry', function(){
		if ($("#addfooter").css("visibility")=="hidden")	$("#addfooter").css({opacity: 0, visibility: "visible"}).animate({opacity: 1}, 'fast');
});

$(document).on('pageinit', '#addentry', function(){
	if (!isApp) $("#addfooter").css("position", "relative");
	$("#addfooter").css("visibility","hidden");
	
	
	$(document).on('click', '#new_save', function() { // catch the form's submit event

			if ($('#new_title').val().length > 0) {
				
				if ($("#new_showbutton").val()=="hide" || ($('#new_password').val() == $('#new_passwordconfirm').val())) {
				
				var iv = CryptoJS.lib.WordArray.random(128/8);
                var key = sessionStorage.getItem("k"); 
		        var encrypted = CryptoJS.AES.encrypt($('#new_title').val(), key, { iv: iv });
                var formdata = "title="+encodeURIComponent(encrypted)+"&iv="+encodeURIComponent(iv);
				
				if ($('#new_username').val().length > 0) 
					formdata = formdata + "&usr="+encodeURIComponent(CryptoJS.AES.encrypt($('#new_username').val(), key, { iv: iv }));
				if ($('#new_password').val().length > 0) 
					formdata = formdata + "&pwd="+encodeURIComponent(CryptoJS.AES.encrypt($('#new_password').val(), key, { iv: iv }));
				if ($('#new_url').val().length > 0) 
					formdata = formdata + "&urlentry="+encodeURIComponent(CryptoJS.AES.encrypt($('#new_url').val(), key, { iv: iv }));
				if ($('#new_note').val().length > 0) 
					formdata = formdata + "&note="+encodeURIComponent(CryptoJS.AES.encrypt($('#new_note').val(), key, { iv: iv }));

				$.ajax({url: sessionStorage.getItem('OwnSafeServerUrl')+'/src/addentry.php',
				data: formdata,
				dataType: 'json',
				type: 'post',                  
				async: false,
				beforeSend: function() {$.mobile.loading("show");},
				complete: function() {$.mobile.loading("hide");},
				success: function (result) {
					if(result.status) {
						$("#new_addingSuccess").bind({
							popupafterclose: function(event, ui) {
									event.preventDefault();
									event.stopImmediatePropagation();
									$('#content').load(sessionStorage.getItem('OwnSafeServerUrl')+'/src/page_content.php', function() {
										$('#content').trigger('create');
										$.mobile.changePage("#content");
										$("#ullist").listview("refresh");
										$("#add_entry_form").get(0).reset();
									});
									
							}
						});
						$("#new_addingSuccess").popup(("open"), { transition: 'pop'});
						popupCloser($("#new_closeAddingSuccess"),$('#new_addingSuccess'),popUpCloserTimerS);
					} else {
						if (result.message=="InvalidSession") {
							$("#new_sessionError").popup(("open"), { transition: 'pop' });
							console.log(result.message);
							setTimeout(logout, 3000);
						} else {
							$("#new_error").popup(("open"), { transition: 'pop' });
							console.log(result.message);
						}
					}
				},
				error: function (jqXHR, exception) {
					$("#new_error").popup(("open"), { transition: 'pop'});
					console.log("Error: "+getjqXHRMessage(jqXHR,exception));
				}
			});   
		} else $("#new_passCheck").popup(("open"), { transition: 'pop' });
	} else $("#new_fillFields").popup(("open"), { transition: 'pop' });

	return false;
	});   
	
});


//<!-- ----- showentry ----- -->
$(document).on('pageshow', '#showentry', function(){
		if ($("#showfooter").css("visibility")=="hidden")	$("#showfooter").css({opacity: 0, visibility: "visible"}).animate({opacity: 1}, 'fast');
});

$(document).on('pageinit', '#showentry', function(){ 
	if (!isApp)	$("#showfooter").css("position", "relative");
	$("#showfooter").css("visibility","hidden");

	clearClipboardTxt = $('#show_clearclipboard_anchor').html();

	//<!-- ----- updateentry ----- -->
    
	$(document).on('click', '#show_save', function() { 

            if ($('#show_title').val().length > 0) {
				
				if ($("#show_showbutton").val()=="hide" || ($('#show_password').val() == $('#show_passwordconfirm').val())) {
					var key = sessionStorage.getItem("k");
					var iv = $('#show_iv').val();
                    var formdata = "id="+$('#show_id').val()+"&title="+encodeURIComponent(CryptoJS.AES.encrypt($('#show_title').val(), key, { iv: iv }));
					try {						
						if ($('#show_username').val().length > 0) formdata = formdata + "&usr="+encodeURIComponent(CryptoJS.AES.encrypt($('#show_username').val(), key, { iv: iv }));
						if ($('#show_password').val().length > 0) formdata = formdata + "&pwd="+encodeURIComponent(CryptoJS.AES.encrypt($('#show_password').val(), key, { iv: iv }));
						if ($('#show_url').val().length > 0) formdata = formdata + "&urlentry="+encodeURIComponent(CryptoJS.AES.encrypt($('#show_url').val(), key, { iv: iv }));
						if ($('#show_note').val().length > 0) formdata = formdata + "&note="+encodeURIComponent(CryptoJS.AES.encrypt($('#show_note').val(), key, { iv: iv }));
					} catch (e) {
						console.log(e);
					}						
								$.ajax({url: sessionStorage.getItem('OwnSafeServerUrl')+'/src/updateentry.php',
								data: formdata,
								dataType: 'json',
								type: 'post',                  
								async: false,
								beforeSend: function() {$.mobile.loading("show");},
								complete: function() {$.mobile.loading("hide");},
								success: function (result) {
									if (result.status) {
										
										$("#show_updateSuccess").on({
											popupafterclose: function(event, ui) {
													event.preventDefault();
													event.stopImmediatePropagation();
													$('#content').load(sessionStorage.getItem('OwnSafeServerUrl')+'/src/page_content.php', function() {
														$('#content').trigger('create');
														$.mobile.changePage("#content");
														$("#show_entry_form").get(0).reset();
													});

											}
										});
										$("#show_updateSuccess").popup(("open"), { transition: 'pop'});
										popupCloser($("#show_closeUpdateSuccess"),$('#show_updateSuccess'),popUpCloserTimerS);
									} else {
										if (result.message=="InvalidSession") {
											$("#show_sessionError").popup(("open"), { transition: 'pop' });
											console.log(result.message);
											setTimeout(logout, 3000);
										} else {
											$("#show_error").popup(("open"), { transition: 'pop' });
											console.log(result.message);
										}
									}
								},
								error: function (jqXHR, exception) {
									$("#show_error").popup(("open"), { transition: 'pop'});
									console.log("Error: "+getjqXHRMessage(jqXHR,exception));
								}
							});   

				} else $("#show_passCheck").popup(("open"), { transition: 'pop' });
			} else $("#show_fillFields").popup(("open"), { transition: 'pop' });

		return false;
	});   
	


	//<!-- ----- delentry ----- -->
 	
	$(document).on('click', '#show_popup_delete', function() {

				var formdata = "id="+$('#show_id').val();
				$.ajax({url: sessionStorage.getItem('OwnSafeServerUrl')+'/src/delentry.php',
				data: formdata,
				dataType: 'json',
				type: 'post',                  
				async: false,
				beforeSend: function() {$.mobile.loading("show");},
				complete: function() {$.mobile.loading("hide");},
				success: function (result) {
					if(result.status) {
						$('#content').load(sessionStorage.getItem('OwnSafeServerUrl')+'/src/page_content.php', function() {
							$('#content').trigger('create');
							$.mobile.changePage("#content");
							$("#show_entry_form").get(0).reset();
						});
					} else {
						if (result.message=="InvalidSession") {
							$("#show_updateFailed").popup(("open"), {transition: 'pop'});	
							console.log(result.message);
							setTimeout(logout, 3000);
						} else {
							$("#show_error").popup(("open"), { transition: 'pop' });
							console.log(result.message);
						}
					}
				},
				error: function (jqXHR, exception) {
					$("#show_error").popup(("open"), { transition: 'pop' });
					console.log("Error: "+getjqXHRMessage(jqXHR,exception));
				}
			});   

	return false; 
	});   
});


$(document).on('click', ".entry",function(event) {

		$.ajax({url: sessionStorage.getItem('OwnSafeServerUrl')+'/src/showentry.php',
		data: "id="+$(this).attr("id"),
		dataType: 'json',
		type: 'post',                  
		beforeSend: function() {$.mobile.loading("show");},
		complete: function() {$.mobile.loading("hide");},
		success: function (result) {
			event.preventDefault();
			event.stopImmediatePropagation();
			if(result.status) {
				var data = {id: result.id, title: result.title, user: result.user, password: result.password, urlentry: result.urlentry, note: result.note, iv: result.iv, counter: result.counter}; 
				var isValuesSet = setShowEntryValues(data);
				if (isValuesSet) $.mobile.changePage('#showentry');
				else $("#general_error").popup(("open"), { transition: 'pop' });

			} else {
				if (result.message=="InvalidSession") {
					$("#session_error").popup(("open"), { transition: 'pop' });
					console.log(result.message);
					setTimeout(logout, 3000);
				} else {
					$("#general_error").popup(("open"), { transition: 'pop' });
					console.log(result.message);
				}
			}
		},
		error: function (jqXHR, exception) {
			$("#general_error").popup(("open"), { transition: 'pop'});
			console.log("Error: "+getjqXHRMessage(jqXHR,exception));
		}
	});
});


function setShowEntryValues(data) {
	var isNoError = true;
	resetTimer();
	
	$('#show_headline_show').show();
	$('#show_headline_edit').hide();
	
	$('#show_entry_form').get(0).reset();
        var key = sessionStorage.getItem("k"); 
        var iv = data.iv;
        if (data.iv!=="") $('#show_iv').val(iv);
        if (data.id!=="") $('#show_id').val(data.id);
      
		try {
			if (data.counter!=="") {
	  			$('#show_counter').html("("+data.counter+")");
	  			$('#show_counter').show();
			}
			
			if (data.title!=="") {
				$('#show_title').val(CryptoJS.AES.decrypt(data.title, key, { iv: iv }).toString(CryptoJS.enc.Utf8));
				$('#show_title_txt').html($('#show_title').val());
				$('#show_title').parent().hide();
				$('#show_title_txt').show();
			}
			
			if (data.user!==""){
				$('#show_username').val(CryptoJS.AES.decrypt(data.user, key, { iv: iv }).toString(CryptoJS.enc.Utf8));
				$('#show_username_txt').html($('#show_username').val());
				$('#show_username_txt').show();
				$('#show_copyuser').show();
				$('#show_username_label').show();
				$('#show_username').parent().hide();
			} else {
				$('#show_username').val('');
				$('#show_username_txt').html("");
				$('#show_username_txt').hide();
				$('#show_copyuser').hide();
				$('#show_username_label').hide();
				$('#show_username').parent().hide();
			}
			
			if (data.password!=="") {
				var pas = CryptoJS.AES.decrypt(data.password, key, { iv: iv }).toString(CryptoJS.enc.Utf8);
				$('#show_password').val(pas);
				$('#show_password_txt').html("&bull; &bull; &bull; &bull; &bull; &bull;");
				$('#show_password_txt').show();
				$('#show_copypass').show();
				$('#show_showbutton').show();
				$('#show_password_label').show();
				$('#show_passwordconfirm').val(pas);
				$('#show_passwordconfirm').parent().hide();
				$('#show_passwordconfirm_label').hide();
				$('#show_password').parent().hide();	
				$('#show_password_txt').removeClass('showPasswordColor');
				$('#show_password').css("color", "black");
				$('#show_passwordconfirm').css("color", "black");
			} else {
				$('#show_password').val('');
				$('#show_password_txt').html('');
				$('#show_password_txt').hide();
				$('#show_copypass').hide();
				$('#show_showbutton').hide();
				$('#show_password_label').hide();
				$('#show_passwordconfirm').val('');
				$('#show_passwordconfirm').parent().hide();
				$('#show_passwordconfirm_label').hide();
				$('#show_password').parent().hide();
			}
			
			if (data.urlentry!=="") {
				var url = CryptoJS.AES.decrypt(data.urlentry, key, { iv: iv }).toString(CryptoJS.enc.Utf8);
                $('#show_url_href').attr('href',url);
				$('#show_url_href').html(url);
                $('#show_url').val(url);
				$('#show_copyurl').show();
                $('#show_url_href').show();
                $('#show_url_txt').show();
				$('#show_url_label').show();
				$('#show_url').parent().hide();
			} else {
				$('#show_url_href').attr('href','');
				$('#show_url_href').html('');
				$('#show_url').val('');
				$('#show_copyurl').hide();
				$('#show_url_href').hide();
				$('#show_url_txt').hide();
				$('#show_url_label').hide();
				$('#show_url').parent().hide();
			}
			
			if (data.note!=="") {
				var note = CryptoJS.AES.decrypt(data.note, key, { iv: iv }).toString(CryptoJS.enc.Utf8);
				$('#show_note').val(note);
				var noteFormated = note.replace(/(?:\r\n|\r|\n)/g, '<br />');
				$('#show_note_txt').html(noteFormated);
				$('#show_note').hide();
				$('#show_copynote').show();
				$('#show_note_txt').show();
				$('#show_note_label').show();
			} else {
				$('#show_note').val('');
				$('#show_note_txt').html('');
				$('#show_note').hide();
				$('#show_copynote').hide();
				$('#show_note_txt').hide();
				$('#show_note_label').hide();
			}
			
		} catch (e) {
			console.log(e);
			isNoError=false;
		}
		
		$('#show_edit').show();
		$('#show_delete').hide();
		$('#show_save').hide(); 
		$('#show_genpass').hide();
		$('#show_formkind').val("show");
		
		$('#show_headline').removeClass('ui-icon-edit');
		$('#show_headline').addClass('ui-icon-bullets');

        //$('#show_controlgroup').hide();
        $('#show_headline').text('Show entry');
        $('#show_showbutton').removeClass('ui-btn-active');
        $("#show_showbutton").val("show");
        $('#show_showbutton').css('margin-top','0px');

		
	if (sessionStorage.getItem('clearClipboard') && sessionStorage.getItem('clearClipboard') === "1")  { 
		$('#show_clearclipboard').show();
        }
        return isNoError;
}


//<!-- ----- profile ----- -->

$(document).on('click', "#menu_profile",function(event) {

		$.ajax({url: sessionStorage.getItem('OwnSafeServerUrl')+'/src/getprofile.php',
		dataType: 'json',
		async: false,
		beforeSend: function() {$.mobile.loading("show");},
		complete: function() {$.mobile.loading("hide");},
		success: function (result) {
			if(result.status) {
				if (result.username) $('#profile_username').text(result.username);
				else $('#profile_username').text("");
				if (result.email) $('#profile_email').val(result.email);
				else $('#profile_email').val("");
				if (result.hint) $('#profile_hint').val(result.hint);
				else $('#profile_hint').val("");
				$.mobile.changePage('#profile'); 
			} else {
				if (result.message=="InvalidSession") {
					$("#session_error").popup(("open"), { transition: 'pop' });
					console.log(result.message);
					setTimeout(logout, 3000);
				} else {
					$("#general_error").popup(("open"), { transition: 'pop' });
					console.log(result.message);
				}
			}
		},
		error: function (jqXHR, exception) {
			$("#general_error").popup(("open"), { transition: 'pop'});
			console.log("Error: "+getjqXHRMessage(jqXHR,exception));
		}
	});
});


//<!-- ----- updateprofile ----- -->


$(document).on('pageinit', '#profile', function(){ 
	if (!isApp)	$("#profilefooter").css("position", "relative");
	$("#profilefooter").css("visibility","hidden");

 	$(document).on('click', '#profile_save', function() { // catch the form's submit event

	if (($('#profile_email').val().length > 0 && !isValidEmailAddress($('#profile_email').val())) || ($('#profile_hint').val().length>0 && $('#profile_email').val().length==0)) {
			$("#profile_emailFailed").popup(("open"), { transition: 'pop'});
	
		} else  {
				
			var formdata = "";

			if ($('#profile_email').val().length>0) {
				if (formdata === "") formdata = "email="+encodeURIComponent($('#profile_email').val());
				else formdata = formdata+"&email="+encodeURIComponent($('#profile_email').val());
			}
			if ($('#profile_hint').val().length>0) {
				if (formdata === "") formdata = "hint="+encodeURIComponent($('#profile_hint').val());
				else formdata = formdata+"&hint="+encodeURIComponent($('#profile_hint').val());	
			}
										
				$.ajax({url: sessionStorage.getItem('OwnSafeServerUrl')+'/src/updateprofile.php',
				data: formdata,
				dataType: 'json',
				type: 'post',                  
				async: false,
				beforeSend: function() {$.mobile.loading("show");},
				complete: function() {$.mobile.loading("hide");},
				success: function (result) {
					if (result.status) {

						$("#profile_updateSuccess").on({
							popupafterclose: function(event, ui) {
									event.preventDefault();
									event.stopImmediatePropagation();
									$('#content').load(sessionStorage.getItem('OwnSafeServerUrl')+'/src/page_content.php', function() {
										$('#content').trigger('create');
										$.mobile.changePage("#content");
												$('#profile_username').text("");
												$('#profile_email').val("");
												$('#profile_hint').val("");
									});
							}
						});
						$("#profile_updateSuccess").popup(("open"), { transition: 'pop'});
						popupCloser($("#profile_closeUpdateSuccess"),$('#profile_updateSuccess'),popUpCloserTimerS);
					} else {
						if (result.message=="InvalidSession") {
							$("#profile_sessionError").popup(("open"), { transition: 'pop' });
							console.log(result.message);
							setTimeout(logout, 3000);
						} else {
							$("#profile_error").popup(("open"), { transition: 'pop' });
							console.log(result.message);
						}
					}
				},
				error: function (jqXHR, exception) {
					$("#profile_error").popup(("open"), { transition: 'pop'});
					console.log("Error: "+getjqXHRMessage(jqXHR,exception));
				}
			});   
		} 

		return false;
	});   
	
});



//<!-- ----- delete profile ----- -->

$(document).on('pageinit', '#profile', function(){ 
		
        $(document).on('click', '#profile_popup_delete', function() {
					
					$.ajax({url: sessionStorage.getItem('OwnSafeServerUrl')+'/src/delaccount.php',
					dataType: 'json',
					type: 'post',                  
					async: false,
					beforeSend: function() {$.mobile.loading("show");},
					complete: function() {$.mobile.loading("hide");},
					success: function (result) {
						if(result.status) {
							console.log("Account deleted...");
							logout();
						} else {
							if (result.message=="InvalidSession") {
								$("#profile_sessionError").popup(("open"), { transition: 'pop' });
								console.log(result.message);
								setTimeout(logout, 3000);
							} else if (result.message == "noemail") {
									$("#profile_emailFailed").popup(("open"), { transition: 'pop'});
							} else {
								$("#profile_error").popup(("open"), { transition: 'pop' });
								console.log(result.message);
							}
						}
					},
					error: function (jqXHR, exception) {
						$("#profile_error").popup(("open"), { transition: 'pop'});
						console.log("Error: "+getjqXHRMessage(jqXHR,exception));
					}
				});   

		return false;
		});   
	
});



//<!-- ----- settings ----- -->

$(document).on('click', "#menu_settings",function(event) {

		$.ajax({url: sessionStorage.getItem('OwnSafeServerUrl')+'/src/getsettings.php',
		dataType: 'json',
		async: false,
		beforeSend: function() {$.mobile.loading("show");},
		complete: function() {$.mobile.loading("hide");},
		success: function (result) {
			if(result.status) {
				var data = { logouttime: result.logouttime, passgenlength: result.passgenlength, protocollength: result.protocollength, loginnotify: result.loginnotify};
				setSettingsValues(data);
				$.mobile.changePage('#settings'); 
			} else {
				if (result.message=="InvalidSession") {
					$("#session_error").popup(("open"), { transition: 'pop' });
					console.log(result.message);
					setTimeout(logout, 3000);
				} else {
					$("#general_error").popup(("open"), { transition: 'pop' });
					console.log(result.message);
				}
			}
		},
		error: function (jqXHR, exception) {
			$("#general_error").popup(("open"), { transition: 'pop'});
			console.log("Error: "+getjqXHRMessage(jqXHR,exception));
		}
	});
});

function setSettingsValues(data) {

        if (data.logouttime) {
            $('#settings_logout_slider').val(data.logouttime);
            sessionStorage.setItem("logouttime", data.logouttime);
        }

        if (data.passgenlength) {
            $('#settings_passgen_slider').val(data.passgenlength);
            sessionStorage.setItem("passgenlength", data.passgenlength);
        }
		if (data.protocollength) {
            $('#settings_loginprotocol_slider').val(data.protocollength);
            sessionStorage.setItem("protocollength", data.protocollength);
        }
	
		 if (sessionStorage.getItem("loginnotify"), (sessionStorage.getItem("loginnotify")=="1" || sessionStorage.getItem("loginnotify")=="3")) {
			$('#login_email').prop('checked',true).checkboxradio('refresh');
			
        } else {
			$('#login_email').prop('checked',false).checkboxradio('refresh');
		}

		if (sessionStorage.getItem("loginnotify") && (sessionStorage.getItem("loginnotify")=="2" || sessionStorage.getItem("loginnotify")=="3")) {
			$("#failedlogin_email").prop('checked',true).checkboxradio('refresh');
        } else {
			$('#failedlogin_email').prop('checked',false).checkboxradio('refresh');
		}
	

		$('.slider').slider('refresh');
		
		$('#settings_url').val(sessionStorage.getItem("OwnSafeServerUrl"));
		if (!isApp) $('#settings_serverUrl').hide();
		
		$('#settings_language').html(languageChoiceString);	
		$("#settings_language").val(sessionStorage.getItem("OwnSafeLanguage"));
		$('#settings_language').selectmenu('refresh');
		
}

//<!-- ----- updatesettings ----- -->

$(document).on('pageshow', '#settings', function(){ 
					if ($('#login_email').is(":checked")) {
							sessionStorage.setItem("login_email", "true");
							$('#login_email').prop("checked",true).checkboxradio('refresh');
					} else {
						sessionStorage.setItem("login_email", "false");
						$('#login_email').prop("checked",false).checkboxradio('refresh');
					}
					if ($('#failedlogin_email').is(":checked")) {
							sessionStorage.setItem("failedlogin_email", "true");
							$('#failedlogin_email').prop("checked",true).checkboxradio('refresh');
					} else {
						sessionStorage.setItem("failedlogin_email", "false");
						$('#failedlogin_email').prop("checked",false).checkboxradio('refresh');
					}


});

$(document).on('pageinit', '#settings', function(){ 
	if (!isApp)	$("#settingsfooter").css("position", "relative");
	$("#settingsfooter").css("visibility","hidden");

	
	$(document).on('click', '#settings_save', function() { // catch the form's submit event
				
			var formdata = "";

			if ($('#settings_logout_slider').val()==="") formdata = formdata+"logout=99";
			else formdata = formdata+"logout="+$('#settings_logout_slider').val();
			
			if ($('#settings_passgen_slider').val()==="") formdata = formdata+"&passgen=16";
			else formdata = formdata+"&passgen="+$('#settings_passgen_slider').val();

			if ($('#settings_loginprotocol_slider').val()==="") formdata = formdata+"&loginprotocol=1";
			else formdata = formdata+"&loginprotocol="+$('#settings_loginprotocol_slider').val();


			if (!$('#login_email').is(":checked") && !$('#failedlogin_email').is(":checked")) {
				loginnotify=0;
			} else if ($('#login_email').is(":checked") && !$('#failedlogin_email').is(":checked")) {
				loginnotify=1;
			} else if (!$('#login_email').is(":checked") && $('#failedlogin_email').is(":checked")) {
				loginnotify=2;
			} else if ($('#login_email').is(":checked") && $('#failedlogin_email').is(":checked")) {
				loginnotify=3;
			}
			sessionStorage.setItem("loginnotify", loginnotify);		

			formdata = formdata+"&loginnotify="+loginnotify;
			
			
			$.ajax({url: sessionStorage.getItem('OwnSafeServerUrl')+'/src/updatesettings.php',
			data: formdata,
			dataType: 'json',
			type: 'post',                  
			async: false,
			beforeSend: function() {$.mobile.loading("show");},
			complete: function() {$.mobile.loading("hide");},
			success: function (result) {
				if (result.status) {
					if ($('#settings_logout_slider').val()==="")  sessionStorage.setItem("logouttime", "99");
					else sessionStorage.setItem("logouttime", $('#settings_logout_slider').val());
					if ($('#settings_passgen_slider').val()==="") sessionStorage.setItem("passgenlength", "16");
					else sessionStorage.setItem("passgenlength",$('#settings_passgen_slider').val());
					if ($('#settings_loginprotocol_slider').val()==="") sessionStorage.setItem("protocollength", "1");
					else sessionStorage.setItem("protocollength",  $('#settings_loginprotocol_slider').val());
					
					if ($('#login_email').is(":checked")) sessionStorage.setItem("login_email", "true");
					else sessionStorage.setItem("login_email", "false");
					if ($('#failedlogin_email').is(":checked")) sessionStorage.setItem("failedlogin_email", "true");
					else sessionStorage.setItem("failedlogin_email", "false");

					$("#settings_updateSuccess").on({	
						popupafterclose: function(event, ui) {
								event.preventDefault();
								event.stopImmediatePropagation();
								$('#loginprotocol').load(sessionStorage.getItem('OwnSafeServerUrl')+'/src/page_loginprotocol.php?period='+sessionStorage.getItem("protocollength"),	function() {
									$('#loginprotocol').trigger('create');
								});
								$('#content').load(sessionStorage.getItem('OwnSafeServerUrl')+'/src/page_content.php',function() {
									$('#content').trigger('create');
									$.mobile.changePage("#content");
								});
						}
					});
					$("#settings_updateSuccess").popup(("open"), { transition: 'pop'});
					popupCloser($("#settings_closeUpdateSuccess"),$('#settings_updateSuccess'),popUpCloserTimerL);
				} else {
					if (result.message=="InvalidSession") {
						$("#settings_sessionError").popup(("open"), { transition: 'pop' });
						console.log(result.message);
						setTimeout(logout, 3000);
					} else {
						$("#settings_error").popup(("open"), { transition: 'pop' });
						console.log(result.message);
					}
				}
			},
			error: function (jqXHR, exception) {
				$("#settings_error").popup(("open"), { transition: 'pop'});
				console.log("Error: "+getjqXHRMessage(jqXHR,exception));
			}
		});   

	});   
	
});


//<!-- ----- changepass ----- -->


$(document).on('pageinit', '#changepass', function(){ 
		if (!isApp)	$("#changepassfooter").css("position", "relative");
		$("#changepassfooter").css("visibility","hidden");

			
    $(document).on('click', '#changepass_save', function() { // catch the form's submit event

		if ($('#changepass_curpassword').val().length > 0 && $('#changepass_newpassword').val().length > 0 && $('#changepass_newpasswordconfirm').val().length > 0 && $('#changepass_username').val().length > 0){
			if ($('#changepass_curpassword').val() != $('#changepass_newpassword').val() || $('#changepass_username').val() != sessionStorage.getItem('username')){
				if ($('#changepass_newpassword').css('color') != "rgb(255, 0, 0)" && $('#changepass_newpassword').css('color') != "rgb(0, 0, 0)"){
					if ($('#changepass_newpassword').val() == $('#changepass_newpasswordconfirm').val()) {
						var formdata = "usr="+sessionStorage.getItem('username')+"&pwd="+CryptoJS.SHA256( ($('#changepass_curpassword').val() + sessionStorage.getItem('username').toLowerCase()) );
						
							$.ajax({url: sessionStorage.getItem('OwnSafeServerUrl')+'/src/check-pass.php',
							data: formdata,
							dataType: 'json',
							type: 'post',                  
							async: false,
							beforeSend: function() {$.mobile.loading("show");},
							complete: function() {$.mobile.loading("hide");},
							success: function (result) {
								if(result.status) {
									var cp_result = null;
									$("#changepass_wait").on({
										popupafterclose: function(event, ui) {
											event.preventDefault();
											event.stopImmediatePropagation();
											if (cp_result !== false) {
												$("#changepass_updateSuccess").on({
													popupafterclose: function(event, ui) {
														event.preventDefault();
														event.stopImmediatePropagation();
														$('#content').load(sessionStorage.getItem('OwnSafeServerUrl')+'/src/page_content.php',function(){
															$('#content').trigger('create');
															$.mobile.changePage("#content");
															$("#changepass_form").get(0).reset();
														});
													}
												});
												$("#changepass_updateSuccess").popup(("open"), { transition: 'pop' });
												popupCloser($("#changepass_closeUpdateSuccess"),$('#changepass_updateSuccess'),popUpCloserTimerL);
											} else {
												console.log(result.message);
												$("#changepass_error").popup(("open"), { transition: 'pop' });
											}	
										}
									});
									$("#changepass_wait").on({
										popupafteropen: function(event, ui) {
											cp_result = changePassword();
											$("#changepass_wait").popup("close");
										}
									});
									$("#changepass_wait").popup(("open"), { transition: 'pop' });

								} else if (result.message == "wrong pass") {
									$("#changepass_currentPassFailed").popup(("open"), { transition: 'pop'});
									//$("#changepass_curpassword").focus();

								} else {
									if (result.message=="InvalidSession") {
										$("#changepass_sessionError").popup(("open"), { transition: 'pop' });
										console.log(result.message);
										setTimeout(logout, 3000);
									} else {
										$("#changepass_error").popup(("open"), { transition: 'pop' });
										console.log(result.message);
									}
								}
							},
							error: function (jqXHR, exception) {
								$("#changepass_error").popup(("open"), { transition: 'pop'});
								console.log("Error: "+getjqXHRMessage(jqXHR,exception));
							}
						});   
					} else $("#changepass_passCheck").popup(("open"), { transition: 'pop' });
				} else $("#changepass_passSecurityCheck").popup(("open"), { transition: 'pop' });
			} else $("#changepass_oldnewCheck").popup(("open"), { transition: 'pop' });	
		} else $("#changepass_fillFields").popup(("open"), { transition: 'pop' });

		return false;
		});   
	
});

//<!-- ----- export ----- -->


$(document).on('pageinit', '#export', function(){ 
	$('#export_password').val("");
	if (!isApp)	$("#exportfooter").css("position", "relative");
	$("#exportfooter").css("visibility","hidden");
	
    $(document).on('click', '#export_export', function() { 

		if ($('#export_password').val().length > 0){
			
				var formdata = "usr="+sessionStorage.getItem('username')+"&pwd="+CryptoJS.SHA256( ($('#export_password').val() + sessionStorage.getItem('username').toLowerCase()) );
				$.ajax({url: sessionStorage.getItem('OwnSafeServerUrl')+'/src/check-pass.php',
				data: formdata,
				dataType: 'json',
				type: 'post',                  
				beforeSend: function() {$.mobile.loading("show");},
				complete: function() {$.mobile.loading("hide");},
				success: function (result) {
					if(result.status) {
						entriesCSV = exportEntries();
						
						if (entriesCSV != "noentries") {
							
							$("#export_Success").on({
								popupafterclose: function(event, ui) {
									
									var csvString = entriesCSV;
									var fileName = new Date().toISOString().substring(0, 10)+'_OwnSafeExport.csv';
									var fileDir = 'Ownsafe';
									var filePath = "";
									if (isApp) filePath = cordova.file.externalRootDirectory;
									
									if ( !isApp ) {
										 
   										var arraybuffer = new ArrayBuffer(csvString.length);
    									var view = new Uint8Array(arraybuffer);
    									for (var i=0; i<csvString.length; i++) {
        									view[i] = csvString.charCodeAt(i) & 0xff;
    									}
										var a = document.createElement('a');
										var blob = new Blob([arraybuffer], {type: 'application/octet-stream'});
										var url = (window.webkitURL || window.URL).createObjectURL(blob);
										a.href = url;
										//a.href          = 'data:attachment/csv,' + csvString;
										a.target        = '_blank';
										a.download      = fileName;

										document.body.appendChild(a);
										a.click();
										document.body.removeChild(a);
										event.preventDefault();
										event.stopImmediatePropagation();
										$.mobile.changePage("#content");
										
									} else {
										document.addEventListener("deviceready", onDeviceReady, true);
									}
									function onDeviceReady() {
										window.resolveLocalFileSystemURL(cordova.file.externalRootDirectory, function (dirEntry) {
												 dirEntry.getDirectory(fileDir, { create: true }, function (dirEntry) {
 														dirEntry.getFile(fileName, { create: true }, function (fileEntry) {
															console.log("Create the file: " + fileEntry.fullPath);
														});
														createFile(dirEntry, fileName, false);
											 	},fail);
										},fail);
									}

									function createFile(dirEntry, name, isAppend) {
										// Creates a new file or returns the file if it already exists.
											dirEntry.getFile(name, {create: true, exclusive: false}, function(fileEntry) {
											writeFile(fileEntry, csvString, isAppend);
										}, fail);

									}
									function writeFile(fileEntry, dataObj) {
										// Create a FileWriter object for our FileEntry (log.txt).
										fileEntry.createWriter(function (fileWriter) {
											
											fileWriter.onerror = function (e) {
												console.log("Failed file write: " + e.toString());
												alert("Failed file write: " + e.toString());
												$("#export_error").popup(("open"), { transition: 'pop' });
											};

											// If data object is not passed in, create a new Blob instead.
											if (!dataObj) {
												dataObj = new Blob([csvString], { type: 'text/plain' });
											}
											fileWriter.write(dataObj);
											fileWriter.onwriteend = function(evt) {
												var filePath = fileEntry.fullPath;
												//var fileName = fileEntry.name;
												$("#export_fileName").html("SD-Card:<br>"+filePath);
												console.log('File contents have been written:' + filePath + fileName);
												$("#export_fullPath").popup(("open"), { transition: 'pop' });
											};
										});
									}
		
									function fail(error) {
										console.log("ERROR: "+error.toString());
										alert("ERROR: "+error.toString());
										$("#export_error").popup(("open"), { transition: 'pop' });
									}							
								}
                            });
                            
                            $("#export_fullPath").on({	
									popupafterclose: function(event, ui) {
										$.mobile.changePage("#content");
									}
                            });
                            
							$("#export_Success").popup(("open"), { transition: 'pop' });
						
						} else if (entriesCSV == "noentries") {
							$("#export_noentries").popup(("open"), { transition: 'pop' });
						
						} else {
							$("#export_error").popup(("open"), { transition: 'pop' });
						}

					} else if (result.message == "wrong pass") {
                        $("#export_passwordFailed").popup(("open"), { transition: 'pop'});
                        //$("#export_password").focus();
						
					} else {
						if (result.message=="InvalidSession") {
							$("#export_sessionError").popup(("open"), { transition: 'pop' });
							console.log(result.message);
							setTimeout(logout, 3000);
						} else {
							$("#export_error").popup(("open"), { transition: 'pop' });
							console.log(result.message);
						}
					}
				},
				error: function (jqXHR, exception) {
					$("#export_error").popup(("open"), { transition: 'pop'});
					console.log("Error: "+getjqXHRMessage(jqXHR,exception));
				}
			});   
					
		} else $("#export_fillFields").popup(("open"), { transition: 'pop' });

		return false;
		});   
});



//<!-- ----- import ----- -->

$(document).on('pageinit', '#import', function(){ 
	if (isApp) $('#browser_file_chooser').hide();
	else $('#app_file_chooser').hide();
	$('#import_file_text').val("");
	if (!isApp)	$("#importfooter").css("position", "relative");
	$("#importfooter").css("visibility","hidden");

	
	$("#import_progress").html("");
	$(document).on('click', '#import_file_button', function(e) { 
			if (isApp) {
				window.plugins.mfilechooser.open([], function (uri) {
    				$('#import_file_text').val(uri);
				});
			}
	});
	
    $(document).on('click', '#import_import', function(evt) {
        function getFile() {
			window.resolveLocalFileSystemURI(import_file_text, readFile,fail);
		}
		function readFile(fileEntry) {
			fileEntry.file(function (file) {
                var reader = new FileReader();
	            reader.onloadend = function() {
				    var lines = this.result.split('\n');
				    importLines(lines);
				};
				reader.readAsText(file);
            }, fail);
		}
        function fail(err) {
				console.log(err.toString());
				alert.log(err.toString());
				$("#import_error").popup(("open"), { transition: 'pop' });	
		}
        function importLines(lines) {
					if (lines.length>0) {
						var entrieslength = 0;
						var successlength = 0;
						var errorlength = 0;
						var errormessage = "";
							for(var line = 0; line < lines.length; line++){
							if (lines[line]!==null && lines[line].length>0 && lines[line]!="undefined") {
								entrieslength++;
								$("#import_progress").html(entrieslength);

								var entries = lines[line].split(';');
								if (entries.length>0 && entries.length<=5 && entries!==null && entries!="undefined" && entries[0]!==null && entries[0].length>0 && typeof entries[0] == "string") {

									var encrypted = CryptoJS.AES.encrypt(entries[0], key, { iv: iv });
									var formdata = "title="+encodeURIComponent(encrypted)+"&iv="+encodeURIComponent(iv);

									if (entries[1] && entries[1].length > 0) 
										formdata = formdata + "&usr="+encodeURIComponent(CryptoJS.AES.encrypt(entries[1], key, { iv: iv }));
									if (entries[2] && entries[2].length > 0) 
										formdata = formdata + "&pwd="+encodeURIComponent(CryptoJS.AES.encrypt(entries[2], key, { iv: iv }));
									if (entries[3] && entries[3].length > 0) 
										formdata = formdata + "&urlentry="+encodeURIComponent(CryptoJS.AES.encrypt(entries[3], key, { iv: iv }));
									if (entries[4] && entries[4].length > 0) {
										entries[4]=entries[4].replace(/(<br\/>)/g, '\n');
										formdata = formdata + "&note="+encodeURIComponent(CryptoJS.AES.encrypt(entries[4], key, { iv: iv }));
									}
									$.ajax({url: sessionStorage.getItem('OwnSafeServerUrl')+'/src/addentry.php',
                                        data: formdata,
                                        dataType: 'json',
                                        type: 'post',                  
                                        async: false,
                                        beforeSend: function() {},
                                        complete: function() {},
                                        success: function (result) {
											if(result.status) {successlength++;}
											else if (result.message=="InvalidSession") {
												$("#import_sessionError").popup(("open"), { transition: 'pop' });
												console.log(result.message);
												setTimeout(logout, 3000);
											}
										},
                                        error: function (jqXHR, exception) {
                                            $("#import_error").popup(("open"), { transition: 'pop'});
                                            console.log("Error: "+getjqXHRMessage(jqXHR,exception));
                                        }
									});

									//console.log(formdata);
								} else {
									console.log("ERROR: Invalid data: line "+(line+1));
									errormessage=errormessage+"Invalid data: line "+(line+1)+"<br>";
									errorlength++;
								}
							}
						}
						$("#import_success").bind({
							popupafterclose: function(event, ui) {
								event.preventDefault();
								event.stopImmediatePropagation();
								$('#content').load(sessionStorage.getItem('OwnSafeServerUrl')+'/src/page_content.php', function() { 
									$('#content').trigger('create');
									$.mobile.changePage("#content");
									$("#ullist").listview("refresh");
									$("#import_file").val("");
								});
							}
						});

						$("#import_count").html(entrieslength);
						$("#import_countsuccessfull").html(successlength);
						$("#import_counterror").html(errorlength);
						if (errorlength!==0) {
							if (errormessage.length>100) errormessage = errormessage.substring(0, 155)+"..."; 
							$("#import_errormessage").html("<br><hr>ERROR:<br>"+errormessage);
						}
						$("#import_success").popup(("open"), { transition: 'pop'});

						console.log("Gesamt:"+entrieslength+" OK:"+successlength+" ERROR:"+errorlength);
					} else {
						console.log("Fehler: Die Datei enthaelt keine Zeilen");
					}
				}

			var import_file_text = $('#import_file').val();
			if (isApp ) import_file_text = $('#import_file_text').val();

			if (import_file_text.length > 0){
				if (import_file_text.slice(-3).toUpperCase() == "CSV"){

				$("#import_progress").html("&#8987;");
				var iv = CryptoJS.lib.WordArray.random(128/8);
				var key = sessionStorage.getItem("k"); 
				if (!isApp) {
					var file = $("#import_file")[0].files[0];
					if (!file) {return;}
					var reader = new FileReader();
					reader.onload = function(e) {
							var contents = e.target.result;
							var lines = contents.split('\n');
							importLines(lines);
					};
					reader.readAsText(file);
				} else {
					document.addEventListener("deviceready", getFile, true);
					
				}


			} else $("#import_csvhint").popup(("open"), { transition: 'pop' });	
		} else $("#import_fillFields").popup(("open"), { transition: 'pop' });
		
		$('#import_file_text').val("");
		return false;
		});   
});

// <!-- ----- hilight buttos ----- -->

$(document).on('pageshow', '#content, #profile, #changepass, #settings, #export, #import, #abouthelp, #loginprotocol', function(){ 
	var button = $.mobile.activePage.attr("id");
	$(".menu_content").removeClass( "hilightbackground" );
	$(".menu_profile").removeClass( "hilightbackground" );
	$(".menu_changepass").removeClass( "hilightbackground" );
	$(".menu_settings").removeClass( "hilightbackground" );
	$(".menu_exportimport").removeClass( "hilightbackground" );
	$(".menu_abouthelp").removeClass( "hilightbackground" );
	$(".menu_loginprotocol").removeClass( "hilightbackground" );
	switch(button) {
	case "content":
		$(".menu_content").addClass( "hilightbackground" );
        break;
  case "profile":
       	$(".menu_profile").addClass( "hilightbackground" );	
 				if ($("#profilefooter").css("visibility")=="hidden")	$("#profilefooter").css({opacity: 0, visibility: "visible"}).animate({opacity: 1}, 'fast');
        break;
	case "abouthelp":
        $(".menu_abouthelp").addClass( "hilightbackground" );
        break;
	case "loginprotocol":
        $(".menu_loginprotocol").addClass( "hilightbackground" );
        break;
	case "changepass":
        $(".menu_changepass").addClass( "hilightbackground" );
 				if ($("#changepassfooter").css("visibility")=="hidden")	$("#changepassfooter").css({opacity: 0, visibility: "visible"}).animate({opacity: 1}, 'fast');
        break;
	case "import":
        $(".menu_exportimport").addClass( "hilightbackground" );
 				if ($("#importfooter").css("visibility")=="hidden")	$("#importfooter").css({opacity: 0, visibility: "visible"}).animate({opacity: 1}, 'fast');
        break;
	case "export":
        $(".menu_exportimport").addClass( "hilightbackground" );
        if ($("#exportfooter").css("visibility")=="hidden")	$("#exportfooter").css({opacity: 0, visibility: "visible"}).animate({opacity: 1}, 'fast');
        break;
	case "settings":
        $(".menu_settings").addClass( "hilightbackground" );
 				if ($("#settingsfooter").css("visibility")=="hidden")	$("#settingsfooter").css({opacity: 0, visibility: "visible"}).animate({opacity: 1}, 'fast');
        break;
    default:
        $(".menu_content").addClass( "hilightbackground" );
	} 
});
			   
//<!-- ----- logout ----- -->


function logout() {
	try {clearInterval(counter);}
	catch (err) {}
	if (copyTextToClipboard(' ')) sessionStorage.setItem("clearClipboard", "0");
	if ($('#show_showbutton').val()!="show") {
		$('#show_password').prop("type", "password");
		$('#show_password_txt').removeClass('showPasswordColor');
		$('#show_showbutton').removeClass('ui-btn-active');
		$('#show_showbutton').val("show");
		$('#show_password_txt').html("• • • • • •");
	}
	$('#logout').load(sessionStorage.getItem('OwnSafeServerUrl')+"/src/logout.php",function(){
	        $('#logout').trigger('create');
            $.mobile.changePage("#logout");
	});
}

$(document).on('pageshow', '#logout', function(){
	if (isApp) {
		window.plugins.insomnia.allowSleepAgain();
		setTimeout(function(){ 
			cordova.plugins.notification.local.clearAll();
			MOForceAppClose.forceAppClose();
			}, 500);

	} else {
		location.replace("./");
	}
});

$(document).on('click', ".logoutbutton",function(event) {
		event.preventDefault();
		event.stopImmediatePropagation();
		logout();
});


//<!-- -------------------------------------------------------------------------------------------------------------- -->
//<!-- -------------------------------------------------------------------------------------------------------------- -->
//<!-- -------------------------------------------------------------------------------------------------------------- -->


$(document).on('click', '#show_edit',function(event) {	

		$('#show_headline_edit').show();

		$('#show_title').parent().fadeIn();
		$('#show_title_label').fadeIn();
		$('#show_username').parent().fadeIn();
		$('#show_username_label').fadeIn();
		$('#show_password').parent().fadeIn();
		$('#show_password_label').fadeIn();
		$('#show_passwordconfirm_label').fadeIn();
		$('#show_passwordconfirm').fadeIn();
		$('#show_passwordconfirm').parent().fadeIn();
		$('#show_url').parent().fadeIn();
		$('#show_url_label').fadeIn();
		$('#show_note').fadeIn();
		$('#show_note_label').fadeIn();	
					
		$('#show_password').parent().css("float", "left");
		$('#show_password').css("color", "black");
		$('#show_password').prop("type", "password");
		$('#show_passwordconfirm').css("color", "black");
		$('#show_passwordconfirm').parent().css("float", "left");
		$('#show_passwordconfirm_label').css("clear", "both");
		$('#show_url_label').css("clear", "both");
		$('#show_showbutton').removeClass('ui-btn-active');
		$('#show_showbutton').val("show");
		$('#show_showbutton').css('margin-top','8px');

		$('#show_formkind').val("edit");
		$('#show_note').textinput();
		if (!isApp) $('#show_title').focus();
		
		$('#show_genpass').hide();
		$('#show_headline_show').hide();
		$('#show_title_txt').hide();
		$('#show_counter').hide();
		$('#show_username_txt').hide();
		$('#show_password_txt').hide();
		$('#show_note_txt').hide();
		$('#show_url_txt').hide();
		$('#show_copyuser').hide();
		$('#show_copypass').hide();
		$('#show_copyurl').hide();
		$('#show_copynote').hide();
		$('#show_url_href').hide();
		$('#show_edit').hide();
		$('#show_save').fadeIn();
		$('#show_delete').fadeIn();
		if ($('#show_password').val().length===0) $('#show_genpass').fadeIn();
	
});


$(document).on('vmousedown vmouseup vmouseout vclick', '#show_showbutton',function(event) {
	if (isApp) navigator.vibrate(15);
	if ( ($('#show_formkind').val()!="edit" && event.type=="vmousedown") || ($('#show_formkind').val()=="edit" && event.type=="vclick" && $('#show_showbutton').val()=="show") ) {
		$('#show_showbutton').addClass('ui-btn-active');
        $('#show_showbutton').val("hide");
        $('#show_password').prop("type", "text");
        $('#show_password_txt').html($('#show_password').val());
        $('#show_password_txt').addClass('showPasswordColor');
        $('#show_passwordconfirm').parent().fadeOut();
        $('#show_passwordconfirm').fadeOut();
        $('#show_passwordconfirm_label').fadeOut();
    } else { 
		if (($('#show_formkind').val()!="edit" && event.type!="vmousedown") ||
			($('#show_formkind').val()=="edit" && (event.type=="vclick"))) { 
		    $('#show_password_txt').removeClass('showPasswordColor');
		    $('#show_showbutton').removeClass('ui-btn-active');
		    $('#show_showbutton').val("show");
		    $('#show_password_txt').html("&bull; &bull; &bull; &bull; &bull; &bull;");
		    $('#show_password').prop("type", "password");
		    if ($('#show_formkind').val()=="edit") {
		        $('#show_passwordconfirm').fadeIn();
		        $('#show_passwordconfirm_label').fadeIn();
		        $('#show_passwordconfirm').parent().fadeIn();
		    } else {
		        $('#show_passwordconfirm').parent().fadeOut();
		        $('#show_passwordconfirm').fadeOut();
		        $('#show_passwordconfirm_label').fadeOut();
		    }
		}
	}


}); 

     
$(document).on('click', '#new_showbutton',function(event) {
    if (isApp) navigator.vibrate(15);
    if ($("#new_showbutton").val()=="show") {
        $("#new_showbutton").addClass('ui-btn-active');
        $("#new_showbutton").val("hide");
        $("#new_password" ).prop("type", "text");
        $("#new_passwordconfirm").fadeOut();
        $("#new_passwordconfirm_label").fadeOut();
        $("#new_passwordconfirm_label").parent().fadeOut();
    } else { 
        $("#new_showbutton").removeClass('ui-btn-active');
        $("#new_showbutton").val("show");
        $("#new_password" ).prop("type", "password");
        $("#new_passwordconfirm").fadeIn();
        $("#new_passwordconfirm_label").fadeIn();
        $("#new_passwordconfirm_label").parent().fadeIn();
    }
});


$(document).on('click', '#new_genpass',function(event) {
    var pass = "";
    var passgenlength = sessionStorage.getItem('passgenlength');
    if (passgenlength==="" || passgenlength=="undefined" || passgenlength < 6) passgenlength=12;
    if ($("#new_password").val().length<passgenlength) pass = getRandomPass(passgenlength);
    else if ($("#new_password").val().length>=32) pass = getRandomPass(32);
    else pass = getRandomPass(($("#new_password").val().length+2));
    $("#new_password").val(pass);
    $("#new_passwordconfirm").val(pass);
    verifypass("#new_password","#new_passwordconfirm");
});

$(document).on('click', '#show_genpass',function(event) {
    var pass = "";
    var passgenlength = sessionStorage.getItem('passgenlength');
    if (passgenlength==="" || passgenlength=="undefined" || passgenlength < 6) passgenlength=12;
    if ($("#show_password").val().length<passgenlength) pass = getRandomPass(passgenlength);
    else if ($("#show_password").val().length>=32) pass = getRandomPass(32);
    else pass = getRandomPass(($("#show_password").val().length+2));
    $("#show_password").val(pass);
    $("#show_passwordconfirm").val(pass);
    verifypass("#show_password","#show_passwordconfirm");
});


$(document).on('blur', '#profile_email',function(event) {
	if ($('#profile_email').val().length>0) {
		
		var formdata = "email="+encodeURIComponent($('#profile_email').val());
		
		$.ajax({url: sessionStorage.getItem('OwnSafeServerUrl')+'/src/check-email.php',
			data: formdata,
			dataType: 'json',
			type: 'post',                  
			async: false,
			beforeSend: function() {$.mobile.loading("show");},
			complete: function() {$.mobile.loading("hide");},
			success: function (result) {
				if(result.status) {
					
					if (result.message!="free" && result.message!="owner") {

                        if (result.message=="eexists") {
							$("#profile_emailFailed_eexist").on({
								popupafterclose: function(event, ui) {
									event.preventDefault();
									event.stopImmediatePropagation();
									$("#profile_email").val("");
									//$("#profile_email").focus();
								}
							});
							$("#profile_emailFailed_eexist").popup(("open"), { transition: 'pop'});
						
						} else {
							$("#profile_updateFailed").on({
								popupafterclose: function(event, ui) {
									event.preventDefault();
									event.stopImmediatePropagation();
								}
							});
							$("#profile_error").popup(("open"), { transition: 'pop'});
							console.log("Error: "+result.message);
						}
					}				
				} else {
					if (result.message=="InvalidSession") {
						$("#profile_sessionError").popup(("open"), { transition: 'pop' });
						console.log(result.message);
						setTimeout(logout, 3000);
					} else {
						$("#profile_error").popup(("open"), { transition: 'pop' });
						console.log(result.message);
					}
				}
			},
			error: function (jqXHR, exception) {
				$("#profile_error").popup(("open"), { transition: 'pop' });
				console.log("Error: "+getjqXHRMessage(jqXHR,exception));
			}
		}); 
	}
});

$(document).on('click', '#new_reset',function(event) {
    reset_addentryform();
	passgenActivator("#new_password","#new_genpass");
});

$(document).on('click', '#changepass_reset',function(event) {
    $('#changepass_form').get(0).reset();
    $('#changepass_username').val(sessionStorage.getItem("username"));
});

$(document).on('change', '#profile_okdelete',function(event) {
	
    if ($("#profile_okdelete").is(":checked")) {
        $("#profile_popup_delete").removeClass("disabled");
        $("#profile_popup_delete").buttonMarkup({theme: 'b'});
    } else {
        $("#profile_popup_delete").addClass("disabled");
        $("#profile_popup_delete").buttonMarkup({theme: 'a'});
    }
});

$(document).on('click', "#menu_changepass",function(event) {
	$('#changepass_form').get(0).reset();
	$('#changepass_username').val(sessionStorage.getItem("username"));
	$.mobile.changePage('#changepass'); 
});

$(document).on('click', "#menu_abouthelp",function(event) {
	$.mobile.changePage('#abouthelp'); 
});

$(document).on('click', "#menu_loginprotocol",function(event) {
	$.mobile.changePage('#loginprotocol'); 
})

$(document).on('click', "#button_top",function(event) {
    $('html, body').stop().animate({ scrollTop : 0 }, 500);
    return false;
});

$(document).on('click', "#button_addentry",function(event) {
	$('#new_password').parent().css("float", "left");
	$('#new_passwordconfirm').parent().css("float", "left");
	$('#new_passwordconfirm_label').css("clear", "both");
	$('#new_url_label').css("clear", "both");
  $("#new_password" ).prop("type", "password");
    if ($("#new_passwordconfirm").is(':hidden')) {
        $("#new_passwordconfirm").show();
        $("#new_passwordconfirm_label").show();
        $("#new_passwordconfirm_label").parent().show();
    }
	if ($('#new_showbutton').hasClass('ui-btn-active')) $('#new_showbutton').removeClass('ui-btn-active');
  $("#new_showbutton").val("show");
 	$('#new_username').val('');
	$('#new_title').val('');
	$('#new_password').val('');
	$('#new_passwordconfirm').val('');
	$('#new_url').val('');
	$('#new_note').val('');
	
	$.mobile.changePage('#addentry'); 
});

$(document).on('click', "#menu_exportimport",function(event) {

	switch($.mobile.activePage.attr("id")) {
	case "content":
		$("#content_eiswitch").popup(("open"), { transition: 'pop'});
        break;
    case "profile":
        $("#profile_eiswitch").popup(("open"), { transition: 'pop'});
        break;
	case "abouthelp":
        $("#abouthelp_eiswitch").popup(("open"), { transition: 'pop'});
        break;
	case "changepass":
        $("#changepass_eiswitch").popup(("open"), { transition: 'pop'});
        break;
	case "import":
        $("#import_eiswitch").popup(("open"), { transition: 'pop'});
        break;
	case "export":
        $("#export_eiswitch").popup(("open"), { transition: 'pop'});
        break;
	case "settings":
        $("#settings_eiswitch").popup(("open"), { transition: 'pop'});
        break;
	case "loginprotocol":
        $("#loginprotocol_eiswitch").popup(("open"), { transition: 'pop'});
        break;
    default:
        $("#content_eiswitch").popup(("open"), { transition: 'pop'});
	} 

});

$(document).on('click', "#switch_import",function(event) {
	$("#import_count").html("");
	$("#import_countsuccessfull").html("");
	$("#import_counterror").html("");
	$("#import_errormessage").html("");
	$("#import_file").val("");
	$("#import_progress").html("");
	$.mobile.changePage('#import'); 
});

$(document).on('click', "#switch_export",function(event) {
	$("#export_password").val("");
	$.mobile.changePage('#export'); 
});

$(document).on('click', "#show_delete",function(event) {
	$('#show_deletePopupDialog').popup(("open"), { transition: 'pop'});
});


$(document).on('click', "#show_copyuser",function(event) {
	copyTextToClipboard($("#show_username").val());
	if (isApp) {
		$('#popup_copyBrowserTxt').hide();
        $('#popup_copyAppTxt').show();
		try {clearInterval(clipboardCounter);}
		catch (err) {}
		$('#show_clearclipboard_anchor').html(clearClipboardTxt);
		clipboardCount=start_clipboardCounter;
		clipboardCounter = setInterval(clipboardTimer,1000);
    } else {
		$('#popup_copyBrowserTxt').show();
       	$('#popup_copyAppTxt').hide();
	}
	$('#show_copyPopup').popup(("open"), { transition: 'pop'});
	$('#show_clearclipboard').fadeIn();
	sessionStorage.setItem("clearClipboard", "1");
	popupCloser($('#closePopupButton'),$("#show_copyPopup"),popUpCloserTimerS);
	$('#show_clearclipboard').fadeIn();

});

$(document).on('click', "#show_copypass",function(event) {

	copyTextToClipboard($("#show_password").val());
	if (isApp) {
		$('#popup_copyBrowserTxt').hide();
        $('#popup_copyAppTxt').show();
		try {clearInterval(clipboardCounter);}
		catch (err) {}
		$('#show_clearclipboard_anchor').html(clearClipboardTxt);
		clipboardCount=start_clipboardCounter;
		clipboardCounter = setInterval(clipboardTimer,1000);
    } else {
		$('#popup_copyBrowserTxt').show();
        $('#popup_copyAppTxt').hide();
	}
	$('#show_copyPopup').popup(("open"), { transition: 'pop'});
	$('#show_clearclipboard').fadeIn();
	sessionStorage.setItem("clearClipboard", "1");
	popupCloser($('#closePopupButton'),$("#show_copyPopup"),popUpCloserTimerS);
	$('#show_clearclipboard').fadeIn();

	
});

$(document).on('click', "#show_copyurl",function(event) {
	copyTextToClipboard($("#show_url").val());
	if (isApp) {
		$('#popup_copyBrowserTxt').hide();
        $('#popup_copyAppTxt').show();
		try {clearInterval(clipboardCounter);}
		catch (err) {}
		$('#show_clearclipboard_anchor').html(clearClipboardTxt);
		clipboardCount=start_clipboardCounter;
		clipboardCounter = setInterval(clipboardTimer,1000);
    } else {
		$('#popup_copyBrowserTxt').show();
        $('#popup_copyAppTxt').hide();
	}
	$('#show_copyPopup').popup(("open"), { transition: 'pop'});
	$('#show_clearclipboard').fadeIn();
	sessionStorage.setItem("clearClipboard", "1");
	popupCloser($('#closePopupButton'),$("#show_copyPopup"),popUpCloserTimerS);
	$('#show_clearclipboard').fadeIn();
});

$(document).on('click', "#show_copynote",function(event) {
	copyTextToClipboard($("#show_note").val());
	if (isApp) {
		$('#popup_copyBrowserTxt').hide();
        $('#popup_copyAppTxt').show();
		try {clearInterval(clipboardCounter);}
		catch (err) {}
		$('#show_clearclipboard_anchor').html(clearClipboardTxt);
		clipboardCount=start_clipboardCounter;
		clipboardCounter = setInterval(clipboardTimer,1000);
    } else {
		$('#popup_copyBrowserTxt').show();
        $('#popup_copyAppTxt').hide();
	}
	$('#show_copyPopup').popup(("open"), { transition: 'pop'});
	$('#show_clearclipboard').fadeIn();
	sessionStorage.setItem("clearClipboard", "1");
	popupCloser($('#closePopupButton'),$("#show_copyPopup"),popUpCloserTimerS);
	$('#show_clearclipboard').fadeIn();	
});

function popupCloser(closeButton,popupWindow,closeDelay) {
	var closeTxt = "";
	var closesec = closeDelay;
	if (closeButton!=null) closeTxt = closeButton.html();
	popupWindow.bind({
		popupafterclose: function(event, ui) {
			try {clearInterval(closePopupCounter);
			} catch (err) {}
			if (closeButton!=null) closeButton.html(closeTxt);
		}
	});
	try {clearInterval(closePopupCounter);
	} catch (err) {}
	if (closeButton!=null) closeButton.html(closeTxt+" "+(closeDelay/1000)); 
	closePopupCounter = setInterval(function(){ 
		closesec = closesec-1000;
		if (closesec<=0) {
			try {clearInterval(closePopupCounter);
			} catch (err) {}
			if (closeButton!=null) closeButton.html(closeTxt);
			popupWindow.popup("close");
		} else {
			if (closeButton!=null) closeButton.html(closeTxt+" "+(closesec/1000));
		}
	},1000);		
}

$(document).on('click', '#show_clearclipboard_anchor',function(event) {
	clearInterval(clipboardCounter);
	if (isApp) $('#show_clearclipboard_anchor').html(clearClipboardTxt);
	if (copyTextToClipboard(" ")) {
		if (isApp) navigator.vibrate(15);
		sessionStorage.setItem("clearClipboard", "0");
		$("#show_clearclipboard").fadeOut();
	}
});

$(document).on('click', '#show_url_href',function(event) {
	if (isApp) {
        window.open($('#show_url_href').attr('href'), '_system','location=yes');
		return false;
    }
});

$(document).on('keyup', '#new_password',function(event) {
    verifypass("#new_password","#new_passwordconfirm");
	passgenActivator("#new_password","#new_genpass");
});

$(document).on('keyup', '#new_passwordconfirm',function(event) {
    if ($( "#new_passwordconfirm" ).val() == $( "#new_password" ).val()) $('#new_passwordconfirm').css("color", "green");
    else $('#new_passwordconfirm').css("color", "red");
});

$(document).on('keyup', '#show_password',function(event) {
    verifypass("#show_password","#show_passwordconfirm");
	passgenActivator("#show_password","#show_genpass");
});

$(document).on('keyup', '#show_passwordconfirm',function(event) {
    if ($( "#show_passwordconfirm" ).val() == $( "#show_password" ).val()) $('#show_passwordconfirm').css("color", "green");
    else $('#show_passwordconfirm').css("color", "red");
});


$(document).on('keyup', '#changepass_newpassword',function(event) {
   verifypass("#changepass_newpassword","#changepass_newpasswordconfirm");
});

$(document).on('keyup', '#changepass_newpasswordconfirm',function(event) {
   if ($( "#changepass_newpasswordconfirm" ).val() == $( "#changepass_newpassword" ).val()) $('#changepass_newpasswordconfirm').css("color", "green");
   else $('#changepass_newpasswordconfirm').css("color", "red");
});

$(document).on('keyup focus click', function(event) {
	if (!$(this).hasClass("logoutbutton")) resetTimer();
});

$(document).on('keyup', '#export_password',function(event) {
   if (event.which == 13) {
       $('#export_export').click();
       event.preventDefault();
       event.stopImmediatePropagation();
   }
});

$(document).on('blur', '#changepass_username',function(event) {
	if ($('#changepass_username').val().length>0 && $('#changepass_username').val()!=sessionStorage.getItem("username")) {
		
		var formdata = "usr="+encodeURIComponent($('#changepass_username').val());
		if (isValidEmailAddress($('#changepass_username').val())) formdata += "&email="+encodeURIComponent($('#changepass_username').val());
		
		$.ajax({url: sessionStorage.getItem('OwnSafeServerUrl')+'/src/check-user.php',
			data: formdata,
			dataType: 'json',
			type: 'post',                  
			async: true,
			success: function (result) {
				if(result.status) {
						if (result.message=="uexists") {
							$("#changepass_uexist").on({
								popupafterclose: function(event, ui) {
									event.preventDefault();
									event.stopImmediatePropagation();
									$("#changepass_username").val("");
								}
							});
							$("#changepass_uexist").popup(("open"), { transition: 'pop'});
					}				
				} else {
					$("#su_error").popup(("open"), { transition: 'pop' });
						console.log("Error: "+result.message);
						$.mobile.loading("hide");
				}
			},
			error: function (jqXHR, exception) {
				console.log("Error: "+getjqXHRMessage(jqXHR, exception));
				$.mobile.loading("hide");
			} 
		}); 
	}
});

//<!-- -------------------------------------------------------------------------------------------------------------- -->

function resetTimer(){
		start_counter = sessionStorage.getItem('logouttime');
		if (start_counter!==null) {
			count=start_counter;
			$(".logout").text(count);
			$(".logout").css('color','inherit');
			$.ajax({url: sessionStorage.getItem('OwnSafeServerUrl')+'/src/update-session.php',async: true}); 
		}
}

function clipboardTimer(){
	clipboardCount=clipboardCount-1;
	//console.log("clipboardTimer "+clipboardCount);
	if (isApp) $('#show_clearclipboard_anchor').html(clearClipboardTxt+" "+clipboardCount);
	if (clipboardCount<= 0){
			clearInterval(clipboardCounter);
			if (isApp) navigator.vibrate(15);
			if (isApp) $('#show_clearclipboard_anchor').html(clearClipboardTxt);
			$("#show_clearclipboard_anchor").trigger("click");
	} 
}

function passgenActivator(passfield,passgenbutton) {
	if ($(passfield).val().length===0) {
		$(passgenbutton).fadeIn();
	} else {
		$(passgenbutton).fadeOut();
	}
}

function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(emailAddress);
}


function reset_addentryform(){
	$('#add_entry_form').get(0).reset();
    $('#new_password').css("color", "black");
    $('#new_password').prop("type", "password");
    if ($('#new_passwordconfirm').is(':hidden')) {
        $('#new_passwordconfirm').fadeIn();
        $('#new_passwordconfirm_label').fadeIn();
		$("#new_passwordconfirm_label").parent().fadeIn();
        $('#new_showbutton').removeClass('ui-btn-active');
        $('#new_showbutton').val("show");
    }
}

function getRandomPass(length)
{
    var text = "";
    var possible = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01234567890123456789!@:,.*?=+-_!@:,.*?=-_";
    if (length===0) length = 12;
    for( var i=0; i < length; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}

function changePassword() {
	var response = false;
	$.ajax({url: sessionStorage.getItem('OwnSafeServerUrl')+'/src/getentries.php',
	dataType: 'json',
	type: 'post',                  
	async: false,
	beforeSend: function() {$.mobile.loading("show");},
	complete: function() {$.mobile.loading("hide");},
	success: function (result) {
				
				if(result.status) {
					var upepper = null;
					var newkey = null;

					$.mobile.loading("show");
					uname = sessionStorage.getItem("username");
					upepper = updateUserCredentials();
					console.log("uname: "+uname+" upepper: "+upepper);
					if (upepper !== false) {
						if (upepper != "") {							
							if (result.message != "noentries") {
								
								var key = sessionStorage.getItem("k");
								var json_obj = $.parseJSON(result.entries);
								var keySizeValue = sessionStorage.getItem("keySizeValue");
								var iterationsValue = parseInt(sessionStorage.getItem("iterationsValue"));
								var keySizeParts=keySizeValue.split('/');
								var keyPart1 = parseInt(keySizeParts[0]);
								var keyPart2 = parseInt(keySizeParts[1]);
								newkey = CryptoJS.PBKDF2($('#changepass_newpassword').val(), upepper, { keySize: keyPart1/keyPart2, iterations: iterationsValue}).toString();
								var json_string = "[";

								for (var i in json_obj) {

									var cur_iv          = json_obj[i].iv;
									var cur_id          = json_obj[i].id;
									var cur_title       = CryptoJS.AES.decrypt(json_obj[i].title,    key, { iv: cur_iv }).toString(CryptoJS.enc.Utf8);
									var cur_user        = CryptoJS.AES.decrypt(json_obj[i].user,     key, { iv: cur_iv }).toString(CryptoJS.enc.Utf8);
									var cur_password    = CryptoJS.AES.decrypt(json_obj[i].password, key, { iv: cur_iv }).toString(CryptoJS.enc.Utf8);
									var cur_urlentry    = CryptoJS.AES.decrypt(json_obj[i].urlentry, key, { iv: cur_iv }).toString(CryptoJS.enc.Utf8);
									var cur_note        = CryptoJS.AES.decrypt(json_obj[i].note,     key, { iv: cur_iv }).toString(CryptoJS.enc.Utf8);
									var new_iv          = CryptoJS.lib.WordArray.random(128/8);
									var enc_title       = "";
									var enc_user        = "";
									var enc_password    = "";
									var enc_urlentry    = "";
									var enc_note        = "";
									if (cur_title!=="")			enc_title       = CryptoJS.AES.encrypt(cur_title, newkey, { iv: new_iv });
									if (cur_user!=="")			enc_user        = CryptoJS.AES.encrypt(cur_user, newkey, { iv: new_iv });
									if (cur_password!=="")	enc_password    = CryptoJS.AES.encrypt(cur_password, newkey, { iv: new_iv });
									if (cur_urlentry!=="")	enc_urlentry    = CryptoJS.AES.encrypt(cur_urlentry, newkey, { iv: new_iv });
									if (cur_note!=="")			enc_note        = CryptoJS.AES.encrypt(cur_note, newkey, { iv: new_iv });
									
									if (json_string.length>1) json_string += ",";
									json_string += '{"id":"'+cur_id+'","title":"'+enc_title+'","user":"'+enc_user+'","password":"'+enc_password+'","urlentry":"'+enc_urlentry+'","note":"'+enc_note+'","iv":"'+new_iv+'"}';
					
		                          }

								json_string += "]";
								sessionStorage.setItem("k",newkey);
								$.mobile.loading("hide");
		                          response = sendEntries(json_string);

		          } response = true;
						}
					} else {
						sessionStorage.setItem("username", uname);
						$.mobile.loading("hide");
						$("#changepass_error").popup(("open"), { transition: 'pop'});
						console.log("Error: upepper false");
					}

				} else if (result.message == "noentries") {
                    response = result.message;

        } else {
        	if (result.message=="InvalidSession") {
						$("#changepass_sessionError").popup(("open"), { transition: 'pop' });
						console.log(result.message);
						setTimeout(logout, 3000);
					} else {
						$("#changepass_error").popup(("open"), { transition: 'pop' });
						console.log(result.message);
					}
				}
			},
			error: function (jqXHR, exception) {
				$("#changepass_error").popup(("open"), { transition: 'pop'});
				console.log("Error: "+getjqXHRMessage(jqXHR,exception));
			}
		});  
		
	return response;
}

function updateUserCredentials(){
	
	var formdata = "usr="+$('#changepass_username').val()+"&pwd="+CryptoJS.SHA256( ($('#changepass_newpassword').val()+$('#changepass_username').val().toLowerCase()) );
	
	var upepper = false;
	$.ajax({url: sessionStorage.getItem('OwnSafeServerUrl')+'/src/updateUC.php',
		data: formdata,
		dataType: 'json',
		type: 'post',              
		async: false, 
		beforeSend: function() {$.mobile.loading("show");},
		complete: function() {$.mobile.loading("hide");},
		success: function (result) {

					if(result.status) {
						upepper = result.upepper;
						sessionStorage.setItem("username", result.uname);
					} else {
						console.log("UC: "+result.message);
						if (result.message=="uexists") {
							$("#changepass_uexist").popup(("open"), { transition: 'pop' });
							upepper="";
						} else if (result.message=="InvalidSession") {
							$("#changepass_sessionError").popup(("open"), { transition: 'pop' });
							setTimeout(logout, popUpCloserTimerL);
						}
					}
		},
		error: function (jqXHR, exception) {
			$("#changepass_error").popup(("open"), { transition: 'pop'});
			console.log("Error: "+getjqXHRMessage(jqXHR,exception));
		}
	}); 
	
	return upepper;	
}


function sendEntries(json_string){

	var ok = false;
	var formdata = "entries="+encodeURIComponent(json_string);
	$.ajax({url: sessionStorage.getItem('OwnSafeServerUrl')+'/src/setentries.php',
		data: formdata,
        dataType: "json",
        type: 'post',                  
		async: false,
        beforeSend: function(x) {
           if (x && x.overrideMimeType) {
                x.overrideMimeType("application/j-son;charset=UTF-8");
           }
           $.mobile.loading("show");
        },
		complete: function() {$.mobile.loading("hide");},
		success: function (result) {
				
			if(result.status) {
				ok = true;
			} else {
				if (result.message=="InvalidSession") {
					$("#changepass_sessionError").popup(("open"), { transition: 'pop' });
					console.log(result.message);
					setTimeout(logout, 3000);
				} else {
					$("#changepass_error").popup(("open"), { transition: 'pop' });
					console.log(result.message);
				}
			}
		},
		error: function (jqXHR, exception) {
			$("#changepass_error").popup(("open"), { transition: 'pop'});
			console.log("Error: "+getjqXHRMessage(jqXHR,exception));
		}
	});   
	return ok;
}


function exportEntries() {
	var exportstring = "";
	$.ajax({url: sessionStorage.getItem('OwnSafeServerUrl')+'/src/getentries.php',
	dataType: 'json',
	type: 'post',                  
	async: false,
	beforeSend: function() {$.mobile.loading("show");},
	complete: function() {$.mobile.loading("hide");},
	success: function (result) {
				if(result.status) {

					if (result.message != "noentries") {
						
						var key = sessionStorage.getItem("k");
						var json_obj = $.parseJSON(result.entries);
											
						for (var i in json_obj) {

              var cur_iv      = json_obj[i].iv;
              var cur_id      = json_obj[i].id;
              var cur_title    = unescape(encodeURIComponent(CryptoJS.AES.decrypt(json_obj[i].title,    key, { iv: cur_iv }).toString(CryptoJS.enc.Utf8)));
							var cur_user     = unescape(encodeURIComponent(CryptoJS.AES.decrypt(json_obj[i].user,     key, { iv: cur_iv }).toString(CryptoJS.enc.Utf8)));
							var cur_password = unescape(encodeURIComponent(CryptoJS.AES.decrypt(json_obj[i].password, key, { iv: cur_iv }).toString(CryptoJS.enc.Utf8)));
							var cur_urlentry = unescape(encodeURIComponent(CryptoJS.AES.decrypt(json_obj[i].urlentry, key, { iv: cur_iv }).toString(CryptoJS.enc.Utf8)));
							var cur_note     = unescape(encodeURIComponent(CryptoJS.AES.decrypt(json_obj[i].note,     key, { iv: cur_iv }).toString(CryptoJS.enc.Utf8)));
							cur_note = cur_note.replace(/(?:\r\n|\r|\n)/g, '<br/>');
							exportstring += cur_title+";"+cur_user+";"+cur_password+";"+cur_urlentry+";"+cur_note+"\n";
            }
						
                    } else if (result.message == "noentries") {
                        exportstring = result.message;
                    } else {
                        console.log("Error: "+result.message);
						$("#export_error").popup(("open"), { transition: 'pop'});
					}

				} else {
					if (result.message=="InvalidSession") {
						$("#export_sessionError").popup(("open"), { transition: 'pop' });
						console.log(result.message);
						setTimeout(logout, 3000);
					} else {
						$("#export_error").popup(("open"), { transition: 'pop' });
						console.log(result.message);
					}
				}
			},
			error: function (jqXHR, exception) {
				$("#export_error").popup(("open"), { transition: 'pop'});
				console.log("Error: "+getjqXHRMessage(jqXHR,exception));
			}
		});   
	
	return exportstring;
}


function copyTextToClipboard(text) {
  if (text.length===0) text = " ";
  var textArea = document.createElement("textarea");
  var copySuccessfully = false;
  textArea.value = text;
  textArea.style.position = 'fixed';
  textArea.style.top = 0;
  textArea.style.left = 0;
  textArea.style.height = '2em';
  textArea.style.padding = 0;
  textArea.style.border = 'none';
  textArea.style.outline = 'none';
  textArea.style.boxShadow = 'none';
  textArea.style.background = 'transparent';
  document.body.appendChild(textArea);

  textArea.select();
  try {
    	var successful = document.execCommand('copy');
		if (!successful) {
			cordova.plugins.clipboard.copy(text);
			copySuccessfully = true;
		} else {
			if (isApp) navigator.vibrate(15);
			$("#show_clearclipboard").fadeOut();
			copySuccessfully = true;
		}
	} catch (err) {console.log('Oops, unable to copy, probably this is a browser.');}
	document.body.removeChild(textArea);
  	return copySuccessfully;
}


var errorHandler = function (fileName, e) {  
    var msg = '';

    switch (e.code) {
        case FileError.QUOTA_EXCEEDED_ERR:
            msg = 'Storage quota exceeded';
            break;
        case FileError.NOT_FOUND_ERR:
            msg = 'File not found';
            break;
        case FileError.SECURITY_ERR:
            msg = 'Security error';
            break;
        case FileError.INVALID_MODIFICATION_ERR:
            msg = 'Invalid modification';
            break;
        case FileError.INVALID_STATE_ERR:
            msg = 'Invalid state';
            break;
        default:
            msg = 'Unknown error';
            break;
    }

    console.log('Error (' + fileName + '): ' + msg);
};

