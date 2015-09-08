<!DOCTYPE html>
<html>

  <head>
    <meta charset="utf-8" />
    <title>KioskAuth</title>
    <link rel="stylesheet" href="static/web.css" />
  </head>
  
  <body>
  
    <header>
      <h1>KioskAuth</h1>
      <h2>Tableau de bord</h2>
      <div id="version">
        <div>Logiciel initié par la <a href="https://www.ppi.ingenierie.upmc.fr/">PPI</a> de l'<a href="http://www.upmc.fr/">UPMC</a></div>
        <div style="color: #aaa">revision <?php echo substr(@file_get_contents('../.git/refs/heads/master'), 0, 10) ?></div>
      </div>
    </header>
    
    <section>
    
	  <div style="height: 80px">
        <div id="message" class="notification">L'application est prête.</div>
	  </div>
    
      <div class="box">
      
        <h3>Numéro de l'étudiant</h3>
      
        <p>
          <input type="text" id="uid" /> <input type="button" value="Exécuter" onclick="execute()" /> <br />
          <span style="color: #bbb">Vous pouvez également utiliser le lecteur ou le scanner.</span>
        </p>
        
        <h3>Choix de l'action à exécuter</h3>

        <p>
          <input type="radio" name="action" value="create" id="create" checked="checked" />
          <label for="create">Créer un compte ou réinitialiser le mot de passe</label>
        </p>
        
        <p>
          <input type="radio" name="action" value="ldap" id="ldap" />
          <label for="ldap">Récupérer le nom d'un étudiant</label>
        </p>
        
        <p>
          <input type="radio" name="action" value="analyse" id="analyse" />
          <label for="analyse">Analyser une carte</label>
        </p>
      
      </div>
      
      <div class="box">
      
        <h3>Options de l'application</h3>
        
        <p>
          <a href="kiosk.php">Kiosk</a> <br />
          <span style="color: #bbb">Lancer l'application en mode autonome.</span>
        </p>
        
        <p>
          <a onclick="$('#console').toggle()">Console</a> <br />
          <span style="color: #bbb">Ouvrir la console du développeur.</span>
        </p>
        
      </div>
      
      <pre id="console"></pre>
      
    </section>
    
    <script src="static/jquery-1.11.0.min.js"></script>
    <script src="static/web.js"></script>
  </body>
</html>
