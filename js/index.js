// Copyright (C) 2015 Sebastian Plaza
// This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
// This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty
// of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
// You should have received a copy of the GNU General Public License along with this program. 
// If not, see http://www.gnu.org/licenses/. 

/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */

var app = {
    // Application Constructor
    initialize: function() {
        document.addEventListener('deviceready', this.onDeviceReady.bind(this), false);
    },

    // deviceready Event Handler
    //
    // Bind any cordova events here. Common events are:
    // 'pause', 'resume', etc.
    onDeviceReady: function() {
        this.receivedEvent('deviceready');
    },

    // Update DOM on a Received Event
    receivedEvent: function(id) {
        var parentElement = document.getElementById(id);
        var listeningElement = parentElement.querySelector('.listening');
        var receivedElement = parentElement.querySelector('.received');

        listeningElement.setAttribute('style', 'display:none;');
        receivedElement.setAttribute('style', 'display:block;');

        console.log('Received Event: ' + id);
    }
};

app.initialize();


if(typeof(Storage) === "undefined") {
    alert("Storage not supported! Use another browser such as Firefox");
}

sessionStorage.setItem("ver","Ver. 1.26");
console.log("OwnSafe - "+sessionStorage.getItem("ver"));

var serverUrl = getCookie("OwnSafeServerUrl");
if (serverUrl && serverUrl.trim().length > 0) {
        localStorage.setItem('OwnSafeServerUrl',serverUrl);
        sessionStorage.setItem('OwnSafeServerUrl',serverUrl);
        console.log("ServerURL: "+sessionStorage.getItem('OwnSafeServerUrl'));
} else if (localStorage.getItem('OwnSafeServerUrl') && localStorage.getItem('OwnSafeServerUrl').trim().length !== 0) {
        console.log("LS ServerURL: "+localStorage.getItem('OwnSafeServerUrl'));
        sessionStorage.setItem('OwnSafeServerUrl',localStorage.getItem('OwnSafeServerUrl'));
}

var languageChoiceString = '<option value="EN">English</option><option value="DE">Deutsch</option><option value="PL">Polski</option>';       

var language = getCookie("OwnSafeLanguage");
if (language && language.trim().length > 0) {
        localStorage.setItem('OwnSafeLanguage',language);
        sessionStorage.setItem('OwnSafeLanguage',language);
        console.log("Language: "+sessionStorage.getItem('OwnSafeLanguage'));
} else if (localStorage.getItem('OwnSafeLanguage') && localStorage.getItem('OwnSafeLanguage').trim().length !== 0) {
        console.log("LS Language: "+localStorage.getItem('OwnSafeLanguage'));        
        sessionStorage.setItem('OwnSafeLanguage',localStorage.getItem('OwnSafeLanguage'));    
} else {
    localStorage.setItem('OwnSafeLanguage','EN');
    sessionStorage.setItem('OwnSafeLanguage','EN');
}
var isApp = document.URL.indexOf( 'http://' ) === -1 && document.URL.indexOf( 'https://' ) === -1;

$(document).on('pageinit', '#login', function(){
    if (sessionStorage.getItem('OwnSafeServerUrl') && sessionStorage.getItem('OwnSafeServerUrl')!=="null" && sessionStorage.getItem('OwnSafeServerUrl').trim().length > 0) {
         $('#login').load(sessionStorage.getItem('OwnSafeServerUrl')+'/src/page_login.php', function(data, textStatus, jqXHR){
            console.log("Init: "+textStatus);
            if (textStatus === "success" || textStatus === "notmodified") {
                $('#login').trigger('create');
                $.mobile.changePage("#login");
                checkLoginHints();
                checkMenuButtonHilight();
            } else {
                $.mobile.changePage('#index');
            }
		});
    } else {
        $.mobile.changePage('#index');
    }
}); 


