const ListFriendsOptions = function(options) {
    this.routes = {
        search: options.routes !== undefined && options.routes.search !== undefined ? options.routes.search : null,
        follow: options.routes !== undefined && options.routes.follow !== undefined ? options.routes.follow : null,
        info: options.routes !== undefined && options.routes.info !== undefined ? options.routes.info : null,
    };
    this.token = options.token !== undefined ? options.token : null;
    this.user_id = options.user_id !== undefined ? options.user_id : null;
};

const ListFriendsData = function(data) {
    this.id = data.id !== undefined ? data.id : null;
    this.full_name = data.full_name !== undefined ? data.full_name : null;
    this.user_name = data.user_name !== undefined ? data.user_name : null;
    this.bio = data.bio !== undefined && data.bio !== null ? data.bio : '-';
    this.is_following = data.is_following !== undefined ? data.is_following : null;
    this.is_follower = data.is_follower !== undefined ? data.is_follower : null;
    this.preview = data.preview !== undefined ? data.preview : null;
    this.user_achievement = data.user_achievement !== undefined ? data.user_achievement : null;
};

const ListFriendsItem = function(item, list) {
    this.$ = $(item);
    this.__list = list;

    this.$fullname = $(this.$.find('[data-action=full-name]'));
    this.$username = $(this.$.find('[data-action=user-name]'));
    this.$bio = $(this.$.find('[data-action=bio]'));
    this.$btnInfo = $(this.$.find('[data-action=show-info]'));
    this.$btnFollow = $(this.$.find('[data-action=follow]'));
    this.$btnFollowing = $(this.$.find('[data-action=following]'));
    this.$message = $(this.$.find('[data-action=message]'));
    this.$image = $(this.$.find('[data-action=image]'));
    this.$imageAchievement = $(this.$.find('[data-action=image-achievement]'));
};

ListFriendsItem.prototype.init = function() {
    const data = this.$.data('data');

    this.$fullname.html(data.full_name);
    this.$username.html(data.user_name);
    this.$bio.html(data.bio);
    this.$image.css({backgroundImage: `url(${data.preview})`});
    if(data.user_achievement !== null && data.user_achievement.achievement !== null)
        if(data.user_achievement.achievement.preview !== null) {
            this.$imageAchievement.css({backgroundImage: `url(${data.user_achievement.achievement.preview})`});
            this.$imageAchievement.attr('title', data.user_achievement.achievement.title);
            this.$imageAchievement.tooltip();
        }

    if(data.is_following === null) {
        this.$btnFollow.removeClass('d-none');

        if(data.is_follower !== null) {
            this.$btnFollow.empty();
            this.$btnFollow.append(
                $('<i>', {class: 'fa fa-plus-circle mr-1'}),
                $('<span>').html("Ikuti Balik"),
            );
            this.$message.html("Mengikuti anda")
        }
    }

    if(data.is_following !== null)
        this.$btnFollowing.removeClass('d-none');

    this.$btnFollow.click(() => {
        if(this.__list.options.routes.follow !== null && this.__list.options.token !== null) {
            this.$btnFollow.attr('disabled', 'disabled')
            this.$btnFollow.html($('<i>', {class: 'fa fa-spinner fa-spin'}));

            ServiceAjax.post(this.__list.options.routes.follow, {
                data: {
                    _token: this.__list.options.token,
                    user_id: this.__list.options.user_id,
                    user_follow_id: data.id,
                },
                success: (res) => {
                    if(res) {
                        this.$btnFollow.addClass('d-none');
                        this.$btnFollowing.removeClass('d-none');
                    }
                },
                error: () => {
                    this.$btnFollow.removeAttr('disabled');
                    this.$btnFollow.empty();
                    this.$btnFollow.append(
                        $('<i>', {class: 'fa fa-plus-circle mr-1'}),
                        $('<span>').html("Ikuti")
                    );

                    AlertNotif.adminlte.error(DBMessage.ERROR_NETWORK_MESSAGE, {
                        title: DBMessage.ERROR_NETWORK_TITLE,
                    });
                }
            })
        }
    });

    this.$btnInfo.click(() => {
        console.log(this.__list.options.routes);
        if(this.__list.options.routes.info !== null)
            $.createModal({
                url: this.__list.options.routes.info,
                data: {id: data.id},
            }).open();
    });
};

