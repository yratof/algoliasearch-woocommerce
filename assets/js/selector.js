jQuery(function ($) {
  var current_selector = algolia.woocommerce.selector;
  var valid_tag_names = ['div', 'main', 'section', 'article', 'aside', 'header', 'nav'];

  if (current_selector.length > 0) {
    activate($(current_selector));
  } else if (current_selector.length === 0) {
    // Only guess if no selector is set.
    // We can't presume what manual selector the user might enter.
    var guess = $('.woocommerce-breadcrumb').parent();
    if (guess.length > 0) {
      activate(guess);
      updateInputValue(guess);
    }
  }

  $(document).on('mouseover', valid_tag_names.join(', '), function (e) {
    e.stopPropagation();
    var target = $(e.currentTarget);

    clearSelectors();
    clearSelectorPaths();
    target.addClass('algolia-selector');
  })

  function clearSelectors() {
    $('.algolia-selector').removeClass('algolia-selector');
  }

  function clearSelectorPaths() {
    $('.algolia-selector-path').remove();
  }

  function clearActiveSelectors() {
    $('.algolia-active-selector').removeClass('algolia-active-selector');
  }

  $(document).on('click', '.algolia-selector', function (e) {
    e.preventDefault();

    var target = $(e.currentTarget);

    activate(target);
    updateInputValue(target);
  })

  function activate(target) {
    clearActiveSelectors();
    target.addClass('algolia-active-selector');
  }

  function updateInputValue(target) {
    window.top.jQuery('#algolia-selector').val(computeSelectorPath(target));
  }

  function computeSelectorPath(target) {
    var path = '';

    var selectorId = target.attr('id');
    if (selectorId) {
      // No need to go further if we got an ID.
      return '#' + selectorId;
    }

    var selectorclass = target.attr('class');
    path += target.prop("tagName").toLowerCase();
    if (selectorclass) {
      path += formatClasses(selectorclass);
    }

    var parent = target.parent();

    if (parent) {
      var selectorId = parent.attr('id');
      if (selectorId) {
        return '#' + selectorId + ' > ' + path;
      }

      selectorclass = parent.attr('class');
      if (selectorclass) {
        path = parent.prop("tagName").toLowerCase() + formatClasses(selectorclass) + ' > ' + path;
      } else {
        path = parent.prop("tagName").toLowerCase() + ' > ' + path;
      }

      parent = parent.parent();
      if (parent) {
        var selectorId = parent.attr('id');
        if (selectorId) {
          return '#' + selectorId + ' > ' + path;
        }

        selectorclass = parent.attr('class');
        if (selectorclass) {
          path = parent.prop("tagName").toLowerCase() + formatClasses(selectorclass) + ' > ' + path;
        } else {
          path = parent.prop("tagName").toLowerCase() + ' > ' + path;
        }
      }
    }

    return path;
  }

  function formatClasses(classes) {
    var pieces = classes.split(' ');

    var index = pieces.indexOf('algolia-selector');
    if (index !== -1) {
      pieces.splice(index, 1);
    }
    index = pieces.indexOf('algolia-active-selector');
    if (index !== -1) {
      pieces.splice(index, 1);
    }

    $.grep(pieces, function (n) {
      return n === ""
    });

    if (pieces.length === 0) {
      return '';
    }

    return '.' + pieces.join('.');
  }

});
