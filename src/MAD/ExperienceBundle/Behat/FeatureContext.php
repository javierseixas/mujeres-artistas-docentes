<?php

namespace MAD\ExperienceBundle\Behat;

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Exception\PendingException;
use Behat\Behat\Context\Step\Given;
use Behat\Behat\Context\Step\Then;
use Behat\Behat\Context\Step\When;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

use Behat\MinkExtension\Context\MinkContext;
use Symfony\Component\HttpKernel\KernelInterface;
use Behat\CommonContexts\SymfonyDoctrineContext;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use MAD\UserBundle\Entity\User;
use MAD\ExperienceBundle\Behat\DataContext;

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
     * Actions.
     *
     * @var array
     */
    private $actions = array(
        'viewing'  => 'show',
        'creation' => 'create',
        'editing'  => 'update',
        'building' => 'build',
    );

    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;

        $this->useContext('symfony_doctrine_context',  new SymfonyDoctrineContext);
        $this->useContext('data', new DataContext());
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
     * @Given /^I am logged in as a researcher$/
     */
    public function iAmLoggedInAsResearcher()
    {
        $this->iAmLoggedInAsRole('ROLE_RESEARCHER');
    }

    /**
     * @Given /^I am logged in as a teacher$/
     */
    public function iAmLoggedInAsTeacher()
    {
        $this->iAmLoggedInAsRole('ROLE_TEACHER');
    }

    /**
     * Create user and login with given role.
     *
     * @param string $role
     */
    private function iAmLoggedInAsRole($role)
    {
        $this->getSubContext('data')->thereIsUser('javi', 'email@foo.com', 'password', $role);
        $this->getSession()->visit($this->generatePageUrl('fos_user_security_login'));

        $this->fillField('username', 'javi');
        $this->fillField('password', 'password');
        $this->pressButton('_submit');
    }

    /**
     * @Then /^I should be logged in$/
     */
    public function iShouldBeLoggedIn()
    {
        if (!$this->getSecurityContext()->isGranted('ROLE_USER')) {
            throw new AuthenticationException('User is not authenticated.');
        }
    }

    /**
     * @Then /^I should not be logged in$/
     */
    public function iShouldNotBeLoggedIn()
    {
        if ($this->getSecurityContext()->isGranted('ROLE_USER')) {
            throw new AuthenticationException('User was not expected to be logged in, but he is.');
        }
    }


    /**
     * @Given /^I see the login form$/
     */
    public function iSeeTheLoginForm()
    {
        return array(
            new Given('I should see an "#username" element'),
            new Given('I should see an "#password" element'),
        );
    }

    /**
     * @When /^I fill the form with user "([^"]*)" and "([^"]*)"$/
     */
    public function iFillTheFormWithUserAnd($username, $password)
    {
        return array(
            new When('I fill in "username" with "'.$username.'"'),
            new When('I fill in "_password" with "'.$password.'"'),
            new When('I press "_submit"'),
        );
    }

    /**
     * @Then /^I log in the private area$/
     */
    public function iLogInThePrivateArea()
    {
        return new Then('I should see text matching "Bienvenida patricia"');
    }

    /**
     * @Then /^I should not see the option Preguntar experiencias$/
     */
    public function iShouldNotSeeTheOptionPreguntarExperiencias()
    {
        return new Then('I should not see a "#askExperiencesMenuOpt" element');
    }

    /**
     * @Then /^I should see the option Preguntar experiencias$/
     */
    public function iShouldSeeTheOptionPreguntarExperiencias()
    {
        return new Then('I should see a "#askExperiencesMenuOpt" element');
    }

    /**
     * @Given /^I should be on Mi Espacio$/
     */
    public function iShouldBeOnMiEspacio()
    {
        return new Given('I should be on "/mi-espacio"');
    }

    /**
     * @When /^I click on Mis Experiencias$/
     */
    public function iClickOnMisExperiencias()
    {
        return new When('I follow "myExperiencesMenuOpt"');
    }

    /**
     * @Then /^I should be on Mis Experiencias$/
     */
    public function iShouldBeOnMisExperiencias()
    {
        return new Given('I should be on "/mis-experiencias"');
    }




    /**
     * Generate page url.
     * This method uses simple convention where page argument is prefixed
     * with "sylius_" and used as route name passed to router generate method.
     *
     * @param string $page
     * @param array  $parameters
     *
     * @return string
     */
    private function generatePageUrl($route, array $parameters = array())
    {
        $path = $this->generateUrl($route, $parameters);

        if ('SahiDriver' === strstr(get_class($this->getSession()->getDriver()), 'SahiDriver')) {
            return sprintf('%s%s', $this->getMinkParameter('base_url'), $path);
        }

        return $path;
    }

    /**
     * Generate url.
     *
     * @param string  $route
     * @param array   $parameters
     * @param Boolean $absolute
     *
     * @return string
     */
    private function generateUrl($route, array $parameters = array(), $absolute = false)
    {
        return $this->getService('router')->generate($route, $parameters, $absolute);
    }

    /**
     * Get service by id.
     *
     * @param string $id
     *
     * @return object
     */
    private function getService($id)
    {
        return $this->getContainer()->get($id);
    }

    /**
     * Returns Container instance.
     *
     * @return ContainerInterface
     */
    private function getContainer()
    {
        return $this->kernel->getContainer();
    }


}
