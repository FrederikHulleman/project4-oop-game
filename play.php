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

session_start();
require 'src/config.php';
$selected = array();
$current_phrase = '';
$display_keyboard = '';

//to read the previous keyboard display setting: none or block
if(!empty($_POST['display_keyboard'])) {
  $display_keyboard = filter_input(INPUT_POST,'display_keyboard',FILTER_SANITIZE_STRING);
}
else {
  $display_keyboard = 'none';
}

if($_SERVER['REQUEST_METHOD'] != "POST" || empty($_POST['key'])) {
  session_destroy();
  $_SESSION['selected'] = array();
  $_SESSION['current_phrase'] = '';
  session_start();
}
else {
  if(!empty($_SESSION['selected'])) {
    $selected = $_SESSION['selected'];
  }
  if(!empty($_SESSION['current_phrase'])) {
    $current_phrase = $_SESSION['current_phrase'];
  }
}

$phrase_object = new Phrase($current_phrase,$selected);
$game = new Game($phrase_object);

if(empty($_SESSION['current_phrase'])) {
  $_SESSION['current_phrase'] = $phrase_object->getCurrentPhrase();
}

if(!empty($_POST['key'])) {
  $key = trim(
            strtolower(
              filter_input(INPUT_POST,'key',FILTER_SANITIZE_STRING)
              )
            );
  $phrase_object->setSelected($key);
  $_SESSION['selected'] = $phrase_object->getSelected();
}

$phrase_object->setPhraseAnswer(' i     smell a    rat     ');

echo "phrase current: " . $phrase_object->getCurrentPhrase() . '<br>';
echo "phrase answer: " . $phrase_object->getPhraseAnswer() . '<br>';

if($phrase_object->checkPhraseAnswer()) {
  echo "Phrase answered corectly;";
} else {
  echo "Phrase answered incorectly;";
}

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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <!-- javascript to provide the user with the possibility to use the keys on his/her keyboard -->
    <script type="text/javascript" src="src/js-file.js"></script>

</head>

<body>
<div class="main-container">

    <div id="banner" class="section">

        <!-- <h2 class="header">Phrase Hunter</h2> -->
        <?php
        if($game_over_message = $game->gameOver())
        {
          echo $game_over_message;
        }
        else {
          echo $game->displayScore();
          echo $phrase_object->addPhraseToDisplay();
          echo $game->displayKeyboard($display_keyboard);
        }
      ?>


    </div>
</div>

</body>
</html>
