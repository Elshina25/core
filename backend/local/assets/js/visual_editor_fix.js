BX.ready(function () {
    BX.addCustomEvent('OnEditorInitedBefore', function(toolbar) {
        let _this = this;

        //какие тэги не следует удалять в визуальном редакторе
        let protectTags = [
            'span',
            'svg',
            'use',
            'snippet',
            'collapse',
            'collapse-group',
            'title',
            'content',
            'tabs',
            'tab',
        ];

        // отучаю резать тэги
        BX.addCustomEvent(this, 'OnGetParseRules', BX.proxy(function() {
            for (let index in protectTags) {
                if (protectTags.hasOwnProperty(index)) {
                    let tag = protectTags[index];
                    _this.rules.tags[tag] = {};
                }
            }
        }, this));
    });
});