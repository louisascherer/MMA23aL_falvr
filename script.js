/* script.js – Glücksrad, Formular, Slider, Parallax */

// Aktive Navigation
document.addEventListener("DOMContentLoaded", () => {
  const currentPath = window.location.pathname;
  document.querySelectorAll(".nav-links a").forEach((link) => {
    const href = link.getAttribute("href");
    if (currentPath.includes(href) && href !== "index.html")
      link.classList.add("active");
    else if (
      (currentPath.endsWith("/") || currentPath.endsWith("index.html")) &&
      href === "index.html"
    )
      link.classList.add("active");
  });
});

// Hamburger-Menü
document.addEventListener("DOMContentLoaded", () => {
  const hamburger = document.querySelector(".hamburger");
  const nav = document.querySelector(".nav-links");
  hamburger?.addEventListener("click", (e) => {
    e.stopPropagation();
    hamburger.classList.toggle("active");
    nav.classList.toggle("open");
  });
});

// ===== IMAGE SLIDER =====
document.addEventListener("DOMContentLoaded", () => {
  const track = document.getElementById("sliderTrack");
  const slides = document.querySelectorAll(".slider-slide");
  const prevBtn = document.getElementById("prevBtn");
  const nextBtn = document.getElementById("nextBtn");
  const dotsContainer = document.getElementById("sliderDots");
  if (!track || !slides.length) return;

  let currentIndex = 0;
  const totalSlides = slides.length;

  function createDots() {
    dotsContainer.innerHTML = "";
    for (let i = 0; i < totalSlides; i++) {
      const dot = document.createElement("div");
      dot.classList.add("dot");
      if (i === currentIndex) dot.classList.add("active");
      dot.addEventListener("click", () => goToSlide(i));
      dotsContainer.appendChild(dot);
    }
  }

  function updateDots() {
    document.querySelectorAll(".dot").forEach((dot, idx) => {
      if (idx === currentIndex) dot.classList.add("active");
      else dot.classList.remove("active");
    });
  }

  function goToSlide(index) {
    if (index < 0) index = totalSlides - 1;
    if (index >= totalSlides) index = 0;
    currentIndex = index;
    track.style.transform = `translateX(-${currentIndex * 100}%)`;
    updateDots();
  }

  function nextSlide() {
    goToSlide(currentIndex + 1);
  }

  function prevSlide() {
    goToSlide(currentIndex - 1);
  }

  nextBtn?.addEventListener("click", nextSlide);
  prevBtn?.addEventListener("click", prevSlide);
  createDots();
});

// ===== PARALLAX-HINTERGRUND (scrollt mit) =====
document.addEventListener("DOMContentLoaded", () => {
  const parallaxBg = document.getElementById("parallaxBg");
  if (!parallaxBg) return;

  function updateParallax() {
    const scrollY = window.scrollY;
    // Hintergrund leicht verschieben
    parallaxBg.style.backgroundPosition = `center ${scrollY * 0.3}px`;
    // Leichte Farbänderung beim Scrollen
    const opacity = Math.min(0.95, 0.85 + scrollY * 0.0002);
    parallaxBg.style.backgroundColor = `rgba(253, 244, 232, ${opacity})`;
  }

  window.addEventListener("scroll", updateParallax);
  updateParallax();
});

// ===== FORMULARVALIDIERUNG (Regeln als Kommentar) =====
/*
   Validierungsregeln:
   - Vorname: Pflicht, min. 2 Buchstaben (Buchstaben, Leerzeichen, Bindestrich)
   - Nachname: Pflicht, min. 2 Buchstaben
   - E-Mail: Pflicht, gültiges Format
   - Quelle: Auswahl nötig (nicht leer)
   - AGB: Checkbox akzeptiert
*/
document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("contestForm");
  if (!form) return;

  const showError = (id, msg) => {
    const el = document.getElementById(id);
    if (el) el.innerText = msg;
  };
  const clearErrors = () =>
    [
      "errorVorname",
      "errorNachname",
      "errorEmail",
      "errorReferrer",
      "errorTerms",
    ].forEach((id) => showError(id, ""));

  form.addEventListener("submit", (e) => {
    e.preventDefault();
    clearErrors();
    let valid = true;
    const vorname = document.getElementById("vorname").value.trim();
    const nachname = document.getElementById("nachname").value.trim();
    const email = document.getElementById("email").value.trim();
    const referrer = document.getElementById("referrer").value;
    const terms = document.getElementById("terms").checked;

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

    const emailPattern = /^[^\s@]+@([^\s@]+\.)+[^\s@]+$/;
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

    const feedback = document.getElementById("formFeedback");
    if (valid) {
      feedback.innerHTML =
        '<div class="success-msg">✅ Teilnahme erfolgreich! Viel Glück.</div>';
      form.reset();
      setTimeout(() => (feedback.innerHTML = ""), 4000);
    } else {
      feedback.innerHTML =
        '<div style="color:#b33; margin-top:1rem;">⚠️ Bitte korrigieren.</div>';
    }
  });
});

