<?php
/*
Game.php to create a Game class with methods for showing the game, handling interactions, and checking for game over.
*/

class Game
{
  /*
  The Game class properties:
    - $phrase an instance of the Phrase class to use with the game
    - $lives an integer for the number of wrong chances to guess the phrase
    - $maxLives an integer for maximum number of wrong changes to guess the phrase. This property is used to calculated the lives property: lives = maxLives minus #incorrect characters

  The Game class methods:
    - __construct($phrase) > initialize object with a phrase object
    - getLives() > returns how many wrong changes the user has to guess the phrase
    - setLives($lives) > sets how many wrong changes the user has to guess the phrase
    - checkForWin() > validates whether the user guessed all correct characters
    - checkForLose() > validates whether the user has 0 lives left, or not
    - gameOver() > validates whether the games has ended or not, and if not, whether the user won or lost (for both scenario's: answered full phrase,
          or by entering character by character). Prepares the  HTML to display the message 
    - displayKeyboard() > prepares HTML string with styling for the onscreen keyboard
    - displayScore() > prepare HTML to display how many lives the user still has
  */
  private $phrase;
  private $lives;
  private $maxLives = 5;

  /*
    The class should include a constructor which accepts a Phrase object and sets the property
  */
  public function __construct($phrase)
  {
    $this->phrase = $phrase;
  }

  public function getLives()
  {
    return $this->lives;
  }

  public function setLives($lives)
  {
    $this->lives = $lives;
  }

  /*
  - checkForWin(): this method checks to see if the player has selected all of the letters.
  */
  public function checkForWin()
  {
    //thanks to https://www.php.net/manual/en/function.array-diff.php
    //compare all unique characters in the phrase with all correctly selected characters.
    //$remaining_correct_characters is the array with the values which are in the 1st array, but not in the 2nd array. So these are the remaining correct characters the user still has to select.
    $remaining_correct_characters = array_diff($this->phrase->getCurrentPhraseAllCharacters(),$this->phrase->getSelectedCorrect());

    if(empty($remaining_correct_characters) && is_array($remaining_correct_characters)) {
      return TRUE;
    }
    return FALSE;
  }

  /*
  - checkForLose(): this method checks to see if the player has guessed too many wrong letters.
  */

  public function checkForLose()
  {
    //thanks to https://www.php.net/manual/en/function.array-diff.php
    //compare all selected characters from phrase->getSelected() with the unique characters in the phrase from phrase->getCurrentPhraseAllCharacters()
    //$incorrect_characters is the array with the values which are in the 1st array, but not in the 2nd array. So these are all incorrect characters.
    $incorrect_characters = array_diff($this->phrase->getSelected(),$this->phrase->getCurrentPhraseAllCharacters());

    if(is_array($incorrect_characters)) {
      $count_incorrect = count($incorrect_characters);
      //set remaining lives
      $this->setLives($this->maxLives - $count_incorrect);
      if($this->getLives() == 0) {
        return TRUE;
      }
    }
    return FALSE;
  }

  /*
  - gameOver(): this method displays one message if the player wins and another message if they lose.
    It returns false if the game has not been won or lost.
  */
  public function gameOver()
  {
    $image = $game_over_message = '';

    $start_tag = '<h1 id="game-over-message">' . PHP_EOL;
    $end_tag = '</h1>' . PHP_EOL;

    //the html for the button to start over
    $start_over_button = '<form method="post" action="play.php">' . PHP_EOL;
    $start_over_button .= '<input id="btn__reset" name="start" type="submit" value="Start Over" />' . PHP_EOL;
    $start_over_button .= '</form>' . PHP_EOL;

    //check whether the user has submitted a full phrase answer, if he/she did:
    if(!empty($this->phrase->getPhraseAnswer()))
    {
      //if the full phrase answer is correct:
      if($this->phrase->checkPhraseAnswer())
      {
        $image = '<img src="images/win.gif" />' . PHP_EOL;
        $game_over_message = 'WOW. Bonus points!<br>You filled in the right phrase in one time: ';
      }
      //if the full phrase answer is incorrect:
      else {
        $image = '<img src="images/lose.gif" />' . PHP_EOL;
        $game_over_message = 'That\'s too bad! That wasn\'t the right phrase.<br>The phrase was: ';
      }
    }
    //if no full phrase answer was submitted:
    else
    {
      //checkForWin says all correct characters have been selected, and checkForLose says less than maxLives incorrect characters were submitted
      if($this->checkForWin() && !$this->checkForLose())
      {
        $game_over_message = 'Congratulations on guessing: ';
      }
      //checkForWin says not all correct characters have been selected, and checkForLose says maxLives or more incorrect characters were submitted
      elseif($this->checkForLose() && !$this->checkForWin())
      {
        $game_over_message = 'Better luck next time! The phrase was: ';
      }
    }

    if(!empty($game_over_message)) {
      $output = $image
                . $start_tag
                . $game_over_message
                . '"'. $this->phrase->getCurrentPhrase() . '"'
                . $end_tag
                . $start_over_button;
      return $output;
    }
    return FALSE;

  }

