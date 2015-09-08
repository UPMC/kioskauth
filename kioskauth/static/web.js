
/**
 * Set the message bar.
 *
 * @param message string
 * @param status string
 * @return void
 */
function message(message, css)
{
  $('#message').slideUp(400, function () {
    $('#message').html(message);
    $('#message').attr('class', css);
    $('#message').slideDown();
  });
}

/**
 * Try to get the uid from card reader, and if it's not
 * possible, get it from user interface.
 *
 * @param void
 * @return integer
 */
function reader()
{
  var uid = '';
  
  $.ajax({
    dataType: 'json',
    type: 'GET',
    url: 'api_reader.php',
    async: false,
    complete: function(jqXHR, textStatus)
    {
      console(dump(jqXHR));
    },
    success: function(data, textStatus, jqXHR)
    {
      if (data['status'] == 'success') {
        uid = data['uid'];
        $('#uid').val(uid);
      }
      else if ($('#uid').val() != '') {
        uid = $('#uid').val();
      }
      else {
        message("Impossible de récupérer le numéro étudiant.", 'error');
      }
    },
    error: function()
    {
      message("Impossible de récupérer le numéro étudiant.", 'error');
    }
  });
  
  return uid;
}

function create()
{
  message("La création du compte est en cours...", 'notification');
  uid = reader();
  
  if (uid != '')
  {
    $.ajax({
      dataType: 'json',
      type: 'GET',
      url: 'api_create.php',
      data: { uid: uid },
      complete: function(jqXHR, textStatus)
      {
        console(dump(jqXHR));
      },
      success: function(data, textStatus, jqXHR)
      {
        if (data['status'] == 'enabled') {
          message("Le mot de passe du compte a été réinitialisé.", 'success');
          printer(uid, data['password'], 'recovery');
        }
        else if (data['status'] == 'created') {
          message("Un nouveau compte a été créé.", 'success');
          printer(uid, data['password'], 'new');
        }
        else if (data['status'] == 'disabled') {
          message("Action impossible pour un compte désactivé.", 'error');
        }
        else {
          message("Erreur ApiCreate, impossible de créer le compte.", 'error');
        }
      },
      error: function()
      {
        message("Erreur jQuery_ApiCreate, impossible de créer le compte.", 'error');
      }
    });
  }
}

function ldap()
{
  uid = reader();
  
  if (uid != '')
  {
    $.ajax({
      dataType: 'json',
      type: 'GET',
      url: 'api_ldap.php',
      data: { uid: uid },
      complete: function(jqXHR, textStatus)
      {
        console(dump(jqXHR));
      },
      success: function(data, textStatus, jqXHR)
      {
        if (data['status'] == 'success') {
          message("Ce numéro d'étudiant correspond à <strong>"+data['givenname']+" "+data['sn']+"</strong>.", 'success');
        }
        else {
          message("Erreur ApiLdap, impossible de récupérer les informations dans l'annuaire", 'error');
        }
      },
      error: function()
      {
        message("Erreur jQuery_ApiLdap, impossible de récupérer les informations dans l'annuaire", 'error');
      }
    });
  }
}

function printer(uid, password, type)
{
  $.ajax({
    dataType: 'json',
    type: 'GET',
    url: 'api_printer.php',
    data: { uid: uid, password: password, givenname: '', sn: '', type: type },
    complete: function(jqXHR, textStatus)
    {
      console(dump(jqXHR));
    },
    success: function(data, textStatus, jqXHR)
    {
      if (data['status'] != 'success') {
        message("Erreur ApiPrinter, impossible d'imprimer le document.", 'error');
      }
    },
    error: function()
    {
      message("Erreur jQuery_ApiPrinter, impossible d'imprimer le document.", 'error');
    }
  });
}

function analyse()
{
  message("Récupération des données de la carte...", 'notification');
  
  $.ajax({
    dataType: 'json',
    type: 'GET',
    url: 'api_analyse.php',
    complete: function(jqXHR, textStatus)
    {
      $('#console').show();
      console(dump(jqXHR));
    },
    success: function(data, textStatus, jqXHR)
    {
      if (data['status'] == 'success') {
        message("Données de la carte récupérées avec succès. Solde Moneo de <strong>"+data['moneo']+" euros</strong>.", 'success');
        $('#uid').val(data['uid']);
      }
      else if (data['status'] == 'noreader') {
        message("Aucun lecteur de carte n'est disponible sur votre ordinateur.", 'error');
      }
      else if (data['status'] == 'nocard') {
        message("Aucune carte n'est détectée par le lecteur.", 'error');
      }
      else if (data['status'] == 'invalid') {
        message("Carte illisible ou non supportée.", 'error');
      }
      else {
        message("Erreur ApiAnalyse, impossible de récupérer les données de la carte.", 'error');
      }
    },
    error: function(jqXHR, textStatus, errorThrown)
    {
      message("Erreur jQuery_ApiAnalyse, impossible de récupérer les données de la carte.", 'error');
    }
  });
}

function execute()
{
  var selected = $('input:radio[name=action]:checked').val();
  
  if (selected == 'create') {
    console("execute() lance l'action create()");
    create();
  }
  else if (selected == 'ldap') {
    console("execute() lance l'action ldap()");
    ldap();
  }
  else if (selected == 'analyse') {
    console("execute() lance l'action analyse()");
    analyse();
  }
  
  $('#uid').select();
}

function dump(obj)
{
  this.result = "";
  this.indent = -2;
  
  this.dumpLayer = function(obj)
  {
    this.indent += 29;
 
    for (var i in obj)
    {
      if (typeof(obj[i]) == "object")
      {
        this.result += "                            ".substring(0,this.indent)+i+": "+"\n";
        this.dumpLayer(obj[i]);
      }
      else
      {
        this.result += "                            ".substring(0,this.indent)+i+": "+obj[i]+"\n";
      }
    }
    
    this.indent -= 29;
  }
  
  this.dumpLayer(obj);
  return this.result.slice(27).slice(0, -1);
}

function console(row)
{
  d = new Date();
  $('#console').append(d.toISOString()+" # "+row+"\n");
}

function actionClick()
{
  selected = $('input:radio[name=action]:checked').val();
  
  console("action modifiée : "+selected);
  
  if (selected == 'analyse') {
    $('#uid').attr('disabled', 'disabled');
  }
  else {
    $('#uid').removeAttr('disabled');
  }
}

$(document).ready(function()
{
  $('input:radio[name=action]').change(function() {
    actionClick();
  });
  
  $('#uid').keypress(function(e) {
    if (e.which == 13) {
      execute();
    }
  });
  
  console("nouvelle instance de l'application");
});
