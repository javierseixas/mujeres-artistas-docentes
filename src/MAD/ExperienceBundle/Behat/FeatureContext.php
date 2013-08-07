<?php

namespace MAD\ExperienceBundle\Behat;

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Behat\Context\Step\Given;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

use Behat\MinkExtension\Context\MinkContext;
use Symfony\Component\HttpKernel\KernelInterface;
use Behat\CommonContexts\SymfonyDoctrineContext;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use MAD\UserBundle\Entity\User;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Features context.
 */
class FeatureContext extends MinkContext implements KernelAwareInterface
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
     * Sets HttpKernel instance.
     * This method will be automatically called by Symfony2Extension ContextInitializer.
     *
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * Returns the Doctrine entity manager.
     *
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEntityManager()
    {
        return $this->kernel->getContainer()->get('doctrine')->getManager();
    }

    /**
     * @Transform /^table:username,password,email$/
     */
    public function castUsersTable(TableNode $usersTable)
    {
        $users = array();
        foreach ($usersTable->getHash() as $userHash) {
            $user = new User();
            $user->setUsername($userHash['username']);
            $user->setPassword($userHash['password']);
            $user->setEmail($userHash['email']);
            $users[] = $user;
        }

        return $users;
    }

    /**
     * @Given /^these users:$/
     */
    public function theseUsers(array $users)
    {
        foreach ($users as $user ) {
            $this->getEntityManager()->persist($user);
        }
        $this->getEntityManager()->flush();
    }


    /**
     * @Given /^I see the login form$/
     */
    public function iSeeTheLoginForm()
    {
        return array(
            new Given('I should see an "username" element'),
            new Given('I should see an "password" element'),
        );
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
