Feature: Teacher login
    In order to access to the private area
    As a teacher
    I need to be able to Login through a login form

    Background:
    	Given these users:
    	    | username | password | email           |
    	    | ross     | ross     | ross@test.local |
    	    | patricia | pati     | pati@test.local |

    Scenario: Successful login
    	Given I see the login form
    	 When I fill the form
    	 Then I log in the private area
