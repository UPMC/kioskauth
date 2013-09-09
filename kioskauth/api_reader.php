<?php

$atrs = array('3B66000090D1020140B1', '3B66000090D1020152B1', '3B690000AC04000004B18C6121');

$data = array('status' => 'error');

$context = scard_establish_context();

if (scard_is_valid_context($context))
{
  $readers = scard_list_readers($context);
  //$readers[0] = 'SCM Microsystems Inc. SCR35xx USB Smart Card Reader 0';
  
  if (is_array($readers) && count($readers) > 0)
  {
    $connection = scard_connect($context, $readers[0]);
	
    if ($connection === false)
    {
      $data = array('status' => 'nocard');
    }
    else
    {
      $card = scard_status($connection);
      
      scard_transmit($connection, '00A4040C06A00000006991');
      scard_transmit($connection, '00B201BC16');
      
      $uid = pack('H*', (substr(scard_transmit($connection, '00B204C43C'), 18, 14)));
      
      //$data = array('status' => 'success', 'uid' => 2900632); 
      
      if (preg_match('#[^0-9]#', $uid) > 0 || (int)$uid === 0 || !in_array($card['ATR'], $atrs))
      {
        $data = array('status' => 'invalid');
      }
      else
      {
        $data = array('status' => 'success', 'uid' => (int)$uid);
      }
    }
    
    scard_disconnect($connection);
  }
  
  scard_release_context($context);
}

echo json_encode($data);
