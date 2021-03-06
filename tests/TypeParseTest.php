<?php
namespace Psalm\Tests;

use Psalm\Type;

class TypeParseTest extends TestCase
{
    /**
     * @return void
     */
    public function setUp()
    {
        //parent::setUp();
    }

    /**
     * @return void
     */
    public function testIntOrString()
    {
        $this->assertSame('int|string', (string) Type::parseString('int|string'));
    }

    /**
     * @return void
     */
    public function testBoolOrIntOrString()
    {
        $this->assertSame('bool|int|string', (string) Type::parseString('bool|int|string'));
    }

    /**
     * @return void
     */
    public function testNullable()
    {
        $this->assertSame('null|string', (string) Type::parseString('?string'));
    }

    /**
     * @return void
     */
    public function testArray()
    {
        $this->assertSame('array<int, int>', (string) Type::parseString('array<int, int>'));
        $this->assertSame('array<int, string>', (string) Type::parseString('array<int, string>'));
        $this->assertSame('array<int, static>', (string) Type::parseString('array<int, static>'));
    }

    /**
     * @return void
     */
    public function testArrayWithSingleArg()
    {
        $this->assertSame('array<mixed, int>', (string) Type::parseString('array<int>'));
    }

    /**
     * @return void
     */
    public function testArrayWithNestedSingleArg()
    {
        $this->assertSame('array<mixed, array<mixed, int>>', (string) Type::parseString('array<array<int>>'));
    }

    /**
     * @return void
     */
    public function testArrayWithUnion()
    {
        $this->assertSame('array<int|string, string>', (string) Type::parseString('array<int|string, string>'));
    }

    /**
     * @return void
     */
    public function testGeneric()
    {
        $this->assertSame('B<int>', (string) Type::parseString('B<int>'));
    }

    /**
     * @return void
     */
    public function testIntersection()
    {
        $this->assertSame('I1&I2', (string) Type::parseString('I1&I2'));
    }

    /**
     * @return void
     */
    public function testPhpDocSimpleArray()
    {
        $this->assertSame('array<mixed, A>', (string) Type::parseString('A[]'));
    }

    /**
     * @return void
     */
    public function testPhpDocUnionArray()
    {
        $this->assertSame('array<mixed, A|B>', (string) Type::parseString('(A|B)[]'));
    }

    /**
     * @return void
     */
    public function testPhpDocMultiDimensionalArray()
    {
        $this->assertSame('array<mixed, array<mixed, A>>', (string) Type::parseString('A[][]'));
    }

    /**
     * @return void
     */
    public function testPhpDocMultidimensionalUnionArray()
    {
        $this->assertSame('array<mixed, array<mixed, A|B>>', (string) Type::parseString('(A|B)[][]'));
    }

    /**
     * @return void
     */
    public function testPhpDocUnionOfArrays()
    {
        $this->assertSame('array<mixed, A|B>', (string) Type::parseString('A[]|B[]'));
    }

    /**
     * @return void
     */
    public function testPhpDocUnionOfArraysOrObject()
    {
        $this->assertSame('array<mixed, A|B>|C', (string) Type::parseString('A[]|B[]|C'));
    }

    /**
     * @return void
     */
    public function testPsalmOnlyAtomic()
    {
        $this->assertSame('class-string', (string) Type::parseString('class-string'));
    }

    /**
     * @expectedException \Psalm\Exception\TypeParseTreeException
     *
     * @return void
     */
    public function testInvalidType()
    {
        Type::parseString('array(A)');
    }

    /**
     * @return void
     */
    public function testObjectLikeWithSimpleArgs()
    {
        $this->assertSame('array{a:int, b:string}', (string) Type::parseString('array{a:int, b:string}'));
    }

    /**
     * @return void
     */
    public function testObjectLikeWithUnionArgs()
    {
        $this->assertSame(
            'array{a:int|string, b:string}',
            (string) Type::parseString('array{a:int|string, b:string}')
        );
    }

    /**
     * @return void
     */
    public function testObjectLikeWithGenericArgs()
    {
        $this->assertSame(
            'array{a:array<int, string|int>, b:string}',
            (string) Type::parseString('array{a:array<int, string|int>, b:string}')
        );
    }

    /**
     * @return void
     */
    public function testObjectLikeWithIntKeysAndUnionArgs()
    {
        $this->assertSame(
            'array{0:stdClass|null}',
            (string)Type::parseString('array{stdClass|null}')
        );
    }

    /**
     * @return void
     */
    public function testObjectLikeWithIntKeysAndGenericArgs()
    {
        $this->assertSame(
            'array{0:array<mixed, mixed>}',
            (string)Type::parseString('array{array}')
        );

        $this->assertSame(
            'array{0:array<int, string>}',
            (string)Type::parseString('array{array<int, string>}')
        );
    }

    /**
     * @return void
     */
    public function testObjectLikeOptional()
    {
        $this->assertSame(
            'array{a:int, b?:int}',
            (string)Type::parseString('array{a:int, b?:int}')
        );
    }
}
