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

require 'src/config.php';

$phrase = new Phrase("  223 Ik Ben Een Idioot !!!!  ");
$game = new Game($phrase);

// echo $phrase->getCurrentPhrase() . PHP_EOL;
// $phrase->setCurrentPhrase("  echt wel  ");
// echo $phrase->getCurrentPhrase() . PHP_EOL;

//CORRECT:

$phrase->checkLetter(' B ');
$phrase->checkLetter(' k ');
$phrase->checkLetter('n');
$phrase->checkLetter('d');
$phrase->checkLetter('t');
$phrase->checkLetter(' E ');
$phrase->checkLetter(' o ');
$phrase->checkLetter(' i ');

//INCORRECT
$phrase->checkLetter(' A ');
$phrase->checkLetter('z');
$phrase->checkLetter('c');
$phrase->checkLetter('x');
// $phrase->checkLetter('y');
// $phrase->checkLetter('m');
//var_dump($phrase->getSelected());

echo $game->checkForWin();
echo $game->checkForLose();
// if($game->checkForWin()) {
//   echo "Congratulations";
// }
// else {
//   echo "bummer";
// }
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
        <?php echo $phrase->addPhraseToDisplay(); ?>
    </div>
</div>

</body>
</html>
