<?php
namespace App\Globals;
use PDF;
use App;
class Pdf_global
{
    public static function show_pdf($html, $orient = null, $footer = '', $paper_size = 'a4')
	{
		$html_b = Pdf_global::bootstrap($html);
        $pdf = App::make('snappy.pdf.wrapper');
        if($footer != '')
        {
            $pdf->setOption('footer-right', $footer.' Page [page] of [topage]');
        }
        $pdf->loadHTML($html_b);
        if($orient != null)
        {
            $pdf->setOrientation('landscape');
        }
        if($paper_size != null)
        {
            $explode = explode("/", $paper_size);
            if(isset($explode[1]))
            {
                $pdf->setOption("page-width", $explode[0]."in")->setOption("page-height", $explode[1]."in");
            }
            else
            {
                $pdf->setPaper($paper_size);
            }
        }
        $pdf->setOption('viewport-size','1366x1024');
        return $pdf->inline();
	}
	public static function show_pdfv2($html, $orient = null, $footer = '')
	{
		$html_b = Pdf_global::bootstrap($html);
        $pdf = App::make('snappy.pdf.wrapper');

        if($footer != '')
        {
	        $pdf->setOption('footer-right', $footer.' Page [page] of [topage]');
        }

        $pdf->loadHTML($html_b);

        return $pdf->inline();
	}
	public static function show_image($html)
	{
		$html_b = Pdf_global::bootstrap($html);
		// return $html_b;
                $pdf = App::make('snappy.image.wrapper');
                $pdf->loadHTML($html_b);
                return $pdf->download('card.jpg');
	}
	public static function show_image_url($html)
	{
		$html_b = Pdf_global::bootstrap($html);
                $pdf = App::make('snappy.image.wrapper');
                $pdf->loadHTML($html_b);
                return $pdf->download('card.jpg');
	}
	public static function bootstrap($html)
	{
		$data['html'] = $html;
        $wew = str_replace('<div class="wrapper-top-scroll">',"",$data['html']);
        $wew = str_replace('<div class="div-top-scroll">',"",$wew);
        $wew = str_replace('<div class="wrapper-bottom-scroll">',"",$wew);
        $wew = str_replace('<div class="div-bottom-scroll">',"",$wew);
        $data['html'] = $wew;
		return view('pdf.body', $data);
	}
}