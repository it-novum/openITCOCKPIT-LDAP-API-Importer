<?php
/**
 * @copyright     Copyright (c) it-novum GmbH
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

namespace itnovum\openITCOKPIT\LDAP\API;


use RuntimeException;
use Symfony\Component\Yaml\Yaml;

class Config {

    /**
     * @var string
     */
    private $configFile = __DIR__ . '/' . '../etc/config.yml';

    /**
     * @var array
     */
    private $config = [];

    public function __construct() {
        if(!file_exists($this->configFile)){
            throw new RuntimeException(sprintf('Config file %s not found', $this->configFile));
        }

        $this->config = Yaml::parseFile($this->configFile);
    }

    public function getHost(){
        return $this->config['host'];
    }

    public function getUser(){
        return $this->config['user'];
    }

    public function getPassword(){
        return $this->config['password'];
    }

}
