# tp-test 

## Installation

1) clone this repository on your local workstation
2) docker-compose up -d
3) docker exec -it tp-test_web_1 bash (the container name is autogenerated
maybe you have to run 'docker ps' to check the name)
4) composer install

## Usage

You can log in the platform using the following account.   
email: admin@admin.fr  
password: admin

The platform is originally populated of 4 contacts.

You can run test by running php bin/phpunit inside the container (step 3 to get into it)