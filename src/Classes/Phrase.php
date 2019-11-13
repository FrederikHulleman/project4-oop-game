<?php
/*
Phrase.php to create a Phrase class to handle the phrases
*/

class Phrase
{
  /*
  The Phrase class properties:
    - $currentPhrase a string holding the current selected phrase which needs to be guessed by the  user.
    - $selected an array holding all characters guessed by the user
    - $phraseAnswer a string holding the full phrase answer submitted by the user
    - $phraseArray with all possible phrases, from which is randomly selected

  The Phrase class methods:
    - __construct($phrase=null,$selected=[]) > to either set the phrase property to the value from its input, or select a random phrase from the phraseArray.
        If a selected array is sent with the construct call, then set the selected property  with this array
    - getCurrentPhrase() > returns currently selected phrase
    - setCurrentPhrase($value) > sets current phrase to $value
    - getPhraseAnswer() > returns full phrase answer submitted by user
    - setPhraseAnswer($value) > sets full phrase answer to $value, submitted by  user
    - checkPhraseAnswer() > validates whether the submitted full phrase answer is correct or not
    - getSelected() > returns the array with all guessed characters, submitted by the user
    - getSelectedCorrect() > returns the array with only the correctly guessed characters, submitted by the user > used by Game method checkForWin()
    - setSelected($value) > adds guessed character ($value) to the selected array
    - normalizeStringsAndCharacters($value) > remove all unnecessary spaces and non alphabetic characters
    - checkLetter($value) > validates whether a character is correct or not (whether it is part of the current  phrase)
    - getCurrentPhraseAllCharacters() > returns all unique characters from the phrase > used by Game methods checkForLose() & checkForWin()
    - addPhraseToDisplay($show_full_answer_screen) > prepares HTML for displaying the letter boxes for the current phrase, incl styling
        > this is shown in 2 scenario's
            1) the user is entering single characters > then also a 'i know the answer' button is shown, which redirects the user to the full phrase answer input screen
            2) the user is entering the full phrase answer > then only the message
        > when the user didn't guess a character yet, but wants to guess the full phrase, only  a message is shown
    - displayInputFullPhrase() > prepares HTML for displaying the input form for the full phrase answer

  */
  private $currentPhrase;
  private $selected = array();
  private $phraseAnswer;
  private $phraseArray = ['Birds of a Feather Flock Together',
                            'On the Ropes',
                            'Knuckle Down',
                            'Elephant in the Room',
                            'Between a Rock and a Hard Place',
                            'I Smell a Rat',
                            'Not the Sharpest Tool in the Shed'];

  /*
    The class must include a constructor that accepts two OPTIONAL parameters:
    - $phrase a string, or if empty, get a random phrase
    - $selected an array of selected letters
  */
  public function __construct($phrase=null,$selected=[])
  {
    if(!empty($phrase))
    {
        $this->setCurrentPhrase($phrase);
    }
    else {
        $this->setCurrentPhrase($this->phraseArray[array_rand($this->phraseArray)]);
    }

    if(!empty($selected) && is_array($selected))
    {
        foreach($selected as $character) {
          $this->setSelected($character);
        }
    }
  }

  public function getCurrentPhrase()
  {
    return $this->currentPhrase;
  }

  public function setCurrentPhrase($value)
  {
    $normalized_phrase = $this->normalizeStringsAndCharacters($value);
    if(strlen($normalized_phrase) > 0 && ctype_alpha(str_replace(' ','',$normalized_phrase))) {
      $this->currentPhrase = $normalized_phrase;
    }

  }

  public function getPhraseAnswer()
  {
    return $this->phraseAnswer;
  }

  public function setPhraseAnswer($value)
  {
    $normalized_phrase_answer = $this->normalizeStringsAndCharacters($value);
    if(strlen($normalized_phrase_answer) > 0 && ctype_alpha(str_replace(' ','',$normalized_phrase_answer))) {
      $this->phraseAnswer = $normalized_phrase_answer;
    }
  }

  public function checkPhraseAnswer()
  {
    if($this->getCurrentPhrase() == $this->getPhraseAnswer()) {
      return TRUE;
    }

    return FALSE;
  }

  public function getSelected()
  {
    return $this->selected;
  }

  public function getSelectedCorrect()
  {
    //thanks to https://www.php.net/manual/en/function.array-filter.php
    //create an array with previous selected characters which were correct. Each character from the selected array from the phrase, is validated by using the checkLetter method of the phrase
    $selected_correct_characters = array_filter(
                                      $this->getSelected(),
                                      array($this,'checkLetter')
                                    );
    return $selected_correct_characters;
  }

  public function setSelected($value)
  {
    $character = $this->normalizeStringsAndCharacters($value);

    //thanks to https://www.php.net/manual/en/function.ctype-alpha.php
    if(!in_array($character,$this->getSelected()) && strlen($character) == 1 && ctype_alpha($character)) {
      $this->selected[] = $character;
    }
  }

  public function normalizeStringsAndCharacters($value)
  {
    //thanks to https://stackoverflow.com/questions/659025/how-to-remove-non-alphanumeric-characters
    //make lower case, remove whitespace before and after, and remove non alphabetic characters

    //thanks for removing multiple spaces
    //see: https://www.techfry.com/php-tutorial/how-to-remove-whitespace-characters-in-a-string-in-php-trim-function

    $normalized_output = strtolower(
                          trim(
                            preg_replace("/[^A-Za-z ]/",'',
                              preg_replace('/\s+/', ' ',
                                filter_var($value, FILTER_SANITIZE_STRING)
                              )
                            )
                          )
                        );

      return $normalized_output;
  }

