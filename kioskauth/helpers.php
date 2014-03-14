<?php

/**
 * Open simple ini file and return values
 *
 * @param string File path
 * @return array
 */
function getConfigIni($path)
{
  $config = array();
  
  if (is_readable($path))
  {
    $ini = explode("\n", file_get_contents($path));
    
    foreach ($ini as $row)
    {
      $xp    = explode('=', $row, 2);
      $xp[0] = trim($xp[0]);
      
      if ($xp[0] != '')
      {
        if (isset($xp[1]))
        {
          $xp[1] = trim($xp[1]);
          $config[$xp[0]] = ($xp[1] != '') ? $xp[1] : null;
        }
      }
    }
  }
  
  return $config;
}


phpinfo();