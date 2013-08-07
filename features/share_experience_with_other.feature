@teacher
Feature: Share experience with others
    In order to share my experiences
    As a teacher
    I will need to be able to share my experiences with the others

    Background:
        Given I'm on the Gathering Place
         When I click on Mis experiencies
         Then I should be on Mis experiencias page

    Scenario: I'm logged in and I successfully write an experience
        Given I click on Escribir experiencia
         Then I should see the form to write experiences
         When I write an experience
          And I share it with the researcher
         Then I should see "Has compartido tu experiencia con todas"
