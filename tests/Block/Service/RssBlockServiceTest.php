<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\BlockBundle\Tests\Block\Service;

use Sonata\BlockBundle\Block\BlockContext;
use Sonata\BlockBundle\Block\Service\RssBlockService;
use Sonata\BlockBundle\Model\Block;
use Sonata\BlockBundle\Test\AbstractBlockServiceTestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RssBlockServiceTest extends AbstractBlockServiceTestCase
{
    /*
     * only test if the API is not broken
     */
    public function testService(): void
    {
        $service = new RssBlockService('sonata.page.block.rss', $this->twig);

        $block = new Block();
        $block->setType('core.text');
        $block->setSettings([
            'content' => 'my text',
        ]);

        $optionResolver = new OptionsResolver();
        $service->configureSettings($optionResolver);

        $blockContext = new BlockContext($block, $optionResolver->resolve());

        $formMapper = $this->getMockBuilder('Sonata\\AdminBundle\\Form\\FormMapper')
            ->disableOriginalConstructor()
            ->getMock();
        $formMapper->expects($this->exactly(2))->method('add');

        $service->buildCreateForm($formMapper, $block);
        $service->buildEditForm($formMapper, $block);

        $service->execute($blockContext);
    }
}
