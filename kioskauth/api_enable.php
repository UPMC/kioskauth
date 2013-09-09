<?php

require_once 'config.php';

$data = array('status' => 'error');

if (isset($_GET['uid']))
{
  $uid = abs((int)$_GET['uid']);
  
  if ($uid != 0)
  {
    $ssh = ssh2_connect(sshHostname);
    ssh2_auth_password($ssh, sshUsername, sshPassword);
    
    $stream = ssh2_exec($ssh, 'echo -e "uw1W8KVtm5mu\n" | sudo -S bash /root/scripts/accountEnable '.$uid);
    
    stream_set_blocking($stream, true);
    $stream = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
    $stream = stream_get_contents($stream);
    
    if (preg_match('#Status(\s*):(\s*)success(\s*)\n#i', $stream) > 0)
    {
      $data = array('status' => 'success');
    }
  }
}

echo json_encode($data);
