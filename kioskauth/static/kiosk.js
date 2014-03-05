
/**
 * Set the message bar and edit the image url.
 *
 * @param message string
 * @param status string
 * @param image string
 * @return void
 */
function message(message, status, image)
{
  $('#message').html(message);
  
  if (status == 'notification') {
    $('#message').attr('class', 'notification');
  }
  else if (status == 'success') {
    $('#message').attr('class', 'success');
  }
  else if (status == 'error') {
    $('#message').attr('class', 'error');
  }
  
  if (image != '') {
    if ($('#picture').attr('src') != 'static/'+image) {
      $('#picture').attr('src', 'static/'+image);
    }
  }
}

/**
 * Get the smart card reader status and launch actions.
 *
 * @param void
 * @return void
 */
function reader()
{
  $.ajax({
    dataType: 'json',
    type: 'GET',
    url: 'api_reader.php',
    success: function(data, textStatus, jqXHR)
    {
      if (data['status'] == 'success') {
        message("Exécution en cours...", 'notification', 'loader.gif');
        ldap(data['uid']);
        remove();
      }
      else if (data['status'] == 'nocard') {
        message("Insérez votre carte dans le lecteur avec tendresse", 'notification', 'insert.gif');
        setTimeout(function() { reader(); }, 1000);
      }
      else if (data['status'] == 'invalid') {
        message("Carte non supportée, récupérez votre carte", 'error', 'bug.png');
        remove();
      }
      else {
        message("Erreur ApiReader, veuillez vous adresser au technicien", 'error', 'bug.png');
        remove();
      }
    },
    error: function()
    {
      /* En raison d'un grand nombre d'erreur 500 provoquées par scard_disconnect */
      message("Erreur jQuery_ApiReader, tentative de redémarrage de l'application...", 'error', 'bug.png');
      setTimeout(function() { location.reload(); }, 2000);
    }
  });
}

/**
 * Get the smart card reader status and launch actions.
 *
 * @param uid string
 * @return void
 */
function ldap(uid)
{
  $.ajax({
    dataType: 'json',
    type: 'GET',
    url: 'api_ldap.php',
	  data: { uid: uid },
    success: function(data, textStatus, jqXHR)
    {
      if (data['status'] == 'success') {
        create(uid, data);
      }
      else {
        message("Votre compte est introuvable dans l'annuaire UPMC", 'error', 'bug.png');
      }
    },
    error: function()
    {
      message("Erreur jQuery_ApiLdap, veuillez vous adresser au technicien", 'error', 'bug.png');
    }
  });
}

/**
 * Launch query to create new user (or change password).
 *
 * @param uid string
 * @return user array
 */
function create(uid, user)
{
  $.ajax({
    dataType: 'json',
    type: 'GET',
    url: 'api_create.php',
	  data: { uid: uid },
    success: function(data, textStatus, jqXHR)
    {
      if (data['status'] == 'enabled') {
        printer(uid, data['password'], user, 'recovery');
      }
      else if (data['status'] == 'created') {
        printer(uid, data['password'], user, 'new');
      }
      else if (data['status'] == 'disabled') {
        message("Votre compte n'est pas actif, avez-vous signé la charte ?", 'error', 'bug.png');
      }
      else {
        message("Erreur ApiCreate, veuillez vous adresser au technicien", 'error', 'bug.png');
      }
    },
    error: function()
    {
      message("Erreur jQuery_ApiCreate, veuillez vous adresser au technicien", 'error', 'bug.png');
    }
  });
}

/**
 * Launch query to print a receipt.
 *
 * @param uid string
 * @param password string
 * @param user array
 * @param type string
 * @return void
 */
function printer(uid, password, user, type)
{
  $.ajax({
    dataType: 'json',
    type: 'GET',
    url: 'api_receipt.php',
	  data: { uid: uid, password: password, givenname: user['givenname'], sn: user['sn'], type: type },
    success: function(data, textStatus, jqXHR)
    {
      if (data['status'] == 'success') {
        message("Exécution terminée, récupérez votre carte", 'success', 'remove.gif');
      }
      else {
        message("Erreur ApiPrinter, veuillez vous adresser au technicien", 'error', 'bug.png');
      }
    },
    error: function()
    {
      message("Erreur jQuery_ApiPrinter, veuillez vous adresser au technicien", 'error', 'bug.png');
    }
  });
}

/**
 * Get the smart card reader status until card is removed.
 *
 * @param void
 * @return void
 */
function remove()
{
  $.ajax({
    dataType: 'json',
    type: 'GET',
    url: 'api_reader.php',
    success: function(data, textStatus, jqXHR)
    {
      if (data['status'] == 'nocard') {
        location.reload();
      }
      else {
        setTimeout(function() { remove(); }, 1000);
      }
    },
    error: function()
    {
      /* En raison d'un grand nombre d'erreur 500 provoquées par scard_disconnect */
      message("Erreur jQuery_ApiReader, tentative de redémarrage de l'application...", 'error', 'bug.png');
      setTimeout(function() { location.reload(); }, 2000);
    }
  });
}

/**
 * Animate image for screensaver.
 *
 * @param void
 * @return void
 */
function screensaver()
{
  image = $("#screensaver img");
  
  image.fadeOut(500, function() {
    maxLeft = $(window).width() - image.width();
    maxTop = $(window).height() - image.height();
    leftPos = Math.floor(Math.random() * (maxLeft + 1));
    topPos = Math.floor(Math.random() * (maxTop + 1));
    image.css({ left: leftPos, top: topPos }).fadeIn(200);
  });
    
  //clearInterval(ss);
}