  /*
    checkLetter(): checks to see if a letter matches a letter in the phrase. Accepts a single letter to check against the phrase. Returns true or false.
  */
  public function checkLetter($value)
  {
    $character = $this->normalizeStringsAndCharacters($value);

    //thanks to https://www.php.net/manual/en/function.ctype-alpha.php
    if(strlen($character) == 1 && ctype_alpha($character)) {
      if(strpos($this->getCurrentPhrase(), $character) !== FALSE)  {
        return true;
      }
    }
    return false;

  }

  public function getCurrentPhraseAllCharacters()
  {
    //thanks to https://www.php.net/manual/en/function.count-chars.php
    //create an array with all allowable unique characters in the phrase
    //count_chars gives a string with all unique characters in the phrase
    //str_split creates an array with all unique characters
    $all_correct_characters = str_split(
                          count_chars(
                            str_replace(' ','',$this->getCurrentPhrase())
                          ,3)
                        );
    return $all_correct_characters;
  }

  /*
    addPhraseToDisplay(): Builds the HTML for the letters of the phrase. Each letter is presented by an empty box, one list item for each letter.
    See the example_html/phrase.txt file for an example of what the render HTML for a phrase should look like when the game starts.
    When the player correctly guesses a letter, the empty box is replaced with the matched letter. Use the class "hide" to hide a letter and "show" to show a letter.
    Make sure the phrase displayed on the screen doesn't include boxes for spaces: see example HTML.
  */
  public function addPhraseToDisplay($show_full_answer_screen)
  {
    $display_phrase = '<div id="phrase" class="section">' . PHP_EOL;

    //if the user asked to fill in the full phrase, then this button doesn't have to be dispayed
    if(!$show_full_answer_screen) {
      $display_phrase .= '<form method="post" action="play.php">' . PHP_EOL;
      $display_phrase .= '<input id="btn__answer" name="to_answer" type="submit" value="I know the answer" />' . PHP_EOL;
      $display_phrase .= '</form>' . PHP_EOL;
    }
    else {
      if(count($this->getSelected()) > 0) {
        $display_phrase .= '<h3 class="header">You\'ve already guessed the following characters:</h3>' . PHP_EOL;
      } else {
        $display_phrase .= '<h3 class="header">Wow, that\'s very brave! You haven\'t guessed any characters yet and you already know the answer. Good luck :)</h3>' . PHP_EOL;
        $display_phrase .= '</div>' . PHP_EOL;
        return $display_phrase;
      }
    }
    $display_phrase .= '<ul>' . PHP_EOL;

    //thanks to https://stackoverflow.com/questions/4601032/php-iterate-on-string-characters
    $characters = str_split($this->currentPhrase);
    //start position to look for a space to add a BR
    $next_new_line = 10;

    $all_selected_characters = $this->getSelected();
    $last_selected_character = end($all_selected_characters);

    foreach ($characters as $key=>$character)
    {
      $display_phrase .= '<li class="';

      if($character == " ") {
        $display_phrase .= 'hide space"> ';
      } else {
        if($character == $last_selected_character && !$show_full_answer_screen) {
           $display_phrase .= 'flash ';
        }
        if(in_array($character,$this->getSelected())) {
          $display_phrase .= 'show ';
        } else {
          $display_phrase .= 'hide ';
        }
        $display_phrase .= 'letter '.$character.'">'.$character.'</li>' . PHP_EOL;
      }
      $display_phrase .= '</li>' . PHP_EOL;

      if($character == " " && $key>= $next_new_line) {
        $display_phrase .= '<br>' . PHP_EOL;
        $next_new_line = $key + 10;
      }
    }
    $display_phrase .= '</ul>' . PHP_EOL;
    $display_phrase .= '</div>' . PHP_EOL;

    return $display_phrase;
  }

  public function displayInputFullPhrase($words_submitted = [])
  {
    $display_full_phrase = '';
    $words = explode(' ',$this->getCurrentPhrase());
    $display_full_phrase .= '<h3 class="header">Fill in the full phrase:</h3>' . PHP_EOL;
    $display_full_phrase .= '<form method="post" action="play.php">' . PHP_EOL;
    $display_full_phrase .= '<div class="section">' . PHP_EOL;
    foreach($words as $key=>$word) {
      $display_full_phrase .= '<input id="word'.($key+1).'" type="text" name="words[]" size="'.(strlen($word)+1).'" maxlength="'.strlen($word).'"';
      if($key==0) {
        $display_full_phrase .= ' autofocus';
      }
      if (!empty($words_submitted[$key])) {
        $display_full_phrase .= ' value="'.$words_submitted[$key].'"';
      }
      $display_full_phrase .= ' />' . PHP_EOL;
    }
    $display_full_phrase .= '</div>' . PHP_EOL;
    $display_full_phrase .= '<br><br><br><input id="btn__back" name="go_back" type="submit" value="Go back" />' . PHP_EOL;
    $display_full_phrase .= '<input id="btn__answer" name="submit_answer" type="submit" value="Am I right?" />' . PHP_EOL;
    $display_full_phrase .= '</form>' . PHP_EOL;

    return $display_full_phrase;
  }
}
?>
