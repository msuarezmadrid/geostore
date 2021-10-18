;
(function($) {
  "use strict";

  /**
   * Usage:
   * show: $(...).boxspin()
   * hide: $(...).boxspin(false)
   */
  $.fn.boxspin = function(show) {
    if (!this.length) {
      return this;
    }

    if (false === show) {
      $(this).closest('.box').find('.overlay').remove();
    } else {
      $(this).closest('.box').append('<div class="overlay"><i class="fa fa-refresh fa-spin fa-lg fa-fw"></i></div>');
    }

    return this;
  }
})(jQuery);