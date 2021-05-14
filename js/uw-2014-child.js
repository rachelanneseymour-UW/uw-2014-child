jQuery(document).ready(function(){
  // we're relying on running after UW.elements is defined but before the parent theme's
  // document ready handler. Given we register our document handler first I think this 
  // is a safe assumption
  
  // UW scripts aren't loaded in admin so protect against them not being there
  // ideally we shouldn't load in admin either but this was easier to figure out
  UW = window.UW || {};
  UW.elements = window.UW.elements || {};
  UW.elements.radio = '.uw-please-do-not-apply';
  UW.elements.checkbox = '.uw-please-do-not-apply';
});