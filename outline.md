# Outline

## Discuss Project Goals, Tools, Etc
- What are we building?
- What tools will we use
   - docker
   - silex
   - psr-7
   - uuid
   - phinx
   - valitron
   - collection
   
   - phpunit
   - behat
   - faker
   - guzzle
   - mink extensions
- frontend app
  - Vue
- What are we not doing?
  - Authentication
  - Server architecture
  - long term data persistence (docker db is ephemeral)
  
- If there is something you don't understand, please raise your hand and ask about it.

## Behavior Test Loop
- read and explain `features/create-chirp.feature`
- run the test
  - append-snippets
- open `features/bootstrap/Api/CreateChirpContext.php`
  - Using faker for data
  - Use Guzzle for http requests to post to the app
    - downside to using guzzle, it throws exceptions on 400x
  - start filling in steps for first test, run test each time
    - write a chirp: generate text
    - submit the chirp: generate the rest of the data, post it. 
    - see it in my timeline: do a new request to the timeline endpoint
    
## Unit Test Loop
- Start on the outside with the Controller/Action
  - Create Method needs To:
    - Process/Handle User Input 
    - Persist Data (DB)
    - Return an appropriate response.
  - Do proof-of-concept working code first if needed
    - `public/test.php`
      - Stub request data
      - Persist Data 
        - run DB migration: `docker-compose exec api vendor/bin/phinx migrate`
      - Write SQL
      - Run POC.
  - Start unit tests
    - `tests/Unit/Chirp/CreateActionTest.php`
      - setup: We've identified 2 collaborators, mock those.
      - `testCreateSendsRequestToTransformer`
      - `testCreateReturnsInvalidChirpResponseOnTransformerException`
      - `testCreateSendsChirpToPersistence`
      - `testCreateReturnsInternalErrorResponseOnPersistenceException`
      - `testCreateSendsSavedChirpToTransformer`
      - `testCreateReturnsInternalServerErrorResponseOnTransformerException`
      - `testCreateReturnsChirpCreatedResponseOnSuccess`
    - `tests/Unit/Chirp/JsonApiChirpTransformerTest.php`
      - toChirp method:
        - needs to validate, and convert
        - Send validate logic to a validator class.
        - `testToChirpSendsJsonToValidator`
        - `testToChirpThrowsInvalidJsonExceptionWhenJsonInvalid`
        - `testToChirpReturnsChirpWithPropertiesFromJson`
      - POC code: `validate.php`
        - create data array
        - set up rules
        - validate
      - Work on tests
