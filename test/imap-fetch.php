<?php
/**
 * Testing IMAP transport on Zeta Components Mail
 * @author gabe@fijiwebdesing.com 
 * @url http://www.fijiwebdesign.com
 * @profile http://www.linkedin.com/in/bucabay
 */

require_once('ZetaMail/autoload.php');

$user = 'gabe@fijiwebdesign.com';
$pass = '@f4cf538$';

$server = 'imap.gmail.com';
$port = 993;
$options = new ezcMailImapTransportOptions();
$options->ssl = true;
 
try {
	$Imap = new ezcMailImapTransport($server, $port, $options);
} catch (Exception $e) {
	var_dump($e);
	die;
}

$Imap->authenticate($user, $pass);

$Imap->selectMailbox( 'Inbox', false);

$mailboxes = $Imap->listMailboxes();

var_dump($mailboxes);

//$msgs = $Imap->listMessages();

//var_dump($msgs);

$msg1 = $Imap->fetchByMessageNr(1);

echo '<pre>';
print_r($msg1);

