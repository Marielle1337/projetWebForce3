document.addEventListener('DOMContentLoaded', function(){


// Changer les feuilles de style
function setActiveStyleSheet(title) {
  var i, a, main;
  for(i=0; (a = document.getElementsByTagName("link")[i]); i++) {
    if(a.getAttribute("rel").indexOf("style") != -1 && a.getAttribute("title")) {
      a.disabled = true;
      if(a.getAttribute("title") == title) a.disabled = false;
    }
  }
}

function getActiveStyleSheet() {
  var i, a;
  for(i=0; (a = document.getElementsByTagName("link")[i]); i++) {
    if(a.getAttribute("rel").indexOf("style") != -1 && a.getAttribute("title") && !a.disabled) return a.getAttribute("title");
  }
  return null;
}

function getPreferredStyleSheet() {
  var i, a;
  for(i=0; (a = document.getElementsByTagName("link")[i]); i++) {
    if(a.getAttribute("rel").indexOf("style") != -1
       && a.getAttribute("rel").indexOf("alt") == -1
       && a.getAttribute("title")
       ) return a.getAttribute("title");
  }
  return null;
}

function createCookie(name,value,days) {
  if (days) {
    var date = new Date();
    date.setTime(date.getTime()+(days*24*60*60*1000));
    var expires = "; expires="+date.toGMTString();
  }
  else expires = "";
  document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
  var nameEQ = name + "=";
  var ca = document.cookie.split(';');
  for(var i=0;i < ca.length;i++) {
    var c = ca[i];
    while (c.charAt(0)==' ') c = c.substring(1,c.length);
    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
  }
  return null;
}

window.onload = function(e) {
  var cookie = readCookie("style");
  var title = cookie ? cookie : getPreferredStyleSheet();
  setActiveStyleSheet(title);
}

window.onunload = function(e) {
  var title = getActiveStyleSheet();
  createCookie("style", title, 365);
}

var cookie = readCookie("style");
var title = cookie ? cookie : getPreferredStyleSheet();
setActiveStyleSheet(title);

document.addEventListener('click', function(event){
	$theme1 = document.getElementById('theme1');
	$theme2 = document.getElementById('theme2');

	if(event.target == $theme2) {
		setActiveStyleSheet("eco");
	} else if(event.target == $theme1) {
		setActiveStyleSheet("standard");
	}
}); 

// Editeur de texte
tinymce.init({
  selector: 'textarea.admin',
  height: 500,
  plugins: [
    'advlist autolink lists link image charmap print preview anchor',
    'searchreplace visualblocks code fullscreen',
    'insertdatetime media table contextmenu paste code'
  ],
  toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
  content_css: [
    '//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
    '//www.tinymce.com/css/codepen.min.css'
  ]
});

// API Instagram
var token = '3567021820.89f2636.d211939a983c4c67a5e9339bf1af7584',
    username = 'benjamincerbai', // rudrastyh - my username :)
    num_photos = 4;
 
$.ajax({ // the first ajax request returns the ID of user rudrastyh
  url: 'https://api.instagram.com/v1/users/search',
  dataType: 'jsonp',
  type: 'GET',
  data: {access_token: token, q: username}, // actually it is just the search by username
  success: function(data){
    console.log(data);
    $.ajax({
      url: 'https://api.instagram.com/v1/users/' + data.data[0].id + '/media/recent', // specify the ID of the first found user
      dataType: 'jsonp',
      type: 'GET',
      data: {access_token: token, count: num_photos},
      success: function(data2){
        console.log(data2);
        for(x in data2.data){
          $('ul').append('<li><img src="'+data2.data[x].images.thumbnail.url+'"></li>');  
        }
          },
      error: function(data2){
        console.log(data2);
        console.log('coucou');
      }
    });
  },
  error: function(data){
    console.log(data);
  }
});


});