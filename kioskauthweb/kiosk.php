<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
  <title>KioskAuth</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel="stylesheet" type="text/css" href="static/kiosk.css" />
  <script type="text/javascript" src="static/jquery/jquery-1.9.1.js"></script>
</head>

<body>
  <div id="header">
    <div id="title">KIOSK SERVICE D'AUTHENTIFICATION</div>
	  <div id="subtitle">UFR 919</div>
  </div>
</body>


<div class="minibox">
  Créez votre compte informatique ou récupérez votre mot de passe.
</div>

<div style="width: 400px; margin: 100px auto; text-align: center">
  <img src="static/bug.png" id="picture" />
</div>

<div id="message" class="error">
  Kiosk hors service, veuillez vous adresser au technicien.
</div>

<script type="text/javascript">

function reader()
{
  $.ajax({
    dataType: "json",
    type: 'GET',
    url: 'api_reader.php',
    success: function(data, textStatus, jqXHR)
    {
      if (data['status'] == 'success')
      {
        ldap(data['uid']);
        remove();
      }
      else if (data['status'] == 'nocard')
      {
        $('#message').html('Veuillez insérer votre carte dans le lecteur.');
        $('#message').attr('class', 'notification');
        $('#picture').attr('src', 'static/reader.png');
        
        $.ajax({
          dataType: "json",
          type: 'GET',
          url: 'api_led.php',
          data: { 'led': 'card.green.blink' },
          success: function(data, textStatus, jqXHR) {},
          error: function() {}
        });
        
        setTimeout(function() { reader(); }, 1000);
      }
      else if (data['status'] == 'invalid')
      {
        $('#message').html('Carte illisible ou non supportée.');
        $('#message').attr('class', 'error');
        $('#picture').attr('src', 'static/bug.png');
        
        $.ajax({
          dataType: "json",
          type: 'GET',
          url: 'api_led.php',
          data: { 'led': 'card.red.blink' },
          success: function(data, textStatus, jqXHR) {},
          error: function() {}
        });
      
        remove();
      }
      else
      {
        $('#message').html('Erreur ApiReader, veuillez vous adresser au technicien.');
        $('#message').attr('class', 'error');
        $('#picture').attr('src', 'static/bug.png');
        
        remove();
      }
    },
    error: function()
    {
      $('#message').html('Erreur QueryReader, veuillez vous adresser au technicien.');
      $('#message').attr('class', 'error');
      $('#picture').attr('src', 'static/bug.png');
    }
  });
}

function ldap(uid)
{
  $('#message').html("Récupération de votre fiche dans l'annuaire UPMC...");
  $('#message').attr('class', 'notification');
  $('#picture').attr('src', 'static/loader.gif');
  
  $.ajax({
    dataType: "json",
    type: 'GET',
    url: 'api_ldap.php',
	  data: { uid: uid },
    success: function(data, textStatus, jqXHR)
    {
      if (data['status'] == 'success')
      {
        create(uid, data);
      }
      else
      {
        $('#message').html('Erreur ApiLdap. Veuillez vous adresser au technicien.');
        $('#message').attr('class', 'error');
        $('#picture').attr('src', 'static/bug.png');
      }
    },
    error: function()
    {
      $('#message').html("Erreur QueryLdap. Veuillez vous adresser au technicien.");
      $('#message').attr('class', 'error');
      $('#picture').attr('src', 'static/bug.png');
    }
  });
}

function create(uid, user)
{
  $('#message').html("Execution de la commande sur le serveur d'authentification...");
  $('#message').attr('class', 'notification');
  $('#picture').attr('src', 'static/loader.gif');
  
  $.ajax({
    dataType: "json",
    type: 'GET',
    url: 'api_create.php',
	  data: { uid: uid },
    success: function(data, textStatus, jqXHR)
    {
      if (data['status'] == 'enabled')
      {
        $('#message').html('Votre mot de passe a été réinitialisé.');
        $('#message').attr('class', 'success');

        setTimeout(function() { printer(uid, data['password'], user, 'recovery'); }, 2000);
      }
      else if (data['status'] == 'created')
      {
        $('#message').html('Votre compte a été créé. Il est en attente de validation.');
        $('#message').attr('class', 'success');
      
        setTimeout(function() { printer(uid, data['password'], user, 'new'); }, 2000);
      }
      else if (data['status'] == 'disabled')
      {
        $('#message').html("Votre compte n'est pas actif. Avez-vous signé la charte ?");
        $('#message').attr('class', 'error');
        $('#picture').attr('src', 'static/bug.png');
      }
      else
      {
        $('#message').html('Erreur ApiCreate. Veuillez vous adresser au technicien.');
        $('#message').attr('class', 'error');
        $('#picture').attr('src', 'static/bug.png');
      }
    },
    error: function()
    {
      $('#message').html("Erreur QueryCreate. Veuillez vous adresser au technicien.");
      $('#message').attr('class', 'error');
      $('#picture').attr('src', 'static/bug.png');
    }
  });
}

function printer(uid, password, user, type)
{
  $('#message').html("Impression des informations de connexion en cours...");
  $('#message').attr('class', 'notification');
  $('#picture').attr('src', 'static/loader.gif');
  
  $.ajax({
    dataType: "json",
    type: 'GET',
    url: 'api_receipt.php',
	  data: { uid: uid, password: password, givenname: user['givenname'], sn: user['sn'], type: type },
    success: function(data, textStatus, jqXHR)
    {
      if (data['status'] == 'success')
      {
        $('#message').html("Impression du document. Vous pouvez récupérer votre carte.");
        $('#message').attr('class', 'success');
      }
      else
      {
        $('#message').html("Erreur ApiPrinter. Veuillez vous adresser au technicien.");
        $('#message').attr('class', 'error');
        $('#picture').attr('src', 'static/bug.png');
      }
    },
    error: function()
    {
      $('#message').html("Erreur QueryPrinter. Veuillez vous adresser au technicien.");
      $('#message').attr('class', 'error');
      $('#picture').attr('src', 'static/bug.png');
    }
  });
}

function remove()
{
  $.ajax({
    dataType: "json",
    type: 'GET',
    url: 'api_reader.php',
    success: function(data, textStatus, jqXHR)
    {
      if (data['status'] == 'nocard')
      {
        location.reload();
      }
      else
      {
        setTimeout(function() { remove(); }, 1000);
      }
    },
    error: function()
    {
      $('#message').html('Erreur QueryReader, veuillez vous adresser au technicien.');
      $('#message').attr('class', 'error');
      $('#picture').attr('src', 'static/bug.png');
    }
  });
}

$(document).ready(function() {
  reader();
});

</script>

</body>
</html>
