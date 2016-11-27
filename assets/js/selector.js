jQuery(function($) {
  var current_selector = algolia.woocommerce.selector;
  if(current_selector.length > 0) {
    activate(current_selector);
  }
  
  $(document).on('mouseover', 'div, main', function(e) {
    var target = $(e.target);
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
  
  $(document).on('click', '.algolia-selector', function(e) {
    e.preventDefault();
    activate(e.target);
  })

  function activate(selector) {
    var target = $(selector);
    clearActiveSelectors();
    target.addClass('algolia-active-selector');

    window.top.jQuery('#algolia-selector').val(computeSelectorPath(selector));
  }

  function computeSelectorPath(selector) {
    var target = $(selector);
    var path = '';

    var selectorId = target.attr('id');
    if(selectorId) {
      path += '#' + selectorId;
    } else {
      var selectorclass = target.attr('class');
      if(selectorclass) {
        path += formatClasses(selectorclass);
      }
    }


    var parent = target.parent();

    if(parent) {
      var selectorId = parent.attr('id');

      if(selectorId) {
        path = '#' + selectorId + ' ' + path;
      } else {
        var selectorclass = parent.attr('class');
        if(selectorclass) {
          path = formatClasses(selectorclass) + ' ' + path;
        }
      }
    }

    return path;
  }

  function formatClasses(classes) {
    var pieces = classes.split(' ');
    pieces.splice(pieces.indexOf('algolia-selector'), 1);
    pieces.splice(pieces.indexOf('algolia-active-selector'), 1);


    if(pieces.length === 0) {
      return '';
    }

    return '.' + pieces.join('.');
  }

});
