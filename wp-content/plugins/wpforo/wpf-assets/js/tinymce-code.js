(function() {
  
  tinymce.create('tinymce.plugins.wpforo_source_code_button', {
    /**
     * Initializes the plugin, this will be executed after the plugin has been created.
     * This call is done before the editor instance has finished it's initialization so use the onInit event
     * of the editor instance to intercept that event.
     *
     * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
     * @param {string} url Absolute URL to where the plugin is located.
     */
    init : function(ed, url) {
      
	function showDialog() {
		var win = ed.windowManager.open({
				title: "Source code",
				body: {
					type: 'textbox',
					name: 'code',
					multiline: true,
					minWidth: ed.getParam("code_dialog_width", 600),
					minHeight: ed.getParam("code_dialog_height", Math.min(tinymce.DOM.getViewPort().h - 200, 500)),
					spellcheck: false,
					style: 'direction: ltr; text-align: left'
				},
				onSubmit: function(e) {
					// We get a lovely "Wrong document" error in IE 11 if we
					// don't move the focus to the editor before creating an undo
					// transation since it tries to make a bookmark for the current selection
					ed.focus();

					ed.undoManager.transact(function() {
						ed.setContent(e.data.code);
					});

					ed.selection.setCursorLocation();
					ed.nodeChanged();
				}
			});

			// Gecko has a major performance issue with textarea
			// contents so we need to set it when all reflows are done
			win.find('#code').value(ed.getContent({source_view: true}));
		}

		ed.addCommand("mceCodeEditor", showDialog);

		ed.addButton('source_code', {
			icon: 'codesample',
			tooltip: 'Source code',
			onclick: showDialog
		});

		ed.addMenuItem('code', {
			icon: 'code',
			text: 'Source code',
			context: 'tools',
			onclick: showDialog
		});

    },
  });

  // Register plugin
  tinymce.PluginManager.add('wpforo_source_code_button', tinymce.plugins.wpforo_source_code_button);
  
})();