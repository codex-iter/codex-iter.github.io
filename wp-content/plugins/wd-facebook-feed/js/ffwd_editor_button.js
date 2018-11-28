(function () {
  tinymce.create('tinymce.plugins.wd_fb_mce', {
    init:function (ed, url) {
      var c = this;
      c.url = url;
      c.editor = ed;
      ed.addCommand('mcewd_fb_mce', function () {
        ed.windowManager.open({
          file:ffwd_admin_ajax,
          width:300 + ed.getLang('wd_fb_mce.delta_width', 0),
          height:150 + ed.getLang('wd_fb_mce.delta_height', 0),
          inline:1
        }, {
          plugin_url:url
        });
        var e = ed.selection.getNode(), d = wp.media.gallery, f;
        if (typeof wp === "undefined" || !wp.media || !wp.media.gallery) {
          return
        }
        if (e.nodeName != "IMG" || ed.dom.getAttrib(e, "class").indexOf("wd_fb_shortcode") == -1) {
          return
        }
        f = d.edit("[" + ed.dom.getAttrib(e, "title") + "]");

      });

      ed.addButton('wd_fb_mce', {
        title:'Insert Facebook Feed',
        cmd:'mcewd_fb_mce',
        image: ffwd_plugin_url + '/images/ffwd/ffwd_logo_editor.png'
      });

      ed.onMouseDown.add(function (d, f) {
        if (f.target.nodeName == "IMG" && d.dom.hasClass(f.target, "wd_fb_shortcode")) {
          var g = tinymce.activeEditor;
          g.wpGalleryBookmark = g.selection.getBookmark("simple");
          g.execCommand("mcewd_fb_mce");
        }
      });

      ed.onBeforeSetContent.add(function (d, e) {
        e.content = c._do_wd_fb(e.content)
      });

      ed.onPostProcess.add(function (d, e) {
        if (e.get) {
          e.content = c._get_wd_fb(e.content)
        }
      })

    },

    _do_wd_fb:function (ed) {
      return ed.replace(/\[WD_FB([^\]]*)\]/g, function (d, c) {
        return '<img src="' + ffwd_plugin_url + '/images/ffwd/ffwd_logo_large.png" class="wd_fb_shortcode mceItem" title="WD_FB' + tinymce.DOM.encode(c) + '" />';
      })
    },

    _get_wd_fb:function (b) {
      function ed(c, d) {
        d = new RegExp(d + '="([^"]+)"', "g").exec(c);
        return d ? tinymce.DOM.decode(d[1]) : "";
      }

      return b.replace(/(?:<p[^>]*>)*(<img[^>]+>)(?:<\/p>)*/g, function (e, d) {
        var c = ed(d, "class");
        if (c.indexOf("wd_fb_shortcode") != -1) {
          return "<p>[" + tinymce.trim(ed(d, "title")) + "]</p>"
        }
        return e
      })
    }

  });
  tinymce.PluginManager.add('wd_fb_mce', tinymce.plugins.wd_fb_mce);
})();
