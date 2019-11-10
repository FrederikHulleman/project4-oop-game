$(document).ready(function(){
  //make sure the keyboard display prefrence from the user storing in a cookie is applied to the keyboard
  var qwerty = document.getElementById("qwerty");
  var keyboard_display = getCookie('keyboard_display');
  var toggle_link = document.getElementById('toggle_link');
  if (keyboard_display === "block") {
    qwerty.style.display = "block";
    setCookie('keyboard_display', "block", 7);
    toggle_link.innerHTML = 'Click here to hide the onscreen keyboard.';
  }
  else {
    qwerty.style.display = "none";
    setCookie('keyboard_display', "none", 7);
    toggle_link.innerHTML = 'Click here to show the onscreen keyboard.';
  }
  //function to use keydown event and to submit the chosen key
  document.body.addEventListener("keydown", function(event) {
  if (event.keyCode >= 65 && event.keyCode <= 90) {
        var form = document.getElementById("key_board");
        var myvar = document.createElement('input');
        myvar.setAttribute('name', 'key');
        myvar.setAttribute('type', 'hidden');
        myvar.setAttribute('value', String.fromCharCode(event.keyCode));
        form.appendChild(myvar);
        form.submit();
      }
    });

  });
//the function to show or hide the onscreen keyboard
function display_keyboard() {
  //thanks for toggle display to https://www.w3schools.com/howto/howto_js_toggle_hide_show.asp
  //thanks for setting input values to https://stackoverflow.com/questions/1350917/send-post-variable-with-javascript
  var qwerty = document.getElementById("qwerty");
  var toggle_link = document.getElementById('toggle_link');
  if (qwerty.style.display === "none") {
    qwerty.style.display = "block";
    setCookie('keyboard_display', 'block', 7);
    toggle_link.innerHTML = 'Click here to hide the onscreen keyboard.';
  } else {
    qwerty.style.display = "none";
    setCookie('keyboard_display', 'none', 7);
    toggle_link.innerHTML = 'Click here to show the onscreen keyboard.';
  }
}

//thanks to https://www.w3schools.com/js/js_cookies.asp
function setCookie(cname, cvalue, exdays) {
  var d = new Date();
  d.setTime(d.getTime() + (exdays*24*60*60*1000));
  var expires = "expires="+ d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

//thanks to https://www.w3schools.com/js/js_cookies.asp
function getCookie(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for(var i = 0; i <ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}
