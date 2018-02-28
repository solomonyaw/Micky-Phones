(function() {
    tinymce.PluginManager.add('facebook_post_embed', function(editor, url) {
        editor.addButton('facebook_post_embed', {
            text:  false,
            title: 'Facebook Post Embed',
            icon: 'facebook-post-embed-icon',
            onclick: function() {
                editor.insertContent('[fb_pe url="" bottom="30"]');
            }
        });
    });
})();