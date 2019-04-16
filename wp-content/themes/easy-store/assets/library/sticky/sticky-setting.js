/*
 * Settings of the sticky menu
 */

jQuery(document).ready(function(){
   var wpAdminBar = jQuery('#wpadminbar');
   if (wpAdminBar.length) {
      jQuery(".es-main-menu-wrapper").sticky({topSpacing:wpAdminBar.height()});
   } else {
      jQuery(".es-main-menu-wrapper").sticky({topSpacing:0});
   }
});