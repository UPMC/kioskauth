<?php

$data = array('status' => 'success', 'readers' => array(), 'printers' => array());

$context = scard_establish_context();

if (scard_is_valid_context($context))
{
  $data = array('status' => 'success');
  $readers = scard_list_readers($context);
  $data['readers'] = array_combine($readers, $readers);
}

$data['printers'] = printer_list(PRINTER_ENUM_LOCAL, null, 1);

echo json_encode($data);
