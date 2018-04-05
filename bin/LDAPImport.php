<?php
/**
 * @copyright     Copyright (c) it-novum GmbH
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

use GuzzleHttp\Client;
use itnovum\openITCOKPIT\LDAP\API\Config;
use itnovum\openITCOKPIT\LDAP\API\Exception\UserAlreadyExistsException;
use itnovum\openITCOKPIT\LDAP\API\Requests;
use itnovum\openITCOKPIT\LDAP\API\User;

require_once __DIR__ . '/../vendor/autoload.php';

$Config = new Config();

$Client = new Client([
    'base_uri'        => 'https://' . $Config->getHost(),
    'headers'         => [
        'Content-Type' => 'application/json'
    ],
    'verify'          => false,
    'cookies'         => true,
    'connect_timeout' => 5,
    'timeout'         => 10
]);

$Request = new Requests($Client, $Config);
$Request->login($Config);

printf('Login successful%s%s', PHP_EOL, PHP_EOL);

$User = new User($Request);
$users = $User->getUsersFromLdap();


foreach ($users as $samAccountName => $name) {
    try {
        $userId = $User->createUser($samAccountName, $name);
        printf('User "%s" (%s) created successfully.%s', $samAccountName, $userId, PHP_EOL);
    } catch (UserAlreadyExistsException $e) {
        echo $e->getMessage() . PHP_EOL;
    }
}

echo 'All users imported'.PHP_EOL;

