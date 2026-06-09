/* script.js – Glücksrad, Slider, Parallax, Warenkorb, Formular mit API */

// ===== AKTIVE NAVIGATION =====
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

// ===== HAMBURGER MENÜ =====
document.addEventListener("DOMContentLoaded", () => {
  const hamburger = document.querySelector(".hamburger");
  const nav = document.querySelector(".nav-links");
  hamburger?.addEventListener("click", (e) => {
    e.stopPropagation();
    hamburger.classList.toggle("active");
    nav.classList.toggle("open");
  });
});

// ===== IMAGE SLIDER (nur auf index.html) =====
document.addEventListener("DOMContentLoaded", () => {
  const track = document.getElementById("sliderTrack");
  if (!track) return;
  const slides = document.querySelectorAll(".slider-slide");
  const prevBtn = document.getElementById("prevBtn");
  const nextBtn = document.getElementById("nextBtn");
  const dotsContainer = document.getElementById("sliderDots");
  if (!slides.length) return;

  let currentIndex = 0;
  const totalSlides = slides.length;

  function createDots() {
    if (!dotsContainer) return;
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
    if (!dotsContainer) return;
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

// ===== PARALLAX-HINTERGRUND =====
document.addEventListener("DOMContentLoaded", () => {
  const parallaxBg = document.getElementById("parallaxBg");
  if (!parallaxBg) return;
  function updateParallax() {
    const scrollY = window.scrollY;
    parallaxBg.style.backgroundPosition = `center ${scrollY * 0.3}px`;
    const opacity = Math.min(0.95, 0.85 + scrollY * 0.0002);
    parallaxBg.style.backgroundColor = `rgba(253, 244, 232, ${opacity})`;
  }
  window.addEventListener("scroll", updateParallax);
  updateParallax();
});

// ===== FORMULARVALIDIERUNG & API (Gewinnspiel) =====
/*
   Validierungsregeln (nur als Kommentar, nicht sichtbar):
   - Vorname: Pflicht, min. 2 Buchstaben (Buchstaben, Leerzeichen, Bindestrich)
   - Nachname: Pflicht, min. 2 Buchstaben
   - E-Mail: Pflicht, gültiges Format
   - Quelle: Auswahl nötig (nicht leer)
   - AGB: Checkbox akzeptiert
*/
document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("contestForm");
  if (!form) return;

  function showError(id, msg) {
    const el = document.getElementById(id);
    if (el) el.innerText = msg;
  }
  function clearErrors() {
    [
      "errorVorname",
      "errorNachname",
      "errorEmail",
      "errorReferrer",
      "errorTerms",
    ].forEach((id) => showError(id, ""));
  }

  function validateForm() {
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
    return valid;
  }

  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    clearErrors();
    if (!validateForm()) {
      document.getElementById("formFeedback").innerHTML =
        '<div style="color:#b33;">⚠️ Bitte korrigieren.</div>';
      return;
    }
    const formData = new FormData(form);
    formData.append(
      "terms",
      document.getElementById("terms").checked ? "true" : "false",
    );

    try {
      const response = await fetch("submit_gewinnspiel.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: formData,
      });
      const feedback = document.getElementById("formFeedback");

      const text = await response.text();
      let result = {};
      try {
        result = text ? JSON.parse(text) : {};
      } catch (parseErr) {
        feedback.innerHTML = `<div style="color:#b33;">⚠️ Serverfehler. Ungültige Server-Antwort: ${text}</div>`;
        setTimeout(() => (feedback.innerHTML = ""), 5000);
        return;
      }

      if (!response.ok) {
        const errMsg =
          result && (result.error || result.message)
            ? Array.isArray(result.error)
              ? result.error.join(", ")
              : result.error || result.message
            : response.statusText || "Fehler";
        feedback.innerHTML = `<div style="color:#b33;">⚠️ ${errMsg}</div>`;
      } else if (result.success) {
        feedback.innerHTML = `<div class="success-msg">✅ ${result.message}</div>`;
        form.reset();
      } else {
        let errMsg = Array.isArray(result.error)
          ? result.error.join(", ")
          : result.error || "Fehler";
        feedback.innerHTML = `<div style="color:#b33;">⚠️ ${errMsg}</div>`;
      }
      setTimeout(() => (feedback.innerHTML = ""), 5000);
    } catch (err) {
      document.getElementById("formFeedback").innerHTML =
        `<div style="color:#b33;">⚠️ Serverfehler. ${err.message || err}.</div>`;
    }
  });
});

