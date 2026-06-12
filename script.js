/* script.js – Navigation, Slider, Glücksrad und Gewinnspiel-Formular */

// ===== AKTIVE NAVIGATION =====
document.addEventListener("DOMContentLoaded", function () {
  var currentPath = window.location.pathname;
  var links = document.querySelectorAll(".nav-links a");
  for (var i = 0; i < links.length; i++) {
    var href = links[i].getAttribute("href");
    // Aktuelle Seite im Menü hervorheben
    if (currentPath.indexOf(href) !== -1) {
      links[i].classList.add("active");
    }
  }
});

// ===== HAMBURGER MENÜ =====
document.addEventListener("DOMContentLoaded", function () {
  var hamburger = document.querySelector(".hamburger");
  var nav = document.querySelector(".nav-links");
  if (!hamburger || !nav) {
    return;
  }
  hamburger.addEventListener("click", function (e) {
    e.stopPropagation();
    hamburger.classList.toggle("active");
    nav.classList.toggle("open");
  });
});

// ===== IMAGE SLIDER (nur auf index.php) =====
document.addEventListener("DOMContentLoaded", function () {
  var track = document.getElementById("sliderTrack");
  if (!track) {
    return;
  }
  var slides = document.querySelectorAll(".slider-slide");
  var prevBtn = document.getElementById("prevBtn");
  var nextBtn = document.getElementById("nextBtn");
  var dotsContainer = document.getElementById("sliderDots");
  if (slides.length === 0) {
    return;
  }

  var currentIndex = 0;
  var totalSlides = slides.length;

  // Punkte unter dem Slider erstellen (einer pro Bild)
  function createDots() {
    if (!dotsContainer) {
      return;
    }
    dotsContainer.innerHTML = "";
    for (var i = 0; i < totalSlides; i++) {
      var dot = document.createElement("div");
      dot.classList.add("dot");
      if (i === currentIndex) {
        dot.classList.add("active");
      }
      // Klick auf einen Punkt springt zum passenden Bild
      dot.setAttribute("data-index", i);
      dot.addEventListener("click", function () {
        var ziel = parseInt(this.getAttribute("data-index"));
        goToSlide(ziel);
      });
      dotsContainer.appendChild(dot);
    }
  }

  // Aktiven Punkt markieren
  function updateDots() {
    if (!dotsContainer) {
      return;
    }
    var dots = document.querySelectorAll(".dot");
    for (var i = 0; i < dots.length; i++) {
      if (i === currentIndex) {
        dots[i].classList.add("active");
      } else {
        dots[i].classList.remove("active");
      }
    }
  }

  // Slider auf ein bestimmtes Bild verschieben
  function goToSlide(index) {
    if (index < 0) {
      index = totalSlides - 1;
    }
    if (index >= totalSlides) {
      index = 0;
    }
    currentIndex = index;
    track.style.transform = "translateX(-" + currentIndex * 100 + "%)";
    updateDots();
  }

  if (nextBtn) {
    nextBtn.addEventListener("click", function () {
      goToSlide(currentIndex + 1);
    });
  }
  if (prevBtn) {
    prevBtn.addEventListener("click", function () {
      goToSlide(currentIndex - 1);
    });
  }
  createDots();
});

