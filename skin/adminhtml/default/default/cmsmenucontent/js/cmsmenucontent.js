var selected = Class.create({
    initialize: function() {
        this.userselected = $F("menu_block_order").split(",");
        this.getText();
        Event.observe($("menu_menu_items"), "change", this.updateForm.bindAsEventListener(this));
        $("menu_menu_items").up("tr").insert({ after: '<tr><td class="label">Block Display Order</td><td id="displayblockorder" class="value"></td></tr>' });
        this.outputOrder();
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
    },
    outputOrder: function() {
        var html = new Array();
        var hidden = new Array();
        this.userselected.each(function(s) {
            html.push(this.valuetext[s]);
            hidden.push(s);
        }.bind(this));
        hidden = hidden.join(",");
        html = html.join("<br />");
        $("menu_block_order").value = hidden;
        $("displayblockorder").update(html);
    }
});

document.observe("dom:loaded", function() {
    var selecteditems = new selected();
});