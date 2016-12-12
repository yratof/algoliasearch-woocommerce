var Handlebars = require('handlebars');

module.exports = function (file, context){
    var suffix = 'Algolia Search plugin for WooCommerce';
    
    if(file.data.root.title === suffix) {
        return new Handlebars.SafeString(suffix);
    }

    return new Handlebars.SafeString(file.data.root.title + ' - ' + suffix);
}
