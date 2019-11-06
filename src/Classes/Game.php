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

  /*
    The class should include a constructor which accepts a Phrase object and sets the property
  */
  public function __construct($phrase)
  {
    $this->phrase = $phrase;
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
    sort($selected_correct_characters);

    //thanks to https://www.php.net/manual/en/function.count-chars.php
    //create an array with all allowable unique characters in the phrase
    //count_chars gives a string with all unique characters in the phrase
    //str_split creates an array with all unique characters
    $all_correct_characters = str_split(
                          count_chars(
                            str_replace(' ','',$this->phrase->getCurrentPhrase())
                          ,3)
                        );
    sort($all_correct_characters);

    //thanks to https://www.php.net/manual/en/function.array-diff.php
    //compare all unique characters in the phrase with all correctly selected characters.
    //$diff is the array with the values which are in the 1st array, but not in the 2nd array. So these are the remaining correct characters the user still has to select.
    $diff = array_diff($all_correct_characters,$selected_correct_characters);

    if(empty($diff) && is_array($diff)) {
      return "WIN";
    }
    return "NOT WIN";
  }

  /*
  - checkForLose(): this method checks to see if the player has guessed too many wrong letters.
  */

  public function checkForLose()
  {
    $diff = array();
    //thanks to https://www.php.net/manual/en/function.count-chars.php
    //create an array with all allowable unique characters in the phrase
    //count_chars gives a string with all unique characters in the phrase
    //str_split creates an array with all unique characters
    $all_correct_characters = str_split(
                          count_chars(
                            str_replace(' ','',$this->phrase->getCurrentPhrase())
                          ,3)
                        );
    sort($all_correct_characters);

    //thanks to https://www.php.net/manual/en/function.array-diff.php
    //compare all unique characters in the phrase with all correctly selected characters.
    //$diff is the array with the values which are in the 1st array, but not in the 2nd array. So these are the remaining correct characters the user still has to select.

    $diff = array_diff($this->phrase->getSelected(),$all_correct_characters);
    
    if(is_array($diff) && count($diff) >= 5) {
      return "LOSE";
    }
    return "NOT LOSE";
  }

  /*
  - gameOver(): this method displays one message if the player wins and another message if they lose. It returns false if the game has not been won or lost.
  */

  /*
  - displayKeyboard(): Create a onscreen keyboard form. See the example_html/keyboard.txt file for an example of what the render HTML for the keyboard should look like. If the letter has been selected the button should be disabled. Additionally, the class "correct" or "incorrect" should be added based on the checkLetter() method of the Phrase object. Return a string of HTML for the keyboard form.
  */

  /*
  - displayScore(): Display the number of guesses available. See the example_html/scoreboard.txt file for an example of what the render HTML for a scoreboard should look like. Return string HTML of Scoreboard.
  */

}
?>
