Feature: Teacher login
    In order to access to the private area
    As a teacher or researcher
    I need to be able to Login through a login form

    Background:
    	Given these users:
    	    | username | password | email           | roles                        |
    	    | ross     | ross     | ross@test.local | ROLE_TEACHER,ROLE_RESEARCHER |
    	    | patricia | pati     | pati@test.local | ROLE_TEACHER                 |

    @teacher
    Scenario: Successful login
    	Given I am on the homepage
          And I see the login form
    	 When I fill the form with user "patricia" and "pati"
    	 Then I log in the private area
          But I should not see the option Preguntar experiencias

    @researcher
    Scenario: Successful login
        Given I am on the homepage
          And I see the login form
         When I fill the form with user "ross" and "ross"
         Then I log in the private area
          And I should see the option Preguntar experiencias