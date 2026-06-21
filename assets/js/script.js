/* =========================================================
   ALERT BOX AUTO HIDE
========================================================= */
const alertBox = document.querySelector(".alert");

if (alertBox) {
  setTimeout(() => {
    alertBox.classList.add("hide");
    setTimeout(() => {
      alertBox.remove();
    }, 500);
  }, 3000);
}

/* =========================================================
   COPY PAYMENT
========================================================= */
function copyPayment() {
  const el = document.getElementById("paymentNumber");
  if (!el) return;

  navigator.clipboard.writeText(el.innerText);
  Swal.fire({
    icon: "success",
    title: "Berhasil",
    text: "Nomor berhasil disalin",
    timer: 1500,
    showConfirmButton: false,
  });
}

/* =========================================================
   CANCEL ORDER
========================================================= */
function cancelOrder(id) {
  Swal.fire({
    title: "Batalkan Pesanan?",
    text: "Pesanan yang dibatalkan tidak dapat diproses kembali.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#F47C48",
    cancelButtonColor: "#6C757D",
    confirmButtonText: "Ya, Batalkan",
    cancelButtonText: "Kembali",
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = "cancel_order.php?id=" + id;
    }
  });
}

/* =========================================================
   PAYMENT SELECT (SWAL)
========================================================= */
function choosePayment() {
  Swal.fire({
    title: "Pilih Metode Pembayaran",
    html: `
      <div class="swal-payment-list">
        <button type="button" class="swal-payment-btn" onclick="setPayment('DANA')">DANA</button>
        <button type="button" class="swal-payment-btn" onclick="setPayment('GOPAY')">GOPAY</button>
        <button type="button" class="swal-payment-btn" onclick="setPayment('SEABANK')">SEABANK</button>
        <button type="button" class="swal-payment-btn" onclick="setPayment('QRIS')">QRIS</button>
      </div>
    `,
    showConfirmButton: false,
  });
}

/* =========================================================
   SET PAYMENT
========================================================= */
function setPayment(method) {
  const input = document.getElementById("payment_method");
  const display = document.getElementById("selectedMethod");

  if (input) input.value = method;
  if (display) display.innerHTML = method;

  Swal.close();
}

/* =========================================================
   PAYMENT MODAL
========================================================= */
function openPaymentModal() {
  const modal = document.getElementById("paymentModal");
  if (modal) modal.classList.add("show");
}

function closePaymentModal() {
  const modal = document.getElementById("paymentModal");
  if (modal) modal.classList.remove("show");
}

window.onclick = function (e) {
  const modal = document.getElementById("paymentModal");
  if (e.target === modal) closePaymentModal();
};

/* =========================================================
   UPLOAD LOADING SYSTEM
========================================================= */
function setupUploadLoading(formId, loadingId) {
  const form = document.getElementById(formId);
  if (!form) return;

  form.addEventListener("submit", () => {
    const loading = document.getElementById(loadingId);
    if (loading) loading.style.display = "flex";
  });
}

/* upload forms */
setupUploadLoading("productUploadForm", "productLoading");
setupUploadLoading("productUpdateForm", "productLoading");
setupUploadLoading("musicUploadForm", "musicLoading");
setupUploadLoading("musicUpdateForm", "musicLoading");
setupUploadLoading("bannerUploadForm", "bannerLoading");
setupUploadLoading("bannerUpdateForm", "bannerLoading");

/* =========================================================
   DISABLE ZOOM (CTRL + SCROLL)
========================================================= */
document.addEventListener(
  "wheel",
  function (e) {
    if (e.ctrlKey) e.preventDefault();
  },
  { passive: false },
);

document.addEventListener("keydown", function (e) {
  if (e.ctrlKey && ["+", "-", "=", "0"].includes(e.key)) {
    e.preventDefault();
  }
});

/* =========================================================
   PAYMENT UPLOAD VALIDATION (DOM READY)
========================================================= */
document.addEventListener("DOMContentLoaded", () => {
  const loading = document.getElementById("paymentLoading");
  const form = document.getElementById("paymentUploadForm");

  if (loading) loading.style.display = "none";
  if (!form) return;

  form.addEventListener("submit", (e) => {
    const file = document.getElementById("proofFile").files[0];

    if (!file) {
      e.preventDefault();
      Swal.fire({ icon: "error", title: "File kosong" });
      return;
    }

    const allowed = ["image/jpeg", "image/png", "image/webp"];
    if (!allowed.includes(file.type)) {
      e.preventDefault();
      Swal.fire({ icon: "error", title: "Format tidak valid" });
      return;
    }

    if (file.size > 1024 * 1024) {
      e.preventDefault();
      Swal.fire({ icon: "error", title: "File terlalu besar" });
      return;
    }

    if (loading) loading.style.display = "flex";
  });
});

