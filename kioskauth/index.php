<!DOCTYPE html>
<html>

  <head>
    <meta charset="utf-8" />
    <title>KioskAuth</title>
    <link rel="stylesheet" href="static/web.css" />
    <link rel="stylesheet" href="static/jquery/jquery-ui-1.10.2.custom.css" />
  </head>
  
  <body>
  
    <header>
      <h1>KioskAuth</h1>
      <h2>Tableau de bord</h2>
      <a href="kiosk.php" id="button">Lancer l'application Kiosk</a>
    </header>
    
    <section>
    
      <div id="message" class="notification">En attente d'une action à exécuter...</div>
    
      <div class="box">
      
        <h3>Numéro de l'étudiant</h3>
      
        <p>
          <input type="text" id="uid" /> <input type="button" value="Exécuter" id="execute" /> <br />
          <span style="color: #bbb">Vous pouvez également utiliser le lecteur ou le scanner.</span>
        </p>
        
        <h3>Choix de l'action à exécuter</h3>

      <p>
        <input type="radio" name="action" value="create" id="create" checked="checked" />
        <label for="create">Créer un compte ou réinitialiser le mot de passe</label>
      </p>
      
      <p>
        <input type="radio" name="action" value="enable" id="enable" />
        <label for="enable">Activer un compte</label>
      </p>
      
      <p>
        <input type="radio" name="action" value="ldap" id="ldap" />
        <label for="ldap">Afficher un profil étudiant depuis l'annuaire</label>
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
          <a onclick="$('#fancy').toggle()">Configuration</a> <br />
          <span style="color: #bbb">Paramètres utilisateur de l'application.</span>
        </p>
        
        <p>
          <a onclick="$('#console').toggle()">Console</a> <br />
          <span style="color: #bbb">Ouvrir la console du développeur.</span>
        </p>
        
      </div>
      
      <pre id="console"></pre>
      
    </section>


      <div id="fancy">
        <div>
          <fieldset>
            <legend>Tableau de bord</legend>
            
            <h2>Lecteur de carte</h2>
            <p><select id="readersAdm"></select></p>
            
            <h2>Imprimer vers</h2>
            <p><select id="printersAdm"></select></p>
            
            <p>
              <input type="checkbox" checked="checked" id="userDashboardReceipt" />
              <label for="userDashboardReceipt">Utiliser le format ticket pour cette imprimante</label>
            </p>
            
          </fieldset>
          
          <fieldset>
            <legend>Kiosk</legend>
            
            <h2>Lecteur de carte</h2>
            <p><select id="readersKiosk"></select></p>
            
            <h2>Imprimer vers</h2>
            <p><select id="printersKiosk"></select></p>
            
            <p>
              <input type="checkbox" checked="checked" id="userKioskReceipt" />
              <label for="userKioskReceipt">Utiliser le format ticket pour cette imprimante</label>
            </p>
          
          </fieldset>
          
          <p>
            <input type="button" value="Enregistrer" onclick="config()" />
            <input type="button" value="Fermer" id="close" />
          </p>
      </div>
    </div>

    
    <script src="static/jquery-1.11.0.min.js"></script>
    <script src="static/web.js"></script>
  
  </body>
</html>
