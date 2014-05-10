<?php

namespace Behat\Mink\Tests\Driver\Basic;

use Behat\Mink\Tests\Driver\TestCase;

class NavigationTest extends TestCase
{
    public function testRedirect()
    {
        $this->getSession()->visit($this->pathTo('/redirector.php'));
        $this->assertEquals($this->pathTo('/redirect_destination.php'), $this->getSession()->getCurrentUrl());
    }

    public function testPageControlls()
    {
        $this->getSession()->visit($this->pathTo('/randomizer.php'));
        $number1 = $this->getSession()->getPage()->find('css', '#number')->getText();

        $this->getSession()->reload();
        $number2 = $this->getSession()->getPage()->find('css', '#number')->getText();

        $this->assertNotEquals($number1, $number2);

        $this->getSession()->visit($this->pathTo('/links.php'));
        $this->getSession()->getPage()->clickLink('Random number page');

        $this->assertEquals($this->pathTo('/randomizer.php'), $this->getSession()->getCurrentUrl());

        $this->getSession()->back();
        $this->assertEquals($this->pathTo('/links.php'), $this->getSession()->getCurrentUrl());

        $this->getSession()->forward();
        $this->assertEquals($this->pathTo('/randomizer.php'), $this->getSession()->getCurrentUrl());
    }

    public function testLinks()
    {
        $this->getSession()->visit($this->pathTo('/links.php'));
        $page = $this->getSession()->getPage();
        $link = $page->findLink('Redirect me to');

        $this->assertNotNull($link);
        $this->assertRegExp('/redirector\.php$/', $link->getAttribute('href'));
        $link->click();

        $this->assertEquals($this->pathTo('/redirect_destination.php'), $this->getSession()->getCurrentUrl());

        $this->getSession()->visit($this->pathTo('/links.php'));
        $page = $this->getSession()->getPage();
        $link = $page->findLink('basic form image');

        $this->assertNotNull($link);
        $this->assertRegExp('/basic_form\.php$/', $link->getAttribute('href'));
        $link->click();

        $this->assertEquals($this->pathTo('/basic_form.php'), $this->getSession()->getCurrentUrl());

        $this->getSession()->visit($this->pathTo('/links.php'));
        $page = $this->getSession()->getPage();
        $link = $page->findLink("Link with a ");

        $this->assertNotNull($link);
        $this->assertRegExp('/links\.php\?quoted$/', $link->getAttribute('href'));
        $link->click();

        $this->assertEquals($this->pathTo('/links.php?quoted'), $this->getSession()->getCurrentUrl());
    }
}