$(document).on('pageinit', '#index', function(){
     checkInstallStatus();    
     $(document).on('click', '#submit_button', function() {

        if($('#url').length && $('#url').val().length > 0){
            $('#url').val($('#url').val().trim());
            if ($('#url').val().slice(-1)=="/") $('#url').val($('#url').val().slice(0, -1));
            
			var url = $('#url').val().trim()+'/src/init.php';
            console.log("Trýing to connect to "+url);
			if ($("#language")) {
            
                    language = $("#language").val();
				
                    var formdata = 'language='+language;
                    $.ajax({url: url,
                    data: formdata,
                    dataType: 'json',
                    beforeSend: function() {$.mobile.loading("show");},
                    complete: function() {$.mobile.loading("hide");},
                    success: function (result) {
                        if (result.status == "OK") {
                            setCookie("OwnSafeServerUrl",$('#url').val(),3650);
                            setCookie("OwnSafeLanguage",language,3650);
                            localStorage.setItem('OwnSafeServerUrl',$("#url").val());
                            localStorage.setItem('OwnSafeLanguage',language);
                            sessionStorage.setItem('OwnSafeServerUrl',$("#url").val());
                            sessionStorage.setItem('OwnSafeLanguage',language);
                            $("#error").html("");
                            console.log("Submit OK");
                            $('#login').load(sessionStorage.getItem('OwnSafeServerUrl')+"/src/page_login.php",function(){
                                $('#login').trigger('create');
                                $.mobile.changePage("#login");
                                checkLoginHints();
                                checkMenuButtonHilight();
                                $("#serverUrlInput").hide();
                            });

                            
                        } else {
                            var html = "<div>Connection error:</div>";
                            html += result.error;
                            console.log(html);
                            $.mobile.loading("hide");
                            $("#serverUrlInput").fadeIn();
                            $("#error").html(html);
                            
                        }
                    },
                        error: function (jqXHR, exception) {
                        var html = "<div>Connection error:</div>";
                        html += getjqXHRMessage(jqXHR, exception);
                        console.log("Init: "+exception);
                        console.log(html);
                        $.mobile.loading("hide");
                        $("#serverUrlInput").fadeIn();
                        $("#error").html(html);
                    }			
				});
			} else console.log("No language");
		} else {
			console.log("No URL");
		}
	});	
	if (!isApp) $('#index_install_browser').hide();
	//   $('#footer_init_text').text("OwnSafe - "+sessionStorage.getItem("ver"));
});

$(document).on('click', '#index_url_href',function(event) {
    if (isApp) {
        window.open($('#index_url_href').attr('href'), '_system','location=yes');
        return false;
    }
});

$(document).on('click', '#index_install', function(event) {
			$.mobile.changePage('#install');	
});

$(document).on('click', '#index_download_osfb', function(event) {
			var link = "http://www.diahal.de/ownsafeApp/INSTALL/ownsafe_for_browser.tar.gz";
			if (isApp) window.open(link, '_system');
            else  window.open(link, '_blank');
});

$(document).on('click', '#index_download_os', function(event) {
			var link = "/INSTALL/ownsafe.tar.gz";
			if (isApp) window.open(link, '_system');
            else  window.open(link, '_blank');
});