// ===== GLÜCKSRAD =====
document.addEventListener("DOMContentLoaded", function () {
  var canvas = document.getElementById("wheelCanvas");
  if (!canvas) {
    return;
  }

  var ctx = canvas.getContext("2d");
  var size = 400;
  canvas.width = size;
  canvas.height = size;

  // Die fünf Felder des Rads (Beschriftung, Hintergrund- und Textfarbe)
  // Farben passend zum Design: Cayenne-Rot, Schwarz, Hellgrau im Wechsel
  var segments = [
    { label: "Gratis Gewürz", color: "#d72638", text: "#ffffff" },
    { label: "10% Rabatt", color: "#111111", text: "#ffffff" },
    { label: "5% Rabatt", color: "#e8e8e8", text: "#111111" },
    { label: "Gratis Versand", color: "#d72638", text: "#ffffff" },
    { label: "15% Rabatt", color: "#e8e8e8", text: "#111111" },
  ];
  var segCount = segments.length;
  // Wie viel Winkel ein Feld einnimmt (voller Kreis geteilt durch Anzahl Felder)
  var angleStep = (Math.PI * 2) / segCount;

  var currentRot = 0; // aktuelle Drehung des Rads
  var spinning = false; // dreht sich das Rad gerade?
  var schonGedreht = false; // wurde das Rad schon einmal gedreht?
  var spinStart = 0; // Startzeit der Drehung
  var startRot = 0; // Drehung beim Start
  var targetRot = 0; // Ziel-Drehung am Ende
  var spinDuration = 3000; // Dauer der Drehung in Millisekunden

  // Zeichnet das Rad bei einer bestimmten Drehung
  function drawWheel(rot) {
    ctx.clearRect(0, 0, size, size);
    var cx = size / 2;
    var cy = size / 2;
    var r = size * 0.42;

    // Jedes Feld als farbiges Kreisstück mit Text zeichnen
    for (var i = 0; i < segCount; i++) {
      var start = i * angleStep + rot;
      var end = (i + 1) * angleStep + rot;
      ctx.beginPath();
      ctx.moveTo(cx, cy);
      ctx.arc(cx, cy, r, start, end);
      ctx.fillStyle = segments[i].color;
      ctx.fill();

      ctx.save();
      ctx.translate(cx, cy);
      ctx.rotate(start + angleStep / 2);
      ctx.font = "bold 13px 'Neue Montreal', sans-serif";
      ctx.fillStyle = segments[i].text;
      ctx.textAlign = "right";
      ctx.textBaseline = "middle";
      ctx.fillText(segments[i].label, r * 0.9, 0);
      ctx.restore();
    }

    // Pfeil oben am Rad (schwarz)
    ctx.beginPath();
    ctx.moveTo(cx + 18, cy - r - 8);
    ctx.lineTo(cx - 18, cy - r - 8);
    ctx.lineTo(cx, cy - r + 12);
    ctx.fillStyle = "#111111";
    ctx.fill();
    // Kleiner Kreis in der Mitte (rot)
    ctx.beginPath();
    ctx.arc(cx, cy, r * 0.08, 0, 2 * Math.PI);
    ctx.fillStyle = "#d72638";
    ctx.fill();
  }

  // Eine Drehung starten
  function spin() {
    // Während einer laufenden Drehung oder nach dem ersten Dreh nicht neu starten
    if (spinning || schonGedreht) {
      return;
    }
    // Das Rad darf nur einmal gedreht werden: Button deaktivieren
    schonGedreht = true;
    var btn = document.getElementById("spinWheelBtn");
    if (btn) {
      btn.disabled = true;
      btn.innerText = "Bereits gedreht";
    }
    // Zufällig 5 bis 8 ganze Umdrehungen plus einen Zufallswinkel
    var ganzeRunden = 5 + Math.floor(Math.random() * 4);
    var zufallsWinkel = Math.random() * 2 * Math.PI;
    startRot = currentRot;
    targetRot = currentRot + ganzeRunden * 2 * Math.PI + zufallsWinkel;
    spinStart = performance.now();
    spinning = true;
    requestAnimationFrame(animate);
  }

  // Findet das Feld, das am Ende oben beim Pfeil steht
  function getGewinn() {
    // Der Pfeil zeigt nach oben, das ist der Winkel -90 Grad
    var pfeil = -Math.PI / 2;
    // Drehung gegenrechnen, damit wir das passende Feld finden
    var rest = (pfeil - currentRot) % (2 * Math.PI);
    // Negativen Wert in den positiven Bereich (0 bis 2*PI) bringen
    while (rest < 0) {
      rest += 2 * Math.PI;
    }
    // Aus dem Winkel die Feld-Nummer berechnen
    var index = Math.floor(rest / angleStep);
    return segments[index].label;
  }

  // Berechnet jedes Bild der Animation und zeichnet das Rad neu
  function animate(now) {
    var t = (now - spinStart) / spinDuration;
    if (t > 1) {
      t = 1;
    }
    // Das Rad wird gegen Ende langsamer (einfache Abbrems-Kurve)
    var ease = 1 - Math.pow(1 - t, 3);
    var newRot = startRot + (targetRot - startRot) * ease;
    drawWheel(newRot);

    if (t < 1) {
      // Solange die Zeit nicht abgelaufen ist: nächstes Bild zeichnen
      requestAnimationFrame(animate);
    } else {
      // Drehung fertig: Endstellung merken und Gewinn anzeigen
      spinning = false;
      currentRot = targetRot;
      var gewinn = getGewinn();
      zeigeGewinn(gewinn);
    }
  }

  // Zeigt den Gewinn an und blendet das Formular ein
  function zeigeGewinn(gewinn) {
    var hinweis = document.getElementById("gewinnHinweis");
    if (hinweis) {
      // Kleines Geschenk-Icon (SVG) statt Emoji vor den Text setzen
      var geschenk =
        '<svg class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="8" width="18" height="4" rx="1"/><path d="M12 8v13"/><path d="M19 12v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-7"/><path d="M7.5 8a2.5 2.5 0 0 1 0-5A4.8 8 0 0 1 12 8a4.8 8 0 0 1 4.5-5 2.5 2.5 0 0 1 0 5"/></svg>';
      hinweis.innerHTML = geschenk + " Dein Gewinn: " + gewinn;
    }
    var formCard = document.getElementById("gewinnspielCard");
    if (formCard) {
      formCard.classList.remove("is-hidden");
    }
  }

  var spinBtn = document.getElementById("spinWheelBtn");
  if (spinBtn) {
    spinBtn.addEventListener("click", spin);
  }

  // Rad einmal beim Laden zeichnen
  drawWheel(currentRot);
});

