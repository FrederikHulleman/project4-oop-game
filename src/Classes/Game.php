<?php
/*
Game.php to create a Game class with methods for showing the game, handling interactions, and checking for game over.
*/

class Game
{
  /*
    The class must have at least two properties:
    - $phrase an instance of the Phrase class to use with the game
    - $lives an integer for the number of wrong chances to guess the phrase
  */
  private $phrase;
  private $lives;
  private $max_lives = 5;

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
    //thanks to https://www.php.net/manual/en/function.array-filter.php
    //create an array with previous selected characters which were correct. Each character from the selected array from the phrase, is validated by using the checkLetter method of the phrase
    $selected_correct_characters = array_filter(
                                      $this->phrase->getSelected(),
                                      array($this->phrase,'checkLetter')
                                    );

    //thanks to https://www.php.net/manual/en/function.count-chars.php
    //create an array with all allowable unique characters in the phrase
    //count_chars gives a string with all unique characters in the phrase
    //str_split creates an array with all unique characters
    $all_correct_characters = str_split(
                          count_chars(
                            str_replace(' ','',$this->phrase->getCurrentPhrase())
                          ,3)
                        );

    //thanks to https://www.php.net/manual/en/function.array-diff.php
    //compare all unique characters in the phrase with all correctly selected characters.
    //$diff is the array with the values which are in the 1st array, but not in the 2nd array. So these are the remaining correct characters the user still has to select.
    $diff = array_diff($all_correct_characters,$selected_correct_characters);

    if(empty($diff) && is_array($diff)) {
      return TRUE;
    }
    return FALSE;
  }

  /*
  - checkForLose(): this method checks to see if the player has guessed too many wrong letters.
  */

  public function checkForLose()
  {
    //thanks to https://www.php.net/manual/en/function.count-chars.php
    //create an array with all allowable unique characters in the phrase
    //count_chars (mode 3) gives a string with all unique characters in the phrase
    //str_split creates an array with all unique characters
    $all_correct_characters = str_split(
                                count_chars(
                                  str_replace(' ','',$this->phrase->getCurrentPhrase())
                                ,3) // mode 3: to give a string of all unique characters in string
                              );

    //thanks to https://www.php.net/manual/en/function.array-diff.php
    //compare all unique characters in the phrase with all correctly selected characters.
    //$diff is the array with the values which are in the 1st array, but not in the 2nd array. So these are the remaining correct characters the user still has to select.

    $diff = array_diff($this->phrase->getSelected(),$all_correct_characters);

    if(is_array($diff)) {
      $count_incorrect = count($diff);
      $this->setLives($this->max_lives - $count_incorrect);
      if($count_incorrect >= 5) {
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

    $start_over_button = '<form method="post" action="play.php">' . PHP_EOL;
    $start_over_button .= '<input id="btn__reset" name="start" type="submit" value="Start Over" />' . PHP_EOL;
    $start_over_button .= '</form>' . PHP_EOL;

    if(!empty($this->phrase->getPhraseAnswer()))
    {
      if($this->phrase->checkPhraseAnswer())
      {
        return '<img src="images/win.gif" />'
                  . '<h1 id="game-over-message">WOW. Bonus points!<br>You filled in the right phrase in one time: "'
                  . $this->phrase->getCurrentPhrase()
                  . '"</h1>' . PHP_EOL
                  . $start_over_button;
      }
      else {
        return '<img src="images/lose.gif" />'
                  . '<h1 id="game-over-message">That\'s too bad! That wasn\'t the right phrase.<br>The phrase was: "'
                  . $this->phrase->getCurrentPhrase()
                  . '"</h1>' . PHP_EOL
                  . $start_over_button;
      }
    }
    else
    {
      if($this->checkForWin() && !$this->checkForLose())
      {
        return '<h1 id="game-over-message">Congratulations on guessing: "'
                  . $this->phrase->getCurrentPhrase()
                  . '"</h1>' . PHP_EOL
                  . $start_over_button;
      }
      elseif($this->checkForLose() && !$this->checkForWin())
      {
        return '<h1 id="game-over-message">The phrase was: "'
                  . $this->phrase->getCurrentPhrase()
                  . '". Better luck next time!</h1>' . PHP_EOL
                  . $start_over_button;
      }
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
    $keyrows = array();
    $keyrows[] = array('q','w','e','r','t','y','u','i','o','p');
    $keyrows[] = array('a','s','d','f','g','h','j','k','l');
    $keyrows[] = array('z','x','c','v','b','n','m');

    $keyboard_string = '';
    $keyboard_string .= '<p>You can use your machine\'s keyboard. ' . PHP_EOL;
    $keyboard_string .= '<a id="toggle_link" href="javascript:;" onclick="display_keyboard()">Click here to show the onscreen keyboard.</a>' . PHP_EOL;
    $keyboard_string .= '</p>' . PHP_EOL;
    $keyboard_string .= '<div id="qwerty" class="section" style="display:block">' . PHP_EOL;
    $keyboard_string .= '<form id="key_board" method="post" action="play.php">' . PHP_EOL;
    foreach($keyrows as $keyrow) {
      $keyboard_string .= '<div class="keyrow">' . PHP_EOL;

      foreach($keyrow as $key) {
        $keyboard_string .= '<button id="key" name="key" value="'.$key.'"';

        //default styling
        $styling = ' class="key"';

        //reset styling when character was selected and correct or incorrect
        if(in_array($key,$this->phrase->getSelected())) {
          $styling = ' class="key ';
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

    for ($i=1; $i <= $this->max_lives; $i++) {
      $score_string .= '<li class="tries"><img src="images/';
      if ($i <= $this->getLives()) {
        $score_string .= 'liveHeart.png" class="hearts"';
      } else {
        $score_string .= 'lostHeart.png"';
      }
      $score_string .= ' height="35px" widght="30px"></li>' . PHP_EOL;
    }

    $score_string .= '</ol>' . PHP_EOL;
    $score_string .= '</div>' . PHP_EOL;

    return $score_string;

  }

}
?>
