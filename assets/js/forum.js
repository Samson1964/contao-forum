// Wir registrieren einen EventHandler für unser Input-Element (#uploadFile)
// wenn es sich ändert
$('body').on('change', '#ctrl_image', function() {
   var data = new FormData(); // das ist unser Daten-Objekt ...
   data.append('file', $("#ctrl_image")[0].files[0]); // ... an die wir unsere Datei anhängen
   $.ajax({
      url: 'myscript.php', // Wohin soll die Datei geschickt werden?
      data: data,          // Das ist unser Datenobjekt.
      type: 'POST',         // HTTP-Methode, hier: POST
      processData: false,
      contentType: false,
      // und wenn alles erfolgreich verlaufen ist, schreibe eine Meldung
      // in das Response-Div
      success: function() 
      { 
      	$("#responses").append("Datei erfolgreich hochgeladen");
      }
   });
});
