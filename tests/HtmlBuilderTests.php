<?php

class HtmlBuilderTest extends TestCase 
{
    public function setUp()
    {
        parent::setUp();

        $this->builder  = app('Keevitaja\Linguist\HtmlBuilder');
        $this->config = app('Illuminate\Config\Repository');
        $this->request = app('Illuminate\Http\Request');

        $this->config->set('app.locale', 'en');
        $this->config->set('linguist.default', 'en');
    }

    public function test_link_to_hide_default_false()
    {
        $this->config->set('linguist.hide_default', false);

        $attributes = ['class' => 'some-class'];
        $linkFromBuilder = $this->builder->linkTo('some/uri', 'Some Title', $attributes);
        $linkToTestAgainst = '<a href="'.$this->request->root().'/en/some/uri" class="some-class">Some Title</a>';

        $this->assertEquals($linkFromBuilder, $linkToTestAgainst);
    }

    public function test_link_to_hide_default_true()
    {
        $this->config->set('linguist.hide_default', true);

        $attributes = ['class' => 'some-class'];
        $linkFromBuilder = $this->builder->linkTo('some/uri', 'Some Title', $attributes);
        $linkToTestAgainst = '<a href="'.$this->request->root().'/some/uri" class="some-class">Some Title</a>';

        $this->assertEquals($linkFromBuilder, $linkToTestAgainst);
    }

    public function test_link_to_route_hide_default_false()
    {
        $this->config->set('linguist.hide_default', false);

        app('router')->get('test/this/route', ['as' => 'named.route', function() {}]);
        
        $attributes = ['class' => 'some-class'];
        $linkFromBuilder = $this->builder->linkToRoute('named.route', 'Some Title', [], $attributes);
        $linkToTestAgainst = '<a href="'.$this->request->root().'/en/test/this/route" class="some-class">Some Title</a>';

        $this->assertEquals($linkFromBuilder, $linkToTestAgainst);
    }

    public function test_link_to_route_hide_default_true()
    {
        $this->config->set('linguist.hide_default', true);

        app('router')->get('test/this/route', ['as' => 'named.route', function() {}]);
        
        $attributes = ['class' => 'some-class'];
        $linkFromBuilder = $this->builder->linkToRoute('named.route', 'Some Title', [], $attributes);
        $linkToTestAgainst = '<a href="'.$this->request->root().'/test/this/route" class="some-class">Some Title</a>';

        $this->assertEquals($linkFromBuilder, $linkToTestAgainst);
    }
}