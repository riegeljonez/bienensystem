<?php

function admin_user_title() {
  return _("All Angels");
}

function admin_user() {
  global $user, $privileges, $tshirt_sizes, $privileges;
  
  $html = '';
  
  if (! isset($_REQUEST['id'])) {
    redirect(users_link());
  }
  
  $user_id = $_REQUEST['id'];
  if (! isset($_REQUEST['action'])) {
    $user_source = User($user_id);
    if ($user_source === false) {
      engelsystem_error('Unable to load user.');
    }
    if ($user_source == null) {
      error(_('This user does not exist.'));
      redirect(users_link());
    }
    
    $html .= "Hallo,<br />" . "hier kannst du den Eintrag &auml;ndern. Unter dem Punkt 'Gekommen' " . "wird der Engel als anwesend markiert, ein Ja bei Aktiv bedeutet, " . "dass der Engel aktiv war und damit ein Anspruch auf ein T-Shirt hat. " . "Wenn T-Shirt ein 'Ja' enth&auml;lt, bedeutet dies, dass der Engel " . "bereits sein T-Shirt erhalten hat.<br /><br />\n";
    
    $html .= "<form action=\"" . page_link_to("admin_user") . "&action=save&id=$user_id\" method=\"post\">\n";
    $html .= "<table border=\"0\">\n";
    $html .= "<input type=\"hidden\" name=\"Type\" value=\"Normal\">\n";
    $html .= "<tr><td>\n";
    $html .= "<table>\n";
    $html .= "  <tr><td>Nick</td><td>" . "<input type=\"text\" size=\"40\" name=\"eNick\" value=\"" . $user_source['Nick'] . "\"></td></tr>\n";
    $html .= "  <tr><td>lastLogIn</td><td>" . date("Y-m-d H:i", $user_source['lastLogIn']) . "</td></tr>\n";
    $html .= "  <tr><td>Name</td><td>" . "<input type=\"text\" size=\"40\" name=\"eName\" value=\"" . $user_source['lastname'] . "\"></td></tr>\n";
    $html .= "  <tr><td>Vorname</td><td>" . "<input type=\"text\" size=\"40\" name=\"eVorname\" value=\"" . $user_source['prename'] . "\"></td></tr>\n";
    $html .= "  <tr><td>Alter</td><td>" . "<input type=\"text\" size=\"5\" name=\"eAlter\" value=\"" . $user_source['age'] . "\"></td></tr>\n";
    //$html .= "  <tr><td>Telefon</td><td>" . "<input type=\"text\" size=\"40\" name=\"eTelefon\" value=\"" . $user_source['Telefon'] . "\"></td></tr>\n";
    $html .= "  <tr><td>Handy</td><td>" . "<input type=\"text\" size=\"40\" name=\"eHandy\" value=\"" . $user_source['mobile'] . "\"></td></tr>\n";
    $html .= "  <tr><td>DECT</td><td>" . "<input type=\"text\" size=\"4\" name=\"eDECT\" value=\"" . $user_source['DECT'] . "\"></td></tr>\n";
    $html .= "  <tr><td>email</td><td>" . "<input type=\"text\" size=\"40\" name=\"eemail\" value=\"" . $user_source['email'] . "\"></td></tr>\n";
    $html .= "<tr><td>" . form_checkbox('email_shiftinfo', _("Please send me an email if my shifts change"), $user_source['email_shiftinfo']) . "</td></tr>\n";
    //$html .= "  <tr><td>jabber</td><td>" . "<input type=\"text\" size=\"40\" name=\"ejabber\" value=\"" . $user_source['jabber'] . "\"></td></tr>\n";
    $html .= "  <tr><td>Size</td><td>" . html_select_key('size', 'eSize', $tshirt_sizes, $user_source['Size']) . "</td></tr>\n";
    
    $options = [
        '1' => _("Yes"),
        '0' => _("No") 
    ];
    
    // Gekommen?
    $html .= "  <tr><td>Gekommen</td><td>\n";
    $html .= html_options('eGekommen', $options, $user_source['Gekommen']) . "</td></tr>\n";
    
    // Aktiv?
    $html .= "  <tr><td>Aktiv</td><td>\n";
    $html .= html_options('eAktiv', $options, $user_source['Aktiv']) . "</td></tr>\n";
    
    // Aktiv erzwingen
    if (in_array('admin_active', $privileges)) {
      $html .= "  <tr><td>" . _("Force active") . "</td><td>\n";
      $html .= html_options('force_active', $options, $user_source['force_active']) . "</td></tr>\n";
    }
    
    // T-Shirt bekommen?
    $html .= "  <tr><td>T-Shirt</td><td>\n";
    $html .= html_options('eTshirt', $options, $user_source['Tshirt']) . "</td></tr>\n";
    
    $html .= "  <tr><td>Hometown</td><td>" . "<input type=\"text\" size=\"40\" name=\"Hometown\" value=\"" . $user_source['Hometown'] . "\"></td></tr>\n";
    
    $html .= "</table>\n</td><td valign=\"top\"></td></tr>";
    
    $html .= "</td></tr>\n";
    $html .= "</table>\n<br />\n";
    $html .= "<input type=\"submit\" value=\"Speichern\">\n";
    $html .= "</form>";
    
    $html .= "<hr />";
    
    $html .= form_info('', _('Please visit the angeltypes page or the users profile to manage users angeltypes.'));
    
    $html .= "Hier kannst Du das Passwort dieses Engels neu setzen:<form action=\"" . page_link_to("admin_user") . "&action=change_pw&id=$user_id\" method=\"post\">\n";
    $html .= "<table>\n";
    $html .= "  <tr><td>Passwort</td><td>" . "<input type=\"password\" size=\"40\" name=\"new_pw\" value=\"\"></td></tr>\n";
    $html .= "  <tr><td>Wiederholung</td><td>" . "<input type=\"password\" size=\"40\" name=\"new_pw2\" value=\"\"></td></tr>\n";
    
    $html .= "</table>";
    $html .= "<input type=\"submit\" value=\"Speichern\">\n";
    $html .= "</form>";
    
    $html .= "<hr />";
    
    $my_highest_group = sql_select("SELECT * FROM `UserGroups` WHERE `uid`='" . sql_escape($user['UID']) . "' ORDER BY `group_id` LIMIT 1");
    if (count($my_highest_group) > 0) {
      $my_highest_group = $my_highest_group[0]['group_id'];
    }
    
    $his_highest_group = sql_select("SELECT * FROM `UserGroups` WHERE `uid`='" . sql_escape($user_id) . "' ORDER BY `group_id` LIMIT 1");
    if (count($his_highest_group) > 0) {
      $his_highest_group = $his_highest_group[0]['group_id'];
    }
    
    if ($user_id != $user['UID'] && $my_highest_group <= $his_highest_group) {
      $html .= "Hier kannst Du die Benutzergruppen des Engels festlegen:<form action=\"" . page_link_to("admin_user") . "&action=save_groups&id=" . $user_id . "\" method=\"post\">\n";
      $html .= '<table>';
      
      $groups = sql_select("SELECT * FROM `Groups` LEFT OUTER JOIN `UserGroups` ON (`UserGroups`.`group_id` = `Groups`.`UID` AND `UserGroups`.`uid` = '" . sql_escape($user_id) . "') WHERE `Groups`.`UID` >= '" . sql_escape($my_highest_group) . "' ORDER BY `Groups`.`Name`");
      foreach ($groups as $group) {
        $html .= '<tr><td><input type="checkbox" name="groups[]" value="' . $group['UID'] . '"' . ($group['group_id'] != "" ? ' checked="checked"' : '') . ' /></td><td>' . $group['Name'] . '</td></tr>';
      }
      
      $html .= '</table>';
      
      $html .= "<input type=\"submit\" value=\"Speichern\">\n";
      $html .= "</form>";
      
      $html .= "<hr />";
    }
    
    $html .= buttons([
        button(user_delete_link($user_source), glyph('lock') . _("delete"), 'btn-danger') 
    ]);
    
    $html .= "<hr />";
  } else {
    switch ($_REQUEST['action']) {
      case 'save_groups':
        if ($user_id != $user['UID']) {
          $my_highest_group = sql_select("SELECT * FROM `UserGroups` WHERE `uid`='" . sql_escape($user['UID']) . "' ORDER BY `group_id`");
          $his_highest_group = sql_select("SELECT * FROM `UserGroups` WHERE `uid`='" . sql_escape($user_id) . "' ORDER BY `group_id`");
          
          if (count($my_highest_group) > 0 && (count($his_highest_group) == 0 || ($my_highest_group[0]['group_id'] <= $his_highest_group[0]['group_id']))) {
            $groups_source = sql_select("SELECT * FROM `Groups` LEFT OUTER JOIN `UserGroups` ON (`UserGroups`.`group_id` = `Groups`.`UID` AND `UserGroups`.`uid` = '" . sql_escape($user_id) . "') WHERE `Groups`.`UID` >= '" . sql_escape($my_highest_group[0]['group_id']) . "' ORDER BY `Groups`.`Name`");
            $groups = [];
            $grouplist = [];
            foreach ($groups_source as $group) {
              $groups[$group['UID']] = $group;
              $grouplist[] = $group['UID'];
            }
            
            if (! is_array($_REQUEST['groups'])) {
              $_REQUEST['groups'] = [];
            }
            
            sql_query("DELETE FROM `UserGroups` WHERE `uid`='" . sql_escape($user_id) . "'");
            $user_groups_info = [];
            foreach ($_REQUEST['groups'] as $group) {
              if (in_array($group, $grouplist)) {
                sql_query("INSERT INTO `UserGroups` SET `uid`='" . sql_escape($user_id) . "', `group_id`='" . sql_escape($group) . "'");
                $user_groups_info[] = $groups[$group]['Name'];
              }
            }
            $user_source = User($user_id);
            engelsystem_log("Set groups of " . User_Nick_render($user_source) . " to: " . join(", ", $user_groups_info));
            $html .= success("Benutzergruppen gespeichert.", true);
          } else {
            $html .= error("Du kannst keine Engel mit mehr Rechten bearbeiten.", true);
          }
        } else {
          $html .= error("Du kannst Deine eigenen Rechte nicht bearbeiten.", true);
        }
        break;
      
      case 'save':
        $force_active = $user['force_active'];
        if (in_array('admin_active', $privileges)) {
          $force_active = $_REQUEST['force_active'];
        }
        $SQL = "UPDATE `User` SET 
              `Nick` = '" . sql_escape($_POST["eNick"]) . "', 
              `lastname` = '" . sql_escape($_POST["eName"]) . "',
              `prename` = '" . sql_escape($_POST["eVorname"]) . "',
              `mobile` = '" . sql_escape($_POST["eHandy"]) . "',
              `age` = '" . sql_escape($_POST["eAlter"]) . "',
              `email` = '" . sql_escape($_POST["eemail"]) . "',
              `email_shiftinfo` = " . sql_bool(isset($_REQUEST['email_shiftinfo'])) . ", 
              `jabber` = '" . sql_escape($_POST["ejabber"]) . "', 
              `Size` = '" . sql_escape($_POST["eSize"]) . "', 
              `Gekommen`= '" . sql_escape($_POST["eGekommen"]) . "', 
              `Aktiv`= '" . sql_escape($_POST["eAktiv"]) . "', 
              `force_active`= " . sql_escape($force_active) . ", 
              `Tshirt` = '" . sql_escape($_POST["eTshirt"]) . "', 
              `Hometown` = '" . sql_escape($_POST["Hometown"]) . "' 
              WHERE `UID` = '" . sql_escape($user_id) . "' 
              LIMIT 1";
        sql_query($SQL);
        engelsystem_log("Updated user: " . $_POST["eNick"] . ", " . $_POST["eSize"] . ", arrived: " . $_POST["eGekommen"] . ", active: " . $_POST["eAktiv"] . ", tshirt: " . $_POST["eTshirt"]);
        $html .= success("Änderung wurde gespeichert...\n", true);
        break;
      
      case 'change_pw':
        if ($_REQUEST['new_pw'] != "" && $_REQUEST['new_pw'] == $_REQUEST['new_pw2']) {
          set_password($user_id, $_REQUEST['new_pw']);
          $user_source = User($user_id);
          engelsystem_log("Set new password for " . User_Nick_render($user_source));
          $html .= success("Passwort neu gesetzt.", true);
        } else {
          $html .= error("Die Eingaben müssen übereinstimmen und dürfen nicht leer sein!", true);
        }
        break;
    }
  }
  
  return page_with_title(_("Edit user"), [
      $html 
  ]);
}
?>
