jQuery(function($) {

  /* TODO: remove path*/
  $(document).on('mouseover', 'div, main', function(e) {
    var target = $(e.target);
    clearSelectors();
    clearSelectorPaths();
    target.addClass('algolia-selector');

    var path = '';

    var selectorId = target.attr('id');
    if(selectorId) {
      path += '#' + selectorId;
    } else {
      var selectorclass = target.attr('class');
      if(selectorclass) {
        path += '.' + selectorclass;
      }
    }


    var parent = target.parent();

    if(parent) {
      var selectorId = parent.attr('id');
      var selectorclass = parent.attr('class');

      if(selectorId) {
        path = '#' + selectorId + ' ' + path;
      } else {
        var selectorclass = parent.attr('class');
        if(selectorclass) {
          path = '.' + selectorclass  + ' ' + path;
        }
      }
    }

    console.log(path);
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

  /* TODO: remove path*/
  $(document).on('click', '.algolia-selector', function(e) {
    var target = $(e.target);
    clearActiveSelectors();
    target.addClass('algolia-active-selector');
  })

});
