$(document).ready(function(){

  // Initialise the Mansonry Grid
  // =======================================================
  var $grid = $('.grid').masonry({
    itemSelector: '.card',
    fitWidth: true,
    gutter: 20
    // columnWidth: 50
  });


  // Let's write something in the footer at random
  var jokes = [
    "<p>- What is a programmer's favourite hangout place?</p><p> - Foo Bar</p>",
    "<p>- Why do PHP programmers have to wear glasses?</p><p> - Because they don't C# (see sharp)</p>",
    "<p>Unix is user friendly. It's just very particular about who its friends are.</p>",
    "<p>How did the programmer die in the shower? He read the shampoo bottle instructions: Lather. Rinse. Repeat.</p>"
  ];

  var randJoke = jokes[Math.floor(Math.random() * jokes.length)];

  $('.footer-joke').html(randJoke);

});