<?php

// Empfange die Formulardaten über die POST-Methode
$name = $_POST['name'];
$email = $_POST['email'];
$subject = $_POST['subject'];
$message = $_POST['message'];
$recaptcha = $_POST['recaptcha-response'];

// Überprüfe, ob alle benötigten Felder ausgefüllt wurden
if (empty($name) || empty($email) || empty($subject) || empty($message)) {
  echo "Bitte fülle alle benötigten Felder aus.";
  exit;
}

// Überprüfe, ob die eingegebene E-Mail-Adresse gültig ist
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  echo "Bitte gib eine gültige E-Mail-Adresse ein.";
  exit;
}

// Überprüfe, ob das reCaptcha korrekt ausgefüllt wurde
$secret = 'DEINE_SECRET_KEY';
$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$recaptcha}");
$response = json_decode($response, true);

if ($response['success'] === false) {
  echo "Bitte bestätige, dass du kein Roboter bist.";
  exit;
}

// Setze den Betreff der E-Mail
$subject = "[Kontaktanfrage] {$subject}";

// Setze den Inhalt der E-Mail
$body = "Von: {$name}\nE-Mail: {$email}\nNachricht:\n{$message}";

// Setze den Absender der E-Mail
$headers = "From: {$name} <{$email}>";

// Sende die E-Mail
if (mail('contact@example.com', $subject, $body, $headers)) {
  echo 'OK';
} else {
  echo "Beim Versenden der E-Mail ist ein Fehler aufgetreten.";
}

?>