// ===== FORMULARVALIDIERUNG (Gewinnspiel) =====
/*
   Validierungsregeln:
   - Vorname: Pflicht, min. 2 Buchstaben
   - Nachname: Pflicht, min. 2 Buchstaben
   - E-Mail: Pflicht, gültiges Format
   - Quelle: Auswahl nötig (nicht leer)
   - AGB: Checkbox akzeptiert
*/
document.addEventListener("DOMContentLoaded", function () {
  var form = document.getElementById("contestForm");
  if (!form) {
    return;
  }

  // Fehlertext bei einem Feld anzeigen
  function showError(id, msg) {
    var el = document.getElementById(id);
    if (el) {
      el.innerText = msg;
    }
  }

  // Alle Fehlertexte zurücksetzen
  function clearErrors() {
    var ids = [
      "errorVorname",
      "errorNachname",
      "errorEmail",
      "errorReferrer",
      "errorTerms",
    ];
    for (var i = 0; i < ids.length; i++) {
      showError(ids[i], "");
    }
  }

  // Prüft alle Felder und gibt true zurück, wenn alles in Ordnung ist
  function validateForm() {
    var valid = true;
    var vorname = document.getElementById("vorname").value.trim();
    var nachname = document.getElementById("nachname").value.trim();
    var email = document.getElementById("email").value.trim();
    var referrer = document.getElementById("referrer").value;
    var terms = document.getElementById("terms").checked;

    if (!vorname) {
      showError("errorVorname", "Vorname erforderlich");
      valid = false;
    } else if (!/^[A-Za-zÄÖÜäöüß\s\-]{2,}$/.test(vorname)) {
      showError("errorVorname", "Mind. 2 Buchstaben");
      valid = false;
    }

    if (!nachname) {
      showError("errorNachname", "Nachname erforderlich");
      valid = false;
    } else if (!/^[A-Za-zÄÖÜäöüß\s\-]{2,}$/.test(nachname)) {
      showError("errorNachname", "Mind. 2 Buchstaben");
      valid = false;
    }

    var emailPattern = /^[^\s@]+@([^\s@]+\.)+[^\s@]+$/;
    if (!email) {
      showError("errorEmail", "E-Mail erforderlich");
      valid = false;
    } else if (!emailPattern.test(email)) {
      showError("errorEmail", "Ungültiges Format");
      valid = false;
    }

    if (!referrer || referrer === "") {
      showError("errorReferrer", "Bitte wählen");
      valid = false;
    }
    if (!terms) {
      showError("errorTerms", "AGB akzeptieren");
      valid = false;
    }
    return valid;
  }

  form.addEventListener("submit", function (e) {
    clearErrors();
    // Wenn die Eingaben nicht gültig sind: Formular NICHT abschicken
    if (!validateForm()) {
      e.preventDefault();
      // Hinweis mit Warn-Icon (SVG) statt Emoji anzeigen
      var warnung =
        '<svg class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>';
      document.getElementById("formFeedback").innerHTML =
        '<div class="error-msg">' + warnung + " Bitte korrigieren.</div>";
      return;
    }
    // Alles gültig: das Formular wird normal an submit_gewinnspiel.php geschickt
  });
});

// ===== NEWSLETTER (Footer) =====
document.addEventListener("DOMContentLoaded", function () {
  var form = document.getElementById("newsletterForm");
  if (!form) {
    return;
  }
  form.addEventListener("submit", function (e) {
    var feld = document.getElementById("newsletterEmail");
    var feedback = document.getElementById("newsletterFeedback");
    var wert = "";
    if (feld) {
      wert = feld.value.trim();
    }
    // Einfache Prüfung: Eingabe vorhanden und sieht nach E-Mail aus
    if (wert.length < 3 || wert.indexOf("@") === -1) {
      // Ungültig: Formular NICHT abschicken, nur Hinweis zeigen
      e.preventDefault();
      feedback.innerText = "Bitte gib eine gültige E-Mail ein.";
      return;
    }
    // Gültig: Formular wird normal an submit_newsletter.php geschickt (speichert in der DB)
  });
});

// ===== KLEINE INFO-BUTTONS =====
document.addEventListener("DOMContentLoaded", function () {
  // "Mehr erfahren" auf der Startseite
  var moreInfoBtn = document.getElementById("moreInfoBtn");
  if (moreInfoBtn) {
    moreInfoBtn.addEventListener("click", function () {
      alert("Erfahre mehr über unsere Gewürze.");
    });
  }
  // "Entdecken" auf der Über-uns-Seite
  var entdeckenBtn = document.getElementById("entdeckenBtn");
  if (entdeckenBtn) {
    entdeckenBtn.addEventListener("click", function () {
      alert("Besuche unseren Shop für die neuesten Kreationen.");
    });
  }
});
