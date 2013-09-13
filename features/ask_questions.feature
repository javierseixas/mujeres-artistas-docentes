@researcher
Feature: Ask questions
    In order to mark a guidelines to the teachers
    As an investigator
    I need to ask them questions that they will receive and will answer

    Background:
        Given I'm logged in as a researcher
          And I should see the option Preguntar experiencias


    Scenario: I create a new experience successfully
        Given I go to Preguntar experiencias
          And I should see a form to create questions
         When I write the question
          And I send the form
         Then I should see a success message
          And an email should be send to all the teachers
