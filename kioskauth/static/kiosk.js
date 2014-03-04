
function reader()
{
  $.ajax({
    dataType: 'json',
    type: 'GET',
    url: 'api_reader.php',
    success: function(data, textStatus, jqXHR)
    {
      if (data['status'] == 'success')
      {
        $('#message').html("Exécution en cours...");
        $('#message').attr('class', 'notification');
        $('#picture').attr('src', 'static/loader.gif');
        
        ldap(data['uid']);
        remove();
      }
      else if (data['status'] == 'nocard')
      {
        $('#message').html('Insérez votre carte dans le lecteur.');
        $('#message').attr('class', 'notification');

        if ($('#picture').attr('src') != 'static/insert.gif')
        {
		      $('#picture').attr('src', 'static/insert.gif');
		    }
        
        setTimeout(function() { reader(); }, 1000);
      }
      else if (data['status'] == 'invalid')
      {
        $('#message').html('Carte non supportée.');
        $('#message').attr('class', 'error');
        $('#picture').attr('src', 'static/bug.png');

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
      $('#message').html("Erreur QueryReader, redémarrage de l'application...");
      $('#message').attr('class', 'error');
      $('#picture').attr('src', 'static/bug.png');

      /* En raison d'un grand nombre d'erreur 500 provoquées par scard_disconnect */
      setTimeout(function() { location.reload(); }, 2000);
    }
  });
}

function ldap(uid)
{
  $.ajax({
    dataType: 'json',
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
        $('#message').html("Votre compte est introuvable dans l'annuaire UPMC.");
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
  $.ajax({
    dataType: 'json',
    type: 'GET',
    url: 'api_create.php',
	  data: { uid: uid },
    success: function(data, textStatus, jqXHR)
    {
      if (data['status'] == 'enabled')
      {
        printer(uid, data['password'], user, 'recovery');
      }
      else if (data['status'] == 'created')
      {
        printer(uid, data['password'], user, 'new');
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
  $.ajax({
    dataType: 'json',
    type: 'GET',
    url: 'api_receipt.php',
	  data: { uid: uid, password: password, givenname: user['givenname'], sn: user['sn'], type: type },
    success: function(data, textStatus, jqXHR)
    {
      if (data['status'] == 'success')
      {
        $('#message').html("Exécution terminée. Récupérez votre carte.");
        $('#message').attr('class', 'success');
		    $('#picture').attr('src', 'static/remove.gif');
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
    dataType: 'json',
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
      $('#message').html("Erreur QueryReader, redémarrage de l'application...");
      $('#message').attr('class', 'error');
      $('#picture').attr('src', 'static/bug.png');

      /* En raison d'un grand nombre d'erreur 500 provoquées par scard_disconnect */
      setTimeout(function() { location.reload(); }, 2000);
    }
  });
}

$(document).ready(function() {
  reader();
});
