<?php
function login_title()
{
    return _("Login");
}

function register_title()
{
    return _("Register");
}

function logout_title()
{
    return _("Logout");
}

// Engel registrieren
function guest_register()
{
    global $tshirt_sizes, $enable_tshirt_size, $default_theme, $user;

    $event_config = EventConfig();
    if ($event_config === false) {
        engelsystem_error("Unable to load event config.");
    }

    $msg = "";

    $nick = "";
    $lastname = "";
    $prename = "";

    $age = "";
    $gender = ""; //new

    //$tel = "";
    //$dect = "";
    $mobile = "";
    $mail = "";
    $email_shiftinfo = false;
    //$jabber = "";
    //$hometown = "";
    $comment = "";
    $tshirt_size = '';
    $password_hash = "";
    $selected_angel_types = [];
    $planned_arrival_date = null;
    $planned_arrival_sort = ""; //new

    $meeting_attending = false;//new

    $nami = false;//new
    $efz = false;//new

    $adressstreet = "";//new
    $postalcode = "";//new
    $adresstown = "";//new

    $diocese = "";//new
    $localgroup = "";//new

    $vegan = false;//new
    $vegetarian = false;//new
    $omnivore = false;//new
    $halal = false;//new
    $specialfood = "";//new
    $specialhealth = "";//new


    $specialskills = "";//new
    $specialmaterial = "";//new

    $sleepinhouse = false;//new
    $sleepintent = false;//new


    $angel_types_source = sql_select("SELECT * FROM `AngelTypes` ORDER BY `name`");
    $angel_types = [];

    foreach ($angel_types_source as $angel_type) {
        $angel_types[$angel_type['id']] = $angel_type['name'] . ($angel_type['restricted'] ? " (fest, beschränkt)" : "");
        if (!$angel_type['restricted']) {
            $selected_angel_types[] = $angel_type['id'];
        }
    }

    if (isset($_REQUEST['submit'])) {
        $valid = true;

        if (isset($_REQUEST['nick']) && strlen(User_validate_Nick($_REQUEST['nick'])) > 1) {
            $nick = User_validate_Nick($_REQUEST['nick']);
            if (sql_num_query("SELECT * FROM `User` WHERE `Nick`='" . sql_escape($nick) . "' LIMIT 1") > 0) {
                $valid = false;
                $msg .= error(sprintf(_("Your nick &quot;%s&quot; already exists."), $nick), true);
            }
        } else {
            $valid = false;
            $msg .= error(sprintf(_("Your nick &quot;%s&quot; is too short (min. 2 characters)."), User_validate_Nick($_REQUEST['nick'])), true);
        }

        if (isset($_REQUEST['mail']) && strlen(strip_request_item('mail')) > 0) {
            $mail = strip_request_item('mail');
            if (!check_email($mail)) {
                $valid = false;
                $msg .= error(_("E-mail address is not correct."), true);
            }
        } else {
            $valid = false;
            $msg .= error(_("Please enter your e-mail."), true);
        }

        if (isset($_REQUEST['email_shiftinfo'])) {
            $email_shiftinfo = true;
        }

        /*if (isset($_REQUEST['jabber']) && strlen(strip_request_item('jabber')) > 0) {
          $jabber = strip_request_item('jabber');
          if (! check_email($jabber)) {
            $valid = false;
            $msg .= error(_("Please check your jabber account information."), true);
          }
        }*/

        if ($enable_tshirt_size) {
            if (isset($_REQUEST['tshirt_size']) && isset($tshirt_sizes[$_REQUEST['tshirt_size']]) && $_REQUEST['tshirt_size'] != '') {
                $tshirt_size = $_REQUEST['tshirt_size'];
            } else {
                $valid = false;
                $msg .= error(_("Please select your shirt size."), true);
            }
        }

        if (isset($_REQUEST['password']) && strlen($_REQUEST['password']) >= MIN_PASSWORD_LENGTH) {
            if ($_REQUEST['password'] != $_REQUEST['password2']) {
                $valid = false;
                $msg .= error(_("Your passwords don't match."), true);
            }
        } else {
            $valid = false;
            $msg .= error(sprintf(_("Your password is too short (please use at least %s characters)."), MIN_PASSWORD_LENGTH), true);
        }

        if (isset($_REQUEST['planned_arrival_date']) && DateTime::createFromFormat("Y-m-d", trim($_REQUEST['planned_arrival_date']))) {
            $planned_arrival_date = DateTime::createFromFormat("Y-m-d", trim($_REQUEST['planned_arrival_date']))->getTimestamp();
        } else {
            $valid = false;
            $msg .= error(_("Please enter your planned date of arrival."), true);
        }

        $selected_angel_types = [];
        foreach (array_keys($angel_types) as $angel_type_id) {
            if (isset($_REQUEST['angel_types_' . $angel_type_id])) {
                $selected_angel_types[] = $angel_type_id;
            }
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
        if (isset($_REQUEST['gender'])) {
            $gender = strip_request_item('gender');
        }
        if (isset($_REQUEST['mobile'])) {
            $mobile = strip_request_item('mobile');
        }
        if (isset($_REQUEST['comment'])) {
            $comment = strip_request_item_nl('comment');
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
        if (isset($_REQUEST['vegan'])) {
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
        }
        if (isset($_REQUEST['specialfood'])) {
            $specialfood = strip_request_item('specialfood');
        }
        if (isset($_REQUEST['sleepinhouse'])) {
            $sleepinhouse = true;
        }
        if (isset($_REQUEST['sleepintent'])) {
            $sleepintent = true;
        }
        if (isset($_REQUEST['specialhealth'])) {
            $specialhealth = strip_request_item('specialhealth');
        }
        if (isset($_REQUEST['planned_arrival_sort'])) {
            $planned_arrival_sort = strip_request_item('planned_arrival_sort');
        }
        if (isset($_REQUEST['meeting_attending'])) {
            $meeting_attending = true;
        }
        if (isset($_REQUEST['nami'])) {
            $nami = true;
        }
        if (isset($_REQUEST['efz'])) {
            $efz = true;
        }
        if (isset($_REQUEST['specialskills'])) {
            $specialskills = strip_request_item('specialskills');
        }
        if (isset($_REQUEST['specialmaterial'])) {
            $specialmaterial = strip_request_item('specialmaterial');
        }

        /*
         * Missing fields:
         * prename
         * age
         * mobile
         * password
         */

        if ($valid) {
            sql_query("
          INSERT INTO `User` SET 
          `color`='" . sql_escape($default_theme) . "', 
          `nick`='" . sql_escape($nick) . "', 
          `prename`='" . sql_escape($prename) . "', 
          `lastname`='" . sql_escape($lastname) . "',
          `age`='" . sql_escape($age) . "', 
          `gender`='" . sql_escape($gender) . "',
          `mobile`='" . sql_escape($mobile) . "',
          `email`='" . sql_escape($mail) . "', 
          `email_shiftinfo`=" . sql_bool($email_shiftinfo) . ", 
          `size`='" . sql_escape($tshirt_size) . "',
          `password`='" . sql_escape($password_hash) . "', 
          `kommentar`='" . sql_escape($comment) . "', 
          `CreateDate`=NOW(),
          `Sprache`='" . sql_escape($_SESSION["locale"]) . "',
          `arrival_date`=NULL,
          `planned_arrival_date`='" . sql_escape($planned_arrival_date) . "',
          `planned_arrival_sort`='" . sql_escape($planned_arrival_sort) . "',
          `meeting_attending`='" . sql_escape($meeting_attending) . "',
          `nami`='" . sql_escape($nami) . "',
          `efz`='" . sql_escape($efz) . "',
          `adressstreet`='" . sql_escape($adressstreet) . "',
          `postalcode`='" . sql_escape($postalcode) . "',
          `adresstown`='" . sql_escape($adresstown) . "',
          `diocese`='" . sql_escape($diocese) . "',
          `localgroup`='" . sql_escape($localgroup) . "',
          `vegan`='" . sql_escape($vegan) . "',
          `vegetarian`='" . sql_escape($vegetarian) . "',
          `omnivore`='" . sql_escape($omnivore) . "',
          `halal`='" . sql_escape($halal) . "',
          `specialfood`='" . sql_escape($specialfood) . "',
          `specialhealth`='" . sql_escape($specialhealth) . "',
          `specialskills`='" . sql_escape($specialskills) . "',
          `specialmaterial`='" . sql_escape($specialmaterial) . "',
          `sleepinhouse`='" . sql_escape($sleepinhouse) . "',
          `sleepintent`='" . sql_escape($sleepintent) . "'
          ");

            // Assign user-group and set password
            $user_id = sql_id();
            sql_query("INSERT INTO `UserGroups` SET `uid`='" . sql_escape($user_id) . "', `group_id`=-2");
            set_password($user_id, $_REQUEST['password']);

            // Assign angel-types
            $user_angel_types_info = [];
            foreach ($selected_angel_types as $selected_angel_type_id) {
                sql_query("INSERT INTO `UserAngelTypes` SET `user_id`='" . sql_escape($user_id) . "', `angeltype_id`='" . sql_escape($selected_angel_type_id) . "'");
                $user_angel_types_info[] = $angel_types[$selected_angel_type_id];
            }

            engelsystem_log("User " . User_Nick_render(User($user_id)) . " signed up as: " . join(", ", $user_angel_types_info));
            success(_("Angel registration successful!"));

            // User is already logged in - that means a coordinator has registered an angel. Return to register page.
            if (isset($user)) {
                redirect(page_link_to('register'));
            }

            // If a welcome message is present, display registration success page.
            if ($event_config != null && $event_config['event_welcome_msg'] != null) {
                return User_registration_success_view($event_config['event_welcome_msg']);
            }

            redirect('?');
        }
    }

    return page([
        div('container', [

            div('row', [
                div('col-md-5 col-md-offset-2', [
                    "<br>",
                    _('<h3>' . "LIEBE BIENE!" . '</h3>'),
                    _("Wir freuen uns riesig, dass du dich bereit erklärt hast uns bei der Organisation und Durchführung des TuttiFruttis zu unterstützen! Bitte lies dir " . "<a href='http://tuttifrutti-lager.de/teilnahmebedingungen/' target='_blank'>" . "die Teilnehmer- und Rahmenbedingungen" . "</a>" . " des Diözesanlagers 2017 durch, da du diesen mir deiner Anmeldung zustimmst und sie wichtige Informationen enthalten. Alle weiteren Infos findest du in den " . "<a href='http://tuttifrutti-lager.de/anmeldung/' target='_blank'>" . " Teilnehmer-Anmeldeunterlagen" . "</a>" . ". Bitte vergiss auch nicht den dortigen Gesundheitsbogen im Vorhinein auszufüllen und mitzubringen - dazu musst du Zuhause nämlich in deinen Impfpass schauen. Anmeldeschluss ist der 19. Mai 2017, weil wir bis dahin dem Zeltplatz Bescheid geben müssen, mit wie vielen Leuten wir kommen."),
                ]),
                div('col-md-3', [
                    "<br>",
                    _('<h3>' . "KOSTEN" . '</h3>'),
                    _("Als Helfer fällt für dich ein Betrag von 30€ an. Bitte überweise den Betrag auf folgendes Konto:"),
                    "<br>",
                    _("Empfänger: DPSG Augsburg"),
                    "<br>",
                    _("IBAN: DE53750903000200128600"),
                    "<br>",
                    _("BIC: GENODEF1M05"),
                    "<br>",
                    _("Bank: Liga Bank"),
                    "<br>",
                    _("Verwendungszweck: Helferbeitrag TuttiFrutti"),
                ]),
                div('col-md-12', [
                    "<br><br>",
                    $msg,
                    msg(),
                    "<br><br>",
                ])
            ]),

            form([
                div('row', [
                    div('col-md-8 col-md-offset-2', [

                        /* USER INFO */

                        div('row', [

                            div('col-sm-6', [
                                form_text('prename', _("First name") . ' ' . entry_required(), $prename)
                            ]),
                            div('col-sm-6', [
                                form_text('lastname', _("Last name") . ' ' . entry_required(), $lastname)
                            ]),
                            div('col-sm-6', [
                                form_text('adressstreet', _("Straße Hausnummer") . ' ' . entry_required(), $adressstreet)
                            ]),
                            div('col-sm-3', [
                                form_text('postalcode', _("PLZ") . ' ' . entry_required(), $postalcode)
                            ]),
                            div('col-sm-3', [
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

                        "<p><hr></p>",


                        div('row', [
                            div('col-sm-4', [
                                form_text('nick', _("Anmeldename (= Spitzname)") . ' ' . entry_required(), $nick)
                            ]),
                            div('col-sm-8', [
                                form_email('mail', _("E-Mail") . ' ' . entry_required(), $mail),
                                form_checkbox('email_shiftinfo', _("Please send me an email if my shifts change"), $email_shiftinfo)
                            ])
                        ]),

                        div('row', [
                            div('col-sm-6', [
                                form_password('password', _("Password") . ' ' . entry_required())
                            ]),
                            div('col-sm-6', [
                                form_password('password2', _("Confirm password") . ' ' . entry_required())
                            ])
                        ]),

                        "<p><hr></p>",

                        /* SLEEP & FOOD & HEALTH & SIZE */

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

                        /* KNOWLEDGE & MATERIAL */

                        div('row', [
                            div('col-sm-6', [
                                form_textarea('specialskills', _("Ich kann:"), $specialskills),
                                form_info("", _("Mit deinen Fähigkeiten kannst uns weiterhelfen!  Ihr habt einen Kettensägenschein oder könnt 7,5t fahren? Tetris ist für euch kein Spiel sondern nur eine Frage der Logistik? Teil es uns mit!"))
                            ]),
                            div('col-sm-6', [
                                form_textarea('specialmaterial', _("Ich habe:"), $specialmaterial),
                                form_info("", _("Auf einem Großlager wird sehr viel Material benötigt. Du hast zufällig eine Jurte im Dachboden? Dein Stamm könnte uns ein Gerüstzelt ausleihen? Dein Papa kennt die Bundeskanzlerin persönlich? Wenn du uns Ressourcen zur Verfügung stellen kannst, wären wir sehr dankbar."))
                            ]),
                        ]),

                        "<p><hr></p>",

                        /* COMING & GOING */

                        div('row', [
                            div('col-sm-6', [
                                form_date('planned_arrival_date', _("Planned date of arrival") . ' ' . entry_required(), $planned_arrival_date, time())
                            ]),
                            div('col-sm-6', [
                                form_text('planned_arrival_sort', _("Geplante Art der Anreise"), $planned_arrival_sort)
                            ]),
                            div('col-sm-12', [
                                form_checkbox('meeting_attending', _("<b>Ich nehme am Vorbereitungswochenende vom 05.05. bis 07.05.2017 teil.</b>"), $meeting_attending)
                            ])
                        ]),

                        "<p><hr></p>",


                        /* NAMI & VERSICHERUNG & ZUSTIMMUNG LAGERREGELN AGB */

                        div('row', [
                            div('col-sm-6', [
                                form_checkbox('nami', _("Ich bin in NaMi gemeldet"), $nami),
                                form_info("", _("Wer nicht in NaMi gemeldet ist, muss von uns für die Zeit des Lagers versichert werden, daher ist diese Auskunft wichtig."))

                            ]),
                            div('col-sm-6', [
                                form_checkbox('efz', _("Ich habe mein eFZ auf Bundesebene kontrollieren lassen"), $efz),
                                form_info("", _("<b>Unbedenklichkeitserklärung und Kinderschutz</b><br>
Jede Biene, die auf dem Lager anwesend sein wird, benötigt eine Bestätigung, dass sie sich nicht nach Delikten strafbar gemacht hat, welche im §72a SGB VIII aufgeführt sind. <br>Du kennst wahrscheinlich mittlerweile das Verfahren zum erweiterten Führungszeugnis (eFz) in der DPSG. Falls du also den Prozess in den letzten fünf Jahren schon einmal durchgemacht hast, dann lade dir doch einfach von <a href='https://nami.dpsg.de/' target='_blank'>deinem NaMi-Konto</a> die Unbedenklichkeits-Bestätigung herunter und <a href='mailto:mail@dpsg-augsburg.de'>schick sie uns</a>.
<br><br>Alternativ kannst du uns auch direkt Einblick in dein Führungszeugnis gewähren. Falls du ein aktuelles beantragen musst, dann können wir dir eine Bestätigung für das Bürgerbüro schreiben, damit du nichts bezahlen musst. Des Weiteren kannst du dein Führungszeugnis oder deine Unbedenklichkeitsbestätigung auch zum Vorbereitungswochenende (05. – 07.05.2017) zur Einsicht durch den Vorstand mitbringen.
<br><br>
Falls du noch Fragen rund um das Thema eFz hast, kannst du dir das <a href='http://www.dpsg-augsburg.de/fuer-euch/downloads/' target='_blank'>eFz-Paket</a> auf unserer Diözesanseite herunterladen.
                                "))
                            ])
                        ]),

                        "<p><hr></p>",

                        /* TYPES TO HELP */
                        // NEUE IDEE: Farbcodes + Emoji-Kombination für Helfer-Rollen
                        div('row', [
                            div('col-sm-12', [
                                "<p><h4>Erklärung zu den Rollen im Bienensystem</h4></p><p>Mit diesen Kategorien wirst du als Helfer einem jeweiligen Bereich zugeteilt. Du hast eine Anfrage direkt von einem AK bekommen? Z.B. von der Logistik? Dann setz dein Häkchen bei Logistik (fest, beschränkt). Du wurdest nicht angefragt, aber würdest z.B. gerne einen Logistikjob übernehmen? Dann setz dein Häkchen bei Logistik (frei). Gerne kannst du auch mehrere Häkchen setzten, wenn dich mehr interessiert. Du hast keine Präferenzen und kannst überall mit anpacken? Dann setz dein Häkchen bei Springer. Diese Angabe hilft uns beim Helfermanagement. Sie ist somit nicht in Stein gemeißelt und kann später bei Bedarf umgeändert werden.</p><br>",

                                form_checkboxes('angel_types', _("What do you want to do?"), /*. sprintf(" (<a href=\"%s\">%s</a>)", page_link_to('angeltypes') . '&action=about', _("Description of job types")),*/ $angel_types, $selected_angel_types),
                        form_info("", _("Restricted angel types need will be confirmed later by an archangel. You can change your selection in the options section.")),

                                //"<p><hr><h4>Allgemeine Erklärung des Bienensystems</h4></p>",

                            ]),
                            div('text-center', [
                                "<hr>",
                                form_submit('submit', _("Register")),
                                "<div class='text-center'><p  style='text-align:center;'><hr><h4>Du möchtest uns noch was sagen? <br>Nutze die Direktnachrichten- oder Fragefunktion im Bienensystem!</h4></p><p>Falls du irgendwelche Fragen zur Helfer-Anmeldung oder zum Lager hast, dann zögere nicht uns anzurufen oder anzuschreiben. Wir helfen dir gerne weiter: +49 821 3166 3468 oder mail@dpsg-augsburg.de</p></div>",
                            ])
                        ])

                    ]) // col-md-8
                ]) // row
            ]) // form
        ]) // container

    ]); // page
} // guest-register function

function entry_required()
{
    return '<span class="text-info glyphicon glyphicon-warning-sign"></span>';
}

function guest_logout()
{
    session_destroy();
    redirect(page_link_to("start"));
}

function guest_login()
{
    $nick = "";

    unset($_SESSION['uid']);

    if (isset($_REQUEST['submit'])) {
        $valid = true;

        if (isset($_REQUEST['nick']) && strlen(User_validate_Nick($_REQUEST['nick'])) > 0) {
            $nick = User_validate_Nick($_REQUEST['nick']);
            $login_user = sql_select("SELECT * FROM `User` WHERE `Nick`='" . sql_escape($nick) . "'");
            if (count($login_user) > 0) {
                $login_user = $login_user[0];
                if (isset($_REQUEST['password'])) {
                    if (!verify_password($_REQUEST['password'], $login_user['password'], $login_user['UID'])) {
                        $valid = false;
                        error(_("Your password is incorrect.  Please try it again."));
                    }
                } else {
                    $valid = false;
                    error(_("Please enter a password."));
                }
            } else {
                $valid = false;
                error(_("No user was found with that Nickname. Please try again. If you are still having problems, ask an Dispatcher."));
            }
        } else {
            $valid = false;
            error(_("Please enter a nickname."));
        }

        if ($valid) {
            $_SESSION['uid'] = $login_user['UID'];
            $_SESSION['locale'] = $login_user['Sprache'];

            redirect(page_link_to('news'));
        }
    }

    $event_config = EventConfig();
    if ($event_config === false) {
        engelsystem_error("Unable to load event config.");
    }

    return page([
        div('col-md-12', [
            div('row', [
                div('col-md-4', [
                    EventConfig_countdown_page($event_config)
                ]),
                div('col-md-4', [
                    heading(login_title(), 2),
                    msg(),
                    form([
                        form_text('nick', _("Nick"), $nick),
                        form_password('password', _("Password")),
                        form_submit('submit', _("Login")),
                        buttons([
                            button(page_link_to('user_password_recovery'), _("I forgot my password"))
                        ]),
                        info(_("Please note: You have to activate cookies!"), true)
                    ])
                ]),
                div('col-md-4', [
                    heading(register_title(), 2),
                    get_register_hint(),
                    heading(_("What can I do?"), 2),
                    '<p>' . _("Please read about the jobs you can do to help us.") . '</p>',
                    buttons([
                        button(page_link_to('angeltypes') . '&action=about', _("Teams/Job description") . ' &raquo;')
                    ])
                ])
            ])
        ])
    ]);
}

function get_register_hint()
{
    global $privileges;

    if (in_array('register', $privileges)) {
        return join('', [
            '<p>' . _("Please sign up, if you want to help us!") . '</p>',
            buttons([
                button(page_link_to('register'), register_title() . ' &raquo;')
            ])
        ]);
    }

    return error(_("Registration is disabled."), true);
}

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

?>