<?php
/**
 * Created by PhpStorm.
 * User: dx
 * Date: 30.05.18
 * Time: 0:22
 */

namespace CodexSoft\Code\Context;

use CodexSoft\Code\Context\ContextTestData\ChildClass;
use CodexSoft\Code\Context\ContextTestData\GrandparentClass;
use CodexSoft\Code\Context\ContextTestData\ParentClass;
use PHPUnit\Framework\TestCase;

class ContextTest extends TestCase
{

    public function testGetOrNull()
    {

        $child = new ChildClass();
        $parent = new ParentClass();
        $grandparent = new GrandparentClass();

        Context::create([$child],true);

        foreach ([
            DependencyManager::ACCEPT_SAME,
            DependencyManager::ACCEPT_PARENTS,
            DependencyManager::ACCEPT_CHILDREN,
            DependencyManager::ACCEPT_BOTH
        ] as $accepter) {

            $object = Context::getOrNull(ChildClass::class, $accepter);
            $this->assertNotNull($object);

            /** @var ChildClass $object */
            $objectClass = \get_class($object);

            $this->assertInstanceOf(ChildClass::class,$object);
            $this->assertInstanceOf(ParentClass::class,$object);
            $this->assertInstanceOf(GrandparentClass::class,$object);
            $this->assertSame(ChildClass::class,$objectClass);
            $this->assertNotSame(ParentClass::class,$objectClass);
            $this->assertNotSame(GrandparentClass::class,$objectClass);

        }

        $object = Context::getOrNull(ParentClass::class, DependencyManager::ACCEPT_CHILDREN);
        $this->assertNotNull($object);

        /** @var ChildClass $object */
        $objectClass = \get_class($object);

        $this->assertInstanceOf(ChildClass::class,$object);
        $this->assertInstanceOf(ParentClass::class,$object);
        $this->assertInstanceOf(GrandparentClass::class,$object);
        $this->assertSame(ChildClass::class,$objectClass);
        $this->assertNotSame(ParentClass::class,$objectClass);
        $this->assertNotSame(GrandparentClass::class,$objectClass);

        $object = Context::getOrNull(GrandparentClass::class, DependencyManager::ACCEPT_CHILDREN);
        $this->assertNotNull($object);

        /** @var ChildClass $object */
        $objectClass = \get_class($object);

        $this->assertInstanceOf(ChildClass::class,$object);
        $this->assertInstanceOf(ParentClass::class,$object);
        $this->assertInstanceOf(GrandparentClass::class,$object);
        $this->assertSame(ChildClass::class,$objectClass);
        $this->assertNotSame(ParentClass::class,$objectClass);
        $this->assertNotSame(GrandparentClass::class,$objectClass);

        $object = Context::getOrNull(ParentClass::class, DependencyManager::ACCEPT_BOTH);
        $this->assertNotNull($object);

        /** @var ChildClass $object */
        $objectClass = \get_class($object);

        $this->assertInstanceOf(ChildClass::class,$object);
        $this->assertInstanceOf(ParentClass::class,$object);
        $this->assertInstanceOf(GrandparentClass::class,$object);
        $this->assertSame(ChildClass::class,$objectClass);
        $this->assertNotSame(ParentClass::class,$objectClass);
        $this->assertNotSame(GrandparentClass::class,$objectClass);

        $object = Context::getOrNull(GrandparentClass::class, DependencyManager::ACCEPT_BOTH);
        $this->assertNotNull($object);

        /** @var ChildClass $object */
        $objectClass = \get_class($object);

        $this->assertInstanceOf(ChildClass::class,$object);
        $this->assertInstanceOf(ParentClass::class,$object);
        $this->assertInstanceOf(GrandparentClass::class,$object);
        $this->assertSame(ChildClass::class,$objectClass);
        $this->assertNotSame(ParentClass::class,$objectClass);
        $this->assertNotSame(GrandparentClass::class,$objectClass);

        $object = Context::getOrNull(ParentClass::class, DependencyManager::ACCEPT_SAME);
        $this->assertNull($object);

        $object = Context::getOrNull(ParentClass::class, DependencyManager::ACCEPT_PARENTS);
        $this->assertNull($object);

        $object = Context::getOrNull(GrandparentClass::class, DependencyManager::ACCEPT_SAME);
        $this->assertNull($object);

        $object = Context::getOrNull(GrandparentClass::class, DependencyManager::ACCEPT_PARENTS);
        $this->assertNull($object);

        Context::destroy();

        // — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — —

        Context::create([$parent],true);

        $object = Context::getOrNull(ChildClass::class, DependencyManager::ACCEPT_SAME);
        $this->assertNull($object);

        $object = Context::getOrNull(ChildClass::class, DependencyManager::ACCEPT_CHILDREN);
        $this->assertNull($object);

        $object = Context::getOrNull(ChildClass::class, DependencyManager::ACCEPT_PARENTS);

        /** @var ParentClass $object */
        $objectClass = \get_class($object);

        $this->assertInstanceOf(ParentClass::class,$object);
        $this->assertInstanceOf(GrandparentClass::class,$object);
        $this->assertSame(ParentClass::class,$objectClass);
        $this->assertNotSame(ChildClass::class,$objectClass);
        $this->assertNotSame(GrandparentClass::class,$objectClass);

        $object = Context::getOrNull(ChildClass::class, DependencyManager::ACCEPT_BOTH);

        /** @var ParentClass $object */
        $objectClass = \get_class($object);

        $this->assertInstanceOf(ParentClass::class,$object);
        $this->assertInstanceOf(GrandparentClass::class,$object);
        $this->assertSame(ParentClass::class,$objectClass);
        $this->assertNotSame(ChildClass::class,$objectClass);
        $this->assertNotSame(GrandparentClass::class,$objectClass);



        $object = Context::getOrNull(ParentClass::class, DependencyManager::ACCEPT_SAME);

        /** @var ParentClass $object */
        $objectClass = \get_class($object);

        $this->assertInstanceOf(ParentClass::class,$object);
        $this->assertInstanceOf(GrandparentClass::class,$object);
        $this->assertSame(ParentClass::class,$objectClass);
        $this->assertNotSame(ChildClass::class,$objectClass);
        $this->assertNotSame(GrandparentClass::class,$objectClass);

        $object = Context::getOrNull(ParentClass::class, DependencyManager::ACCEPT_CHILDREN);

        /** @var ParentClass $object */
        $objectClass = \get_class($object);

        $this->assertInstanceOf(ParentClass::class,$object);
        $this->assertInstanceOf(GrandparentClass::class,$object);
        $this->assertSame(ParentClass::class,$objectClass);
        $this->assertNotSame(ChildClass::class,$objectClass);
        $this->assertNotSame(GrandparentClass::class,$objectClass);

        $object = Context::getOrNull(ParentClass::class, DependencyManager::ACCEPT_PARENTS);

        /** @var ParentClass $object */
        $objectClass = \get_class($object);

        $this->assertInstanceOf(ParentClass::class,$object);
        $this->assertInstanceOf(GrandparentClass::class,$object);
        $this->assertSame(ParentClass::class,$objectClass);
        $this->assertNotSame(ChildClass::class,$objectClass);
        $this->assertNotSame(GrandparentClass::class,$objectClass);

        $object = Context::getOrNull(ParentClass::class, DependencyManager::ACCEPT_BOTH);

        /** @var ParentClass $object */
        $objectClass = \get_class($object);

        $this->assertInstanceOf(ParentClass::class,$object);
        $this->assertInstanceOf(GrandparentClass::class,$object);
        $this->assertSame(ParentClass::class,$objectClass);
        $this->assertNotSame(ChildClass::class,$objectClass);
        $this->assertNotSame(GrandparentClass::class,$objectClass);



        $object = Context::getOrNull(GrandparentClass::class, DependencyManager::ACCEPT_SAME);
        $this->assertNull($object);

        $object = Context::getOrNull(GrandparentClass::class, DependencyManager::ACCEPT_CHILDREN);

        /** @var ParentClass $object */
        $objectClass = \get_class($object);

        $this->assertInstanceOf(ParentClass::class,$object);
        $this->assertInstanceOf(GrandparentClass::class,$object);
        $this->assertSame(ParentClass::class,$objectClass);
        $this->assertNotSame(ChildClass::class,$objectClass);
        $this->assertNotSame(GrandparentClass::class,$objectClass);

        $object = Context::getOrNull(GrandparentClass::class, DependencyManager::ACCEPT_PARENTS);
        $this->assertNull($object);

        $object = Context::getOrNull(GrandparentClass::class, DependencyManager::ACCEPT_BOTH);

        /** @var ParentClass $object */
        $objectClass = \get_class($object);

        $this->assertInstanceOf(ParentClass::class,$object);
        $this->assertInstanceOf(GrandparentClass::class,$object);
        $this->assertSame(ParentClass::class,$objectClass);
        $this->assertNotSame(ChildClass::class,$objectClass);
        $this->assertNotSame(GrandparentClass::class,$objectClass);

        Context::destroy();

    }

}
