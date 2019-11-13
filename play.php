<?php
/*
play.php to handle the HTML, instantiating objects, storing sessions and calling appropriate methods

This file creates a new instance of the Phrase class which OPTIONALLY accepts the current phrase as a string, and an array of selected letters.

This file creates a new instance of the Game class which accepts the created instance of the Phrase class.

The constructor should handle storing the phrase string and selected letters in sessions or another storage mechanism.

In the body of the page you should play the game. To play the game:
  - Use the gameOver method to check if the game has been won or lost and display appropriate messages.
  - If the game is still in play, display the game items: displayPhrase(), displayKeyboard(), displayScore()
*/

//------------------------------------------ 1. INITIALIZATION ----------------------------------------------------

session_start();
require 'src/config.php';
$selected = $words = array();
$current_phrase = '';
$phrase_answer = '';
//$show_full_answer_screen > to control which screen should be displayed: true: show the form for the full phrase answer; false: show the single character screen with keyboard
//default is 'single character' screen
$show_full_answer_screen = false;


//------------------------------------------ 2. SESSION VARIABLES ----------------------------------------------------

//only destroy the session if the request method is something else than  post (e.g. GET) and when all buttons used within a round are not used
if($_SERVER['REQUEST_METHOD'] != "POST" || (empty($_POST['go_back']) && empty($_POST['key']) && empty($_POST['to_answer']) && empty($_POST['submit_answer']))) {
  session_destroy();
  $_SESSION['selected'] = array();
  $_SESSION['current_phrase'] = '';
  session_start();
}
//If request method is POST and one of the buttons used within a round is used, then the session is used here
else {
  if(!empty($_SESSION['selected'])) {
    $selected = $_SESSION['selected'];
  }
  if(!empty($_SESSION['current_phrase'])) {
    $current_phrase = $_SESSION['current_phrase'];
  }
}

//------------------------------------------ 3. INITIALIZE OBJECTS  ----------------------------------------------------

//initialize phrase & selected objects, if it's not a new round, then the $selected and $current_phrase from the session are input for the Phrase  object
$phrase_object = new Phrase($current_phrase,$selected);
$game = new Game($phrase_object);

//in case a new round was started and the session is empty, then the new current  phrase should be stored in a session
if(empty($_SESSION['current_phrase'])) {
  $_SESSION['current_phrase'] = $phrase_object->getCurrentPhrase();
}

//------------------------------------------ 4. HANDLE FORM SUBMITS  ----------------------------------------------------
/*
    **************************************** 4.1. SINGLE CHARACTER SCREEN ***********************************************
*/
//when a user pressed or clicked a key on the keyboard, this character should be added to the select property of the phrase object and the session variable should be updated
if(!empty($_POST['key'])) {
  $key = trim(
            strtolower(
              filter_input(INPUT_POST,'key',FILTER_SANITIZE_STRING)
              )
            );
  $phrase_object->setSelected($key);
  $_SESSION['selected'] = $phrase_object->getSelected();
}

//if the user clicked the  'i know  the answer' button, then the full phrase answer sreen should be displayed
if(!empty($_POST['to_answer'])) {
  $show_full_answer_screen = true;
}

/*
    **************************************** 4.2. FULL PHRASE ANSER SCREEN ***********************************************
*/
//if the user clicked the  'go back' button on the full phrase answer screen, then the 'single character' screen should be displayed
if(!empty($_POST['go_back'])) {
  $show_full_answer_screen = false;
}

//when a user submits a full phrase answer, after validation of the input, the phrase object is updated with the submitted phrase answer
if(!empty($_POST['submit_answer'])) {
  $words = filter_input(INPUT_POST,'words',FILTER_SANITIZE_STRING,FILTER_REQUIRE_ARRAY);

  //to validate whether one of the words is empty
  $continue = true;
  foreach($words as $word) {
    if(empty($word)) {
      $continue = false;
    }
  }

  //if all words are not empty:
  if ($continue) {
    //convert single words into 1 string, separated by spaces
    $phrase_answer = implode(' ',$words);
    $phrase_object->setPhraseAnswer($phrase_answer);
    //the user returns to default mode: single character
    $show_full_answer_screen = false;
  }
  //if 1 or more words are empty:
  else {
    //the user gets another change to fill  in the full  phrase answer
    $show_full_answer_screen = true;
    $message = 'You didn\'t fill in all words';
  }
}


//------------------------------------------ 5. BUILD UP HTML   ----------------------------------------------------
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Phrase Hunter</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/styles.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <!-- javascript to provide the user with the possibility to use the keys on his/her keyboard -->


</head>

<body>
<div class="main-container">
    <div id="banner" class="section">
        <?php
        //display potential relevant messages
        if(!empty($message)) {
          echo '<h3 class="message">'.$message.'</h3>';
        }

        //validate whether the game is over or not
        if($game_over_message = $game->gameOver())
        {
          //if yes, display error message
          echo $game_over_message;
        }
        //if the game still continious:
        else {

          //if the user wants to fill in the full phrase, display the input text fields and the letter boxed with the previously correctly  guessed characters,
          //so the user can use the correct guesses from the 'single character' screen to fill in the full phrase  answer
          if($show_full_answer_screen) {
            //display letter boxes  with correctly guessed characters:
            echo $phrase_object->addPhraseToDisplay($show_full_answer_screen);
            //display full phrase answer input  form
            echo $phrase_object->displayInputFullPhrase($words);
          }
          //if the user is still in 'single character' mode, then display the score, letter boxes and keyboard should be displayed
          else {
            echo $game->displayScore();
            echo $phrase_object->addPhraseToDisplay($show_full_answer_screen);
            echo $game->displayKeyboard();
          }


        }
      ?>


    </div>
</div>
<!-- ------------------------------------------ 6. JAVASCRIPT   ---------------------------------------------------- -->
<script type="text/javascript">
  // make sure the other scripts also know on which screen the user is, so the right functions for the right screen are executed
  var mode = '<?php if ($show_full_answer_screen) {echo 'full_answer';} else {echo 'single_characters';} ?>';
</script>
<script type="text/javascript" src="src/functions.js"></script>
<script type="text/javascript" src="src/main.js"></script>
</body>
</html>
