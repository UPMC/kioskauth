<?php

$data = array('status' => 'error');

$context = scard_establish_context();

if (scard_is_valid_context($context))
{
  $readers = scard_list_readers($context);
  $readers[0] = 'SCM Microsystems Inc. SCR35xx USB Smart Card Reader 0';
  
  if (is_array($readers) && count($readers) > 0)
  {
    $connection = scard_connect($context, $readers[0]);
	
    if ($connection === false)
    {
      $data = array('status' => 'nocard');
    }
    else
    {
      $data = array('status' => 'success', 'readers' => $readers, 'context' => $context, 'connection' => $connection, 'card' => scard_status($connection));
      
      # ANB
      scard_transmit($connection, '00A4040C06A00000006991');
      scard_transmit($connection, '00B201BC16');
      
      $anb = array('00B201C43C', '00B202C43C', '00B203C43C', '00B204C43C', '00B205C43C', '00B206C43C', '00B207C43C', '00B208C43C', '00B209C43C', '00B20AC43C');
      
      foreach ($anb as $row) {
        $data['anb'][$row] = substr(scard_transmit($connection, $row), 0, -4);
      }
      
      # Moneo
      scard_transmit($connection, '00A4040C06A00000006900');
      $solde = scard_transmit($connection, '00B201C409');
      $data['moneo'] = (int)substr($solde, 0, 4).','.substr($solde, 4, 2);
      
      $data['uid'] = pack('H*', (substr($data['anb']['00B204C43C'], 18, 14)));
    }
  }
}

echo json_encode($data);
