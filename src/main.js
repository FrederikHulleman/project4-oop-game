// if mode = single_characters, means that the javascript is executed related to the keyboard hide/show and to 'listen' to keydown events
if(mode == 'single_characters') {
  //make sure the keyboard display prefrence from the user is retrieved from the cookie and is applied to the keyboard
  //thanks for toggle display to https://www.w3schools.com/howto/howto_js_toggle_hide_show.asp
  var qwerty = document.getElementById("qwerty");
  var keyboard_display = getCookie('keyboard_display');
  var toggle_link = document.getElementById('toggle_link');
  //if cookie value is none, then apply it, change the link text and set a cookie
  if (keyboard_display === "none") {
    qwerty.style.display = "none";
    setCookie('keyboard_display', "none", 7);
    toggle_link.innerHTML = 'Click here to show the onscreen keyboard.';
  }
  //if cookie value is NOT none, then apply 'block', change the link text and set a cookie
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
      //create a hidden input form field with the value of the pressed key
      myvar.setAttribute('name', 'key');
      myvar.setAttribute('type', 'hidden');
      //fromCharCode returns the actual character (instead of the keycode)
      myvar.setAttribute('value', String.fromCharCode(event.keyCode));
      //link the new hidden input field to the key_board form
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
    //to select the current input element which triggered the keyup  event:
    var target = e.srcElement || e.target;
    //retrieve the max length value of the current input element
    var maxLength = parseInt(target.attributes["maxlength"].value, 10);
    //retrieve the string length of the current value of the current input element
    var myLength = target.value.length;
    //if the current value length is equal or higher than the max value, put the focus to the next input element
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
    //if the current value length is zero, and the last key used is delete or backspace, put the focus to the previous input element
    //if the check on backspace or delete is not applied, the focus might jump back to the previous after hitting a regular key 
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
