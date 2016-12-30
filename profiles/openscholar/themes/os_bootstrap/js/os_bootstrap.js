(function ($) {

  Drupal.behaviors.OsBoostrapCollapse = {
    attach: function (context) {

      $('.os_bootstrap_collapse_button').click(function() {
        $( ".os_bootstrap_menu_collapse" ).toggle();
      });

      $(".os_bootstrap_menu_collapse ul.nice-menu .menuparent > a").click(function (event, context) {

        if (!Drupal.settings.huji.mobile) {
          return;
        }

        if (!$(this).hasClass('os-added-menu')) {
          event.preventDefault();
          $(this).addClass('os-added-menu');
          return;
        }

        $(this).removeClass('os-added-menu');
      });
    }
  };

  Drupal.behaviors.OsPublicationsElementOrder  = {
    attach: function (context) {
      $(".node-biblio").each(function(){
        $(this).find(".biblio-title").insertBefore($(this).find(".biblio-authors"));
      });
    }
  };

  Drupal.behaviors.OsThirdLevelMenuPlacement = {
    attach: function (context) {
      $(".menuparent > ul > .menuparent").mouseover(function (){
        var menuWidth = $(this).find('ul').outerWidth();
        var direction = $('html').attr('dir');
        var cssObject;

        if (direction == 'rtl') {
          cssObject = {
            'right': 'inherit',
            'left': -menuWidth
          };
        }
        else {
          cssObject = {
            'left': 'inherit',
            'right': -menuWidth
          };
        }

        var thirdLevelMenu = $(this).find('ul');
        thirdLevelMenu.css(cssObject);
      })
    }
  }

})(jQuery);
