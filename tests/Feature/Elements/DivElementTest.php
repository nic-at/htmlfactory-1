<?php
namespace HtmlFactoryTests\Feature\Elements;

use HtmlFactoryTests\TestCase;

class DivElementTest extends TestCase
{

    public function testSimpleDivElement()
    {
        $html = \Html::div()
            ->generate();

        $this->assertHtmlEquals(
            '<div></div>',
            $html
        );
    }

    public function testComplexDivElement()
    {
        $html = \Html::div();
        $this->applyGeneralAttributes($html);
        $html->generate();

        $this->assertHtmlEquals(
            '<div aria-describedby="describedById" class="myFirstClass mySecondClass" data-my-first-data-attribute="myFirstDataAttributeValue" data-my-second-data-attribute="mySecondDataAttributeValue" hidden id="myId" role="myFirstRole mySecondRole" style="display:block;color:black" title="My Title"></div>',
            $html
        );
    }

    public function testComplexDivElementUsingView()
    {
        $html = \Html::div();
        $this->applyGeneralAttributes($html);
        $this->applyCorrespondingView($html);
        $result = $html->generate();

        $this->assertHtmlEquals(
            'text before element <div aria-describedby="describedById" class="myFirstClass mySecondClass" data-my-first-data-attribute="myFirstDataAttributeValue" data-my-second-data-attribute="mySecondDataAttributeValue" hidden id="myId" role="myFirstRole mySecondRole" style="display:block;color:black" title="My Title"></div> text after element',
            $result
        );
    }

}