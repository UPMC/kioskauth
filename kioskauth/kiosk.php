<!DOCTYPE html>
<html>

  <head>
    <meta charset="utf-8" />
    <title>KioskAuth</title>
    <link rel="stylesheet" href="static/kiosk.css" />
  </head>
  
  <body>
  
    <header>Kiosk Service d'Authentification</header>
    
    <div id="message" class="error">Kiosk hors service, veuillez vous adresser au technicien</div>

    <section>
      <img src="static/bug.png" id="picture" alt="Picture" />
    </section>
    
    <div id="screensaver"><img src="static/screensaver.png" alt="Screensaver" id="ssimg" /></div>
    
    <script src="static/jquery-1.11.0.min.js"></script>
    <script src="static/kiosk.js"></script>
  
    <script>

      $(document).ready(function() {
        reader();
        ss = setInterval(screensaver, 5000);
      });

    </script>
  
  </body>
</html>
