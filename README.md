# Wave PHP - Double Loop Tutorial 
This tutorial will walk through practicing the Double Loop Workflow to create a small application.

## Requirements
- [Docker Desktop](https://www.docker.com/products/docker-desktop) 
- PHP 7.2
- [Composer](https://getcomposer.org/download/)

### Recommended
- [Postman App](https://www.getpostman.com/)
- Chrome with the [JSON Viewer extension](https://chrome.google.com/webstore/detail/json-viewer/gbmdgpbipfallnflgajpaliibnhdgobh)

## Setup - Do this before the conference!
- `composer install` 
- `docker-compose up -d`
- Browse to `localhost:3000` and confirm there is a PHP info page
  - If you're using docker-machine instead of Docker-for-X, use the correct IP address.
  
#### Info
- [JSON API](http://jsonapi.org/) - Our API will be implementing JSON API

### Outline

#### Ensure in Project Setup
- phinx.yml

#### Project Init Tasks
- `vendor/bin/behat --init`  

#### Creating Chirps Task

##### Add & Run Behavior Test
- Add feature file `features/create-chirp.feature`
- View prompts: `vendor/bin/behat`
- Create new Context File: `CreateChirpContext`
- configure `behat.yml`
- `vendor/bin/behat --append-snippets`
- `vendor/bin/behat`
- Fill in test code
    - Add Faker to Context, generate some text
    - Add Guzzle Client to Context, post a chirp
    - Use Guzzle to request the timeline, throw exception if not present
    
#### Add Unit Tests
- Add Unit Test for `ChirpIoService->create`
    - Create Method needs To:
        - Take in User Input and turn it into a Chirp
        - Send Chirp to Persistence Layer (DB)
        - Return an appropriate response.
    - Do proof-of-concept working code first if needed
        - Stub request data
        - Create Chirp from data
        - Persist Chirp - need DB migration
            
        - All routes will accept a PSR-7 request (uses Guzzle PSR 7 interface)
            - Create PSR-7 compliant Request/Response base classes
        - Create 