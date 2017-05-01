<?php

function settings_title() {
  return _("Settings");
}

function user_settings() {
  global $enable_tshirt_size, $tshirt_sizes, $themes, $locales;
  global $user;
  
  $msg = "";
  $nick = $user['Nick'];
  $lastname = $user['lastname'];
  $prename = $user['prename'];

  $age = $user['age'];
  $gender = $user['gender']; //new

  $mobile = $user['mobile'];
  $mail = $user['email'];
  $email_shiftinfo = $user['email_shiftinfo'];
  $tshirt_size = $user['Size'];
  $selected_theme = $user['color'];
  $selected_language = $user['Sprache'];
  $planned_arrival_date = $user['planned_arrival_date'];
  $planned_departure_date = $user['planned_departure_date'];

  $planned_arrival_sort = $user['planned_arrival_sort'];//new

  $meeting_attending = $user['meeting_attending'];//new

  $nami = $user['nami'];//new
  $efz = $user['efz'];//new

  $adressstreet = $user['adressstreet'];//new
  $postalcode = $user['postalcode'];//new
  $adresstown = $user['adresstown'];//new

  $diocese = $user['diocese'];//new
  $localgroup = $user['localgroup'];//new

  $vegan = $user['vegan'];//new
  $vegetarian = $user['vegetarian'];//new
  $omnivore = $user['omnivore'];//new
  $halal = $user['halal'];//new
  $specialfood = $user['specialfood'];//new
  $specialhealth = $user['specialhealth'];//new


  $specialskills = $user['specialskills'];//new
  $specialmaterial = $user['specialmaterial'];//new

  $sleepinhouse = $user['sleepinhouse'];//new
  $sleepintent = $user['sleepintent'];//new



  
  if (isset($_REQUEST['submit'])) {
    $valid = true;
    
    if (isset($_REQUEST['mail']) && strlen(strip_request_item('mail')) > 0) {
      $mail = strip_request_item('mail');
      if (! check_email($mail)) {
        $valid = false;
        $msg .= error(_("E-mail address is not correct."), true);
      }
    } else {
      $valid = false;
      $msg .= error(_("Please enter your e-mail."), true);
    }

    $email_shiftinfo = isset($_REQUEST['email_shiftinfo']);
    
    if (isset($_REQUEST['tshirt_size']) && isset($tshirt_sizes[$_REQUEST['tshirt_size']])) {
      $tshirt_size = $_REQUEST['tshirt_size'];
    } elseif ($enable_tshirt_size) {
      $valid = false;
    }
    
    if (isset($_REQUEST['planned_arrival_date']) && DateTime::createFromFormat("Y-m-d", trim($_REQUEST['planned_arrival_date']))) {
      $planned_arrival_date = DateTime::createFromFormat("Y-m-d", trim($_REQUEST['planned_arrival_date']))->getTimestamp();
    } else {
      $valid = false;
      $msg .= error(_("Please enter your planned date of arrival."), true);
    }
    
    if (isset($_REQUEST['planned_departure_date']) && $_REQUEST['planned_departure_date'] != '') {
      if (DateTime::createFromFormat("Y-m-d", trim($_REQUEST['planned_departure_date']))) {
        $planned_departure_date = DateTime::createFromFormat("Y-m-d", trim($_REQUEST['planned_departure_date']))->getTimestamp();
      } else {
        $valid = false;
        $msg .= error(_("Please enter your planned date of departure."), true);
      }
    } else {
      $planned_departure_date = null;
    }
    
    // Trivia
    if (isset($_REQUEST['lastname'])) {
      $lastname = strip_request_item('lastname');
    }
    if (isset($_REQUEST['prename'])) {
      $prename = strip_request_item('prename');
    }
    if (isset($_REQUEST['age']) && preg_match("/^[0-9]{0,4}$/", $_REQUEST['age'])) {
      $age = strip_request_item('age');
    }
    if (isset($_REQUEST['mobile'])) {
      $mobile = strip_request_item('mobile');
    }

    if (isset($_REQUEST['gender'])) {
      $gender = strip_request_item('gender');
    }
    if (isset($_REQUEST['adressstreet'])) {
      $adressstreet = strip_request_item('adressstreet');
    }
    if (isset($_REQUEST['postalcode'])) {
      $postalcode = strip_request_item('postalcode');
    }
    if (isset($_REQUEST['adresstown'])) {
      $adresstown = strip_request_item('adresstown');
    }
    if (isset($_REQUEST['diocese'])) {
      $diocese = strip_request_item('diocese');
    }
    if (isset($_REQUEST['localgroup'])) {
      $localgroup = strip_request_item('localgroup');
    }
    /*if (isset($_REQUEST['vegan'])) {
      $vegan = true;
    }
    if (isset($_REQUEST['vegetarian'])) {
      $vegetarian = true;
    }
    if (isset($_REQUEST['omnivore'])) {
      $omnivore = true;
    }
    if (isset($_REQUEST['halal'])) {
      $halal = true;
    }*/
    $vegan = isset($_REQUEST['vegan']);
    $vegetarian = isset($_REQUEST['vegetarian']);
    $omnivore = isset($_REQUEST['omnivore']);
    $halal = isset($_REQUEST['halal']);



    if (isset($_REQUEST['specialfood'])) {
      $specialfood = strip_request_item('specialfood');
    }

    /*if (isset($_REQUEST['sleepinhouse'])) {
        $sleepinhouse = true;
    }
    if (isset($_REQUEST['sleepintent'])) {
      $sleepintent = true;
    }*/

    $sleepinhouse = isset($_REQUEST['sleepinhouse']);
    $sleepintent = isset($_REQUEST['sleepintent']);


    if (isset($_REQUEST['specialhealth'])) {
      $specialhealth = strip_request_item('specialhealth');
    }
    if (isset($_REQUEST['planned_arrival_sort'])) {
      $planned_arrival_sort = strip_request_item('planned_arrival_sort');
    }

    /*if (isset($_REQUEST['meeting_attending'])) {
      $meeting_attending = true;
    }*/
    $meeting_attending = isset($_REQUEST['meeting_attending']);


    /*if (isset($_REQUEST['nami'])) {
      $nami = true;
    }
    if (isset($_REQUEST['efz'])) {
      $efz = true;
    }*/

    $nami = isset($_REQUEST['nami']);
    $efz = isset($_REQUEST['efz']);



    if (isset($_REQUEST['specialskills'])) {
      $specialskills = strip_request_item('specialskills');
    }
    if (isset($_REQUEST['specialmaterial'])) {
      $specialmaterial = strip_request_item('specialmaterial');
    }

    
    if ($valid) {
      sql_query("
          UPDATE `User` SET
          `nick`='" . sql_escape($nick) . "',
          `prename`='" . sql_escape($prename) . "',
          `lastname`='" . sql_escape($lastname) . "',
          `age`='" . sql_escape($age) . "',
          `gender`='" . sql_escape($gender) . "',
          `mobile`='" . sql_escape($mobile) . "',
          `email`='" . sql_escape($mail) . "',
          `email_shiftinfo`=" . sql_bool($email_shiftinfo) . ",
          `size`='" . sql_escape($tshirt_size) . "',
          `planned_arrival_sort`='" . sql_escape($planned_arrival_sort) . "',
          `meeting_attending`=" . sql_bool($meeting_attending) . ",
          `nami`=" . sql_bool($nami) . ",
          `efz`=" . sql_bool($efz) . ",
          `adressstreet`='" . sql_escape($adressstreet) . "',
          `postalcode`='" . sql_escape($postalcode) . "',
          `adresstown`='" . sql_escape($adresstown) . "',
          `diocese`='" . sql_escape($diocese) . "',
          `localgroup`='" . sql_escape($localgroup) . "',
          `vegan`=" . sql_bool($vegan) . ",
          `vegetarian`=" . sql_bool($vegetarian) . ",
          `omnivore`=" . sql_bool($omnivore) . ",
          `halal`=" . sql_bool($halal) . ",
          `specialfood`='" . sql_escape($specialfood) . "',
          `specialhealth`='" . sql_escape($specialhealth) . "',
          `specialskills`='" . sql_escape($specialskills) . "',
          `specialmaterial`='" . sql_escape($specialmaterial) . "',
          `sleepinhouse`=" . sql_bool($sleepinhouse) . ",
          `sleepintent`=" . sql_bool($sleepintent) . ",
          `planned_arrival_date`='" . sql_escape($planned_arrival_date) . "',
          `planned_departure_date`=" . sql_null($planned_departure_date) . "
          WHERE `UID`='" . sql_escape($user['UID']) . "'");
      
      success(_("Settings saved."));
      redirect(page_link_to('user_settings'));
    }
  } elseif (isset($_REQUEST['submit_password'])) {
    $valid = true;
    
    if (! isset($_REQUEST['password']) || ! verify_password($_REQUEST['password'], $user['password'], $user['UID'])) {
      $msg .= error(_("-> not OK. Please try again."), true);
    } elseif (strlen($_REQUEST['new_password']) < MIN_PASSWORD_LENGTH) {
      $msg .= error(_("Your password is to short (please use at least 6 characters)."), true);
    } elseif ($_REQUEST['new_password'] != $_REQUEST['new_password2']) {
      $msg .= error(_("Your passwords don't match."), true);
    } elseif (set_password($user['UID'], $_REQUEST['new_password'])) {
      success(_("Password saved."));
    } else {
      error(_("Failed setting password."));
    }
    redirect(page_link_to('user_settings'));
  } elseif (isset($_REQUEST['submit_theme'])) {
    $valid = true;
    
    if (isset($_REQUEST['theme']) && isset($themes[$_REQUEST['theme']])) {
      $selected_theme = $_REQUEST['theme'];
    } else {
      $valid = false;
    }
    
    if ($valid) {
      sql_query("UPDATE `User` SET `color`='" . sql_escape($selected_theme) . "' WHERE `UID`='" . sql_escape($user['UID']) . "'");
      
      success(_("Theme changed."));
      redirect(page_link_to('user_settings'));
    }
  } elseif (isset($_REQUEST['submit_language'])) {
    $valid = true;
    
    if (isset($_REQUEST['language']) && isset($locales[$_REQUEST['language']])) {
      $selected_language = $_REQUEST['language'];
    } else {
      $valid = false;
    }
    
    if ($valid) {
      sql_query("UPDATE `User` SET `Sprache`='" . sql_escape($selected_language) . "' WHERE `UID`='" . sql_escape($user['UID']) . "'");
      $_SESSION['locale'] = $selected_language;
      
      success("Language changed.");
      redirect(page_link_to('user_settings'));
    }
  }

  return page_with_title(settings_title(), [
      $msg,
      msg(),
      div('row', [
          div('col-md-6', [
              form([
                  form_info('', _("Here you can change your user details.")),
                  form_info(entry_required() . ' = ' . _("Entry required!")),
                  form_text('nick', _("Nick"), $nick, true),
                  div('row', [
                      div('col-sm-6', [
                      form_text('prename', _("First name") . ' ' . entry_required(), $prename)
                      ]),
                      div('col-sm-6', [
                          form_text('lastname', _("Last name") . ' ' . entry_required(), $lastname)
                      ])
                  ]),
                  form_text('adressstreet', _("Straße Hausnummer") . ' ' . entry_required(), $adressstreet),
                  div('row', [
                      div('col-sm-3', [
                      form_text('postalcode', _("PLZ") . ' ' . entry_required(), $postalcode)
                      ]),
                      div('col-sm-9', [
                          form_text('adresstown', _("Stadt") . ' ' . entry_required(), $adresstown)
                      ]),
                      div('col-sm-6', [
                          form_text('diocese', _("Diözese"), $diocese)
                      ]),
                      div('col-sm-6', [
                          form_text('localgroup', _("Stamm"), $localgroup)
                      ])
                  ]),

                  div('row', [
                      div('col-sm-6', [
                          form_text('mobile', _("Mobile") . ' ' . entry_required(), $mobile)
                      ]),
                      div('col-sm-3', [
                          form_text('age', _("Age"), $age),
                        //form_info(entry_required() . ' = ' . _("Entry required!"))
                      ]),
                      div('col-sm-3', [
                          form_text('gender', _("Geschlecht"), $gender),
                        //form_info(entry_required() . ' = ' . _("Entry required!"))
                      ])
                  ]),

                  form_text('mail', _("E-Mail") . ' ' . entry_required(), $mail),
                  form_checkbox('email_shiftinfo', _("Please send me an email if my shifts change"), $email_shiftinfo),

                  "<p><hr></p>",

                  /* COMING & GOING */

                  div('row', [
                      div('col-sm-6', [
                          form_date('planned_arrival_date', _("Planned date of arrival") . ' ' . entry_required(), $planned_arrival_date, time())
                      ]),
                      div('col-sm-6', [
                          form_date('planned_departure_date', _("Planned date of departure"), $planned_departure_date, time())
                      ]),
                      div('col-sm-12', [
                          form_text('planned_arrival_sort', _("Geplante Art der Anreise"), $planned_arrival_sort)
                      ]),
                      div('col-sm-12', [
                          form_checkbox('meeting_attending', _("<b>Ich nehme am Vorbereitungswochenende vom 05.05. bis 07.05.2017 teil.</b>"), $meeting_attending)
                      ])
                  ]),

                  "<p><hr></p>",

                  /* SLEEP & EAT */


                  div('row', [
                      div('col-sm-6', [
                          "<p><h5><b>Ich schlafe..</b></h5></p>",
                          form_checkbox('sleepintent', _("Ich schlafe im Zelt."), $sleepintent),
                          form_checkbox('sleepinhouse', _("Ich schlafe im Haus."), $sleepinhouse),
                          form_info("", _("Standardmäßig schlafen alle Helfer in Zelten, solltest du jedoch einen Platz im Haus benötigen, dann gib es hier an."))
                      ]),
                      div('col-sm-6', [
                          "<p><h5><b>Ich esse...</b>"  . ' ' . entry_required() . "</h5></p>",
                          form_checkbox('vegan', _("vegan"), $vegan),
                          form_checkbox('vegetarian', _("vegetarisch"), $vegetarian),
                          form_checkbox('omnivore', _("herkömmlich"), $omnivore),
                          form_checkbox('halal', _("Halāl"), $halal),
                        //form_info("", _("Standardmäßig schlafen alle Helfer in Zelten, solltest du jedoch einen Platz im Haus benötigen, dann gib es hier an.")),
                          form_text('specialfood', _("Unverträglichkeiten:"), $specialfood)
                      ])
                  ]),

                  div('row', [
                      div('col-sm-6', [
                          form_text('specialhealth', _("Wichtige Gesundheitsinfo/Medikamente:"), $specialhealth)
                      ]),
                      div('col-sm-6', [
                          $enable_tshirt_size ? form_select('tshirt_size', _("Shirt size") . ' ' . entry_required(), $tshirt_sizes, $tshirt_size) : ''
                      ])
                  ]),

                  "<p><hr></p>",

                  /* SLEEP & EAT */


                  div('row', [
                      div('col-sm-6', [
                          form_textarea('specialskills', _("Ich kann:"), $specialskills),
                          //form_info("", _("Mit deinen Fähigkeiten kannst uns weiterhelfen!  Ihr habt einen Kettensägenschein oder könnt 7,5t fahren? Tetris ist für euch kein Spiel sondern nur eine Frage der Logistik? Teil es uns mit!"))
                      ]),
                      div('col-sm-6', [
                          form_textarea('specialmaterial', _("Ich habe:"), $specialmaterial),
                          //form_info("", _("Auf einem Großlager wird sehr viel Material benötigt. Du hast zufällig eine Jurte im Dachboden? Dein Stamm könnte uns ein Gerüstzelt ausleihen? Dein Papa kennt die Bundeskanzlerin persönlich? Wenn du uns Ressourcen zur Verfügung stellen kannst, wären wir sehr dankbar."))
                      ]),
                  ]),

                  div('row', [
                      div('col-sm-6', [
                          form_checkbox('nami', _("Ich bin in NaMi gemeldet"), $nami),
                          form_info("", _("Wer nicht in NaMi gemeldet ist, muss von uns für die Zeit des Lagers versichert werden, daher ist diese Auskunft wichtig."))

                      ]),
                      div('col-sm-6', [
                          form_checkbox('efz', _("Ich habe mein eFZ auf Bundesebene kontrollieren lassen"), $efz),
                          /*form_info("", _("<b>Unbedenklichkeitserklärung und Kinderschutz</b><br>
Jede Biene, die auf dem Lager anwesend sein wird, benötigt eine Bestätigung, dass sie sich nicht nach Delikten strafbar gemacht hat, welche im §72a SGB VIII aufgeführt sind. <br>Du kennst wahrscheinlich mittlerweile das Verfahren zum erweiterten Führungszeugnis (eFz) in der DPSG. Falls du also den Prozess in den letzten fünf Jahren schon einmal durchgemacht hast, dann lade dir doch einfach von <a href='https://nami.dpsg.de/' target='_blank'>deinem NaMi-Konto</a> die Unbedenklichkeits-Bestätigung herunter und <a href='mailto:mail@dpsg-augsburg.de'>schick sie uns</a>.
<br><br>Alternativ kannst du uns auch direkt Einblick in dein Führungszeugnis gewähren. Falls du ein aktuelles beantragen musst, dann können wir dir eine Bestätigung für das Bürgerbüro schreiben, damit du nichts bezahlen musst. Des Weiteren kannst du dein Führungszeugnis oder deine Unbedenklichkeitsbestätigung auch zum Vorbereitungswochenende (05. – 07.05.2017) zur Einsicht durch den Vorstand mitbringen.
<br><br>
Falls du noch Fragen rund um das Thema eFz hast, kannst du dir das <a href='http://www.dpsg-augsburg.de/fuer-euch/downloads/' target='_blank'>eFz-Paket</a> auf unserer Diözesanseite herunterladen.
                                "))*/
                      ])
                  ]),

                  form_info('', _('Please visit the angeltypes page to manage your angeltypes.')),
                  form_submit('submit', _("Save"))
              ])
          ]),
          div('col-md-6', [
              form([
                  form_info(_("Here you can change your password.")),
                  form_password('password', _("Old password:")),
                  form_password('new_password', _("New password:")),
                  form_password('new_password2', _("Password confirmation:")),
                  form_submit('submit_password', _("Save"))
              ]),
              form([
                  form_info(_("Here you can choose your color settings:")),
                  form_select('theme', _("Color settings:"), $themes, $selected_theme),
                  form_submit('submit_theme', _("Save"))
              ]),
              form([
                  form_info(_("Here you can choose your language:")),
                  form_select('language', _("Language:"), $locales, $selected_language),
                  form_submit('submit_language', _("Save"))
              ])
          ])
      ])
  ]);
}

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
?>
