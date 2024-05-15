// Override Kegiatan Page Sidebar
$(document).ready(function() {
    if (window.location.href.indexOf("Kegiatan") > -1 || window.location.href.indexOf("kegiatan") > -1) {
      $('body').addClass('toggle-sidebar');
    }
  });

// Active Nav-link
$(document).ready(function() {
  var path = $(location).attr('href');
  $('.nav-link').each(function() {
    var href = $(this).attr('href');
    if (href === path) {
      $(this).removeClass('collapsed');
      $(this).addClass('active');
    }
  });
});
