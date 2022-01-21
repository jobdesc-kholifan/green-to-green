+function($) {
    const CustomSelectOptions = function(options, element) {
        this.url = element.data('url');
        if(this.url === undefined && options.url !== undefined)
            this.url = options.url;

        this.params = element.data('params');
        if(this.params === undefined && options.params !== undefined)
            this.params = options.params;
    };

    const CustomSelect = function(selector, options = {}) {
        this.$ = $(selector);
        this.options = new CustomSelectOptions(options, this.$);

        this.$input = this.$.find('input');
        this.$inputHidden = this.$.find('input[type=hidden]');
        this.$items = this.$.find('.options');
        this.$wrapperItems = this.$.find('.list-options');

        this.init();
    };

    CustomSelect.prototype.init = function() {
        this.$input.on('focusin', () => {
            this.$.addClass('open');
            this.$wrapperItems.css({width: this.$.width()});

            if(this.options.url !== undefined) {
                ServiceAjax.get(this.options.url, {
                    data: this.options.params,
                    success: (res) => {
                        this.$wrapperItems.empty();
                        res.forEach(item => {
                            this.$wrapperItems.append(
                                $('<div>', {class: 'options', 'data-value': item.id}).text(item.text),
                            );
                        });

                        this.$items = this.$.find('.options');

                        this.initItems();
                    }
                })
            }
        });

        this.$input.on('focusout', () => {
            setTimeout(() => this.$.removeClass('open'), 200);
        });

        this.initItems();
    };

    CustomSelect.prototype.initItems = function() {
        this.$items.each((i, item) => {
            const $item = $(item);
            $item.click(() => {
                this.$input.val($item.text());
                this.$inputHidden.val($item.data('value'));
                this.$input.focusout();
            });
        });
    };

    CustomSelect.prototype.clear = function() {
        console.log("Here");
        this.$input.val(null);
        this.$inputHidden.val(null);
    };

    CustomSelect.prototype.val = function(value) {
        if(value === undefined)
            return this.$inputHidden.val();

        this.$inputHidden.val(value);
    };

    CustomSelect.prototype.set = function(value) {
        this.$input.val(value.text);
        this.$inputHidden.val(value.id);
    };

    CustomSelect.prototype.text = function(value) {
        if(value === undefined)
            return this.$input.val();

        this.$input.val(value);
    };

    $.fn.customSelect = function(options) {
        const $this = $(this);
        const data = new CustomSelect($this, options);
        $this.data('app.customSelect', data);

        return data;
    };
} (jQuery);