// <!-- ----- hilight buttos ----- -->
$( document ).on( "pageshow" , '#install, #index', function ( event, data ) {
    
	var button = $.mobile.activePage.attr("id");
	$(".index_settings_button").removeClass( "hilightbackground" );
	$(".index_install_button").removeClass( "hilightbackground" );
	switch(button) {
	case "index":
        $('#menu_indexfooter_text').html("OwnSafe - "+sessionStorage.getItem("ver"));
		$(".index_settings_button").addClass( "hilightbackground" );
        break;
    case "install":
        $('#menu_installfooter_text').html("OwnSafe - "+sessionStorage.getItem("ver"));
       	$(".index_install_button").addClass( "hilightbackground" );	
        break;
    default:
        $('#menu_indexfooter_text').html("OwnSafe - "+sessionStorage.getItem("ver"));
        $(".index_settings_button").addClass( "hilightbackground" );
	} 
});
//<!-- ------------------------------------------------------------------------------------------ -->
function checkInstallStatus() {

     if (!sessionStorage.getItem('OwnSafeServerUrl') || sessionStorage.getItem('OwnSafeServerUrl')==="null" || sessionStorage.getItem('OwnSafeServerUrl').trim().length === 0  || sessionStorage.getItem('init')==="1") {
	
        if (isApp) {
           	$("#serverUrlInput").show();
        } else {
            $("#url").val(document.URL);
            if (sessionStorage.getItem('init')==="1") $("#serverUrlInput").show();
            else $("#serverUrlInput").hide();
        }
         
        if (sessionStorage.getItem('OwnSafeServerUrl') && sessionStorage.getItem('OwnSafeServerUrl')!=="null" && sessionStorage.getItem('OwnSafeServerUrl').trim().length > 0) {
            $("#url").val(sessionStorage.getItem('OwnSafeServerUrl'));
        }
        $('#language').html(languageChoiceString);
        $("#language").val(sessionStorage.getItem('OwnSafeLanguage'));
        $('#language').selectmenu('refresh');
        $("#indexHeader").show();
        $("#indexFooter").show();
        $("#init").show();
        $('#waitcontainer').hide();
         
	} else {
        var html = "<div>Connection error: "+sessionStorage.getItem('OwnSafeServerUrl')+"</div>";
        $("#url").val(sessionStorage.getItem('OwnSafeServerUrl'));
        $('#language').html(languageChoiceString);
        $("#language").val(sessionStorage.getItem('OwnSafeLanguage'));
        $('#language').selectmenu('refresh');
		$("#error").html(html);
        $("#indexHeader").show();
        $("#indexFooter").show();
        $("#init").show();
        $('#waitcontainer').hide();
		$('#backindex').hide();
    }
}


function getjqXHRMessage(jqXHR, exception) {
        var msg = '';
			
        if (jqXHR.status === 0) {
            msg = 'Not connect.\n Verify Network or URL.';
        } else if (jqXHR.status == 404) {
            msg = 'Requested page not found. [404]';
        } else if (jqXHR.status == 500) {
            msg = 'Internal Server Error [500].';
        } else if (exception === 'parsererror') {
            msg = 'Requested JSON parse failed.';
        } else if (exception === 'timeout') {
            msg = 'Time out error.';
        } else if (exception === 'abort') {
            msg = 'Ajax request aborted.';
        } else {
			msg = 'Uncaught Error: ' + jqXHR.responseText + '<br>';
        }
    return msg;
}

$(document).on('keyup', '#serverUrlInput',function(event) {
    if (event.which == 13) {
            $('#submit_button').click();
            event.preventDefault();
			event.stopImmediatePropagation();
    }
});

$(document).on('change focusout', '#language', function() {
    if (!isApp) {
         $('#submit_button').click();
    }
})


function checkLoginHints() {
 	if (sessionStorage.getItem("showNoHttpsMessage")=="1" && window.location.protocol != "https:") $('#log_insecurelogin').show();
 	if (sessionStorage.getItem("showNoHttpsMessage")=="1" && window.location.protocol != "https:") $('#su_insecurelogin').show();
 	if (sessionStorage.getItem('clearClipboard') && sessionStorage.getItem('clearClipboard')=="1") $('#login_clearclipboard').show();
 	if (sessionStorage.getItem('logoutmessage') && sessionStorage.getItem('logoutmessage') !== null)  {
 		$('#log_logoutmessage').show();
 		sessionStorage.removeItem('logoutmessage');
 	}
    $('.menu_footer_text').html("OwnSafe - "+sessionStorage.getItem("ver"));
	if (app && $.mobile.activePage.attr('id')=="login") {
        document.addEventListener("deviceready", checkExportedFileExists, true);
    }
} 


function setCookie(name,value,duration){
      var now=new Date();
      var expireTime=new Date(now.getTime()+duration*86400000);
      document.cookie=name+"="+value+";expires="+expireTime.toGMTString()+";";
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) === 0) return c.substring(name.length,c.length);
    }
    return "";
}

function removeCookie(cname) {
    setCookie(cname,"",0);
}
