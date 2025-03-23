$(document).ready(function () {
   
    const defaultView = window.location.hash.substring(1) || 'home';
    loadView(defaultView);
  
    // Handle navigation
    $(window).on('hashchange', function () {
      const viewName = window.location.hash.substring(1); 
      loadView(viewName);
    });
  
    function loadView(viewName) {
      $.get(`views/${viewName}.html`)
        .done(function (html) {
          $('#app').html(html);
        })
        .fail(function (error) {
          console.error('Error loading view:', error);
          $('#app').html('<p>Error loading page. Please try again.</p>');
        });
    }
  });