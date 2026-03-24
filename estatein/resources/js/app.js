const toggle = document.querySelector('[data-drawer-toggle]');
const drawer = document.querySelector('[data-drawer]');
const overlay = document.querySelector('[data-drawer-overlay]');
const panel = document.querySelector('[data-drawer-panel]');
const close = document.querySelector('[data-drawer-close]');

function openDrawer() {
  drawer.classList.remove('hidden');
  requestAnimationFrame(() => {
    panel.classList.remove('translate-x-full');
  });
}

function closeDrawer() {
  panel.classList.add('translate-x-full');
  panel.addEventListener('transitionend', () => {
    drawer.classList.add('hidden');
  }, { once: true });
}

if (toggle && drawer) {
  toggle.addEventListener('click', openDrawer);
  overlay.addEventListener('click', closeDrawer);
  close.addEventListener('click', closeDrawer);
}
