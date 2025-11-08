/* script.js - SafeSpace
   Shared JS for all pages: navbar toggle, modals, fake geolocation,
   SOS timer, form validations, map tooltips, dashboard tabs, and alerts.
   Author: Rohan
*/

document.addEventListener('DOMContentLoaded', () => {
  // NAVBAR TOGGLE (mobile)
  const burger = document.querySelector('.hamburger');
  const nav = document.querySelector('.nav');
  if (burger && nav) {
    burger.addEventListener('click', () => nav.classList.toggle('open'));
  }

  // GENERIC MODAL HANDLER
  function openModal(id) {
    const backdrop = document.querySelector(`#${id}`);
    if (!backdrop) return;
    backdrop.style.display = 'flex';
    const modal = backdrop.querySelector('.modal');
    setTimeout(()=> modal.classList.add('show'), 20);
  }
  function closeModal(id) {
    const backdrop = document.querySelector(`#${id}`);
    if (!backdrop) return;
    const modal = backdrop.querySelector('.modal');
    modal.classList.remove('show');
    setTimeout(()=> backdrop.style.display = 'none', 220);
  }
  // Close buttons
  document.querySelectorAll('.modal-backdrop .close-x').forEach(btn=>{
    btn.addEventListener('click', (e)=>{
      const backdrop = e.target.closest('.modal-backdrop');
      backdrop && closeModal(backdrop.id);
    });
  });
  // Close on backdrop click
  document.querySelectorAll('.modal-backdrop').forEach(back=>{
    back.addEventListener('click', (e)=>{
      if (e.target === back) closeModal(back.id);
    })
  });

  /* =======================
     LOGIN / REGISTER
     ======================= */
  /*const loginForm = document.querySelector('#loginForm');
  if (loginForm) {
    loginForm.addEventListener('submit', (e)=>{
      e.preventDefault();
      // Fake validation
      const email = loginForm.querySelector('input[name="email"]').value.trim();
      const pass = loginForm.querySelector('input[name="password"]').value.trim();
      if (!email || !pass) {
        showToast('Please fill all fields', 'error');
        return;
      }
      // Fake success
      showToast('Welcome back!', 'success');
      setTimeout(()=> {
        window.location.href = 'dashboard.html';
      }, 900);
    });
  }

  const regForm = document.querySelector('#registerForm');
  if (regForm) {
    regForm.addEventListener('submit', (e)=>{
      e.preventDefault();
      const name = regForm.querySelector('input[name="name"]').value.trim();
      const email = regForm.querySelector('input[name="email"]').value.trim();
      const p1 = regForm.querySelector('input[name="password"]').value;
      const p2 = regForm.querySelector('input[name="confirm"]').value;
      if (!name || !email || !p1 || !p2) {
        showToast('Complete the form', 'error'); return;
      }
      if (p1.length < 6) { showToast('Password must be 6+ chars', 'error'); return; }
      if (p1 !== p2) { showToast('Passwords do not match', 'error'); return; }

      // success modal
      const rb = document.querySelector('#registerModal');
      if (rb) openModal('registerModal');
      // reset
      regForm.reset();
    });
  }*/

  // REPORT FORM (fake geolocation)
  const reportForm = document.querySelector('#reportForm');
  if (reportForm) {
    reportForm.addEventListener('submit', (e)=>{
      e.preventDefault();
      const desc = reportForm.querySelector('textarea[name="desc"]').value.trim();
      if (!desc) { showToast('Please provide a description', 'error'); return; }
      openModal('reportModal');
      reportForm.reset();
    });
    // get location simulation
    const getLocBtn = document.querySelector('#getLocationBtn');
    if (getLocBtn) {
      getLocBtn.addEventListener('click', ()=>{
        const locInput = reportForm.querySelector('input[name="location"]');
        locInput.value = 'Locating...';
        // simulate locating
        setTimeout(()=> {
          locInput.value = 'Sector 18, Near Metro Station, Delhi';
          showToast('Location found', 'success');
        }, 900);
      });
    }
  }

  // SOS PAGE: big button + countdown
  const sosBtn = document.querySelector('#sosButton');
  if (sosBtn) {
    let sosTimer = null;
    let countdown = 10;
    const sosPopup = document.querySelector('#sosPopup');
    const cancelSos = document.querySelector('#cancelSos');
    const sosCountEl = document.querySelector('#sosCount');

    function startSOS() {
      sosBtn.classList.add('sos-animated');
      openModal('sosModal');
      countdown = 10;
      sosCountEl && (sosCountEl.textContent = countdown);
      sosTimer = setInterval(()=>{
        countdown--;
        sosCountEl && (sosCountEl.textContent = countdown);
        if (countdown <= 0) {
          clearInterval(sosTimer);
          sosTimer = null;
          // fake sending
          closeModal('sosModal');
          openModal('sosSentModal');
        }
      }, 1000);
    }
    function cancelSOS() {
      sosBtn.classList.remove('sos-animated');
      if (sosTimer) clearInterval(sosTimer);
      sosTimer = null;
      closeModal('sosModal');
      showToast('SOS Cancelled', 'error');
    }
    sosBtn.addEventListener('click', startSOS);
    if (cancelSos) cancelSos.addEventListener('click', cancelSOS);
  }

  // MAP: tooltips & mock search
  const mapCanvas = document.querySelector('.map-canvas');
  if (mapCanvas) {
    // Example markers with positions (left%, top%)
    const markers = [
      {id:'m1', left:'36%', top:'38%', safe:false, title:'Incident: Harassment reported', details:'10:05 PM - Unverified'},
      {id:'m2', left:'55%', top:'62%', safe:true, title:'Safe Zone: Police Booth', details:'Open 24/7'},
      {id:'m3', left:'22%', top:'68%', safe:false, title:'Caution: Low-light area', details:'Multiple reports'},
      {id:'m4', left:'74%', top:'34%', safe:true, title:'Community Safe Spot', details:'CCTV available'}
    ];
    // create markers
    markers.forEach(m=>{
      const el = document.createElement('div');
      el.className = `marker ${m.safe ? 'safe':'unsafe'}`;
      el.style.left = m.left; el.style.top = m.top;
      el.dataset.title = m.title; el.dataset.details = m.details;
      el.textContent = m.safe ? 'S':'!';
      mapCanvas.appendChild(el);

      // tooltip
      const tip = document.createElement('div');
      tip.className = 'tooltip';
      tip.innerHTML = `<strong>${m.title}</strong><div class="small">${m.details}</div>`;
      mapCanvas.appendChild(tip);

      el.addEventListener('mousemove', (ev)=>{
        tip.style.left = ev.offsetX + 'px';
        tip.style.top = (ev.offsetY - 16) + 'px';
        tip.style.opacity = '1';
      });
      el.addEventListener('mouseleave', ()=> tip.style.opacity = '0');
      el.addEventListener('click', ()=>{
        // show info modal
        const info = document.querySelector('#markerInfo');
        if (info) {
          info.querySelector('.modal-body').innerHTML = `<h3>${m.title}</h3><p>${m.details}</p><p class="small">Coordinates: ${m.left} , ${m.top}</p>`;
          openModal('markerInfo');
        }
      });
    });

    // simple search mock
    const mapSearch = document.querySelector('#mapSearch');
    if (mapSearch) {
      mapSearch.addEventListener('submit', (e)=>{
        e.preventDefault();
        const q = mapSearch.querySelector('input').value.trim().toLowerCase();
        if (!q) { showToast('Enter location to search', 'error'); return; }
        showToast(`Showing results for "${q}"`, 'success');
        // highlight first marker that matches "safe" or "station" keywords (mock)
        const all = mapCanvas.querySelectorAll('.marker');
        all.forEach(el=>el.style.transform = 'translate(-50%,-100%) scale(1)');
        setTimeout(()=> {
          let matched = all[0];
          // simple heuristic
          if (q.includes('safe')) matched = Array.from(all).find(x=>x.classList.contains('safe')) || all[0];
          matched.style.transform = 'translate(-50%,-100%) scale(1.4)';
        }, 300);
      });
    }
  }

  // Dashboard tabs
  const tabs = document.querySelectorAll('.tab');
  if (tabs.length) {
    tabs.forEach(t=>{
      t.addEventListener('click', ()=>{
        tabs.forEach(x=>x.classList.remove('active'));
        t.classList.add('active');
        // show corresponding content
        const target = t.dataset.target;
        document.querySelectorAll('.tab-content').forEach(tc=>{
          if (tc.id === target) tc.style.display = 'block'; else tc.style.display = 'none';
        });
      });
    });
  }

  // Contact form
  const contactForm = document.querySelector('#contactForm');
  if (contactForm) {
    contactForm.addEventListener('submit', (e)=>{
      e.preventDefault();
      const name = contactForm.querySelector('input[name="name"]').value.trim();
      const email = contactForm.querySelector('input[name="email"]').value.trim();
      const msg = contactForm.querySelector('textarea[name="message"]').value.trim();
      if (!name || !email || !msg) { showToast('Please fill all fields', 'error'); return; }
      showToast('Message Sent Successfully!', 'success');
      contactForm.reset();
    });
  }

  // Generic toast function
  function showToast(message, type='info') {
    // create ephemeral toast
    const el = document.createElement('div');
    el.className = 'card';
    el.style.position = 'fixed';
    el.style.right = '18px';
    el.style.bottom = '90px';
    el.style.zIndex = 200;
    el.style.minWidth = '220px';
    el.style.padding = '12px 16px';
    el.style.borderRadius = '10px';
    el.style.boxShadow = '0 10px 40px rgba(2,6,23,0.8)';
    el.style.opacity = '0';
    el.style.transition = 'all .2s';
    if (type === 'error') el.style.borderLeft = '4px solid var(--danger)';
    if (type === 'success') el.style.borderLeft = '4px solid var(--success)';
    el.innerHTML = `<strong style="display:block;margin-bottom:6px">${message}</strong><div class="small">SafeSpace</div>`;
    document.body.appendChild(el);
    requestAnimationFrame(()=> el.style.opacity = '1');
    setTimeout(()=> {
      el.style.opacity = '0';
      setTimeout(()=> el.remove(), 500);
    }, 2000);
  }

  // small progressive enhancement: fade-in animations
  document.querySelectorAll('.fade-in').forEach((el, i)=>{
    el.style.opacity = 0;
    el.style.transform = 'translateY(10px)';
    setTimeout(()=> {
      el.style.transition = 'all .5s cubic-bezier(.2,.9,.3,1)';
      el.style.opacity = 1; el.style.transform = 'translateY(0)';
    }, 180 * (i+1));
  });
});
