const OrderDetailOptions = function(options) {
    this.routes = {
        selectCategory: options.routes !== undefined && options.routes.selectCategory !== undefined ? options.routes.selectCategory : null,
    };

    this.slugs = {
        rubbishCategory: options.slugs !== undefined && options.slugs.rubbishCategory !== undefined ? options.slugs.rubbishCategory : null,
    }
};

const OrderDetailForm = function(selector, orderDetail) {
    this.$ = $(selector);
    this.__orderDetail = orderDetail;

    this.$selectCategory = $(this.$.find('[data-action=select-category]'));
    this.$inputQty = $(this.$.find('[data-action=input-qty]'));
    this.$delete = $(this.$.find('[data-action=remove]'));
};
OrderDetailForm.prototype.init = function() {
    FormComponents.customSelect.init();

    this.$delete.click(() => {
        if(this.__orderDetail.$.children().length === 1) {
            this.$inputQty.val(null);
            this.$selectCategory.data('app.customSelect').clear();
        } else this.$.remove();
    })
};

const OrderDetail = function(selector, options = {}) {
    this.$ = $(selector);
    this.options = new OrderDetailOptions(options);

    this.$form = $('<div>', {class: 'row'}).append(
        $('<div>', {class: 'col-12 col-sm-8 form-group custom-select-rounded', 'data-toggle': 'custom-select', 'data-url': this.options.routes.selectCategory, 'data-params': `{"parent_slug": "${this.options.slugs.rubbishCategory}"}`, 'data-action': 'select-category'}).append(
            $('<label>', {class: 'ml-3'}).text('Kategori Sampah'),
            $('<div>', {class: 'input-group border rounded-pill bg-light border-olive'}).append(
                $('<input>', {type: 'text', class: 'form-control border-0 form-control-lg pl-4 bg-transparent', placeholder: 'Kategori Sampah', 'required': ''}),
                $('<input>', {type: 'hidden'}),
                $('<div>', {class: 'input-group-append pr-2'}).append(
                    $('<span>', {class: 'input-group-text bg-transparent border-0 text-olive'}).append(
                        $('<i>', {class: 'fa fa-angle-down'})
                    )
                )
            ),
            $('<div>', {class: 'list-options'}),
        ),
        $('<div>', {class: 'col-12 col-sm-4 form-group d-flex'}).append(
            $('<div>').append(
                $('<label>', {class: 'ml-3'}).text("Jumlah"),
                $('<div>', {class: 'input-group border rounded-pill bg-light border-olive'}).append(
                    $('<input>', {type: 'text', class: 'form-control border-0 form-control-lg pl-4 bg-transparent', placeholder: 'Jumlah', 'required': '', onkeydown: 'return Helpers.isNumberKey(event)', 'data-action': 'input-qty'}),
                    $('<div>', {class: 'input-group-append pr-2'}).append(
                        $('<span>', {class: 'input-group-text bg-transparent border-0 text-olive'}).append('KG'),
                    )
                )
            ),
            $('<div>').append(
                $('<label>').text('Aksi'),
                $('<button>', {type: 'button', class: 'btn btn-danger rounded-pill btn-md mt-1 ml-1', 'data-action': 'remove'}).append(
                    $('<i>', {class: 'fa fa-trash'})
                )
            )
        )
    );
};

OrderDetail.prototype.add = function() {
    const $form = $(this.$form.clone());
    const formHandler = new OrderDetailForm($form, this);
    $form.data('formHandler', formHandler);

    this.$.append($form);

    formHandler.init();

    return $form;
};

OrderDetail.prototype.toJson = function() {
    const json = [];
    this.$.children().each((i, child) => {
        const $child = $(child);
        const $formHandler = $child.data('formHandler');
        const $selectCategory =$formHandler.$selectCategory.data('app.customSelect');

        json.push({
            category_id: $selectCategory.val(),
            category: {
                id: $selectCategory.val(),
                text: $selectCategory.text(),
            },
            qty: $formHandler.$inputQty.val(),
        });
    });

    return json;
};

OrderDetail.prototype.toString = function() {
    return JSON.stringify(this.toJson());
};
