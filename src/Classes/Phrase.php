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
  private $phraseAnswer;
  private $phrase_array = ['Birds of a Feather Flock Together',
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
        $this->setCurrentPhrase($this->phrase_array[array_rand($this->phrase_array)]);
    }

    if(!empty($selected) && is_array($selected))
    {
        $this->selected = $selected;
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
    $display_phrase .= '<form method="post" action="play.php">' . PHP_EOL;
    $display_phrase .= '<input id="btn__answer" name="to_answer" type="submit" value="Fill in full phrase" />' . PHP_EOL;
    $display_phrase .= '</form>' . PHP_EOL;
    $display_phrase .= '<ul>' . PHP_EOL;

    //thanks to https://stackoverflow.com/questions/4601032/php-iterate-on-string-characters
    $characters = str_split($this->currentPhrase);
    //start position to look for a space to add a BR
    $next_new_line = 10;
    foreach ($characters as $key=>$character)
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

      if($character == " " && $key>= $next_new_line) {
        $display_phrase .= '<br>' . PHP_EOL;
        $next_new_line = $key + 10;
      }
    }
    $display_phrase .= '</ul>' . PHP_EOL;
    $display_phrase .= '</div>' . PHP_EOL;

    return $display_phrase;
  }

  public function displayInputFullPhrase()
  {
    $display_full_phrase = '';
    $words = explode(' ',$this->getCurrentPhrase());

    $display_full_phrase = '<div id="phrase" class="section">' . PHP_EOL;
    $display_full_phrase .= '<form method="post" action="play.php">' . PHP_EOL;
    foreach($words as $key=>$word) {
      $display_full_phrase .= '<input id="btn__answer" size="'.strlen($word).'" maxlength="'.strlen($word).'" name="words[]" type="text" placeholder="Word ' . ($key+1) .'" />' . PHP_EOL;
    }
    $display_full_phrase .= '<input id="btn__answer" name="submit_answer" type="submit" value="Am I right?" />' . PHP_EOL;
    $display_full_phrase .= '</form>' . PHP_EOL;
    $display_full_phrase .= '</div>' . PHP_EOL;

    // $display_full_phrase = '<div id="phrase" class="section">' . PHP_EOL;
    // $display_full_phrase .= '<form method="post" action="play.php">' . PHP_EOL;
    // $display_full_phrase .= '<input id="btn__answer" name="give_answer" type="submit" value="Fill in full phrase" />' . PHP_EOL;
    // $display_full_phrase .= '</form>' . PHP_EOL;
    // $display_full_phrase .= '<ul>' . PHP_EOL;
    //
    // //thanks to https://stackoverflow.com/questions/4601032/php-iterate-on-string-characters
    // $characters = str_split($this->currentPhrase);
    // //start position to look for a space to add a BR
    // $next_new_line = 10;
    // foreach ($characters as $key=>$character)
    // {
    //   $display_full_phrase .= '<li class="';
    //
    //   if($character == " ") {
    //     $display_full_phrase .= 'hide space"> ';
    //   } else {
    //     if(in_array($character,$this->getSelected())) {
    //       $display_full_phrase .= 'show ';
    //     } else {
    //       $display_full_phrase .= 'hide ';
    //     }
    //     $display_full_phrase .= 'letter '.$character.'">'.$character.'</li>' . PHP_EOL;
    //   }
    //   $display_full_phrase .= '</li>' . PHP_EOL;
    //
    //   if($character == " " && $key>= $next_new_line) {
    //     $display_full_phrase .= '<br>' . PHP_EOL;
    //     $next_new_line = $key + 10;
    //   }
    // }
    // $display_full_phrase .= '</ul>' . PHP_EOL;
    // $display_full_phrase .= '</div>' . PHP_EOL;

    return $display_full_phrase;
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

  public function checkPhraseAnswer()
  {
    if($this->getCurrentPhrase() == $this->getPhraseAnswer()) {
      return TRUE;
    }

    return FALSE;
  }


  public function getCurrentPhrase()
  {
    return $this->currentPhrase;
  }

  public function setCurrentPhrase($value)

  {
    //thanks to https://stackoverflow.com/questions/659025/how-to-remove-non-alphanumeric-characters
    //make lower case, remove whitespace before and after, and remove non alphabetic characters

    //thanks for removing multiple spaces
    //see: https://www.techfry.com/php-tutorial/how-to-remove-whitespace-characters-in-a-string-in-php-trim-function
    $this->currentPhrase = strtolower(
                              trim(
                                preg_replace("/[^A-Za-z ]/",'',
                                  preg_replace('/\s+/', ' ',
                                    filter_var($value, FILTER_SANITIZE_STRING)
                                  )
                                )
                              )
                            );
  }

  public function getPhraseAnswer()
  {
    return $this->phraseAnswer;
  }

  public function setPhraseAnswer($value)

  {
    //thanks to https://stackoverflow.com/questions/659025/how-to-remove-non-alphanumeric-characters
    //make lower case, remove whitespace before and after, and remove non alphabetic characters

    //thanks for removing multiple spaces
    //see: https://www.techfry.com/php-tutorial/how-to-remove-whitespace-characters-in-a-string-in-php-trim-function
    $this->phraseAnswer = strtolower(
                            trim(
                              preg_replace('/\s+/', ' ',
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
    if(!in_array($character,$this->getSelected()) && strlen($character) == 1 && ctype_alpha($character)) {
      $this->selected[] = $character;
    }
  }

}
?>
