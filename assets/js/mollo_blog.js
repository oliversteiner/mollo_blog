(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.molloBlog = {
    attach(context, settings) {

      if (!drupalSettings.molloBlog) {
        drupalSettings.molloBlog = {
          once: false,
          targets: [],
          activeTargetID: 0,
        }
      }

      if (!drupalSettings.molloBlog.once) {
        drupalSettings.molloBlog.once = true;
        this.load();
      }
    },

    load() {
      $(document).ready(function () {

        const $targets = $('.blog-teaser-detail');
        drupalSettings.molloBlog.targets = $targets;
        $($targets[0]).show();


      })

    },

    showDetail(targetID) {
      if (targetID !== drupalSettings.molloBlog.activeTargetID) {
        const $targets = drupalSettings.molloBlog.targets
        $targets.hide();
        $('.blog-teaser-detail-' + targetID).show();
        drupalSettings.molloBlog.activeTargetID = targetID

      }
    },
    goToNode(targetID) {

    }
  };
})(jQuery, Drupal, drupalSettings);



