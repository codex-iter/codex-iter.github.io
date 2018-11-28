(function() {
  
  tinymce.create('tinymce.plugins.wpforo_pre_button', {
    /**
     * Initializes the plugin, this will be executed after the plugin has been created.
     * This call is done before the editor instance has finished it's initialization so use the onInit event
     * of the editor instance to intercept that event.
     *
     * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
     * @param {string} url Absolute URL to where the plugin is located.
     */
    init : function(ed, url) {
      ed.addCommand('preFormat', function() {
        ed.formatter.toggle('pre');
      });
      
      ed.addButton('pre', {
        title : 'Code',
        cmd : 'preFormat',
        icon: 'code'
      });
      
      ed.addShortcut('ctrl+0','pre','preFormat')
    },
  });

  // Register plugin
  tinymce.PluginManager.add('wpforo_pre_button', tinymce.plugins.wpforo_pre_button);
  
})();