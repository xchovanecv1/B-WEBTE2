<?php
    mb_internal_encoding('UTF-8');

    require_once './plugins/dompdf/autoload.inc.php';
    require_once './Page.php';
    
    // reference the Dompdf namespace
    use Dompdf\Dompdf;

    function generate_pdf($data)
    {
    // instantiate and use the dompdf class
        $dompdf = new Dompdf();

        $html = "<!DOCTYPE html><html>
                    <head>
                        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />
                        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
                        <link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css\">
                        <script src=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js\"></script>
                        <script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js\"></script> 
                        <style>*{font-family:DejaVu Sans; sans-serif !important; } h2{font-family:DejaVu Sans; sans-serif !important;}</style>
                    </head>
                    <body>";
        $html .= $data;

        $html .= "</body></html>";
        
        $dompdf->loadHtml($html, 'UTF-8');

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A3', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream();
    }
?>