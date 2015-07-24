<?php

class HtmlBuilderTest extends Orchestra\Testbench\TestCase 
{
    public function setUp()
    {
        parent::setUp();

        $this->config = app('Illuminate\Config\Repository');
        $this->url = app('Illuminate\Routing\UrlGenerator');

        $this->config->set('app.locale', 'en');
        $this->config->set('linguist.default', 'en');
        $this->config->set('linguist.locales', ['en', 'fr', 'et']);

        $this->url->forceRootUrl('http://keevitaja.com');
    }

    public function test_link_to_hide_default_false()
    {
        $this->config->set('linguist.hide_default', false);

        $attributes = ['class' => 'some-class'];
        $linkFromBuilder = lnk_to('some/uri', 'Some Title', $attributes);
        $linkToTestAgainst = '<a href="http://keevitaja.com/en/some/uri" class="some-class">Some Title</a>';

        $this->assertEquals($linkFromBuilder, $linkToTestAgainst);
    }

    public function test_link_to_hide_default_true()
    {
        $this->config->set('linguist.hide_default', true);

        $attributes = ['class' => 'some-class'];
        $linkFromBuilder = lnk_to('some/uri', 'Some Title', $attributes);
        $linkToTestAgainst = '<a href="http://keevitaja.com/some/uri" class="some-class">Some Title</a>';

        $this->assertEquals($linkFromBuilder, $linkToTestAgainst);
    }

    public function test_link_to_route_hide_default_false()
    {
        $this->config->set('linguist.hide_default', false);

        app('router')->get('test/this/route', ['as' => 'named.route', function() {}]);
        
        $attributes = ['class' => 'some-class'];
        $linkFromBuilder = lnk_to_route('named.route', 'Some Title', [], $attributes);
        $linkToTestAgainst = '<a href="http://keevitaja.com/en/test/this/route" class="some-class">Some Title</a>';

        $this->assertEquals($linkFromBuilder, $linkToTestAgainst);
    }

    public function test_link_to_route_hide_default_true()
    {
        $this->config->set('linguist.hide_default', true);

        app('router')->get('test/this/route', ['as' => 'named.route', function() {}]);
        
        $attributes = ['class' => 'some-class'];
        $linkFromBuilder = lnk_to_route('named.route', 'Some Title', [], $attributes);
        $linkToTestAgainst = '<a href="http://keevitaja.com/test/this/route" class="some-class">Some Title</a>';

        $this->assertEquals($linkFromBuilder, $linkToTestAgainst);
    }

    public function test_link_to_route_secure()
    {
        $this->config->set('linguist.hide_default', false);
        $this->url->forceRootUrl('https://keevitaja.com');

        app('router')->get('test/show/{test}', ['as' => 'test.show', function() {}]);
        
        $attributes = ['class' => 'test'];
        $linkFromBuilder = lnk_to_route('test.show', 'nimetus', [3], $attributes, [], true);
        $linkToTestAgainst = '<a href="https://keevitaja.com/en/test/show/3" class="test">nimetus</a>';

        $this->assertEquals($linkFromBuilder, $linkToTestAgainst);
    }
}