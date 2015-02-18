<?php 

namespace WllFrame\helper;

use PHPUnit_Framework_TestCase;
use WllFrame\helper\helperFormat;

class helper_formatTest extends PHPUnit_Framework_TestCase{

	 public function testRetornaDataFormatoAmericano(){
	 	$dataFormatoBr = '12/10/2014';
	 	$dataFormatoEng = helperFormat::dataBrToEng($dataFormatoBr);

	 	$expected = '2014-10-12';
	 	$this->assertEquals($expected, $dataFormatoEng);
	 }

	 /**
     * @expectedException InvalidArgumentException
     */
	public function testRetornaErroDataFormatoAmericano(){
		$dataFormatoBr = '';
	 	$dataFormatoEng = helperFormat::dataBrToEng($dataFormatoBr);
	}

	 public function testRetornaDataFormatoBrasileiro(){
	 	$dataFormatoEng = '2014-10-12';
	 	$dataFormatoBr = helperFormat::dataEngToBr($dataFormatoEng);

	 	$expected = '12/10/2014';
	 	$this->assertEquals($expected, $dataFormatoBr);
	 }

	  /**
     * @expectedException InvalidArgumentException
     */
	public function testRetornaErroDataFormatoBrasileiro(){
		$dataFormatoEng = '';
	 	$dataFormatoEng = helperFormat::dataEngToBr($dataFormatoEng);
	}

	 public function testRetornaTextoComAcentoEmMaiusculo(){
	 	$texto = 'João dos Santos';
	 	$newTexto = helperFormat::acentoLowToUpper($texto);

	 	$expected = 'JoÃo dos Santos';
	 	$this->assertEquals($expected, $newTexto);
	 }

	 public function testRetornaTextoComAcentoEmMinusculo(){
	 	$texto = 'JoÃo dos Santos';
	 	$newTexto = helperFormat::acentoUpperToLow($texto);

	 	$expected = 'João dos Santos';
	 	$this->assertEquals($expected, $newTexto);
	 }
}

?>