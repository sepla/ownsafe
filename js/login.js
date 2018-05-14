// Copyright (C) 2015 Sebastian Plaza
// This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
// This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty
// of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
// You should have received a copy of the GNU General Public License along with this program. 
// If not, see http://www.gnu.org/licenses/. 


$(document).on('pageinit', '#login', function(){
	if (sessionStorage.getItem('OwnSafeServerUrl')!=null) $('#index').load(sessionStorage.getItem('OwnSafeServerUrl')+'/src/page_loginsettings.php',	function()	{$('#index').trigger('create');}); 

	$(document).on('click', '#log_submit', function(event) {
		if($('#log_username').val().length > 0 &&  $('#log_password').val().length > 0){
			var formdata = "usr="+encodeURIComponent($('#log_username').val())+"&pwd="+CryptoJS.SHA256( ($('#log_password').val()+$('#log_username').val().toLowerCase()) );
			$.ajax({url: sessionStorage.getItem('OwnSafeServerUrl')+'/src/check.php',
				data: formdata,
				dataType: 'json',
				type: 'post',
				async: false,
				beforeSend: function() {$.mobile.loading("show");},
				complete: function() {$.mobile.loading("hide");},
				success: function (result) {
					if(result.status) {
						console.log("Message: "+result.message);
							if (result.message=="OK" && result.upepper!=null) {
								sessionStorage.setItem("loginnotify", result.uloginnotify);
								sessionStorage.setItem("lastlogin", result.ulastlogin);
								sessionStorage.setItem("lastfailedlogin", result.ulastfailedlogin);
								sessionStorage.setItem("loginfailure", result.uloginfailure);
								sessionStorage.setItem("passgenlength", result.upassgenlength);
								sessionStorage.setItem("protocollength", result.uprotocollength);
								sessionStorage.setItem("username", result.uname);
								sessionStorage.setItem("logouttime", result.ulogouttime);
								$("#log_keygen").on({
									popupafteropen: function(event, ui) {
										console.log("Starting key generation ("+sessionStorage.getItem('iterationsValue')+" iterations)");
										console.log("If this takes too long the number of iterations have to be reduced (config.php). Export your data first.");
										$('#content').load(sessionStorage.getItem('OwnSafeServerUrl')+'/src/page_content.php',         function()	{$('#content').trigger('create');});
										$('#abouthelp').load(sessionStorage.getItem('OwnSafeServerUrl')+'/src/page_abouthelp.php',     function()	{$('#abouthelp').trigger('create');});
										$('#loginprotocol').load(sessionStorage.getItem('OwnSafeServerUrl')+'/src/page_loginprotocol.php?period='+sessionStorage.getItem("protocollength"),	function()	{$('#loginprotocol').trigger('create');});
										$('#addentry').load(sessionStorage.getItem('OwnSafeServerUrl')+'/src/page_addentry.php',       function()	{$('#addentry').trigger('create');});
										$('#changepass').load(sessionStorage.getItem('OwnSafeServerUrl')+'/src/page_changepass.php',   function()	{$('#changepass').trigger('create');});
										$('#export').load(sessionStorage.getItem('OwnSafeServerUrl')+'/src/page_export.php',           function()	{$('#export').trigger('create');});
										$('#import').load(sessionStorage.getItem('OwnSafeServerUrl')+'/src/page_import.php',           function()	{$('#import').trigger('create');});
										$('#profile').load(sessionStorage.getItem('OwnSafeServerUrl')+'/src/page_profile.php',         function()	{$('#profile').trigger('create');});
										$('#settings').load(sessionStorage.getItem('OwnSafeServerUrl')+'/src/page_settings.php',       function()	{$('#settings').trigger('create');});
										$('#showentry').load(sessionStorage.getItem('OwnSafeServerUrl')+'/src/page_showentry.php',     function()	{$('#showentry').trigger('create');});
										generateKey(result.upepper, sessionStorage.getItem('iterationsValue'));                                                                        
										$("#log_keygen").popup(("close"), {transition: 'none'});
									},
									popupafterclose: function(event, ui) {
										if(result.ulaststatus == "loggedin"){
											$("#log_logoutHint").popup(("open"), { transition: 'pop'});
											$("#log_logoutHint").on({
												popupafterclose: function(event, ui) {
													$.mobile.changePage('#content');
												}
											});
									} else {
										setTimeout(function(){ $.mobile.changePage('#content'); }, 250);
									}
								}
							});
							$("#log_keygen").popup(("open"), { transition: 'pop' });
	
							start_counter = sessionStorage.getItem('logouttime');
							if (start_counter<20) start_counter=20;
								count=start_counter;
							try {clearInterval(counter);}
							catch (err) {}
								counter = setInterval(timer, 1000);
							try {window.plugins.insomnia.keepAwake();}
							catch (err) {}
		
						} else if (result.message == "login failed") {
								$("#log_loginFailed").popup(("open"), { transition: 'pop'});
						} else if (result.message == "blocked") {
								if (result.timeleft == "0") result.timeleft = "<1";
									$("#log_blockedtime").text(result.timeleft);
									$("#log_loginBlocked").popup(("open"), { transition: 'pop'});
							} else if (result.upepper==null) {
									$("#log_error").popup(("open"), { transition: 'pop' });
									console.log("Error: DB user - pepper could not be decrypted or is null: "+result.message);
							} else { 
								$("#log_error").popup(("open"), { transition: 'pop' });
								console.log("Error: unknown error: "+result.message);
							}
					} else {
							$("#log_error").popup(("open"), { transition: 'pop' });
							console.log("Error: "+result.message);
					}
					$.mobile.loading("hide");
				},
				error: function (jqXHR, exception) {
					$("#log_error").popup(("open"), { transition: 'pop' });
					console.log("Error: "+getjqXHRMessage(jqXHR, exception));
					$.mobile.loading("hide");
				}
			});                  
		} else {
			$("#log_fillFields").popup(("open"), { transition: 'pop' });
		}
	});
			
	$(document).on('click', '#menu_signup', function(event) {
		$('#signup').load(sessionStorage.getItem('OwnSafeServerUrl')+'/src/page_signup.php', function() {
			$('#signup').trigger('create');
			$.mobile.changePage('#signup');	
		});
	});

	$(document).on('click', '#menu_login', function(event) {
		$('#login').load(sessionStorage.getItem('OwnSafeServerUrl')+'/src/page_login.php', function() {
			$('#login').trigger('create');
				$.mobile.changePage('#login');
				checkLoginHints();
			});
	});  
});

