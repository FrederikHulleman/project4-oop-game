// if mode = single_characters, means that the javascript is executed related to the keyboard hide/show and to 'listen' to keydown events
if(mode == 'single_characters') {
  //make sure the keyboard display prefrence from the user is retrieved from the cookie and is applied to the keyboard
  //thanks for toggle display to https://www.w3schools.com/howto/howto_js_toggle_hide_show.asp
  var qwerty = document.getElementById("qwerty");
  var keyboard_display = getCookie('keyboard_display');
  var toggle_link = document.getElementById('toggle_link');
  if (keyboard_display === "none") {
    qwerty.style.display = "none";
    setCookie('keyboard_display', "none", 7);
    toggle_link.innerHTML = 'Click here to show the onscreen keyboard.';
  }
  else {
    qwerty.style.display = "block";
    setCookie('keyboard_display', "block", 7);
    toggle_link.innerHTML = 'Click here to hide the onscreen keyboard.';
  }
  //function to use keydown event and to submit the chosen key
  //thanks for setting input values to https://stackoverflow.com/questions/1350917/send-post-variable-with-javascript
  //thanks for addEventListener keydown to https://stackoverflow.com/questions/53093958/how-to-run-javascript-on-keypress-without-an-input-field
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
}
// if mode = full_answer, means that the javascript is executed related to keyup events and shift the focus to the next or previous input
else if(mode == 'full_answer') {
  //changing focus to next input field when an input field has reached is max length
  //changing focus to previous input field when delete or backspace was used to remove all characters from an input field
  //thanks to: https://stackoverflow.com/questions/15595652/focus-next-input-once-reaching-maxlength-value
  document.body.addEventListener("keyup", function(e) {
    var target = e.srcElement || e.target;
    var maxLength = parseInt(target.attributes["maxlength"].value, 10);
    var myLength = target.value.length;
    if (myLength >= maxLength) {
        var next = target;
        while (next = next.nextElementSibling) {
            if (next == null)
                break;
            if (next.tagName.toLowerCase() === "input") {
                next.focus();
                break;
            }
        }
    }
    // Move to previous field if empty (user pressed backspace)
    else if (myLength === 0 && (e.keyCode == 8 || e.keyCode == 46)) {
        var previous = target;
        while (previous = previous.previousElementSibling) {
            if (previous == null)
                break;
            if (previous.tagName.toLowerCase() === "input") {
                previous.focus();
                break;
            }
        }
    }
  });
}
