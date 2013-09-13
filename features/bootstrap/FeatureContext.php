<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

use Behat\CommonContexts\SymfonyDoctrineContext;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Features context.
 */
class FeatureContext extends BehatContext
{
    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        // Initialize your context here
        $this->useContext('symfony_doctrine_context',  new SymfonyDoctrineContext);
    }

    

    /**
     * @Given /^I see the login form$/
     */
    public function iSeeTheLoginForm()
    {
        throw new PendingException();
    }

    /**
     * @When /^I fill the form$/
     */
    public function iFillTheForm()
    {
        throw new PendingException();
    }

    /**
     * @Then /^I log in the private area$/
     */
    public function iLogInThePrivateArea()
    {
        throw new PendingException();
    }

}
