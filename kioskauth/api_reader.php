<?php

# Supported cards ATR
$atrs = array(
  '3B66000090D1020140B1',
  '3B66000090D1020152B1',
  '3B6600000512010101B1',
  '3B690000AC04000004B18C6121'
);

# Default answer
$data = array('status' => 'error');

$context = scard_establish_context();

if (scard_is_valid_context($context))
{
  $readers = scard_list_readers($context);
  
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
  else
  {
    $data = array('status' => 'noreader');
  }
  
  scard_release_context($context);
}

echo json_encode($data);