function generateKey(pepper, iterationsValue) {
        var iterationsValue = parseInt(sessionStorage.getItem("iterationsValue"));
        var keySizeValue = sessionStorage.getItem("keySizeValue");
        var keySizeParts=keySizeValue.split('/');
        var keyPart1 = parseInt(keySizeParts[0]);
        var keyPart2 = parseInt(keySizeParts[1]);
        sessionStorage.setItem("k",CryptoJS.PBKDF2($('#log_password').val(), pepper, { keySize: keyPart1/keyPart2, iterations: iterationsValue}));
        console.log("Key generation completed");  
}

//<!-- ----- signup ----- -->


$(document).on('pageinit', '#signup', function(){

	$(document).on('click', '#su_submit', function(event) {

		if ($('#su_username').val().length > 0 && $('#su_password').val().length > 0 && $('#su_passwordconfirm').val().length > 0){
			
			if ($('#su_password').css('color') != "rgb(255, 0, 0)" && $('#su_password').css('color') != "rgb(0, 0, 0)" ){
				if ($('#su_password').val() == $('#su_passwordconfirm').val()) {

					var formdata = "usr="+encodeURIComponent($('#su_username').val())+"&pwd="+CryptoJS.SHA256( ($('#su_password').val()+$('#su_username').val().toLowerCase()) );
					if (isValidEmailAddress($('#su_username').val())) formdata += "&email="+encodeURIComponent($('#su_username').val());

								$.ajax({url: sessionStorage.getItem('OwnSafeServerUrl')+'/src/signup.php',
								data: formdata,
								dataType: 'json',
								type: 'post',                  
								async: false,
								beforeSend: function() {$.mobile.loading("show");},
								complete: function() {$.mobile.loading("hide");},
								success: function (result) {
									if(result.status) {

										if (result.message == "OK") {

											$("#su_signupSuccess").on({
												popupafterclose: function(event, ui) {
													event.preventDefault();
													event.stopImmediatePropagation();
													$.mobile.changePage("#login");
												}
											});
											$("#su_signupSuccess").popup(("open"), { transition: 'pop'});
										

										} else if (result.message == "uexists") {
											$("#su_signupSuccess").on({
												popupafterclose: function(event, ui) {
													event.preventDefault();
													event.stopImmediatePropagation();
													$("#su_username").val("");
												}
											});
                      $("#su_signupFailed_uexist").popup(("open"), { transition: 'pop'});

										} else if (result.message == "eexists") {
											$("#su_signupSuccess").on({
												popupafterclose: function(event, ui) {
													event.preventDefault();
													event.stopImmediatePropagation();
													$("#su_username").val("");
												}
											});
                      $("#su_signupFailed_eexist").popup(("open"), { transition: 'pop'});

										} else {
                     	$("#su_signupFailed").popup(("open"), { transition: 'pop'});
										}
									} else {
                  	$("#su_error").popup(("open"), { transition: 'pop'});
                    console.log(result.message);
									}
								},
								error: function (jqXHR, exception) {
									$("#su_error").popup(("open"), { transition: 'pop' });
									console.log("Error: "+getjqXHRMessage(jqXHR, exception));
									$.mobile.loading("hide");
								}
							});   
				} else $("#su_passCheck").popup(("open"), { transition: 'pop' });
			} else $("#su_passSecurityCheck").popup(("open"), { transition: 'pop' });
		} else $("#su_fillFields").popup(("open"), { transition: 'pop' });

	});   
	
});


