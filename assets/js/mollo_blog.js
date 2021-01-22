(function($, Drupal, drupalSettings) {
  Drupal.behaviors.molloArtist = {
    attach(context, settings) {
      console.log("Mollo Artist");

        $('#mollo-news', context)
          .once('mollo-news')
          .each(() => {});

    },
  };
})(jQuery, Drupal, drupalSettings);
