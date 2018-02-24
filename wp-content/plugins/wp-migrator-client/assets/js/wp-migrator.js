(function ($) {

    var WpMigratorManager = function () {

        this.migrationSteps   = {};
        this.stack            = [];
        this.lastSearchResult = false;

        this.migrateXhr        = false;
        this.migratePausedArgs = [];

        if (wp_migrator_loc.items) {

            var fuseOptions = {
                keys: ["name", "creator_name"],
                tokenize: true,
                matchAllTokens: true,
                threshold: 0.3
            };

            this.fuses = {

                theme: new Fuse(wp_migrator_loc.items.themes, fuseOptions),
                plugin: new Fuse(wp_migrator_loc.items.plugins, fuseOptions),
            };
        }

        this.init();
    }


    WpMigratorManager.prototype = {

        $document: $(document),

        $context: $("#wp-migrator-panel", this.$document),
        init: function () {
            var self = this;


            self.$document.ready(function () {

                self.$("#wpmg-container").fadeIn();

                self.bindEvents();
                self.checkboxes();
            });
        },

        $: function (selector, context) {

            return $(selector, context);
        },

        animate: function ($el, animateClass, hide) {


            var duration = 1000;

            $el.addClass('animated ' + animateClass).delay(duration).queue(function (n) {

                if (hide) {
                    $el.hide();
                }

                n();
            });

            if (!hide) {
                $el.show();
            }

        },


        animation_start: function () {

            var self = this;

            self.$('.migration-user-settings').hide();
            self.$('.bf-button-primary').hide();

            //
            // self.animate(self.$('.migration-process-control'), 'fadeInUp', false);
            self.$('.migration-process-control').fadeIn();

            self.animate(self.$('.migration-process-detail'), 'fadeInUp', false);

            var $loading = self.$('.migration-process-loading');
            if (!$loading.is(':visible')) {
                self.animate($loading, 'fadeInDown', false);
            }

            self.$('.spin-icon').addClass('fa-spin');
        },

        checkboxes: function () {

            var self = this;

            self.$(".bf-checkbox-multi-state").on('bf-checkbox-change', function () {
                var length = self.$(".setting .bf-checkbox-multi-state[data-current-state='active']").length;

                self.$(":submit").prop('disabled', !Boolean(length));
            }).trigger('bf-checkbox-change');

        },
        bindEvents: function () {
            var self = this;

            // Start migration button
            // self.$(".migration-button").on('click', " a:not(.disabled)", function () {
            self.$("#wp-migrator-form").on('submit', function () {

                var $this = self.$(this);

                if ($this.hasClass('disabled')) {

                    return false;
                }
                self.$(this).addClass('disabled');
                self.$(this).find(':submit').prop('disabled', true);

                self.animation_start();
                self.migrate();

                return false;
            });

            // Search products
            self.$(".wpmg-items-search input").on('keyup', function () {

                var $this       = self.$(this),
                    productType = $this.data('type'),
                    $context    = $this.closest('.wpmg-items');

                self.searchProducts(this.value, productType, $context);
            });

            // Tabs

            self.$(".wpmg-tabs").on('click', 'a', function () {

                var $this      = $(this),
                    $container = $this.closest('li');

                if ($container.hasClass('active')) {
                    return;
                }


                var prevId = self.$(".wpmg-tabs .active a").attr('href').toString().replace(/^\#+/, ''),
                    id     = $this.attr('href').toString().replace(/^\#+/, '');

                $container.addClass('active')
                    .siblings('li').removeClass('active');

                self.$("#" + prevId).stop().fadeOut(function () {
                    self.$("#" + id).stop().fadeIn();
                });

                return false;
            });


            // Pause/play btn

            self.$(".migration-process-control").on("click", "a", function () {

                var changes = false,
                    $this   = $(this),
                    action  = $this.data('action');

                if (action === 'pause') {

                    if (self.migrateXhr) {

                        self.migrateXhr.abort();

                        changes = {
                            action: 'play',
                            label: wp_migrator_loc.labels.play,
                            icon: ['fa-pause', 'fa-play'],
                            parentClass: ['started', 'paused'],

                            loadingAnimation: false
                        };
                    }

                } else if (action === 'play') {

                    self.migrate_ajax_request.apply(self, self.migratePausedArgs);

                    changes = {

                        action: 'pause',
                        label: wp_migrator_loc.labels.pause,
                        icon: ['fa-play', 'fa-pause'],
                        parentClass: ['paused', 'started'],

                        loadingAnimation: true
                    };
                }


                if (!changes) {
                    return false;
                }


                if (changes.loadingAnimation) {

                    self.$(".migrator-loading").removeClass('stop');
                    self.$('.migration-process-loading')
                        .find('.fa').addClass('fa-spin');

                } else {

                    self.$(".migrator-loading").addClass('stop');
                    self.$('.migration-process-loading')
                        .find('.fa').removeClass('fa-spin');
                }

                $this.data('action', changes.action);
                $this.find('span').html(changes.label);

                $this.find('.fa').removeClass(changes.icon[0]).addClass(changes.icon[1]);
                $this.parent().removeClass(changes.parentClass[0]).addClass(changes.parentClass[1]);

                return false;
            });
        },

        migrationCompleted: function (response) {

            var self = this;

            self.$('.migration-process-detail li:last')
                .find('.fa').attr('class', 'fa fa-check-square');

            var $container = self.$(".migration-finished");

            $container.show();

            // Animate finished message

            self.$('.migration-process-control').hide();

            self.animate(self.$('.heading', $container), 'fadeInUp', false);
            self.animate(self.$('.migrator-report', $container), 'fadeInDown', false);

            $(window).off('beforeunload.wp-migration');

            self.$(".migration-process-loading").hide();
            self.$(".migrator-loading").addClass('stop');

            var reports = response.data && typeof response.data === 'object' ? response.data : {};

            ['success', 'skipped', 'warning'].forEach(function (name) {

                if (typeof reports[name] !== 'undefined') {

                    self.$('.' + name + ' .number', $container).html(reports[name]);
                }

            });

            self.$('.message', $container).html(reports.msg);
        },
        /**
         * send ajax request and fire callback on success
         *
         * @param params {object} data to send
         * @param success_callback {Function} callback for ajax.done method
         */
        ajax: function (params, success_callback) {

            /**
             * prepare ajax data
             *
             * @param params {object}
             * @returns {*}
             * @private
             */

            var prepare_params = function (params) {
                var default_obj    = {},
                    default_params = $("#bs-pages-hidden-params").serializeArray();

                if (default_params) {
                    for (var i = 0; i < default_params.length; i++) {
                        default_obj[default_params[i].name] = default_params[i].value;
                    }
                }

                return $.extend(default_obj, params);
            };

            var self = this;

            return $.ajax({
                url: wp_migrator_loc.ajaxurl,
                type: 'POST',
                dataType: 'json',
                data: params//prepare_params(params)
            })
                .done(success_callback)
                .fail(function (e, status, res) {

                    if (status !== 'abort') {

                        var data = res.data || {};

                        self.show_error(data.message, data.code);
                    }
                })
        },

        searchProducts: function (searchQuery, productType, $context) {

            var self = this;

            if (!self.fuses[productType]) {
                return;
            }

            if (searchQuery) {

                var searchResult = self.fuses[productType].search(searchQuery);

                if (searchResult.toString() === self.lastSearchResult.toString()) {
                    // Save as previous search
                } else {

                    // Hide all items
                    self.$(".product-item", $context).hide();

                    // show/hide 404 message
                    self.$(".not-found", $context)[searchResult.length ? 'hide' : 'show']();


                    searchResult.forEach(function (item) {

                        var selector = '#product-item-{id}'.replace(/\{id\}/, item.id);

                        self.$(selector, $context).show();
                    });
                }

                self.lastSearchResult = searchResult;
            } else {
                // Show all item when search query is empty
                self.$(".product-item", $context).show();

                // hide 404 message
                self.$(".not-found", $context).hide();

                self.lastSearchResult = false;
            }
        },


        before_migrate_ajax_request: function (data) {

            var self = this;

            var startStep = parseInt(wp_migrator_loc.paused_step),
                steps     = data.steps;

            var isForce = $("#migrator-force-switch").val() === 'active',
                fresh   = !Boolean(wp_migrator_loc.paused_step) || isForce;

            var activeParts = {},
                passed      = {},
                current;

            if (steps && startStep && !fresh) {

                var td = 0;

                for (var i = 0; i < steps.length; i++) {

                    td += steps[i];

                    if (td >= startStep) {

                        current = data.types[i];

                        break;
                    } else {

                        if (typeof data.types[i] !== 'undefined') {
                            passed[data.types[i]] = true;
                        }
                    }
                }
            }

            $(".migrator-part").each(function () {

                if (this.value !== 'active') {
                    return;
                }

                var id = this.name.toString().match(/.+\[(.*?)\]$/)[1];

                activeParts[id] = true;

            }).promise().done(function () {

                // self.updateDetailBox

                var $context = self.$(".migration-process-detail");

                self.$("ul", $context).children('li').each(function (i) {

                    var liClass = '',
                        icon    = false,
                        $li     = $(this),
                        name    = $li.data('name');


                    if (typeof activeParts[name] === 'undefined') {

                        liClass = 'disabled';

                        icon = 'fa-times';

                    } else if (passed[name]) {

                        icon = 'fa-check-square';

                    } else if (name === current) {

                        icon = 'fa-spinner fa-spin';
                    }

                    if (i > 0 || !fresh) {
                        $li.find('.fa').remove();

                        if (icon) {

                            $li.prepend('<i class="fa ' + icon + '" aria-hidden="true"></i>');
                        }

                        $li[icon ? 'removeClass' : 'addClass']('no-icon');
                    }

                    if (liClass) {
                        $li.addClass(liClass);
                    }

                });


                if (current) {
                    self.$(".index", $context).css('top', $("li." + current, $context).position().top + 35);
                }

            });
        },

        /**
         * Run migration process
         *
         * @private
         */
        migrate: function () {

            var self = this;

            // Display progressbar to user

            $(window).on('beforeunload.wp-migration', function (e) {
                return true;
            });

            var data = self.$("#wp-migrator-form").serialize();

            // self.$('.migration-process-loading .label').html(
            //     wp_migratormigrator - part_loc.labels.starting.replace('%s', '<span class="percentage">0%</span>')
            // );

            // Get install/uninstall steps from server
            self.ajax(data,

                function (response) {

                    if (response && typeof response.success !== 'undefined' && response.success) {

                        var data = (function (steps) { // Prepare data of steps
                            var results   = self.migrationSteps;
                            results.steps = [];
                            results.types = [];

                            var count = 0,
                                total = 0;

                            if (steps) {

                                for (var k in steps) {
                                    results.steps.push(steps[k]);
                                    results.types.push(k);

                                    total += steps[k];

                                    count++;
                                }
                            }

                            results.count = count - 1;
                            results.total = total;

                            return results;
                        })(response.data.steps);


                        var stepProps = (function (startStep, steps) {

                            var index = 0, step_number = 1, progress_step = 1;

                            if (startStep) {

                                var td = 0;

                                for (var i = 0; i < steps.length; i++) {

                                    td += steps[i];

                                    if (td >= startStep) {

                                        index         = i;
                                        progress_step = startStep;
                                        step_number   = Math.abs(td - startStep - steps[i]);

                                        break;
                                    }
                                }
                            }

                            return [index, step_number, progress_step];

                        })(response.data.start_step, data.steps);

                        self.$(".migrator-loading").removeClass('single').removeClass('stop');

                        var product = self.$("input[name='product']").val();

                        self.before_migrate_ajax_request(data);
                        self.migrate_ajax_request.apply(self, [product].concat(stepProps));

                        if (response.data.migration_id) {
                            $("#migration_uuid").val(response.data.migration_id);
                        }

                        self.$('.migration-process-control').removeClass('disabled');
                        self.$('.migration-process-loading .label').html(
                            wp_migrator_loc.labels.importing.replace('%s', '<span class="percentage">0%</span>')
                        );

                    } else {

                        var data = response.data || {};

                        self.show_error(data.message, data.code);
                    }
                }
            );
        },


        migrate_ajax_request: function (product, index, step_number, progress_step) {

            var self       = this,
                ajaxParams = {
                    action: 'migration_process',
                    product: product,
                    current_type: self.migrationSteps.types[index],
                    current_step: step_number,
                };

            self.migratePausedArgs = [].slice.call(arguments);

            self.migrateXhr = self.ajax(
                ajaxParams,
                function (response) {

                    if (response && typeof response.success !== 'undefined' && response.success) {

                        // Increase loading
                        var percentage = Math.max(
                            10,
                            Math.floor(100 / self.migrationSteps.total * progress_step)
                        );
                        self.updateProgressBar(percentage);

                        if (self.migrationSteps.count <= index && self.migrationSteps.steps[index] <= step_number) {

                            self.migrationCompleted(response);

                        } else {

                            if (step_number === 1 && index) {
                                self.updateDetailBox(index, true);
                            }

                            // Calculate next step position
                            if (self.migrationSteps.steps[index] <= step_number) {
                                index++;
                                step_number = 1;
                            } else {
                                step_number++;
                            }

                            self.migrate_ajax_request(product, index, step_number, progress_step + 1);
                        }
                    } else {

                        var data = response.data || {};

                        self.show_error(data.message, data.code);
                    }
                }
            );
        },

        updateProgressBar: function (percentage) {
            var $context = this.$(".migration-process-loading");

            this.$(".mg-pages-progressbar", $context).css('width', percentage + '%');
            this.$(".percentage", $context).text(percentage + '%');
        },

        updateDetailBox: function (index, updatePivot) {

            var $context = this.$('.migration-process-detail');

            var $row = this.$("li:nth-child(" + index + ")", $context);
            $row.find('.fa').attr('class', 'fa fa-check-square');

            var $next = $row.nextAll('li:not(.disabled)').first();

            if ($next.length) {

                $next.find('.fa').remove();
                $next.removeClass('no-icon')
                    .prepend('<i class="fa fa-spinner fa-spin"></i>');

                if (updatePivot) {
                    this.$(".index", $context).css('top', $next.position().top + 35);
                }
            }

        },

        show_error: function (error_message, error_code) {

            var self = this;

            self.stack = [];

            if (typeof $.bs_modal !== 'function') {

                alert("an error occurred");

                return;
            }

            var loc = $.extend({}, wp_migrator_loc.on_error);

            loc.body = loc.display_error
                .replace('%ERROR_CODE%', error_code)
                .replace('%ERROR_MSG%', error_message);


            $.bs_modal({
                content: $.extend(loc, {body: loc.body}),

                buttons: {
                    close_modal: {
                        label: loc.button_ok,
                        type: 'primary',
                        action: 'close'
                    },
                }
            });
        }
    };

    window.WpMigrator = new WpMigratorManager();

})(jQuery);