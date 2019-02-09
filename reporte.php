<?php
require_once("fpdf/fpdf.php");
require_once("Models/Producto.php"); 

Class PDF extends FPDF{

	function Header(){

		//Select Arial bold 15
	    $this->SetFont('Arial','B',15);
	    //Move to the right
	    $this->Cell(80);
	    //Framed title
	    $this->Cell(30,10,'LISTA DE PRODUCTOS',0,0,'C');
	    //Line break
	    $this->Ln(20);
	}

	function Footer(){

		//Go to 1.5 cm from bottom
	    $this->SetY(-15);
	    //Select Arial italic 8
	    $this->SetFont('Arial','I',8);
	    //Print centered page number
	    $this->Cell(0,10,'Pagina '.$this->PageNo(),0,0,'C');
		}
}
	

try {
	$pdf = new PDF();

	$pdf->addPage();

	$producto = new Producto();


	$pdf->Cell(35,10,'PRODUCTO',1,0);
	$pdf->Cell(30,10,'PRECIO',1,0);
	$pdf->Cell(30,10,'IMAGEN',1,0);
	$pdf->Cell(30,10,'USUARIO',1,0);
	//Line break
	$pdf->Ln(10);

	$x = 75;
	$y = 42;
	foreach ($producto->leerTodos() as $prod) {
		
			$pdf->Cell(35,18,$prod->descripcion,1,0);
			$pdf->Cell(30,18,$prod->precio,1,0);
			$pdf->Cell(30,18,$pdf->Image('imagenes/'.$prod->imagen,$x,$y,25,10),1,0,'C');
			$pdf->Cell(30,18,$prod->nombre,1,0);
			$pdf->Ln();

			$y+=18;

		
	}

	$pdf->Output();

} catch (Exception $e) {

	echo "error al imprimir el pdf: ".$e->getMessage();
}
