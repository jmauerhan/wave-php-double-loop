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
- open `features/bootstrap/Frontend/CreateChirpContext.php`
   - uses MinkContext to hook up to the headless chrome
   - Check Selenium: `http://local.chirper.com:4444/wd/hub/status`
   - You can run these two services separately, if you need more control over the headless chrome for debugging. 
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

### `tests/Unit/Chirp/CreateActionTest.php`
- setup: We've identified 2 collaborators, mock those.
- `testCreateSendsRequestToTransformer`
- `testCreateReturnsInvalidChirpResponseOnTransformerException`
- `testCreateSendsChirpToPersistence`
- `testCreateReturnsInternalErrorResponseOnPersistenceException`
- `testCreateSendsSavedChirpToTransformer`
- `testCreateReturnsInternalServerErrorResponseOnTransformerException`
- `testCreateReturnsChirpCreatedResponseOnSuccess`

### `tests/Unit/Chirp/JsonApiChirpTransformerTest.php`
- toChirp method:
  - needs to validate, and convert
  - Send validate logic to a validator class.
  - `testToChirpSendsJsonToValidator`
  - `testToChirpThrowsInvalidJsonExceptionWhenJsonInvalid`
  - `testToChirpReturnsChirpWithPropertiesFromJson`

#### Validator Adapter
- POC code: `validate.php`
   - create data array
   - set up rules
   - validate
- `tests/Unit/Http/Validation/ValitronValidatorTest.php`
   - `testSetRulesPassesRulesIntoValitron`
   - `testIsValidSendsArrayOfDataToValitron`
   - `testIsValidReturnsValidateValue`
   - `testGetErrorsReturnsEmptyArrayForValidData`
   - `testGetErrorsReturnsFlattenedArrayOfErrorsPer`
   
### `tests/Unit/Chirp/PdoPersistenceDriverTest.php`
- refer to `test.php` for code
- `testSavePreparesStatement`
- `testSaveThrowsExceptionWhenPrepareThrowsException`
- `testSaveExecutesStatement`
- `testSaveThrowsExceptionWhenExecuteReturnsFalse`
- `testSaveReturnsTrueWhenChirpInserted`

## Integration Test: `tests/Integration/CreateChirpTest.php`
- Integration test is just to check that all of the parts are actually wired up correctly
- `testValidPostReturnsSuccessfulResponse` 
   - mock data, use Guzzle to post it, assert 201 & response object
- Create `post` route in `public/router.php`

## Run Behavior Tests (API suite)

## Back into Unit Tests for Get Timeline

### `tests/Integration/GetTimelineTest.php`
- preload DB with 3-5 chirps
- Use dates - today, two weeks ago, yesterday, one month ago
- Hit the POST API endpoint with the new data
- Get the GET endpoint
   - rsort the dates 
   ```
   $createdAtDates = array_column(array_column($data, 'attributes'), 'created_at');
   $expected       = $createdAtDates;
   rsort($expected);
   ```
- add get route to router

### `tests/Unit/Chirp/GetTimelineActionTest.php`
- `testGetAllGetsChirpsFromPersistenceDriver`
- `testGetAllReturnsInternalErrorResponseOnPersistenceException`
- `testGetAllTransformsChirpsToJson`
- `testGetAllReturnsInternalErrorResponseOnTransformerException`
- `testGetAllReturnsTimelineResponseWithChirps`

### `tests/Unit/Chirp/PdoPersistenceDriverTest.php`
- `testGetAllExecutesQuery`
- `testGetAllThrowsExceptionWhenQueryReturnsFalse`
- `testGetAllReturnsChirpCollection`

### `tests/Unit/Chirp/JsonApiChirpTransformerTest.php`
- `testToJsonReturnsJsonString`
- `testCollectionToJsonReturnsJsonWithAllChirps`

## Run all unit Tests
## Run Integration Tests
## Run Behavior Tests 

## Fill in Behavior Tests for Validation Scenarios
