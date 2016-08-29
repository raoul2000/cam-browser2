<?php

namespace tests\codeception\unit\src\components;

use Yii;
use Codeception\Specify;
use app\components\VFSHelper;

class VFSHelperTest extends \Codeception\TestCase\Test
{
   use Specify;
   public function testDirname()
   {
     expect('returns parent folder', VFSHelper::dirname('/a/b/c'))->equals('/a/b');
     expect('returns parent folder', VFSHelper::dirname('/a/b/c/d/e/file.txt'))->equals('/a/b/c/d/e');
     expect('returns parent folder', VFSHelper::dirname('/a'))->equals('/');
     expect('returns parent folder', VFSHelper::dirname('/'))->equals('/');
     expect('returns parent folder', VFSHelper::dirname('/  '))->equals('/');
     expect('returns parent folder', VFSHelper::dirname('az '))->equals('/');
     expect('returns parent folder', VFSHelper::dirname('//az '))->equals('/');
   }


   public function testNormalizePath()
   {
     expect('returns normalized folder', VFSHelper::normalizePath('/a/b/c'))->equals('/a/b/c');
     expect('returns normalized folder', VFSHelper::normalizePath('/a/b/c/d/e/file.txt'))->equals('/a/b/c/d/e/file.txt');
     expect('returns normalized folder', VFSHelper::normalizePath('/'))->equals('/');
     expect('returns normalized folder', VFSHelper::normalizePath(' /a '))->equals('/a');
     expect('returns normalized folder', VFSHelper::normalizePath(''))->equals('/');
     expect('returns normalized folder', VFSHelper::normalizePath('az '))->equals('/az');
     expect('returns normalized folder', VFSHelper::normalizePath('//az '))->equals('/az');
   }

}