const ListFriends = function(selector, options = {}) {
    this.$ = $(selector);
    this.options = new ListFriendsOptions(options);

    this.$item = $('<div>', {class: 'col-6 col-sm-3 col-md-2 mb-2'}).append(
        $('<div>', {class: 'shadow bg-white rounded'}).append(
            $('<div>', {class: 'p-3 bg-white rounded'}).append(
                $('<div>', {class: 'position-relative'}).append(
                    $('<div>', {class: 'img-circle mx-auto mb-3 bg-light img-contain', 'data-action': 'image'}).css({width: 100, height: 100}),
                    $('<div>', {class: 'img-circle mx-auto bg-light img-contain img-bottom-right', 'data-action': 'image-achievement'}).css({width: 30, height: 30, right: '15%'}),
                ),
                $('<h6>', {class: 'text-bold text-overflow-ellipsis mb-0', 'data-action': 'full-name'}),
                $('<div>', {class: 'text-sm text-overflow-ellipsis mb-2', 'data-action': 'user-name'}).css({height: 20}),
                $('<p>', {class: 'block-ellipsis-2 d-none d-md-block', 'data-action': 'bio'}).css({height: 40, lineHeight: 1.2}),
                $('<div>', {class: 'text-xs line-height-1 mb-2 text-overflow-ellipsis', 'data-action': 'message'}).css({height: 15}),
                $('<div>', {class: 'd-flex'}).append(
                    $('<button>', {type: 'button', class: 'btn btn-primary btn-sm mr-1', 'data-action': 'show-info'}).append(
                        $('<i>', {class: 'fa fa-info-circle'})
                    ),
                    $('<button>', {type: 'button', class: 'btn bg-olive btn-sm btn-block d-none mt-0', 'data-action': 'follow'}).append(
                        $('<i>', {class: 'fa fa-plus mr-1'}),
                        $('<span>').html('Ikuti')
                    ),
                    $('<button>', {type: 'button', class: 'btn btn-outline-secondary btn-sm btn-block d-none mt-0', 'data-action': 'following'}).append(
                        $('<span>').html('Mengikuti')
                    )
                ),
            )
        )
    );
};

ListFriends.prototype.search = function(value) {
    if(this.options.routes.search !== null)
        if(value !== '')
            ServiceAjax.get(this.options.routes.search, {
                data: {
                    searchValue: value,
                },
                success: (res) => {
                    this.$.empty();
                    this.$.append($('<h5>', {class: 'col-12 mb-3'}).html(`Hasil Pencarian '${value}'`));

                    if(res.result && res.data.length > 0)
                        this.render(res.data);

                    else if(res.result && res.data.length === 0)
                        this.renderNotFound();
                },
                error: () => {
                    AlertNotif.adminlte.error(DBMessage.ERROR_NETWORK_MESSAGE, {
                        title: DBMessage.ERROR_NETWORK_TITLE
                    });
                }
            })
        else this.suggest(6);
};

ListFriends.prototype.suggest = function(number) {
    if(this.options.routes.search !== null)
        ServiceAjax.get(this.options.routes.search, {
            data: {
                random: number,
            },
            success: (res) => {
                this.$.empty();
                this.$.append($('<h5>', {class: 'col-12 mb-3'}).html('Orang yang mungkin anda kenal'));

                if(res.result && res.data.length > 0)
                    this.render(res.data);

                else if(res.result && res.data.length === 0)
                    this.renderNotFound("Tidak menemukan data");
            },
            error: () => {
                AlertNotif.adminlte.error(DBMessage.ERROR_NETWORK_MESSAGE, {
                    title: DBMessage.ERROR_NETWORK_TITLE
                });
            }
        })
};

ListFriends.prototype.render = function(items) {
    this.$.addClass('row');

    items.forEach(item => {
        const $item = $(this.$item.clone());
        const data = new ListFriendsData(item);
        const itemHandler = new ListFriendsItem($item, this);

        $item.data('data', data);
        $item.data('itemHandler', itemHandler);

        itemHandler.init();

        this.$.append($item);
    });
};

ListFriends.prototype.renderNotFound = function(message) {
    this.$.append(($('<div>', {class: 'col-12'}).html(
        $('<i>', {class: 'text-center'}).html(message === undefined ? "Kami tidak menemukan teman yang anda cari" : message)
    )));
};
