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
    
    $stream = ssh2_exec($ssh, 'echo -e "uw1W8KVtm5mu\n" | sudo -S bash /root/scripts/accountCreate etudiant '.$uid);
    
    stream_set_blocking($stream, true);
    $stream = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
    $stream = stream_get_contents($stream);
    
    # We need to reset the password
    if (preg_match('#Status(\s*):(\s*)Enabled(\s*)\n#i', $stream) > 0)
    {
      $stream = ssh2_exec($ssh, 'echo -e "uw1W8KVtm5mu\n" | sudo -S bash /root/scripts/passwordReset '.$uid);
    
    stream_set_blocking($stream, true);
      $stream = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
      $stream = stream_get_contents($stream);
    
    if (preg_match('#Status(\s*):(\s*)Success(\s*),(\s*)Password(\s*):(\s*)([A-Z0-9]+)(\s*)\n#i', $stream, $parse) > 0)
    {
      $data = array('status' => 'enabled', 'password' => $parse[7]);
    }
    }
    else if (preg_match('#Status(\s*):(\s*)Created(\s*),(\s*)Password(\s*):(\s*)([A-Z0-9]+)(\s*)\n#i', $stream, $parse) > 0)
    {
      $data = array('status' => 'created', 'password' => $parse[7]);
    }
    else if (preg_match('#Status(\s*):(\s*)Disabled(\s*)\n#i', $stream) > 0)
    {
      $data = array('status' => 'disabled');
    }
  }
}

echo json_encode($data);
