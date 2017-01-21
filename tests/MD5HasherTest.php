<?php

/**
 * Created by hare.
 * Email: 688977@qq.com
 * Date: 2017/1/21
 * Time: PM9:01
 */
use PHPUnit\Framework\TestCase;

class MD5HasherTest extends TestCase
{
    protected $hasher;
    public function setUp()
    {
        $this->hasher = new \TestHelper\Hasher\MD5Hasher();
    }
    public function testMD5HasherMake()
    {
        $password = md5('password');
        $passwordTwo = $this->hasher->make('password');
        $this->assertEquals($password, $passwordTwo);
    }
}