  /*
  - displayKeyboard(): Create a onscreen keyboard form.
    See the example_html/keyboard.txt file for an example of what the render HTML for the keyboard should look like. If the letter has been selected the button should be disabled.
    Additionally, the class "correct" or "incorrect" should be added based on the checkLetter() method of the Phrase object.
    Return a string of HTML for the keyboard form.
  */
  public function displayKeyboard()
  {
    //create array with all keys and rows
    $keyrows = array();
    $keyrows[] = array('q','w','e','r','t','y','u','i','o','p');
    $keyrows[] = array('a','s','d','f','g','h','j','k','l');
    $keyrows[] = array('z','x','c','v','b','n','m');

    $keyboard_string = '';
    //info & link for the user to hide or show on screen keyboard
    $keyboard_string .= '<p>You can use your machine\'s keyboard. ' . PHP_EOL;
    $keyboard_string .= '<a id="toggle_link" href="javascript:;" onclick="display_keyboard()">Click here to show the onscreen keyboard.</a>' . PHP_EOL;
    $keyboard_string .= '</p>' . PHP_EOL;
    $keyboard_string .= '<div id="qwerty" class="section" style="display:block">' . PHP_EOL;

    //start form
    $keyboard_string .= '<form id="key_board" method="post" action="play.php">' . PHP_EOL;

    // walk through row by row
    foreach($keyrows as $keyrow) {
      $keyboard_string .= '<div class="keyrow">' . PHP_EOL;

      // walk through key by key
      foreach($keyrow as $key) {
        $keyboard_string .= '<button id="key" name="key" value="'.$key.'"';

        //default styling
        $styling = ' class="key"';

        //reset styling when character was selected and correct or incorrect
        if(in_array($key,$this->phrase->getSelected())) {
          $styling = ' class="key ';
          //if character was incorrect
          if(!$this->phrase->checkLetter($key)) {
            $styling .= 'in';
          }
          $styling .= 'correct" disabled';
        }

        $keyboard_string .= $styling;
        $keyboard_string .= '>'. $key .'</button>' . PHP_EOL;
      }

      $keyboard_string .= '</div>' . PHP_EOL;
    }
    $keyboard_string .= '</form>' . PHP_EOL;
    $keyboard_string .= '</div>' . PHP_EOL;

    return $keyboard_string;
  }

  /*
  - displayScore(): Display the number of guesses available. See the example_html/scoreboard.txt file for an example of what the render HTML for a scoreboard should look like.
    Return string HTML of Scoreboard.
  */
  public function displayScore()
  {
    $score_string = '';
    $score_string .= '<div id="scoreboard" class="section">' . PHP_EOL;
    $score_string .= '<p>Remaining lives:</p>' . PHP_EOL;
    $score_string .= '<ol>' . PHP_EOL;

    for ($i=1; $i <= $this->maxLives; $i++) {
      $score_string .= '<li class="tries"><img src="images/';
      if ($i <= $this->getLives()) {
        $score_string .= 'liveHeart.png" class="lives heartBeat"';
      } else {
        $score_string .= 'lostHeart.png" class="looseHearts"';
      }
      $score_string .= ' height="35px" widght="30px"></li>' . PHP_EOL;
    }

    $score_string .= '</ol>' . PHP_EOL;
    $score_string .= '</div>' . PHP_EOL;

    return $score_string;

  }

}
?>
