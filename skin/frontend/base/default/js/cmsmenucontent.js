var menucontent = Class.create({
    afterInit: function() {
        this.initMenuItems();
        this.addObservers();
        this.bookmarkSwitch();
    },
    initMenuItems: function() {
        $H(this.menuitems).each(function(item) {
            $(item.value).setStyle({ height:"0" });
        });
    },
    addObservers: function() {
        $H(this.menuitems).each(function(item) {
            $(item.key).observe("click", function(e) {
                this.displayContent(e.target.id);
            }.bindAsEventListener(this));
        }.bind(this));
    },
    displayContent: function(el) {
        $H(this.menuitems).each(function(item) {
            if (item.key == el) {
                if ($(item.key).hasClassName("blocklink_active")) {
                    $(item.key).removeClassName("blocklink_active")
                    $(item.key).addClassName("blocklink")
                    $(item.value).setStyle({ height:"0" });
                } else {
                    $(item.key).removeClassName("blocklink")
                    $(item.key).addClassName("blocklink_active")
                    var height = $(item.value).scrollHeight;
                    $(item.value).setStyle({ height:height + "px" });
                }
            }
        });
    },
    bookmarkSwitch: function() {
        if (window.location.href.indexOf("#") != -1) {
            var argument = window.location.href.split("#").pop();
            if (argument) {
                var el = $("blocklink_" + argument);
                if (el) {
                    this.displayContent(el.id);
                    Effect.ScrollTo(el, {duration:1.0, offset:-5});
                }
            }
        }
    }
});

document.observe("dom:loaded", function() {
    if (typeof(thismenucontent) == "object") {
        thismenucontent.afterInit();
    }
});