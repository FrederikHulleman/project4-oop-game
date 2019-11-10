$(document).ready(function(){
  document.body.addEventListener("keydown", function(event) {
      if (event.keyCode >= 65 && event.keyCode <= 90) {
        form = document.getElementById("key_board");
        myvar = document.createElement('input');
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

  form = document.getElementById("key_board");
  myvar = document.createElement('input');

  var x = document.getElementById("qwerty");
  if (x.style.display === "none") {
    x.style.display = "block";
    myvar.setAttribute('name', 'display_keyboard');
    myvar.setAttribute('type', 'hidden');
    myvar.setAttribute('value', 'block');
    form.appendChild(myvar);
  } else {
    x.style.display = "none";
    myvar.setAttribute('name', 'display_keyboard');
    myvar.setAttribute('type', 'hidden');
    myvar.setAttribute('value', "none");
    form.appendChild(myvar);
  }


}