/* =========================================================
   CART TOTAL CHECKBOX
========================================================= */
const checks = document.querySelectorAll(".cart-check");
const totalEl = document.getElementById("cartTotal");

if (checks.length && totalEl) {
  function updateTotal() {
    let total = 0;
    checks.forEach((c) => {
      if (c.checked) total += parseInt(c.dataset.price || 0);
    });
    totalEl.innerText = "Rp " + total.toLocaleString("id-ID");
  }

  checks.forEach((c) => c.addEventListener("change", updateTotal));
  updateTotal();
}

/* =========================================================
   SALES CHART
========================================================= */
const canvas = document.getElementById("salesChart");

if (canvas) {
  const salesData = JSON.parse(canvas.dataset.sales || "[]");

  new Chart(canvas, {
    type: "line",
    data: {
      labels: ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"],
      datasets: [{
        label: "Penjualan",
        data: salesData,
        borderWidth: 3,
        tension: 0.4,
        fill: true,
      }],
    },
    options: { responsive: true },
  });
}

/* =========================================================
   POPUP PROFILE + PAYMENT SUCCESS
========================================================= */
if (document.getElementById("profile_success")) {
  Swal.fire({
    icon: "success",
    title: "Berhasil",
    text: "Profil berhasil diperbarui",
    timer: 2000,
    showConfirmButton: false,
  });
}

if (document.getElementById("payment_success")) {
  Swal.fire({
    icon: "success",
    title: "Pembayaran Dikirim",
    text: "Bukti pembayaran berhasil diupload",
    timer: 2000,
    showConfirmButton: false,
  });
}

const err = document.getElementById("payment_error");
if (err) {
  let msg = "Terjadi kesalahan";
  if (err.value === "nofile") msg = "Silakan pilih file terlebih dahulu";
  if (err.value === "filetype") msg = "Format file harus JPG, PNG atau WEBP";
  if (err.value === "filesize") msg = "Ukuran file maksimal 1MB";

  Swal.fire({ icon: "error", title: "Upload Gagal", text: msg });
}

/* =========================================================
   SUCCESS / ERROR FROM URL (MODULE SYSTEM)
========================================================= */
const params = new URLSearchParams(window.location.search);
const success = params.get("success");
const error = params.get("error");
const module = params.get("module");
const action = params.get("action");

if (success) {
  let text = "Proses berhasil";
  if (module === "music") text = action === "update" ? "Music berhasil diperbarui" : "Music berhasil diupload";
  if (module === "banner") text = action === "update" ? "Banner berhasil diperbarui" : "Banner berhasil diupload";
  if (module === "product") text = action === "update" ? "Produk berhasil diperbarui" : "Produk berhasil diupload";

  Swal.fire({ icon: "success", title: "Berhasil", text, timer: 2000, showConfirmButton: false });
  history.replaceState({}, document.title, window.location.pathname);
}

if (error) {
  let msg = "Terjadi kesalahan";
  if (module === "music") {
    if (error === "music_type") msg = "File harus MP3";
    else if (error === "music_size") msg = "Maksimal 15MB";
    else if (error === "cover_type") msg = "Cover tidak valid";
    else if (error === "cover_size") msg = "Cover maksimal 1MB";
  }
  if (module === "banner") {
    if (error === "image_type") msg = "Format gambar tidak valid";
    else if (error === "image_size") msg = "Gambar maksimal 1MB";
  }

  if (module === "product") {
    if (error === "filetype") msg = "Format file tidak valid";
    else if (error === "filesize") msg = "File terlalu besar";
  }

  Swal.fire({ icon: "error", title: "Upload Gagal", text: msg });
  history.replaceState({}, document.title, window.location.pathname);
}

/* =========================================================
   PAYMENT CHANGE POPUP
========================================================= */
const changePayment = document.getElementById("payment_change");
if (changePayment) {
  Swal.fire({
    icon: "success",
    title: "Berhasil",
    text: "Metode pembayaran berhasil diubah",
    timer: 2500,
    showConfirmButton: false,
  });
}

