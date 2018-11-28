(function() {
  
  tinymce.create('tinymce.plugins.wpforo_link_button', {
    /**
     * Initializes the plugin, this will be executed after the plugin has been created.
     * This call is done before the editor instance has finished it's initialization so use the onInit event
     * of the editor instance to intercept that event.
     *
     * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
     * @param {string} url Absolute URL to where the plugin is located.
     */
    init : function(ed, url) {
      
	      	
		function createLinkList(callback) {
			return function() {
				var linkList = ed.settings.link_list;

				if (typeof linkList == "string") {
					tinymce.util.XHR.send({
						url: linkList,
						success: function(text) {
							callback(tinymce.util.JSON.parse(text));
						}
					});
				} else if (typeof linkList == "function") {
					linkList(callback);
				} else {
					callback(linkList);
				}
			};
		}

		function buildListItems(inputList, itemCallback, startItems) {
			function appendItems(values, output) {
				output = output || [];

				tinymce.each(values, function(item) {
					var menuItem = {text: item.text || item.title};

					if (item.menu) {
						menuItem.menu = appendItems(item.menu);
					} else {
						menuItem.value = item.value;

						if (itemCallback) {
							itemCallback(menuItem);
						}
					}

					output.push(menuItem);
				});

				return output;
			}

			return appendItems(inputList, startItems || []);
		}

		function showDialog(linkList) {
			var data = {}, selection = ed.selection, dom = ed.dom, selectedElm, anchorElm, initialText;
			var win, onlyText, textListCtrl, linkListCtrl, relListCtrl, targetListCtrl, classListCtrl, linkTitleCtrl, value;

			function linkListChangeHandler(e) {
				var textCtrl = win.find('#text');

				if (!textCtrl.value() || (e.lastControl && textCtrl.value() == e.lastControl.text())) {
					textCtrl.value(e.control.text());
				}

				win.find('#href').value(e.control.value());
			}

			function buildAnchorListControl(url) {
				var anchorList = [];

				tinymce.each(ed.dom.select('a:not([href])'), function(anchor) {
					var id = anchor.name || anchor.id;

					if (id) {
						anchorList.push({
							text: id,
							value: '#' + id,
							selected: url.indexOf('#' + id) != -1
						});
					}
				});

				if (anchorList.length) {
					anchorList.unshift({text: 'None', value: ''});

					return {
						name: 'anchor',
						type: 'listbox',
						label: 'Anchors',
						values: anchorList,
						onselect: linkListChangeHandler
					};
				}
			}

			function updateText() {
				if (!initialText && data.text.length === 0 && onlyText) {
					this.parent().parent().find('#text')[0].value(this.value());
				}
			}

			function urlChange(e) {
				var meta = e.meta || {};

				if (linkListCtrl) {
					linkListCtrl.value(ed.convertURL(this.value(), 'href'));
				}

				tinymce.each(e.meta, function(value, key) {
					win.find('#' + key).value(value);
				});

				if (!meta.text) {
					updateText.call(this);
				}
			}

			function isOnlyTextSelected(anchorElm) {
				var html = selection.getContent();

				// Partial html and not a fully selected anchor element
				if (/</.test(html) && (!/^<a [^>]+>[^<]+<\/a>$/.test(html) || html.indexOf('href=') == -1)) {
					return false;
				}

				if (anchorElm) {
					var nodes = anchorElm.childNodes, i;

					if (nodes.length === 0) {
						return false;
					}

					for (i = nodes.length - 1; i >= 0; i--) {
						if (nodes[i].nodeType != 3) {
							return false;
						}
					}
				}

				return true;
			}

			selectedElm = selection.getNode();
			anchorElm = dom.getParent(selectedElm, 'a[href]');
			onlyText = isOnlyTextSelected();

			data.text = initialText = anchorElm ? (anchorElm.innerText || anchorElm.textContent) : selection.getContent({format: 'text'});
			data.href = anchorElm ? dom.getAttrib(anchorElm, 'href') : '';

			if (anchorElm) {
				data.target = dom.getAttrib(anchorElm, 'target');
			} else if (ed.settings.default_link_target) {
				data.target = ed.settings.default_link_target;
			}

			if ((value = dom.getAttrib(anchorElm, 'rel'))) {
				data.rel = value;
			}

			if ((value = dom.getAttrib(anchorElm, 'class'))) {
				data['class'] = value;
			}

			if ((value = dom.getAttrib(anchorElm, 'title'))) {
				data.title = value;
			}

			if (onlyText) {
				textListCtrl = {
					name: 'text',
					type: 'textbox',
					size: 40,
					label: 'Link Text',
					onchange: function() {
						data.text = this.value();
					}
				};
			}

			if (linkList) {
				linkListCtrl = {
					type: 'listbox',
					label: 'Link list',
					values: buildListItems(
						linkList,
						function(item) {
							item.value = ed.convertURL(item.value || item.url, 'href');
						},
						[{text: 'None', value: ''}]
					),
					onselect: linkListChangeHandler,
					value: ed.convertURL(data.href, 'href'),
					onPostRender: function() {
						/*eslint consistent-this:0*/
						linkListCtrl = this;
					}
				};
			}

			if (ed.settings.target_list !== false) {
				targetListCtrl = {
					name: 'target',
					type: 'checkbox', 
					checked: true, 
					text: 'Open link in a new tab'
				};
			}

			if (ed.settings.rel_list) {
				relListCtrl = {
					name: 'rel',
					type: 'listbox',
					label: 'Rel',
					values: buildListItems(ed.settings.rel_list)
				};
			}

			if (ed.settings.link_class_list) {
				classListCtrl = {
					name: 'class',
					type: 'listbox',
					label: 'Class',
					values: buildListItems(
						ed.settings.link_class_list,
						function(item) {
							if (item.value) {
								item.textStyle = function() {
									return ed.formatter.getCssText({inline: 'a', classes: [item.value]});
								};
							}
						}
					)
				};
			}

			if (ed.settings.link_title !== false) {
				linkTitleCtrl = {
					name: 'title',
					type: 'textbox',
					label: 'Title',
					value: data.title
				};
			}

			win = ed.windowManager.open({
				title: 'Insert link',
				data: data,
				body: [
					{
						name: 'href',
						type: 'filepicker',
						filetype: 'file',
						size: 40,
						autofocus: true,
						label: 'URL',
						onchange: urlChange,
						onkeyup: updateText
					},
					textListCtrl,
					linkTitleCtrl,
					buildAnchorListControl(data.href),
					linkListCtrl,
					relListCtrl,
					targetListCtrl,
					classListCtrl
				],
				onSubmit: function(e) {
					/*eslint dot-notation: 0*/
					var href;

					data = tinymce.extend(data, e.data);
					href = data.href;
					if ( href && ! /^(?:[a-z]+:|#|\?|\.|\/)/.test( href ) ) {
						href = 'http://' + href;
					}

					// Delay confirm since onSubmit will move focus
					function delayedConfirm(message, callback) {
						var rng = ed.selection.getRng();

						tinymce.util.Delay.setEditorTimeout(editor, function() {
							ed.windowManager.confirm(message, function(state) {
								ed.selection.setRng(rng);
								callback(state);
							});
						});
					}

					function insertLink() {
						var linkAttrs = {
							href: href,
							target: data.target ? data.target : null,
							rel: data.rel ? data.rel : null,
							"class": data["class"] ? data["class"] : null,
							title: data.title ? data.title : null
						};

						if (anchorElm) {
							ed.focus();

							if (onlyText && data.text != initialText) {
								if ("innerText" in anchorElm) {
									anchorElm.innerText = data.text;
								} else {
									anchorElm.textContent = data.text;
								}
							}

							dom.setAttribs(anchorElm, linkAttrs);

							selection.select(anchorElm);
							ed.undoManager.add();
						} else {
							if (onlyText) {
								ed.insertContent(dom.createHTML('a', linkAttrs, dom.encode(data.text)));
							} else {
								ed.execCommand('mceInsertLink', false, linkAttrs);
							}
						}
					}

					if (!href) {
						ed.execCommand('unlink');
						return;
					}

					// Is email and not //user@domain.com
					if (href.indexOf('@') > 0 && href.indexOf('//') == -1 && href.indexOf('mailto:') == -1) {
						delayedConfirm(
							'The URL you entered seems to be an email address. Do you want to add the required mailto: prefix?',
							function(state) {
								if (state) {
									href = 'mailto:' + href;
								}

								insertLink();
							}
						);

						return;
					}

					// Is not protocol prefixed
					if ((ed.settings.link_assume_external_targets && !/^\w+:/i.test(href)) ||
						(!ed.settings.link_assume_external_targets && /^\s*www[\.|\d\.]/i.test(href))) {
						delayedConfirm(
							'The URL you entered seems to be an external link. Do you want to add the required http:// prefix?',
							function(state) {
								if (state) {
									href = 'http://' + href;
								}

								insertLink();
							}
						);

						return;
					}

					insertLink();
				}
			});
		}

		ed.addButton('link', {
			icon: 'link',
			tooltip: 'Insert/edit link',
			shortcut: 'Meta+K',
			onclick: createLinkList(showDialog),
			stateSelector: 'a[href]'
		});

		ed.addButton('unlink', {
			icon: 'unlink',
			tooltip: 'Remove link',
			cmd: 'unlink',
			stateSelector: 'a[href]'
		});

		ed.addShortcut('Meta+K', '', createLinkList(showDialog));
		ed.addCommand('mceLink', createLinkList(showDialog));

		this.showDialog = showDialog;

		ed.addMenuItem('link', {
			icon: 'link',
			text: 'Insert/edit link',
			shortcut: 'Meta+K',
			onclick: createLinkList(showDialog),
			stateSelector: 'a[href]',
			context: 'insert',
			prependToContext: true
		});

      
    },
  });

  // Register plugin
  tinymce.PluginManager.add('wpforo_link_button', tinymce.plugins.wpforo_link_button);
  
})();