// ===== GLÜCKSRAD (korrigiert) =====
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

// ===== WARENKORB & BESTELLUNG (mit produkt_id) =====
document.addEventListener("DOMContentLoaded", () => {
  let cart = [];

  const cartBtn = document.getElementById("cartBtn");
  const cartModal = document.getElementById("cartModal");
  const closeModal = document.querySelector(".close-modal");
  const cartItemsDiv = document.getElementById("cartItems");
  const cartTotalSpan = document.getElementById("cartTotal");
  const cartCountSpan = document.getElementById("cartCount");

  function loadCart() {
    const saved = localStorage.getItem("flavrCart");
    cart = saved ? JSON.parse(saved) : [];
    updateCartUI();
  }

  function saveCart() {
    localStorage.setItem("flavrCart", JSON.stringify(cart));
  }

  function updateCartUI() {
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    if (cartCountSpan) cartCountSpan.innerText = totalItems;
    if (cartItemsDiv) {
      if (cart.length === 0) {
        cartItemsDiv.innerHTML = "<p>Dein Warenkorb ist leer.</p>";
        if (cartTotalSpan) cartTotalSpan.innerText = "0.00";
        return;
      }
      let html = '<div class="cart-items-list">';
      let total = 0;
      cart.forEach((item) => {
        const subtotal = item.price * item.quantity;
        total += subtotal;
        html += `
          <div class="cart-item" data-id="${item.produkt_id}">
            <img src="${item.image}" alt="${item.name}" width="50">
            <div class="cart-item-details"><strong>${item.name}</strong><br>${item.price.toFixed(2)} CHF</div>
            <div class="cart-item-quantity">
              <button class="qty-minus" data-id="${item.produkt_id}">-</button>
              <span>${item.quantity}</span>
              <button class="qty-plus" data-id="${item.produkt_id}">+</button>
            </div>
            <div class="cart-item-subtotal">${subtotal.toFixed(2)} CHF</div>
            <button class="cart-item-remove" data-id="${item.produkt_id}">🗑️</button>
          </div>
        `;
      });
      html += "</div>";
      cartItemsDiv.innerHTML = html;
      if (cartTotalSpan) cartTotalSpan.innerText = total.toFixed(2);
      document
        .querySelectorAll(".qty-minus")
        .forEach((btn) =>
          btn.addEventListener("click", () =>
            changeQuantity(btn.dataset.id, -1),
          ),
        );
      document
        .querySelectorAll(".qty-plus")
        .forEach((btn) =>
          btn.addEventListener("click", () =>
            changeQuantity(btn.dataset.id, 1),
          ),
        );
      document
        .querySelectorAll(".cart-item-remove")
        .forEach((btn) =>
          btn.addEventListener("click", () => removeFromCart(btn.dataset.id)),
        );
    }
  }

  function changeQuantity(id, delta) {
    const index = cart.findIndex((item) => item.produkt_id == id);
    if (index !== -1) {
      const newQty = cart[index].quantity + delta;
      if (newQty <= 0) cart.splice(index, 1);
      else cart[index].quantity = newQty;
      saveCart();
      updateCartUI();
    }
  }

  function removeFromCart(id) {
    cart = cart.filter((item) => item.produkt_id != id);
    saveCart();
    updateCartUI();
  }

  function addToCart(product) {
    const existing = cart.find(
      (item) => item.produkt_id === product.produkt_id,
    );
    if (existing) existing.quantity += 1;
    else cart.push({ ...product, quantity: 1 });
    saveCart();
    updateCartUI();
    const btn = document.querySelector(
      `.product-card[data-produkt-id="${product.produkt_id}"] .add-to-cart`,
    );
    if (btn) {
      const original = btn.innerText;
      btn.innerText = "✓ Hinzugefügt!";
      setTimeout(() => (btn.innerText = original), 1000);
    }
  }

  document.querySelectorAll(".add-to-cart").forEach((btn) => {
    btn.addEventListener("click", () => {
      const card = btn.closest(".product-card");
      if (!card) return;
      const produkt_id = parseInt(card.dataset.produktId);
      const name = card.dataset.name;
      const price = parseFloat(card.dataset.price);
      const image = card.dataset.image;
      addToCart({ produkt_id, name, price, image });
    });
  });

  if (cartBtn && cartModal) {
    cartBtn.addEventListener("click", () => {
      updateCartUI();
      cartModal.style.display = "flex";
    });
  }
  if (closeModal && cartModal) {
    closeModal.addEventListener(
      "click",
      () => (cartModal.style.display = "none"),
    );
    window.addEventListener("click", (e) => {
      if (e.target === cartModal) cartModal.style.display = "none";
    });
  }

  // Bestellung abschicken (mit produkt_id)
  const checkoutForm = document.getElementById("checkoutForm");
  if (checkoutForm) {
    checkoutForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      const name = document.getElementById("checkoutName").value.trim();
      const email = document.getElementById("checkoutEmail").value.trim();
      const adresse = document.getElementById("checkoutAdresse").value.trim();
      const plz = document.getElementById("checkoutPLZ").value.trim();
      const telefon = document.getElementById("checkoutTel").value.trim();
      const zahlungsart = document.getElementById("checkoutPayment").value;
      if (!name || !email || !adresse || !plz) {
        alert("Bitte füllen Sie alle Pflichtfelder aus.");
        return;
      }
      if (!/^[^\s@]+@([^\s@]+\.)+[^\s@]+$/.test(email)) {
        alert("Ungültige E-Mail.");
        return;
      }
      if (cart.length === 0) {
        alert("Warenkorb ist leer.");
        return;
      }
      const produkte = cart.map((item) => ({
        produkt_id: item.produkt_id,
        anzahl: item.quantity,
      }));
      const total = cart.reduce((sum, i) => sum + i.price * i.quantity, 0);
      const payload = {
        kunde: { name, email, adresse, postleitzahl: plz, telefon },
        zahlungsart,
        gesamtpreis: total,
        produkte,
      };
      try {
        const resp = await fetch("submit_bestellung.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(payload),
        });
        const result = await resp.json();
        const feedback = document.getElementById("checkoutFeedback");
        if (result.success) {
          feedback.innerHTML = `<div class="success-msg">✅ ${result.message} (Bestell-Nr. ${result.bestellung_id})</div>`;
          localStorage.removeItem("flavrCart");
          cart = [];
          updateCartUI();
          checkoutForm.reset();
          setTimeout(() => {
            cartModal.style.display = "none";
            feedback.innerHTML = "";
          }, 3000);
        } else {
          feedback.innerHTML = `<div style="color:#b33;">⚠️ ${result.error || "Fehler"}</div>`;
        }
      } catch (err) {
        document.getElementById("checkoutFeedback").innerHTML =
          '<div style="color:#b33;">⚠️ Serverfehler. Bitte später erneut versuchen.</div>';
      }
    });
  }
  loadCart();
});

// ===== ZUSÄTZLICHE EVENT-LISTENER (für index.html) =====
document.addEventListener("DOMContentLoaded", () => {
  document
    .getElementById("moreInfoBtn")
    ?.addEventListener("click", () =>
      alert("Erfahre mehr über unsere Gewürze."),
    );
  document
    .getElementById("interessantBtn")
    ?.addEventListener("click", () => alert("Danke fürs Feedback!"));
  document
    .getElementById("effizientBtn")
    ?.addEventListener("click", () => alert("Schneller Versand – garantiert."));
});
