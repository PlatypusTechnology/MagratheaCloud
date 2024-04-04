<?php

use Magrathea2\Tests\TestsHelper;
use MagratheaCloud\CloudHelper;

include(__DIR__."/../shared/CloudHelper.php");

class cloudHelperTest extends \PHPUnit\Framework\TestCase {
	protected function setUp(): void {
		parent::setUp();
	}
	protected function tearDown(): void {
		parent::tearDown();
	}

	function testGetSize() {
		$this->assertEquals("0.49 KB", CloudHelper::GetSize(500));
		$this->assertEquals("2.5 KB", CloudHelper::GetSize((2.5 * 1024)));
		$this->assertEquals("38.8 MB", CloudHelper::GetSize((38.8 * 1024 * 1024)));
		$this->assertEquals("450 GB", CloudHelper::GetSize((450 * 1024 * 1024 * 1024)));
	}

	function testStringCleaner() {
		$this->assertEquals("meu_coracao_nao_sei_porque", CloudHelper::cleanString("meu coração, não sei porquê"));
		$this->assertEquals("https__wwwsaladacapresecombr_", CloudHelper::cleanString("https://www.saladacaprese.com.br/"));
		$this->assertEquals("C_Windows_System32_rundll", CloudHelper::cleanString("C:/Windows/System32/run.dll"));
		$this->assertEquals("32_de_desconto_no_agriao", CloudHelper::cleanString("32% de desconto no agrião"));
	}

	function testTypeByExtension() {
		$this->assertEquals("image", CloudHelper::GetFileType("png"));
		$this->assertEquals("code", CloudHelper::GetFileType("md"));
		$this->assertEquals("some-ext file", CloudHelper::GetFileType("some-ext"));
	}

}
