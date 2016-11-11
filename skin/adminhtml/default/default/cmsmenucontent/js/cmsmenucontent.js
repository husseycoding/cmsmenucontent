var selected = Class.create({
    afterInit: function() {
        if ($F("menu_block_order")) {
            this.userselected = $F("menu_block_order").split(",");
        } else {
            this.userselected = new Array();
        }
        this.getText();
        Event.observe($("menu_menu_items"), "change", this.updateForm.bindAsEventListener(this));
        $("menu_menu_items").up("tr").insert({ after: '<tr><td class="label">Block Display Order</td><td id="displayblockorder" class="value"></td></tr>' });
        this.outputOrder();
        this.initOrderSort();
        this.addSectionButton();
        Event.observe($("add_cmsmenucontent_break"), "click", this.addBreak.bind(this));
    },
    addColumn: function(id) {
        if (this.userselected.indexOf(id) == -1) {
            this.userselected.push(id);
        }
    },
    removeColumn: function(id) {
        var count = 0;
        this.userselected.each(function(s) {
            if (s == id) {
                this.userselected.splice(count, 1);
                throw $break;
            }
            count++;
        }.bind(this));
    },
    updateForm: function() {
        this.checkForm();
        this.outputOrder();
    },
    checkForm: function() {
        var selectedoptions = {};
        this.userselected.each(function(s) {
            selectedoptions[s] = true;
        }.bind(this));
        for (var i = 0; i < $("menu_menu_items").options.length; i++) {
            var value = $("menu_menu_items").options[i].value;
            if (selectedoptions[value] && !$("menu_menu_items").options[i].selected) {
                this.removeColumn(value);
            } else if (!selectedoptions[value] && $("menu_menu_items").options[i].selected) {
                this.addColumn(value);
            }
        }
    },
    getText: function() {
        this.valuetext = {};
        for (var i = 0; i < $("menu_menu_items").options.length; i++) {
            this.valuetext[$("menu_menu_items").options[i].value] = $("menu_menu_items").options[i].text;
        }
        this.valuetext["sb"] = "Break";
    },
    outputOrder: function() {
        var html = new Array();
        var hidden = new Array();
        this.userselected.each(function(s) {
            html.push("<div class=\"id_" + s + "\" style=\"cursor:pointer\">" + this.valuetext[s] + "</div>");
            hidden.push(s);
        }.bind(this));
        hidden = hidden.join(",");
        html = html.join("");
        $("menu_block_order").value = hidden;
        $("displayblockorder").update(html);
        this.initOrderSort();
    },
    initOrderSort: function() {
        Sortable.create("displayblockorder", {
            tag: "div",
            onChange: function(evt) {
                selecteditems.updateOrder();
            }
        });
    },
    updateOrder: function() {
        var ids = [];
        $("displayblockorder").childElements().each(function(e) {
            var id = e.className.split("_");
            ids.push(id.pop());
        }.bind(this));
        this.userselected = ids;
        $("menu_block_order").value = ids.join(",");
    },
    addSectionButton: function() {
        $("menu_menu_items").up("tr").insert({ after: '<tr><td class="label"></td><td class="value"><button type="button" id="add_cmsmenucontent_break"><span><span><span>Add Break</span></span></span></button><p class=\"note\"><span>Adding breaks creates sections.  Drag breaks to the start or end of block order to remove on save.</span></p></td></tr>' });
    },
    addBreak: function() {
        this.userselected.push("sb");
        this.outputOrder();
    }
});

var selecteditems = new selected();

document.observe("dom:loaded", function() {
    selecteditems.afterInit();
});