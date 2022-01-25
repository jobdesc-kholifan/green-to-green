const AchievementConfig = {
    keyData: 'task.data',
    keyForm: 'task.form',
};

const AchievementOptions = function(options = {}) {
    this.routes = options.routes !== undefined ? options.routes : {};
    this.types = options.types !== undefined ? options.types : {};
};

const TaskData = function(value = {}) {
    this.id = value.id !== undefined ? value.id : null;
    this.achievement_id = value.achievement_id !== undefined ? value.achievement_id : null;
    this.task_type_id = value.task_type_id !== undefined ? value.task_type_id : null;
    this.task_type = value.task_type !== undefined ? value.task_type : {
        id: null,
        text: null,
    };
    this.payload = value.payload !== undefined ? value.payload : null;
};

const TaskForm = function(element, achievement) {
    this.$ = $(element);
    this.__achievement = achievement;

    this.$wrapperForm = $(this.$.find('[data-action=wrapper-form]'));
    this.$selectTask = $(this.$.find('[data-action=selectTask]'));
    this.$btnAdd = $(this.$.find('[data-action=add]'));
    this.$btnRemove = $(this.$.find('[data-action=remove]'));

    this.init();
};

TaskForm.prototype.init = function() {
    FormComponents.select2.init(this.$selectTask);

    this.$selectTask.change(() => {
        this.$.data(AchievementConfig.keyData).task_type_id = this.$selectTask.val();
        this.$.data(AchievementConfig.keyData).task_type = {id: this.$selectTask.val(), text: this.$selectTask.find('option:selected').text()};
        ServiceAjax.get(`${this.__achievement.options.routes.task}/${this.$selectTask.val()}`)
            .done((res) => {
                if(res.result) {
                    this.renderForm(res.data.payload);
                }
            });
    });

    this.$btnAdd.click(() => {
        this.__achievement.add();
        this.$btnAdd.addClass('d-none');
    });

    this.$btnRemove.click(() => {
        if(this.__achievement.$.children().length === 1) {
            this.$.find('input, select').each((i, element) => {
                const $element = $(element);
                if($element.is('select'))
                    $element.val(null).text(null);

                else $element.val(null);
            });
        } else this.$.remove();

        this.__achievement.$.children().last().data(AchievementConfig.keyForm).$btnAdd.removeClass('d-none');
    });
};

TaskForm.prototype.renderForm = function(payload) {
    const jsonPayload = JSON.parse(payload);

    this.$wrapperForm.empty();
    jsonPayload.elements.forEach((element) => {
        const $element = $(element.tag);
        Object.keys(element.attributes).forEach((key) => {
            if(key === 'required') {
                $element.prop('required', true);
            }

            else {
                $element.attr(key, element.attributes[key]);
            }
        });

        const $form = $('<div>', {class: 'form-group'}).append(
            $('<label>').html(element.label),
            $element,
        );

        if(element.clipboard !== undefined && element.clipboard)
            $form.append($('<small>').html(`Kode: {${element.name}}`));

        this.$wrapperForm.append($form);

        $element.on('keypress keyup keydown', () => {
            jsonPayload.requirement[element.name] = $element.val();
            this.$.data(AchievementConfig.keyData).payload = jsonPayload;
        });

        if(element.tag === '<select>') {
            $element.on('change', () => {
                jsonPayload.requirement[element.name] = {
                    value: $element.val(),
                    text: $element.find('option:selected').text()
                };
                this.$.data(AchievementConfig.keyData).payload = jsonPayload;
            });
        }

        if(jsonPayload.requirement !== undefined && jsonPayload.requirement[element.name])
            if(element.tag === '<select>' && jsonPayload.requirement[element.name] !== null) {
                $element.append($('<option>', {value: jsonPayload.requirement[element.name].value}).text(jsonPayload.requirement[element.name].text));
            } else {
                $element.val(jsonPayload.requirement[element.name])
            }

        FormComponents.select2.init();
    });
};

const AchievementTask = function(element, options) {
    this.$ = $(element);
    this.options = new AchievementOptions(options, this.$);

    this.$form = $('<div>').append(
        $('<div>', {class: 'form-group'}).append(
            $('<label>', {'data-action': 'label'}).html('Tugas'),
            $('<select>', {class: 'form-control', 'data-toggle': 'select2', 'data-url': this.options.routes.selectTask, 'data-params': `{"parent_slug": "${this.options.types.task}"}`, 'data-action': 'selectTask', 'required': ''}),
        ),
        $('<div>', {'data-action': 'wrapper-form'}),
        $('<div>', {class: 'form-group text-right'}).append(
            $('<button>', {type: 'button', class: 'btn btn-outline-danger btn-sm mr-1', 'data-action': 'remove'}).append(
                $('<i>', {class: 'fa fa-trash mr-1'}),
                $('<span>').html('Hapus')
            ),
            $('<button>', {type: 'button', class: 'btn btn-outline-primary btn-sm d-none', 'data-action': 'add'}).append(
                $('<i>', {class: 'fa fa-plus-circle mr-1'}),
                $('<span>').html('Tambah')
            )
        )
    );
};

AchievementTask.prototype.add = function() {
    const $form = $(this.$form.clone());
    const form = new TaskForm($form, this);
    const data = new TaskData();

    $form.data(AchievementConfig.keyForm, form);
    $form.data(AchievementConfig.keyData, data);

    this.$.append($form);
    this.$.children().last().data(AchievementConfig.keyForm).$btnAdd.removeClass('d-none');

    return $form;
};

AchievementTask.prototype.set = function(items) {
    items.forEach((item) => {
        const $form = this.add();
        const form = $form.data(AchievementConfig.keyForm);
        const data = new TaskData(item);
        $form.data(AchievementConfig.keyData, data);

        form.$selectTask.append($('<option>', {value: data.task_type_id}).html(data.task_type.text));
        form.$btnAdd.addClass('d-none');
        form.renderForm(data.payload);
    });

    this.$.children().last().data(AchievementConfig.keyForm).$btnAdd.removeClass('d-none');
};

AchievementTask.prototype.toJson = function() {
    const json = [];
    this.$.children().each((i, child) => {
        const $child = $(child);
        const data = $child.data(AchievementConfig.keyData);
        json.push(data);
    });

    return json;
};

AchievementTask.prototype.toString = function() {
    return JSON.stringify(this.toJson());
};