/* =========================================================
   CART VALIDATION
========================================================= */
const cartForm = document.getElementById("cartForm");
if (cartForm) {
  cartForm.addEventListener("submit", (e) => {
    const checkedProducts = document.querySelectorAll(".cart-check:checked");
    if (checkedProducts.length === 0) {
      e.preventDefault();
      return Swal.fire({
        icon: "warning",
        title: "Pilih Produk",
        text: "Pilih minimal 1 produk sebelum checkout",
        timer: 3000,
        showConfirmButton: false,
      });
    }

    const addressComplete = document.getElementById("address_complete");
    if (addressComplete && addressComplete.value === "0") {
      e.preventDefault();
      return Swal.fire({
        icon: "warning",
        title: "Alamat Belum Lengkap",
        text: "Silakan lengkapi alamat terlebih dahulu",
        timer: 3000,
        showConfirmButton: false,
      });
    }

    const paymentMethod = document.getElementById("payment_method");
    if (paymentMethod && paymentMethod.value === "") {
      e.preventDefault();
      return Swal.fire({
        icon: "warning",
        title: "Metode Pembayaran",
        text: "Silakan pilih metode pembayaran",
        timer: 3000,
        showConfirmButton: false,
      });
    }
  });
}

/* =========================================================
   COPY PAYMENT NUMBER
========================================================= */
const copyPaymentBtn = document.getElementById("copyPaymentBtn");
if (copyPaymentBtn) {
  copyPaymentBtn.addEventListener("click", () => {
    const paymentNumber = document.getElementById("paymentNumber");
    if (!paymentNumber) return;

    navigator.clipboard.writeText(paymentNumber.innerText);
    Swal.fire({
      icon: "success",
      title: "Berhasil",
      text: "Nomor pembayaran berhasil disalin",
      timer: 2000,
      showConfirmButton: false,
    });
  });
}

/* =========================================================
   BANNER SLIDER
========================================================= */
const wrapper = document.querySelector(".slider-wrapper");
const slides = document.querySelectorAll(".banner-slide");
if (wrapper && slides.length > 1) {
  let current = 0;
  setInterval(() => {
    current = (current + 1) % slides.length;
    wrapper.style.transform = `translateX(-${current * 100}%)`;
  }, 5000);
}

/* =========================================================
   MUSIC THEME
========================================================= */
const toggle = document.getElementById("themeToggle");
if (toggle) {
  if (localStorage.getItem("musicTheme") === "dark") {
    document.body.classList.add("music-dark");
  }
  toggle.addEventListener("click", () => {
    document.body.classList.toggle("music-dark");
    localStorage.setItem(
      "musicTheme",
      document.body.classList.contains("music-dark") ? "dark" : "light",
    );
  });
}

/* =========================================================
   ADMIN PIN & BLOCKED
========================================================= */
const inputs = document.querySelectorAll(".pin-input");
const realPin = document.getElementById("realPin");
if (inputs.length && realPin) {
  inputs.forEach((input, index) => {
    input.addEventListener("input", () => {
      if (input.value.length === 1 && index < inputs.length - 1) {
        inputs[index + 1].focus();
      }
      let pin = "";
      inputs.forEach((i) => { pin += i.value; });
      realPin.value = pin;
    });
    input.addEventListener("keydown", (e) => {
      if (e.key === "Backspace" && !input.value && index > 0) {
        inputs[index - 1].focus();
      }
    });
  });
}

/* =========================================================
   MUSIC NAV SCROLL
========================================================= */
document.querySelectorAll(".music-nav").forEach((nav) => {
  const slider = nav.nextElementSibling;
  if (!slider) return;
  const prev = nav.querySelector(".music-prev");
  const next = nav.querySelector(".music-next");
  if (next) next.addEventListener("click", () => slider.scrollBy({ left: 350, behavior: "smooth" }));
  if (prev) prev.addEventListener("click", () => slider.scrollBy({ left: -350, behavior: "smooth" }));
});

/* =========================================================
   FAVORITE BUTTON
========================================================= */
document.querySelectorAll(".favorite-btn").forEach((btn) => {
  btn.addEventListener("click", () => {
    fetch("toggle_favorite.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: "music_id=" + btn.dataset.id,
    }).then(() => {
      btn.classList.toggle("active");
    });
  });
});

/* =========================================================
   MUSIC PLAYER
========================================================= */
const audio = document.getElementById("globalAudio");
const playBtns = document.querySelectorAll(".play-music-btn");

