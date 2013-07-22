<?php

$data = array('status' => 'error');

if (isset($_GET['led']))
{
  $led = (string)$_GET['led'];
  
  exec('mode com4: BAUD=9600 PARITY=n DATA=8 STOP=1 to=off dtr=off rts=off');
  
  $com = fopen('COM4', 'w');
  
  if ($com)
  {
    $data = array('status' => 'success');
    
    if ($led == 'card.green.on')
    {
      fwrite($com, 1);
    }
    else if ($led == 'card.green.blink')
    {
      fwrite($com, 2);
    }
    else if ($led == 'card.red.on')
    {
      fwrite($com, 3);
    }
    else if ($led == 'card.red.blink')
    {
      fwrite($com, 4);
    }
    else if ($led == 'printer.green.on')
    {
      fwrite($com, 5);
    }
    else if ($led == 'printer.green.blink')
    {
      fwrite($com, 6);
    }
    else if ($led == 'card.off')
    {
      fwrite($com, 7);
    }
    else if ($led == 'printer.off')
    {
      fwrite($com, 8);
    }
    else
    {
      $data = array('status' => 'error');
    }
    
    fclose($com);
  }
}

echo json_encode($data);
