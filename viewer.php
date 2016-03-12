<?php
/**
 * auth_kitten viewer.php
 *
 */
require_once dirname(__FILE__) . '/Auth/Kitten.php';

$auth = new Auth_Kitten();
$html = $auth->drawImage(basename($_GET['f']));
echo $html;

