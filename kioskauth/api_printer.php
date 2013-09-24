<?php

$x = -180;
$y = 0;

$data = array('status' => 'error');

if (isset($_GET['uid']) && isset($_GET['password']) && isset($_GET['givenname']) && isset($_GET['sn']) && isset($_GET['type']))
{
  $printer = printer_open();
  
  if ($printer !== false)
  {
    $offset = (date('n') >= 9) ? 0 : -1;
	
    printer_set_option($printer, PRINTER_PAPER_FORMAT, PRINTER_FORMAT_A4);
    
    $fontL  = printer_create_font('Arial', 100, 40, PRINTER_FW_NORMAL, false, false, false, 0);
    $fontB = printer_create_font('Arial', 100, 40, PRINTER_FW_BOLD, false, false, false, 0);	
    
    if ($_GET['type'] == 'new')
    {
      printer_start_doc($printer, 'Doc');
      printer_start_page($printer);
      
      printer_draw_bmp($printer, 'static/new.bmp', $x, $y);
      
      printer_select_font($printer, $fontB);
      
      printer_draw_text($printer, utf8_decode((string)$_GET['password']), 2600+$x, 2140+$y);
      printer_draw_text($printer, (date('Y')+$offset).'/'.(date('Y')+$offset+1), 3150+$x, 4114+$y);
      
      printer_select_font($printer, $fontL);
      
      printer_draw_text($printer, utf8_decode((string)$_GET['sn']), 1545+$x, 4724+$y);
      printer_draw_text($printer, utf8_decode((string)$_GET['givenname']), 1545+$x, 4904+$y);
      printer_draw_text($printer, utf8_decode((string)$_GET['uid']), 1545+$x, 5084+$y);
      printer_draw_text($printer, date('d/m/Y'), 2985+$x, 5840+$y);
      
      printer_end_page($printer);
      printer_end_doc($printer);
    }
    else
    {
      printer_start_doc($printer, 'Doc');
      printer_start_page($printer);
      
      printer_draw_bmp($printer, 'static/recovery.bmp', $x, $y);
      
      printer_select_font($printer, $fontB);
      
      printer_draw_text($printer, utf8_decode((string)$_GET['password']), 2600+$x, 2140+$y);
      
      printer_end_page($printer);
      printer_end_doc($printer);
    }
    
    printer_delete_font($fontL);
    printer_delete_font($fontB);
    
    printer_close($printer);
    
    $data = array('status' => 'success');
  }
}

echo json_encode($data);
