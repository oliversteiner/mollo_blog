(function($, Drupal, drupalSettings) {
  Drupal.behaviors.molloBlog = {
    attach(context, settings) {
      console.log('Mollo Blog');

      $('#mollo-blog', context)
        .once('mollo-blog')
        .each(() => {});
    },
  };
})(jQuery, Drupal, drupalSettings);
