document.addEventListener('DOMContentLoaded', function() {
  const icons = [
    'fa-code',
    'fa-laptop-code',
    'fa-keyboard',
    'fa-rocket',
    'fa-lightbulb',
    'fa-magic',
    'fa-gamepad',
    'fa-terminal'
  ];
  
  const iconElement = document.querySelector('.rotating-icon i');
  let currentIcon = 0;
  
  function changeIcon() {
    currentIcon = (currentIcon + 1) % icons.length;
    iconElement.style.opacity = 0;
    
    setTimeout(() => {
      iconElement.className = 'fas ' + icons[currentIcon];
      iconElement.style.opacity = 1;
    }, 500);
  }
  
  setInterval(changeIcon, 2000);
  document.getElementById('current-year').textContent = new Date().getFullYear();
});