if (audio && playBtns.length > 0) {
  const spotifyPlayer = document.getElementById("spotifyPlayer");
  const playPauseBtn = document.getElementById("playPauseBtn");
  const progressBar = document.getElementById("progressBar");
  const currentTime = document.getElementById("currentTime");
  const duration = document.getElementById("duration");
  const volumeControl = document.getElementById("volumeControl");
  const speedControl = document.getElementById("speedControl");
  const downloadBtn = document.getElementById("downloadBtn");
  const spFavoriteBtn = document.getElementById("spFavoriteBtn");

  let currentIndex = -1;
  const songs = [];

  function formatTime(sec) {
    let m = Math.floor(sec / 60);
    let s = Math.floor(sec % 60);
    return m + ":" + String(s).padStart(2, "0");
  }

  function resetPlayButtons() {
    document.querySelectorAll(".play-music-btn").forEach((btn) => {
      btn.innerHTML = '<i class="fa-solid fa-play"></i>';
    });
  }

  function loadSong(index) {
    currentIndex = index;
    const btn = songs[index];
    spotifyPlayer?.classList.add("active");
    resetPlayButtons();
    btn.innerHTML = '<i class="fa-solid fa-pause"></i>';

    audio.src = btn.dataset.audio;
    document.getElementById("spCover").src = btn.dataset.cover;
    document.getElementById("spTitle").innerHTML = `<span>${btn.dataset.title}</span>`;
    document.getElementById("spArtist").innerText = btn.dataset.artist;
    spFavoriteBtn.dataset.id = btn.dataset.musicId || btn.dataset.index;

    if (btn.dataset.favorite == "1") {
      spFavoriteBtn.classList.add("active");
      spFavoriteBtn.innerHTML = '<i class="fa-solid fa-heart"></i>';
    } else {
      spFavoriteBtn.classList.remove("active");
      spFavoriteBtn.innerHTML = '<i class="fa-regular fa-heart"></i>';
    }

    audio.play();
    playPauseBtn.innerHTML = '<i class="fa-solid fa-pause"></i>';
  }

  playBtns.forEach((btn, index) => {
    songs.push(btn);
    btn.addEventListener("click", () => {
      if (currentIndex === index) {
        if (audio.paused) audio.play();
        else audio.pause();
        return;
      }
      loadSong(index);
    });
  });

  playPauseBtn?.addEventListener("click", () => {
    if (audio.paused) {
      audio.play();
      playPauseBtn.innerHTML = '<i class="fa-solid fa-pause"></i>';
    } else {
      audio.pause();
      playPauseBtn.innerHTML = '<i class="fa-solid fa-play"></i>';
    }
  });

  document.getElementById("nextBtn")?.addEventListener("click", () => {
    let next = (currentIndex + 1) % songs.length;
    loadSong(next);
  });

  document.getElementById("prevBtn")?.addEventListener("click", () => {
    let prev = currentIndex - 1 < 0 ? songs.length - 1 : currentIndex - 1;
    loadSong(prev);
  });

  audio.addEventListener("timeupdate", () => {
    if (!audio.duration) return;
    progressBar.value = (audio.currentTime / audio.duration) * 100;
    currentTime.innerText = formatTime(audio.currentTime);
  });

  audio.addEventListener("loadedmetadata", () => {
    duration.innerText = formatTime(audio.duration);
  });

  audio.addEventListener("play", () => {
    playPauseBtn.innerHTML = '<i class="fa-solid fa-pause"></i>';
    resetPlayButtons();
    if (currentIndex >= 0 && songs[currentIndex]) {
      songs[currentIndex].innerHTML = '<i class="fa-solid fa-pause"></i>';
    }
  });

  audio.addEventListener("pause", () => {
    playPauseBtn.innerHTML = '<i class="fa-solid fa-play"></i>';
    resetPlayButtons();
  });

  audio.addEventListener("ended", () => {
    playPauseBtn.innerHTML = '<i class="fa-solid fa-play"></i>';
    resetPlayButtons();
  });

  progressBar?.addEventListener("input", () => {
    audio.currentTime = (progressBar.value / 100) * audio.duration;
  });

  volumeControl?.addEventListener("input", () => {
    audio.volume = volumeControl.value;
  });

  speedControl?.addEventListener("change", () => {
    audio.playbackRate = parseFloat(speedControl.value);
  });

  downloadBtn?.addEventListener("click", () => {
    if (audio.src) window.open(audio.src, "_blank");
  });

  spFavoriteBtn?.addEventListener("click", function () {
    const musicId = this.dataset.id;
    if (!musicId) return;
    fetch("toggle_favorite.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: "music_id=" + musicId,
    }).then(() => {
      this.classList.toggle("active");
      document.querySelectorAll(".favorite-btn").forEach((btn) => {
        if (btn.dataset.id == musicId) btn.classList.toggle("active");
      });
    }).catch((err) => console.error(err));
  });
}

