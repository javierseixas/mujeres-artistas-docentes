@teacher
Feature: Write experience
    In order to write my experiences
    As a teacher
    I need to access a list from where I'll can write them and share with the researcher

    Background:
        Given I am logged in as a teacher
          And I should be on Mi Espacio
         When I click on Mis Experiencias
         Then I should be on Mis Experiencias

    Scenario: I'm logged in and I successfully write an experience
        Given I click on Escribir experiencia
         Then I should see the form to write experiences
         When I write an experience
          And I share it with the researcher
         Then I should see "Has compartido tu experiencia con Rosario"

    Scenario: I'm logged in and I successfully write an experience answering a question from the researcher
        Given I click on Escribir experiencia
          And I should see a question from the researcher
          And I should see the form to write experiences
         When I write an experience
          And I share it with the researcher
         Then I should see "Has compartido tu experiencia con Rosario"
