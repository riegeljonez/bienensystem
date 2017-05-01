<?php

/**
 * User model
 */

/**
 * Delete a user
 *
 * @param int $user_id          
 */
function User_delete($user_id) {
  return sql_query("DELETE FROM `User` WHERE `UID`='" . sql_escape($user_id) . "'");
}

/**
 * Update user.
 *
 * @param User $user          
 */
function User_update($user) {
  return sql_query("UPDATE `User` SET
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
      `kommentar`='" . sql_escape($comment) . "', 
      `CreateDate`=NOW(),
      `Sprache`='" . sql_escape($_SESSION["locale"]) . "',
      `arrival_date`=NULL,
      `planned_arrival_date`='" . sql_escape($planned_arrival_date) . "',
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
      `Gekommen`='" . sql_escape($user['Gekommen']) . "',
      `Aktiv`='" . sql_escape($user['Aktiv']) . "',
      `force_active`=" . sql_bool($user['force_active']) . ",
      `Tshirt`='" . sql_escape($user['Tshirt']) . "',
      `got_voucher`='" . sql_escape($user['got_voucher']) . "',
      `arrival_date`='" . sql_escape($user['arrival_date']) . "',
      `planned_arrival_date`='" . sql_escape($user['planned_arrival_date']) . "'
      WHERE `UID`='" . sql_escape($user['UID']) . "'");
}

/**
 * Counts all forced active users.
 */
function User_force_active_count() {
  return sql_select_single_cell("SELECT COUNT(*) FROM `User` WHERE `force_active` = 1");
}

function User_active_count() {
  return sql_select_single_cell("SELECT COUNT(*) FROM `User` WHERE `Aktiv` = 1");
}

function User_got_voucher_count() {
  return sql_select_single_cell("SELECT SUM(`got_voucher`) FROM `User`");
}

function User_arrived_count() {
  return sql_select_single_cell("SELECT COUNT(*) FROM `User` WHERE `Gekommen` = 1");
}

function User_tshirts_count() {
  return sql_select_single_cell("SELECT COUNT(*) FROM `User` WHERE `Tshirt` = 1");
}

function User_meeting_attending_count() {
  return sql_select_single_cell("SELECT COUNT(*) FROM `User` WHERE `meeting_attending` = 1");
}

/**
 * Returns all column names for sorting in an array.
 */
function User_sortable_columns() {
  return [
      'nick',
      'lastname',
      'prename',
      'age',
      'DECT',
      'email',
      'size',
      'Gekommen',
      'Aktiv',
      'force_active',
      'Tshirt',
      'lastLogIn',
      'specialskills',
      'specialmaterial',
      'specialhealth',
      'specialfood',
      'diocese',
    'nami',
    'efz',
    'meeting_attending'
  ];
}

/**
 * Get all users, ordered by Nick by default or by given param.
 *
 * @param string $order_by          
 */
function Users($order_by = 'Nick') {
  return sql_select("SELECT * FROM `User` ORDER BY `" . sql_escape($order_by) . "` ASC");
}

/**
 * Returns true if user is freeloader
 *
 * @param User $user          
 */
function User_is_freeloader($user) {
  global $max_freeloadable_shifts, $user;
  
  return count(ShiftEntries_freeloaded_by_user($user)) >= $max_freeloadable_shifts;
}

/**
 * Returns all users that are not member of given angeltype.
 *
 * @param Angeltype $angeltype          
 */
function Users_by_angeltype_inverted($angeltype) {
  return sql_select("
      SELECT `User`.*
      FROM `User`
      LEFT JOIN `UserAngelTypes` ON (`User`.`UID`=`UserAngelTypes`.`user_id` AND `angeltype_id`='" . sql_escape($angeltype['id']) . "')
      WHERE `UserAngelTypes`.`id` IS NULL
      ORDER BY `Nick`");
}

/**
 * Returns all members of given angeltype.
 *
 * @param Angeltype $angeltype          
 */
function Users_by_angeltype($angeltype) {
  return sql_select("
      SELECT
      `User`.*,
      `UserAngelTypes`.`id` as `user_angeltype_id`,
      `UserAngelTypes`.`confirm_user_id`,
      `UserAngelTypes`.`coordinator`,
      `UserDriverLicenses`.*
      FROM `User`
      JOIN `UserAngelTypes` ON `User`.`UID`=`UserAngelTypes`.`user_id`
      LEFT JOIN `UserDriverLicenses` ON `User`.`UID`=`UserDriverLicenses`.`user_id`
      WHERE `UserAngelTypes`.`angeltype_id`='" . sql_escape($angeltype['id']) . "'
      ORDER BY `Nick`");
}

/**
 * Returns User id array
 */
function User_ids() {
  return sql_select("SELECT `UID` FROM `User`");
}

/**
 * Strip unwanted characters from a users nick.
 *
 * @param string $nick          
 */
function User_validate_Nick($nick) {
  return preg_replace("/([^a-z0-9üöäß. _+*-]{1,})/ui", '', $nick);
}

/**
 * Returns user by id.
 *
 * @param $user_id UID          
 */
function User($user_id) {
  $user_source = sql_select("SELECT * FROM `User` WHERE `UID`='" . sql_escape($user_id) . "' LIMIT 1");
  if ($user_source === false) {
    return false;
  }
  if (count($user_source) > 0) {
    return $user_source[0];
  }
  return null;
}

/**
 * TODO: Merge into normal user function
 * Returns user by id (limit informations.
 *
 * @param $user_id UID          
 */
function mUser_Limit($user_id) {
  $user_source = sql_select("SELECT `UID`, `Nick`, `Name`, `Vorname`, `Telefon`, `DECT`, `Handy`, `email`, `jabber` FROM `User` WHERE `UID`='" . sql_escape($user_id) . "' LIMIT 1");
  if ($user_source === false) {
    return false;
  }
  if (count($user_source) > 0) {
    return $user_source[0];
  }
  return null;
}

/**
 * Returns User by api_key.
 *
 * @param string $api_key
 *          User api key
 * @return Matching user, null or false on error
 */
function User_by_api_key($api_key) {
  $user = sql_select("SELECT * FROM `User` WHERE `api_key`='" . sql_escape($api_key) . "' LIMIT 1");
  if ($user === false) {
    return false;
  }
  if (count($user) == 0) {
    return null;
  }
  return $user[0];
}

/**
 * Returns User by email.
 *
 * @param string $email          
 * @return Matching user, null or false on error
 */
function User_by_email($email) {
  $user = sql_select("SELECT * FROM `User` WHERE `email`='" . sql_escape($email) . "' LIMIT 1");
  if ($user === false) {
    return false;
  }
  if (count($user) == 0) {
    return null;
  }
  return $user[0];
}

/**
 * Returns User by password token.
 *
 * @param string $token          
 * @return Matching user, null or false on error
 */
function User_by_password_recovery_token($token) {
  $user = sql_select("SELECT * FROM `User` WHERE `password_recovery_token`='" . sql_escape($token) . "' LIMIT 1");
  if ($user === false) {
    return false;
  }
  if (count($user) == 0) {
    return null;
  }
  return $user[0];
}

/**
 * Generates a new api key for given user.
 *
 * @param User $user          
 */
function User_reset_api_key(&$user, $log = true) {
  $user['api_key'] = md5($user['Nick'] . time() . rand());
  $result = sql_query("UPDATE `User` SET `api_key`='" . sql_escape($user['api_key']) . "' WHERE `UID`='" . sql_escape($user['UID']) . "' LIMIT 1");
  if ($result === false) {
    return false;
  }
  if ($log) {
    engelsystem_log(sprintf("API key resetted (%s).", User_Nick_render($user)));
  }
}

/**
 * Generates a new password recovery token for given user.
 *
 * @param User $user          
 */
function User_generate_password_recovery_token(&$user) {
  $user['password_recovery_token'] = md5($user['Nick'] . time() . rand());
  $result = sql_query("UPDATE `User` SET `password_recovery_token`='" . sql_escape($user['password_recovery_token']) . "' WHERE `UID`='" . sql_escape($user['UID']) . "' LIMIT 1");
  if ($result === false) {
    return false;
  }
  engelsystem_log("Password recovery for " . User_Nick_render($user) . " started.");
  return $user['password_recovery_token'];
}

function User_get_eligable_voucher_count(&$user) {
  global $voucher_settings;
  
  $shifts_done = count(ShiftEntries_finished_by_user($user));
  
  $earned_vouchers = $user['got_voucher'] - $voucher_settings['initial_vouchers'];
  $elegible_vouchers = $shifts_done / $voucher_settings['shifts_per_voucher'] - $earned_vouchers;
  if ($elegible_vouchers < 0) {
    return 0;
  }
  
  return $elegible_vouchers;
}

?>
