This is an exmaple script, to create all users from an LDAP Server in openITCOCKPIT.
All users will be created as admin, assigned to the container `/root`.

## Requirements
- [php composer](https://getcomposer.org/download/)
- php
- [openITCOCKPIT installation](https://openitcockpit.io/download/)
- Working LDAP Configuration in openITCOCKPIT
- openITCOCKPIT Version >= 3.4


## Installation
````
git clone https://github.com/it-novum/openITCOCKPIT-LDAP-API-Importer.git
cd openITCOCKPIT-LDAP-API-Importer/
composer install
````

## Configuration
The configuration is done in the file `etc/config.yml`. Set the hostname or ip address
of your openITCOCKPIT Server, and a username and password for the API login.

## Execute
````
php bin/LDAPImport.php
````

### Example
````
$ php bin/LDAPImport.php
Login successful

User "mmustermann" (18) created successfully.
User foobar already exists
User "barfoo" (19) created successfully.
All users imported
````

# License
MIT-License