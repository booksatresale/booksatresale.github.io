<?php
  $name = filter_input(INPUT_POST, 'name');
  $email = filter_input(INPUT_POST, 'email');
  $password = filter_input(INPUT_POST, 'password');
  $dob = filter_input(INPUT_POST, 'dob');
  $mobile = filter_input(INPUT_POST, 'mobile');
  $address = filter_input(INPUT_POST, 'address');

  $msg = 'Your account has been made, <br /> please verify it by clicking the activation link that has been send to your email.';
  $hash = md5( rand(0,1000) );

  $mng = new MongoDB\Driver\Manager("mongodb+srv://reetik:reetik@cluster0.wksa9.mongodb.net/database2?retryWrites=true&w=majority");

  $bulk = new MongoDB\Driver\BulkWrite;
  $doc = ['_id' => strval(new MongoDB\BSON\ObjectID), 'name' => $name, 'email' => $email, 'password' => $password, 'dob' => $dob, 'mobile' => intval($mobile), 'address' => $address,
   'hash' => $hash, 'activation' => 0];
  $bulk->insert($doc);
  $mng->executeBulkWrite('database2.users', $bulk);
  $to      = $email;
  $subject = 'Signup | Verification';
  $message = '
  Thanks for signing up!
  Your account has been created, you can login with the following credentials after you have activated your account by pressing the url below.
  ------------------------
  Email Id: '.$email.'
  Password: '.$password.'
  ------------------------
  Please click this link to activate your account:
  http://localhost/verify.php?email='.$email.'&hash='.$hash.'

  ';
  $headers = 'From:booksatresale.system@gmail.com' . "\r\n";
  mail($to, $subject, $message, $headers);
?>
