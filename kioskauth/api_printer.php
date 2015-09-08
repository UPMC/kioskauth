<?php

$x = 6;
$y = 0;

$data = array('status' => 'error');

if (isset($_GET['uid']) && isset($_GET['password']) && isset($_GET['givenname']) && isset($_GET['sn']) && isset($_GET['type']))
{
  $printer = printer_open();
  
  if ($printer !== false)
  {
    $offset = (date('n') >= 9) ? 0 : -1;
	
    printer_set_option($printer, PRINTER_PAPER_FORMAT, PRINTER_FORMAT_CUSTOM);
    printer_set_option($printer, PRINTER_PAPER_WIDTH, 80);
    
    $fontB = printer_create_font('Calibri', 35, 14, PRINTER_FW_BOLD, false, false, false, 0);
    
    if ($_GET['type'] == 'new')
    {
      printer_start_doc($printer, 'Doc');
      printer_start_page($printer);
      
      printer_draw_bmp($printer, 'static/new.bmp', $x, $y);
      
      printer_select_font($printer, $fontB);
      
      printer_draw_text($printer, utf8_decode((string)$_GET['password']), 325+$x, 372+$y);
      
      printer_end_page($printer);
      
      printer_end_doc($printer);
    }
    else
    {
      printer_start_doc($printer, 'Doc');
      printer_start_page($printer);
      
      printer_draw_bmp($printer, 'static/recovery.bmp', $x, $y);
      
      printer_select_font($printer, $fontB);
      
      printer_draw_text($printer, utf8_decode((string)$_GET['password']), 325+$x, 372+$y);
      
      printer_end_page($printer);
      printer_end_doc($printer);
    }
    
    printer_delete_font($fontB);
    
    printer_close($printer);
    
    $data = array('status' => 'success');
  }
}

echo json_encode($data);
