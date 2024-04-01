<?php

use Magrathea2\Tests\TestsHelper;
use MagratheaCloud\Helper;

include(__DIR__."/../shared/Helper.php");

class cloudHelperTest extends \PHPUnit\Framework\TestCase {
	protected function setUp(): void {
		parent::setUp();
	}
	protected function tearDown(): void {
		parent::tearDown();
	}

	function testGetSize() {
		$this->assertEquals("0.49 KB", Helper::GetSize(500));
		$this->assertEquals("2.5 KB", Helper::GetSize((2.5 * 1024)));
		$this->assertEquals("38.8 MB", Helper::GetSize((38.8 * 1024 * 1024)));
		$this->assertEquals("450 GB", Helper::GetSize((450 * 1024 * 1024 * 1024)));
	}

	function testStringCleaner() {
		$this->assertEquals("meu_coracao_nao_sei_porque", Helper::cleanString("meu coração, não sei porquê"));
		$this->assertEquals("https__wwwsaladacapresecombr_", Helper::cleanString("https://www.saladacaprese.com.br/"));
		$this->assertEquals("C_Windows_System32_rundll", Helper::cleanString("C:/Windows/System32/run.dll"));
		$this->assertEquals("32_de_desconto_no_agriao", Helper::cleanString("32% de desconto no agrião"));
	}

}
