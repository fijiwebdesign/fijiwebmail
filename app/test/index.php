<?php
/**
 * Testing Random things
 */



use Fiji\Factory;

$User = Factory::getUser();
$App = Factory::getApplication();
$Req = Factory::getRequest();

if (!$User->isAuthenticated()) {
    $App->setReturnUrl($Req->getUri());
    $App->redirect('?app=auth', 'Please login to access your email.');
}

echo "User: ";
echo $User->html('username');

?>
