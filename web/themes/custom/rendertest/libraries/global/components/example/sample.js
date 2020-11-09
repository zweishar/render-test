(function ($, Drupal) {
  Drupal.behaviors.example = {
    attach: function (context, settings) {
      if (
        typeof context === 'object' &&
        context?.constructor?.name == 'HTMLDocument'
      ) {
        console.log(
          'Example js file!!! templates/components/example/sample.js'
        );
      }
    },
  };
})(jQuery, Drupal);
