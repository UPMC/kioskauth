<!DOCTYPE html>
<html>

  <head>
    <meta charset="utf-8" />
    <title>KioskAuth</title>
    <link rel="stylesheet" href="static/kiosk.css" />
  </head>
  
  <body>
  
    <header>Kiosk Service d'Authentification</header>
    
    <div id="wrapper">
      <div id="message" class="notification">Initialisation...</div>
    </div>
    
    <section>
      <img src="static/loader.gif" id="picture" alt="Picture" />
    </section>
    
    <div id="screensaver"><img src="static/screensaver.png" alt="Screensaver" /></div>
    
    <script src="static/jquery-1.11.0.min.js"></script>
    <script src="static/kiosk.js"></script>
  
    <script>

      $(document).ready(function() {
      
        ssDelay = 60;
        reader();
        
        $(document).mousemove(function() {
          $('#screensaver').fadeOut(0);
          ssDelay = 60;
        });
        
      });
      
    </script>
  
  </body>
</html>
