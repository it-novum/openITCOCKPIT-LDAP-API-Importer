<?php
/**
 * @copyright     Copyright (c) it-novum GmbH
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

namespace itnovum\openITCOKPIT\LDAP\API;


use GuzzleHttp\Client;
use itnovum\openITCOKPIT\LDAP\API\Exception\UserAlreadyExistsException;
use RuntimeException;

class Requests {

    /**
     * @var Client
     */
    private $Client;

    /**
     * @var Config
     */
    private $Config;

    /**
     * Requests constructor.
     * @param Client $Client
     * @param Config $Config
     */
    public function __construct(Client $Client, Config $Config) {
        $this->Client = $Client;
        $this->Config = $Config;
    }

    /**
     * @param Config $Config
     * @return bool
     */
    public function login(Config $Config){
        $loginresponse = $this->Client->post("/login/login.json",
            [
                'body' => json_encode(
                    [
                        'LoginUser' => [
                            'auth_method' => 'session',
                            'email'       => $Config->getUser(),
                            'password'    => $Config->getPassword()
                        ]
                    ]
                )
            ]
        );

        if (json_decode($loginresponse->getBody()->getContents(), true)['message'] === 'Login successful') {
            return true;
        }

        throw new RuntimeException('Login failed!');
    }

    /**
     * @return array
     */
    public function getLDAPUsers(){
        $response = $this->Client->get("/users/addFromLdap.json");
        $response = json_decode($response->getBody()->getContents(), true);

        if(isset($response['usersForSelect'])){
            return $response['usersForSelect'];
        }

        return [];
    }

    /**
     * @param string $samAccountName
     * @param array $data
     * @return mixed
     * @throws UserAlreadyExistsException
     */
    public function createUserFromLDAP($samAccountName = 'empty', $data = []){

        //Pass complete url to avoid URL parse error
        $response = $this->Client->post(sprintf(
            "https://%s/users/add/ldap:1/samaccountname:%s/fix:1.json",
            $this->Config->getHost(),
            $samAccountName),
            [
                'body' => json_encode($data)
            ]
        );

        if($response->getStatusCode() === 200){
            $data = json_decode($response->getBody()->getContents(), true);

            if(isset($data['error'])){
                if(isset($data['error']['email']) && $data['error']['email'][0] === 'This email address has already been taken.'){
                    throw new UserAlreadyExistsException(sprintf('User %s already exists', $samAccountName));
                }

                $errorMsg = '';
                foreach($data['error'] as $field => $errors){
                    foreach($errors as $error){
                        $errorMsg .= sprintf('Error: %s: %s%s', $field, $error, PHP_EOL);
                    }
                }
                throw new RuntimeException($errorMsg);
            }
        }

        return $data['id'];

    }

}