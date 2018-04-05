<?php
/**
 * @copyright     Copyright (c) it-novum GmbH
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

namespace itnovum\openITCOKPIT\LDAP\API;


use GuzzleHttp\Client;

class User {

    const ACTIVE_USER = 1;

    const USER_TIMEZONE = 'Europe/Berlin';

    const ROOT_CONTAINER = 1;

    /**
     * @var Requests
     */
    private $Requests;

    public function __construct(Requests $Requests) {
        $this->Requests = $Requests;
    }

    /**
     * @return array
     */
    public function getUsersFromLdap() {
        return $this->Requests->getLDAPUsers();
    }

    /**
     * @param $samAccountName
     * @param $name
     * @return mixed
     * @throws Exception\UserAlreadyExistsException
     */
    public function createUser($samAccountName, $name) {
        $data = [
            'User'                    => [
                'status'         => self::ACTIVE_USER,
                'position'       => 'Admin',
                'company'        => '', // Empty on purpose.
                'phone'          => '',
                'timezone'       => self::USER_TIMEZONE,
                'samaccountname' => $samAccountName,
                'full_name'      => $name,
                'Container'      => [
                    self::ROOT_CONTAINER
                ],
                // usergroup id default value 1
                'usergroup_id'   => 1,
            ],
            'ContainerUserMembership' => [
                self::ROOT_CONTAINER => '2' //Set write permissions for ROOT_CONTAINER
            ]
        ];

        return $this->Requests->createUserFromLDAP($samAccountName, $data);
    }

}