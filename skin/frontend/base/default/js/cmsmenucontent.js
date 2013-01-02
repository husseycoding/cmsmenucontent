var menucontent = Class.create({
    afterInit: function() {
        this.initMenuItems();
        this.addObservers();
    },
    initMenuItems: function() {
        var count = 0;
        $H(this.menuitems).each(function(item) {
            if (count == 0) {
                $(item.key).removeClassName("blocklink")
                $(item.key).addClassName("blocklink_active")
                $(item.value).show();
            } else {
                $(item.value).hide();
            }
            count++;
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
                $(item.key).removeClassName("blocklink")
                $(item.key).addClassName("blocklink_active")
                $(item.value).show();
            } else {
                $(item.key).removeClassName("blocklink_active")
                $(item.key).addClassName("blocklink")
                $(item.value).hide();
            }
        });
    }
});

document.observe("dom:loaded", function() {
    if (typeof(thismenucontent) == "object") {
        thismenucontent.afterInit();
    }
});