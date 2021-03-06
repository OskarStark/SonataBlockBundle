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

namespace Sonata\BlockBundle\Tests\Cache;

use PHPUnit\Framework\TestCase;
use Sonata\BlockBundle\Cache\HttpCacheHandler;
use Symfony\Component\HttpFoundation\Response;

class HttpCacheHandlerTest extends TestCase
{
    public function testComputeTtlWithPrivateResponse(): void
    {
        $handler = new HttpCacheHandler();
        $handler->updateMetadata(Response::create()->setTtl(60));
        $handler->updateMetadata(Response::create()->setTtl(55));
        $handler->updateMetadata(Response::create()->setTtl(42));
        $handler->updateMetadata(Response::create()->setTtl(55));

        $handler->alterResponse($response = Response::create());

        $this->assertEquals(0, $response->getTtl());
    }

    public function testComputeTtlWithPublicResponse(): void
    {
        $handler = new HttpCacheHandler();
        $handler->updateMetadata(Response::create()->setTtl(60));
        $handler->updateMetadata(Response::create()->setTtl(55));
        $handler->updateMetadata(Response::create()->setTtl(42));
        $handler->updateMetadata(Response::create()->setTtl(55));

        $handler->alterResponse($response = Response::create()->setTtl(84));

        $this->assertEquals(42, $response->getTtl());
    }

    public function testResponseTtlNotAlteredIfNoRenderedBlock(): void
    {
        $handler = new HttpCacheHandler();

        $handler->alterResponse($response = Response::create()->setTtl(84));

        $this->assertEquals(84, $response->getTtl());
    }
}
