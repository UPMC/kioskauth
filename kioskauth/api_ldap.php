<?php

require_once 'config.php';

$data = array('status' => 'error');

if (isset($_GET['uid']))
{
  $uid = abs((int)$_GET['uid']);
  
  if ($uid != 0)
  {
    $ldap = ldap_connect(ldapHostname, ldapPort);
    ldap_bind($ldap, ldapAuthUser, ldapAuthPassword);
    
    $fields = array('givenname', 'sn', 'mail', 'employeetype', 'etucursusannee', 'familledisciplinaire');
    
    $results = ldap_get_entries($ldap, ldap_search($ldap, ldapSearchDn, '(uid='.$uid.')', $fields, 0, 1));
    
    ldap_unbind($ldap);
    
    if (!empty($results[0]['givenname'][0]) && !empty($results[0]['sn'][0]))
    {
      $data = array('status' => 'success');
      
      foreach ($results[0] as $key => $row)
      {
        if (in_array($key, $fields, true)) {
          $data[$key] = $row[0];
        }
      }
    }
  }
}

echo json_encode($data);