/* =========================================================
   INPUT COUNTERS
========================================================= */
function bindCounter(inputId, counterId, max, options = {}) {
  const input = document.getElementById(inputId);
  const counter = document.getElementById(counterId);
  if (!input || !counter) return;

  const { numeric = false, noSpace = false, colorThresholds = [] } = options;

  const update = () => {
    let val = input.value;
    if (noSpace) val = val.replace(/\s/g, "");
    if (numeric) val = val.replace(/\D/g, "");
    if (val.length > max) val = val.slice(0, max);
    input.value = val;
    counter.innerText = `${val.length}/${max} karakter`;

    let color = "#777";
    for (const t of colorThresholds) {
      if (val.length >= t.min) color = t.color;
    }
    counter.style.color = color;
  };

  update();
  input.addEventListener("input", update);
}

bindCounter("fullname", "nameCounter", 50, {
  colorThresholds: [{ min: 40, color: "#ff9800" }, { min: 50, color: "#e53935" }],
});
bindCounter("age", "ageCounter", 2, {
  numeric: true,
  colorThresholds: [{ min: 2, color: "#e53935" }],
});
bindCounter("email", "emailCounter", 30, {
  noSpace: true,
  colorThresholds: [{ min: 25, color: "#ff9800" }, { min: 30, color: "#e53935" }],
});
bindCounter("phone", "phoneCounter", 13, {
  numeric: true,
  colorThresholds: [{ min: 10, color: "#ff9800" }, { min: 13, color: "#e53935" }],
});

/* =========================================================
   MAP PICKER
========================================================= */
const mapDiv = document.getElementById("map");
const currentBtn = document.getElementById("currentLocationBtn");
if (mapDiv && window.L) {
  const map = L.map("map").setView([-6.2, 106.8], 13);
  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution: "© OpenStreetMap",
  }).addTo(map);

  let marker;
  const setVal = (id, val) => {
    const el = document.getElementById(id);
    if (el) el.value = val;
  };

  function setMarker(lat, lng) {
    if (marker) marker.setLatLng([lat, lng]);
    else marker = L.marker([lat, lng]).addTo(map);
  }

  async function reverseGeocode(lat, lng) {
    try {
      const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`);
      const data = await res.json();
      const a = data.address || {};
      setVal("province", a.state || "");
      setVal("city", a.city || a.county || a.town || "");
      setVal("district", a.suburb || a.city_district || "");
      setVal("village", a.village || a.hamlet || "");
      setVal("street", a.road || "");
    } catch (err) {
      console.error("Reverse geocode error:", err);
    }
  }

    map.on("click", async (e) => {
    const { lat, lng } = e.latlng;
    setMarker(lat, lng);
    await reverseGeocode(lat, lng);
  });

  if (currentBtn) {
    currentBtn.addEventListener("click", () => {
      navigator.geolocation.getCurrentPosition(
        async (pos) => {
          const lat = pos.coords.latitude;
          const lng = pos.coords.longitude;
          map.setView([lat, lng], 16);
          setMarker(lat, lng);
          setVal("latitude", lat);
          setVal("longitude", lng);
          await reverseGeocode(lat, lng);
        },
        () => {
          alert("Izinkan akses lokasi terlebih dahulu");
        },
      );
    });
  }
}

/* =========================================================
   PAYMENT UPLOAD VALIDATION (FINAL)
========================================================= */
const fileInput = document.getElementById("proofFile");
const form = document.getElementById("paymentUploadForm");
const loading = document.getElementById("paymentLoading");

if (form && fileInput) {
  form.addEventListener("submit", (e) => {
    const file = fileInput.files?.[0];
    if (!file) {
      e.preventDefault();
      return Swal.fire({
        icon: "error",
        title: "File kosong",
        text: "Pilih bukti pembayaran terlebih dahulu",
      });
    }

    const allowedTypes = ["image/jpeg", "image/png", "image/webp"];
    if (!allowedTypes.includes(file.type)) {
      e.preventDefault();
      return Swal.fire({
        icon: "error",
        title: "Format tidak didukung",
        text: "Hanya JPG, PNG, atau WEBP",
      });
    }

    if (file.size > 1024 * 1024) {
      e.preventDefault();
      return Swal.fire({
        icon: "error",
        title: "File terlalu besar",
        text: "Maksimal 1 MB",
      });
    }

    if (loading) loading.style.display = "flex";
  });
}