// ===== GLÜCKSRAD (korrigierte Preiszuordnung) =====
document.addEventListener("DOMContentLoaded", () => {
  const canvas = document.getElementById("wheelCanvas");
  if (!canvas) return;

  const ctx = canvas.getContext("2d");
  const size = 400;
  canvas.width = canvas.height = size;

  const segments = [
    { label: "Gratis\nGewürz", color: "#f7c9a3" },
    { label: "10%\nRabatt", color: "#f0bb87" },
    { label: "5%\nRabatt", color: "#eaa96e" },
    { label: "Gratis\nVersand", color: "#e29656" },
    { label: "Nichts\ngetroffen", color: "#d97f41" },
    { label: "15%\nRabatt", color: "#cf6d2b" },
  ];
  const segCount = segments.length;
  const angleStep = (Math.PI * 2) / segCount;
  let currentRot = 0,
    spinning = false,
    spinStart = 0,
    startRot = 0,
    targetRot = 0;
  const spinDuration = 1500;

  function drawWheel(rot) {
    ctx.clearRect(0, 0, size, size);
    const cx = size / 2,
      cy = size / 2,
      r = size * 0.42;
    for (let i = 0; i < segCount; i++) {
      const start = i * angleStep + rot;
      const end = (i + 1) * angleStep + rot;
      ctx.beginPath();
      ctx.moveTo(cx, cy);
      ctx.arc(cx, cy, r, start, end);
      ctx.fillStyle = segments[i].color;
      ctx.fill();
      ctx.save();
      ctx.translate(cx, cy);
      ctx.rotate(start + angleStep / 2);
      ctx.font = "bold 12px 'Inter'";
      ctx.fillStyle = "#2c2418";
      ctx.textAlign = "center";
      ctx.textBaseline = "middle";
      const lines = segments[i].label.split("\n");
      for (let l = 0; l < lines.length; l++) {
        ctx.fillText(
          lines[l],
          r * 0.68,
          (lines.length === 2 ? -4 : 0) + l * 14,
        );
      }
      ctx.restore();
    }
    // Pfeil
    ctx.beginPath();
    ctx.moveTo(cx + 18, cy - r - 8);
    ctx.lineTo(cx - 18, cy - r - 8);
    ctx.lineTo(cx, cy - r + 12);
    ctx.fillStyle = "#3e2c1c";
    ctx.fill();
    ctx.beginPath();
    ctx.arc(cx, cy, r * 0.08, 0, 2 * Math.PI);
    ctx.fillStyle = "#d9b48b";
    ctx.fill();
  }

  function getPrizeIndex(rot) {
    const pointer = -Math.PI / 2;
    let angle = pointer;
    while (angle < 0) angle += 2 * Math.PI;
    angle %= 2 * Math.PI;
    for (let i = 0; i < segCount; i++) {
      let start = (i * angleStep + rot) % (2 * Math.PI);
      let end = ((i + 1) * angleStep + rot) % (2 * Math.PI);
      if (start < end) {
        if (angle >= start && angle < end) return i;
      } else {
        if (angle >= start || angle < end) return i;
      }
    }
    return 0;
  }

  function spin() {
    if (spinning) return;
    const rand = Math.floor(Math.random() * segCount);
    const targetMid = rand * angleStep + angleStep / 2;
    const pointer = -Math.PI / 2;
    let delta = pointer - targetMid - currentRot;
    delta = ((delta % (2 * Math.PI)) + 2 * Math.PI) % (2 * Math.PI);
    const full = 6 + Math.floor(Math.random() * 8);
    targetRot = currentRot + delta + full * 2 * Math.PI;
    startRot = currentRot;
    spinStart = performance.now();
    spinning = true;
    function animate(now) {
      const t = Math.min(1, (now - spinStart) / spinDuration);
      const ease = 1 - Math.pow(1 - t, 3);
      const newRot = startRot + (targetRot - startRot) * ease;
      drawWheel(newRot);
      if (t < 1) requestAnimationFrame(animate);
      else {
        spinning = false;
        currentRot = targetRot % (2 * Math.PI);
        drawWheel(currentRot);
        const prize = segments[getPrizeIndex(currentRot)].label.replace(
          /\n/g,
          " ",
        );
        alert(
          `🎉 Ergebnis: ${prize}! ${prize.includes("Nichts") ? "Schade, nächste Mal mehr Glück!" : "Herzlichen Glückwunsch! Code per E-Mail."}`,
        );
      }
    }
    requestAnimationFrame(animate);
  }
  document.getElementById("spinWheelBtn")?.addEventListener("click", spin);
  drawWheel(currentRot);
});
