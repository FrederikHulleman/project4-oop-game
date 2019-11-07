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
$selected = array();
$current_phrase = '';

require 'src/config.php';

if($_SERVER['REQUEST_METHOD'] != "POST") {
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
</head>

<body>

<div class="main-container">

    <div id="banner" class="section">

        <h2 class="header">Phrase Hunter</h2>
        <?php
        if($game_over_message = $game->gameOver())
        {
          echo $game_over_message;
        }
        else {
          echo $game->displayScore();
          echo $phrase_object->addPhraseToDisplay();
          echo $game->displayKeyboard();
        }
      ?>
    </div>
</div>

</body>
</html>
