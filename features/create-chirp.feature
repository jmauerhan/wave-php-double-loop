Feature: User can create Chirps
  As a User of Chirper
  In order to share my short messages with the world
  I need to be able to create Chirps

  @javascript
  Scenario: User Posts Chirp
    Given I write a Chirp with 100 or less characters
    When I publish the Chirp
    Then I should see it in my timeline