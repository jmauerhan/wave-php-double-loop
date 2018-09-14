Feature: User can create Chirps
  As a User of Chirper
  In order to share my short messages with the world
  I need to be able to create Chirps

  Scenario: User Posts Chirp
    Given I write a Chirp with 100 or less characters
    When I submit the Chirp
    Then I should see it in my timeline

  Scenario: User Submits Invalid Chirp
    Given I write a Chirp with more than 100 characters
    When I submit the Chirp
    Then I should not see it in my timeline
    And I should see an error message