//<!-- ------------------------------------------------------------------------------------------ -->

$( document ).on( "pageshow" ,'#login, #signup, #install, #index', function (event) {
	checkLoginHints();
	checkMenuButtonHilight();
	$('#log_username').focus();
	$('#su_username').focus();
});

$(document).on('keyup', '#log_username, #log_password',function(event) {
    if (event.which == 13) {
            $('#log_submit').click();
            event.preventDefault();
            event.stopImmediatePropagation();
    }
});

$(document).on('keyup', '#su_password, #su_passwordconfirm',function(event) {
    if (event.which == 13) {
            $('#su_submit').click();
            event.preventDefault();
            event.stopImmediatePropagation();
    }
});



$(document).on('blur', '#su_username',function(event) {
	if ($('#su_username').val().length>0) {
		
		var formdata = "usr="+encodeURIComponent($('#su_username').val());
		if (isValidEmailAddress($('#su_username').val())) formdata += "&email="+encodeURIComponent($('#su_username').val());
		
		$.ajax({url: sessionStorage.getItem('OwnSafeServerUrl')+'/src/check-user.php',
			data: formdata,
			dataType: 'json',
			type: 'post',                  
			async: true,
			success: function (result) {
				if(result.status) {

					if (result.message!="free") {

						if (result.message=="uexists") {
							$("#su_signupFailed_uexist").on({
								popupafterclose: function(event, ui) {
									event.preventDefault();
									event.stopImmediatePropagation();
									$("#su_username").val("");
								}
							});
							$("#su_signupFailed_uexist").popup(("open"), { transition: 'pop'});
						
						} else if (result.message=="eexists") {
							$("#su_signupFailed_eexist").on({
								popupafterclose: function(event, ui) {
									event.preventDefault();
									event.stopImmediatePropagation();
									$("#su_username").val("");
								}
							});
							$("#su_signupFailed_eexist").popup(("open"), { transition: 'pop'});
						
						} else {
							$("#su_signupFailed").on({
								popupafterclose: function(event, ui) {
									event.preventDefault();
									event.stopImmediatePropagation();
								}
							});
							$("#su_error").popup(("open"), { transition: 'pop'});
						}
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


$(document).on('click', '#log_sendhint',function(event) {
	
	$("#log_loginFailed").on({
		popupafterclose: function(event, ui) {
			$("#log_loginFailed_email").popup(("open"), { transition: 'pop'});
			event.preventDefault();
			event.stopImmediatePropagation();
			$("#log_loginFailed").unbind();
		}
	});
	$("#log_loginFailed").popup( "close" );



});


$(document).on('click', '#log_sendhint_sendbutton',function(event) {
	$("#log_loginFailed_email").popup( "close" );
	$("#log_loginFailed_email").bind({
		popupafterclose: function(event, ui) {
			event.preventDefault();
			event.stopImmediatePropagation();
			if (!isValidEmailAddress($("#log_sendhint_email").val())) {
				$("#log_emailFailed").popup(("open"), { transition: 'pop'});
			} else {
				var formdata = "email="+encodeURIComponent($("#log_sendhint_email").val())+"&usr="+encodeURIComponent($('#log_username').val());
				
				$.ajax({url: sessionStorage.getItem('OwnSafeServerUrl')+'/src/check-sendmail.php',
					data: formdata,
					dataType: 'json',
					type: 'post',              
					async: false, 
					beforeSend: function() {$.mobile.loading("show");},
					complete: function() {$.mobile.loading("hide");},
					success: function (result) {
						if(result.status) {
							
							$("#log_loginFailed_nohint").on({
								popupafterclose: function(event, ui) {
									event.preventDefault();
									event.stopImmediatePropagation();
								}
							});
							$("#log_loginFailed_noemail").on({
								popupafterclose: function(event, ui) {
									event.preventDefault();
									event.stopImmediatePropagation();
								}
							});
							$("#log_loginFailed_emailok").on({
								popupafterclose: function(event, ui) {
									event.preventDefault();
									event.stopImmediatePropagation();
								}
							});
							$("loginFailed_error").on({
								popupafterclose: function(event, ui) {
									event.preventDefault();
									event.stopImmediatePropagation();
								}
							});
							if (result.message=="nohint") $("#log_loginFailed_nohint").popup(("open"), { transition: 'pop'});
							else if (result.message=="noemail") $("#log_loginFailed_noemail").popup(("open"), { transition: 'pop'});
							else if (result.message=="emailok")	$("#log_loginFailed_emailok").popup(("open"), { transition: 'pop'});
							else $("#log_loginFailed_error").popup(("open"), { transition: 'pop'});
						
						} else {
							$("#log_loginFailed_error").popup(("open"), { transition: 'pop'});
						}
						
					},
					error: function (jqXHR, exception) {
						$("#log_error").popup(("open"), { transition: 'pop' });
						console.log("Error: "+getjqXHRMessage(jqXHR, exception));
						$.mobile.loading("hide");
					}
				}); 
				$("#log_loginFailed_email").unbind();
			}
		}
	});
});


$(document).on('click', '#login_clearclipboard',function(event) {
	copyTextToClipboard(' ');
	sessionStorage.setItem("clearClipboard", "0");
	$("#login_clearclipboard").fadeOut();
});


$(document).on('click', '#menu_login_settings',function(event) {
	sessionStorage.setItem("init", "1");
	checkInstallStatus();
	$.mobile.changePage('#index');

});

$(document).on('click', '#menu_login_install',function(event) {
		$('#install').load(sessionStorage.getItem('OwnSafeServerUrl')+'/src/page_logininstall.php',	function()	{$('#install').trigger('create');});
	$.mobile.changePage('#install');
});

$(document).on('click', '#log_insecurelogin, #su_insecurelogin',function(event) {
	$("#log_insecurelogin").fadeOut();
    $("#su_insecurelogin").fadeOut();
    sessionStorage.setItem("showNoHttpsMessage","0");
});


$(document).on('keyup', '#su_password',function(event) {
    verifypass("#su_password","#su_passwordconfirm");
});

$(document).on('keyup', '#su_passwordconfirm',function(event) {
    if ($( "#su_passwordconfirm" ).val() == $( "#su_password" ).val()) $('#su_passwordconfirm').css("color", "green");
    else $('#su_passwordconfirm').css("color", "red");
});


//<!-- -------------------------------------------------------------------------------------------------------------- -->
function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(emailAddress);
}

function verifypass(passfield, passfieldConfirm) {

    var strongRegex = new RegExp("^(?=.{8,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g");
    var mediumRegex = new RegExp("^(?=.{7,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
    var enoughRegex = new RegExp("(?=.{6,}).*", "g");

     if (false === enoughRegex.test($(passfield ).val())) {
             $(passfield).css("color", "red");
             if ($(passfield ).val().length===0) $(passfield).css("color", "black");
     }  else if (strongRegex.test($(passfield).val())) $(passfield).css("color", "green");
        else if (mediumRegex.test($(passfield).val())) $(passfield).css("color", "orange");
        else $(passfield).css("color", "red");
     
     if ($(passfieldConfirm).val().length>0) {

         if ($(passfieldConfirm).val() == $(passfield).val()) $(passfieldConfirm).css("color", "green");
         else $(passfieldConfirm).css("color", "red");

     } else $(passfieldConfirm).css("color", "black");
} 

var counter = "";
var start_counter = sessionStorage.getItem('logouttime');
if (start_counter<30) start_counter=30;
var count=start_counter;
var documentTitle = document.title;

function timer(){
        count=count-1;
	$(".logout").text(count);
        document.title = documentTitle + " " +count;
	if (count <= 0){
		logout();
		return;
	} else if (count < 10){
		$(".logout").css('color','red');
		if (app) navigator.vibrate(15);
	}
}

function checkMenuButtonHilight () {

	// <!-- ----- hilight buttos ----- -->
	var button = $.mobile.activePage.attr("id");

	$(".menu_login").removeClass( "hilightbackground" );
	$(".menu_signup").removeClass( "hilightbackground" );
	$(".menu_login_settings").removeClass( "hilightbackground" );
	$(".menu_login_install").removeClass( "hilightbackground" );
	switch(button) {
             case "login":
                $(".menu_login").addClass( "hilightbackground" );
                break;
            case "signup":
                $(".menu_signup").addClass( "hilightbackground" );	
                break;
             case "index":
                $(".menu_login_settings").addClass( "hilightbackground" );
                break;
             case "install":
                $(".menu_login_install").addClass( "hilightbackground" );
                break;
            default:
                $(".menu_login").addClass( "hilightbackground" );
	}
}




function checkExportedFileExists() {
		var isExportedFiles =false;
		var fileDir="Ownsafe";
	
		function success(entries) {
				if (entries.length === 0){$('#log_exportfileshint').hide();}
				else {$('#log_exportfileshint').show();	}
		}
		
		function noFolder() {
				$('#log_exportfileshint').hide();
		}
	
		function dirReaderError(err) {
			$('#log_exportfileshint').hide();
			console.log("CheckExportedFiles: "+err.code);
		}
        
        function fileSystemError(err) {
			$('#log_exportfileshint').hide();
			console.log("ResolveFileSystem: "+err.code);
		}
    
        window.resolveLocalFileSystemURL(cordova.file.externalRootDirectory, function (dirEntry) {
				dirEntry.getDirectory(fileDir, { create: false }, function (dirEntry) {
 					var directoryReader = dirEntry.createReader();
   				 	directoryReader.readEntries(success, dirReaderError);
				},noFolder);
		},fileSystemError);
	
}

