import anime from 'animejs/lib/anime.es.js';

document.addEventListener('DOMContentLoaded', () => {
  var buttons = document.querySelectorAll('input[type="submit"].button');

  function animate({ target }, scale = 1) {
    anime.remove(target);
    anime({
      targets: target,
      scale: scale,
      duration: 600,
      elasticity: 300,
    });
  }

  buttons.forEach((button) =>
    button.addEventListener(
      'mouseenter',
      (e) => {
        animate(e, 1.05);
      },
      false
    )
  );

  buttons.forEach((button) =>
    button.addEventListener(
      'mouseleave',
      (e) => {
        animate(e, 1);
      },
      false
    )
  );
});
