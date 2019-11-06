<?php
/*
Phrase.php to create a Phrase class to handle the phrases
*/

class Phrase
{
  /*
    The class must have at least two properties:
    - $currentPhrase a string.
    - $selected an array. Default to an empty array.
  */
  private $currentPhrase;
  private $selected = array();

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

    if(!empty($selected))
    {
        $this->setSelected($selected);
    }
  }

  /*
    addPhraseToDisplay(): Builds the HTML for the letters of the phrase. Each letter is presented by an empty box, one list item for each letter.
    See the example_html/phrase.txt file for an example of what the render HTML for a phrase should look like when the game starts.
    When the player correctly guesses a letter, the empty box is replaced with the matched letter. Use the class "hide" to hide a letter and "show" to show a letter.
    Make sure the phrase displayed on the screen doesn't include boxes for spaces: see example HTML.
  */
  public function addPhraseToDisplay()
  {
    $display_phrase = '<div id="phrase" class="section">' . PHP_EOL;
    $display_phrase .= '<ul>' . PHP_EOL;

    //thanks to https://stackoverflow.com/questions/4601032/php-iterate-on-string-characters
    $characters = str_split($this->currentPhrase);
    foreach ($characters as $character)
    {
      $display_phrase .= '<li class="';

      if($character == " ") {
        $display_phrase .= 'hide space"> ';
      } else {
        if(in_array($character,$this->getSelected())) {
          $display_phrase .= 'show ';
        } else {
          $display_phrase .= 'hide ';
        }
        $display_phrase .= 'letter '.$character.'">'.$character.'</li>' . PHP_EOL;
      }
      $display_phrase .= '</li>' . PHP_EOL;

    }
    $display_phrase .= '</ul>' . PHP_EOL;
    $display_phrase .= '</div>' . PHP_EOL;

    return $display_phrase;
  }

  /*
    checkLetter(): checks to see if a letter matches a letter in the phrase. Accepts a single letter to check against the phrase. Returns true or false.
  */
  public function checkLetter($value)
  {
    //thanks to https://stackoverflow.com/questions/659025/how-to-remove-non-alphanumeric-characters
    //make lower case, remove whitespace before and after, and remove non alphabetic characters
    $character = strtolower(
                  trim(
                    preg_replace("/[^A-Za-z ]/",'',
                      filter_var($value, FILTER_SANITIZE_STRING)
                    )
                  )
                );

    //thanks to https://www.php.net/manual/en/function.ctype-alpha.php
    if(strlen($character) == 1 && ctype_alpha($character)) {
      if(strpos($this->getCurrentPhrase(), $character) !== FALSE)  {
        return true;
      }
    }
    return false;

  }


  public function getCurrentPhrase()
  {
    return $this->currentPhrase;
  }

  public function setCurrentPhrase($value)

  {
    //thanks to https://stackoverflow.com/questions/659025/how-to-remove-non-alphanumeric-characters
    //make lower case, remove whitespace before and after, and remove non alphabetic characters
    $this->currentPhrase = strtolower(
                              trim(
                                preg_replace("/[^A-Za-z ]/",'',
                                  filter_var($value, FILTER_SANITIZE_STRING)
                                )
                              )
                            );
  }

  public function getSelected()
  {
    return $this->selected;
  }

  public function setSelected($value)
  {
    //thanks to https://stackoverflow.com/questions/659025/how-to-remove-non-alphanumeric-characters
    //make lower case, remove whitespace before and after, and remove non alphabetic characters
    $character = strtolower(
                  trim(
                    preg_replace("/[^A-Za-z ]/",'',
                      filter_var($value, FILTER_SANITIZE_STRING)
                    )
                  )
                );

    //thanks to https://www.php.net/manual/en/function.ctype-alpha.php
    if(strlen($character) == 1 && ctype_alpha($character)) {
      $this->selected[] = $character;
    }
  }

}
?>