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
     expect('do not modify folder path', VFSHelper::normalizePath('/a/b/c'))->equals('/a/b/c');
     expect('do not modify folder path', VFSHelper::normalizePath(' /a '))->equals('/a');
     expect('do not modify file path', VFSHelper::normalizePath('/a/b/c/d/e/file.txt'))->equals('/a/b/c/d/e/file.txt');
     expect('resolve .. folders', VFSHelper::normalizePath('/a/b/../d/../file.txt'))->equals('/a/file.txt');
     expect('fails to leave root folder', VFSHelper::normalizePath('/a/../../file.txt'))->equals('/file.txt');
     expect('root folder is not modified', VFSHelper::normalizePath('/'))->equals('/');
     expect('empty string path is turned into root path', VFSHelper::normalizePath(''))->equals('/');
     expect('folder name is trimmed', VFSHelper::normalizePath(' az '))->equals('/az');
     expect('double slah are removed', VFSHelper::normalizePath('//az '))->equals('/az');
     expect('double slah are removed', VFSHelper::normalizePath('/a/b//c/file.txt '))->equals('/a/b/c/file.txt');
   }

}
