(function ($) {
    'use strict';

    var supro = supro || {};
    supro.init = function () {
        supro.$body = $(document.body),
            supro.$window = $(window),
            supro.$header = $('#masthead'),
            supro.ajaxXHR = null;


        // Scroll
        this.scrollTop();
        this.preload();

        // Parallax
        this.parallax();
        this.suproToggleClass();
        this.photoSwipe();

        // Header
        this.canvasPanel();
        this.menuSideBar();
        this.instanceSearch();
        this.toggleModal();
        this.menuHover();
        this.stickyHeader();
        this.headerCss();

        // Blog
        this.blogLayout();

        // Shop
        this.shopView();
        this.catalogSorting();
        this.shopLoadingAjax();
        this.viewPort();
        this.showFilterContent();
        this.filterAjax();
        this.productQuantity();
        this.addWishlist();
        this.showAddedToCartNotice();
        this.toolTipIcon();
        this.productQuickView();
        this.productAttribute();
        this.filterScroll();
        this.recentlyViewedProducts();

        // Single Product
        this.resizeProductGallery();
        this.productThumbnail();
        this.productGalleryCarousel();
        this.productvariation();
        this.singleProductCarousel();
        this.productImagesLightbox();
        this.stickyProductSummary();
        this.loginTab();
        this.addToCartAjax();
        this.buyNow();

        // Login
        this.loginModalAuthenticate();

        // Portfolio
        this.portfolioMasonry();
        this.portfolioLoadingAjax();
        this.portfolioCarousel();
    };

    supro.suproToggleClass = function () {
        supro.$window.on('resize', function () {
            var wWidth = $('#content').width(),
                space = 0;

            if (supro.$window.width() <= 1600) {
                $('.menu-extra li.menu-item-search').removeAttr('id');
            }

            if (wWidth > 1170) {
                space = (wWidth - 1170) / 2;
            }

            $('.portfolio-carousel .swiper-container').css({
                'margin-right': space * -1,
                'margin-left': space * -1,
                'padding-right': space,
                'padding-left': space
            });

            $('.portfolio-carousel .swiper-button-prev').css({
                'left': space - 62
            });

            $('.portfolio-carousel .swiper-button-next').css({
                'right': space - 62
            });

            if (suproData.isRTL !== '1') {
                $('.supro-right-offset').css({
                    'margin-right': space * -1
                });

                $('.supro-left-offset').css({
                    'margin-left': space * -1
                });
            }

        }).trigger('resize');
    };

    supro.resizeProductGallery = function () {
        var $product = $('.woocommerce div.product');
        if (!$product.hasClass('supro-product-layout-6')) {
            return;
        }

        if (supro.$window.width() <= 991) {
            return;
        }

        supro.$window.on('resize', function () {
            var wWidth = $('#content').width(),
                wRight = 0;

            if (wWidth > 1170) {
                wRight = (wWidth - 1170) / 2;
            }

            $('.supro-single-product-detail').find('.woocommerce-product-gallery').css({
                'margin-right': wRight * -1
            });


        }).trigger('resize');
    };

    // Scroll Top
    supro.scrollTop = function () {
        var $scrollTop = $('#scroll-top');
        supro.$window.scroll(function () {
            if (supro.$window.scrollTop() > supro.$window.height()) {
                $scrollTop.addClass('show-scroll');
            } else {
                $scrollTop.removeClass('show-scroll');
            }
        });

        // Scroll effect button top
        $scrollTop.on('click', function (event) {
            event.preventDefault();
            $('html, body').stop().animate({
                    scrollTop: 0
                },
                800
            );
        });
    };

    /**
     * Add page preloader
     */
    supro.preload = function () {
        var $preloader = $('#preloader'),
            without_link = false;

        if (!$preloader.length) {
            return;
        }

        supro.$body.on('click', 'a', function () {
            without_link = false;
            if ($(this).hasClass('sp-without-preloader')) {
                without_link = true;
            }
        });

        supro.$window.on('beforeunload', function (e) {
            if (without_link) {
                return;
            }
            $preloader.removeClass('out').fadeIn(500, function () {
                $preloader.addClass('loading');
            });
        });

        supro.$window.on('pageshow', function () {
            $preloader.fadeOut(100, function () {
                $preloader.addClass('out loading');
            });
        });

        supro.$window.on('beforeunload', function () {
            $preloader.fadeIn('slow');
        });

        NProgress.start();
        supro.$window.on('load', function () {
            NProgress.done();
            $preloader.fadeOut(800);
        });
    };

    // Parallax
    supro.parallax = function () {
        supro.$window.on('resize', function () {
            if (supro.$window.width() > 1199) {
                $('.page-header.parallax .feature-image').parallax('50%', 0.6);
                $('.supro-sale-product.parallax').parallax('50%', 0.6);
            }
        }).trigger('resize');
    };

    // photoSwipe
    supro.photoSwipe = function () {
        var $images = $('.supro-photo-swipe');

        if (!$images.length) {
            return;
        }

        var $links = $images.find('a.photoswipe'),
            items = [];

        $links.each(function () {
            var $this = $(this),
                $w = $this.attr('data-width'),
                $h = $this.attr('data-height');

            items.push({
                src: $this.attr('href'),
                w: $w,
                h: $h
            });

        });

        $images.on('click', 'a.photoswipe', function (e) {
            e.preventDefault();

            var index = $links.index($(this)),
                options = {
                    index: index,
                    bgOpacity: 0.85,
                    showHideOpacity: true,
                    mainClass: 'pswp--minimal-dark',
                    barsSize: {top: 0, bottom: 0},
                    captionEl: false,
                    fullscreenEl: false,
                    shareEl: false,
                    tapToClose: true,
                    tapToToggleControls: false
                };

            var lightBox = new PhotoSwipe(document.getElementById('pswp'), window.PhotoSwipeUI_Default, items, options);
            lightBox.init();
        });
    };

    // Off Canvas Panel
    supro.canvasPanel = function () {
        /**
         * Off canvas cart toggle
         */
        supro.$header.on('click', '.cart-contents', function (e) {
            e.preventDefault();
            supro.openCanvasPanel($('#cart-panel'));
        });

        if (suproData.open_cart_mini == '1') {
            $(document.body).on('added_to_cart', function () {
                supro.openCanvasPanel($('#cart-panel'));
            });
        }

        supro.$header.on('click', '#icon-menu-sidebar', function (e) {
            e.preventDefault();
            supro.openCanvasPanel($('#menu-sidebar-panel'));
        });

        supro.$header.on('click', '#icon-menu-mobile', function (e) {
            e.preventDefault();
            supro.openCanvasPanel($('#menu-sidebar-panel'));
        });

        supro.$window.on('resize', function () {
            if (supro.$window.width() > 1199) {
                supro.$body.on('click', '.un-filter', function (e) {
                    e.preventDefault();
                    supro.openCanvasPanel($('#un-shop-topbar'));
                });
            }

        }).trigger('resize');

        supro.$body.on('click', '#off-canvas-layer, .close-canvas-panel', function (e) {
            e.preventDefault();
            supro.closeCanvasPanel();
        });
    };

    supro.openCanvasPanel = function ($panel) {
        supro.$body.addClass('open-canvas-panel');
        $panel.addClass('open');
    };

    supro.closeCanvasPanel = function () {
        supro.$body.removeClass('open-canvas-panel');
        $('.supro-off-canvas-panel, #primary-mobile-nav, .catalog-sidebar, .shop-topbar').removeClass('open');

        supro.$window.on('resize', function () {
            if (supro.$window.width() > 1199) {
                $('.un-filter').removeClass('active');
            }

        }).trigger('resize');
    };

    // Toggle Menu Sidebar
    supro.menuSideBar = function () {
        var $menuSidebar = $('#menu-sidebar-panel'),
            $item = $menuSidebar.find('li.menu-item-has-children > a');

        $menuSidebar.find('.menu .menu-item-has-children').prepend('<span class="toggle-menu-children"><i class="icon-plus"></i> </span>');

        if (suproData.menu_mobile_behaviour === 'icon' && supro.$window.width() < 1200) {
            $item = $menuSidebar.find('li.menu-item-has-children .toggle-menu-children');
        }

        supro.mobileMenuSidebar($item);
    };

    supro.mobileMenuSidebar = function ($item) {
        $item.on('click', function (e) {
            e.preventDefault();

            $(this).closest('li').siblings().find('ul.sub-menu, ul.dropdown-submenu').slideUp();
            $(this).closest('li').siblings().removeClass('active');

            $(this).closest('li').children('ul.sub-menu, ul.dropdown-submenu').slideToggle();
            $(this).closest('li').toggleClass('active');

        });
    };

    /**
     * Product instance search
     */
    supro.instanceSearch = function () {

        if (suproData.ajax_search === '0') {
            return;
        }

        var xhr = null,
            searchCache = {},
            $modal = $('.search-modal'),
            $form = $modal.find('form'),
            $search = $form.find('input.search-field'),
            $results = $modal.find('.search-results');

        $modal.on('keyup', '.search-field', function (e) {
            var valid = false;

            if (typeof e.which == 'undefined') {
                valid = true;
            } else if (typeof e.which == 'number' && e.which > 0) {
                valid = !e.ctrlKey && !e.metaKey && !e.altKey;
            }

            if (!valid) {
                return;
            }

            if (xhr) {
                xhr.abort();
            }

            search();
        }).on('change', '.product-cats input', function () {
            if (xhr) {
                xhr.abort();
            }

            search();
        }).on('focusout', '.search-field', function () {
            if ($search.val().length < 2) {
                $modal.removeClass('searching searched actived found-products found-no-product invalid-length');
            }
        });

        outSearch();

        /**
         * Private function for search
         */
        function search() {
            var keyword = $search.val(),
                cat = '';

            if ($modal.find('.product-cats').length > 0) {
                cat = $modal.find('.product-cats input:checked').val();
            }

            if (keyword.length < 2) {
                $modal.removeClass('searching searched actived found-products found-no-product').addClass('invalid-length');
                return;
            }

            $modal.removeClass('found-products found-no-product').addClass('searching');

            var keycat = keyword + cat;

            if (keycat in searchCache) {
                var result = searchCache[keycat];

                $modal.removeClass('searching');

                $modal.addClass('found-products');

                $results.find('.woocommerce').html(result.products);

                $(document.body).trigger('supro_ajax_search_request_success', [$results]);

                $results.find('.woocommerce, .buttons').slideDown(function () {
                    $modal.removeClass('invalid-length');
                });

                $modal.addClass('searched actived');
            } else {
                xhr = $.ajax({
                    url: suproData.ajax_url,
                    dataType: 'json',
                    method: 'post',
                    data: {
                        action: 'supro_search_products',
                        nonce: suproData.nonce,
                        term: keyword,
                        cat: cat,
                        search_type: suproData.search_content_type
                    },
                    success: function (response) {
                        var $products = response.data;

                        $modal.removeClass('searching');

                        $modal.addClass('found-products');

                        $results.find('.woocommerce').html($products);

                        $results.find('.woocommerce, .buttons').slideDown(function () {
                            $modal.removeClass('invalid-length');
                        });

                        $(document.body).trigger('supro_ajax_search_request_success', [$results]);

                        // Cache
                        searchCache[keycat] = {
                            found: true,
                            products: $products
                        };

                        $modal.addClass('searched actived');
                    }
                });
            }

        }

        /**
         * Private function for click out search
         */
        function outSearch() {

            if (supro.$body.hasClass('header-layout-3') || supro.$body.hasClass('header-layout-5') || supro.$body.hasClass('header-layout-6')) {
                return;
            }

            var $modal = $('.search-modal'),
                $search = $modal.find('input.search-field');
            if ($modal.length <= 0) {
                return;
            }

            supro.$window.on('scroll', function () {
                if (supro.$window.scrollTop() > 10) {
                    $modal.removeClass('show found-products searched');
                }
            });

            $(document).on('click', function (e) {
                var target = e.target;
                if (!$(target).closest('.extra-menu-item').hasClass('menu-item-search')) {
                    $modal.removeClass('searching searched found-products found-no-product invalid-length');
                }
            });

            $modal.on('click', '.t-icon', function (e) {
                if ($modal.hasClass('actived')) {
                    e.preventDefault();
                    $search.val('');
                    $modal.removeClass('searching searched actived found-products found-no-product invalid-length');
                }

            });
        }
    };

    /**
     *  Toggle modal
     */
    supro.toggleModal = function () {
        supro.$body.on('click', '.menu-extra-search', function (e) {
            e.preventDefault();
            supro.openModal($('.search-modal'));
            $(this).addClass('show');
            $('#search-modal').find('.search-field').focus();
        });

        supro.$body.on('click', '#supro-newsletter-icon', function (e) {
            e.preventDefault();
            supro.openModal($('#footer-newsletter-modal'));
            $(this).addClass('hide');
        });

        if (suproData.login_popup !== '1') {
            supro.$body.on('click', '#menu-extra-login', function (e) {
                e.preventDefault();

                supro.openModal($('#login-modal'));
                $(this).addClass('show');
            });
        }

        supro.$body.on('click', '.close-modal', function (e) {
            e.preventDefault();
            supro.closeModal();
        });
    };

    /**
     * Main navigation sub-menu hover
     */
    supro.menuHover = function () {

        var animations = {
            none: ['show', 'hide'],
            fade: ['fadeIn', 'fadeOut'],
            slide: ['slideDown', 'slideUp']
        };

        var animation = suproData.menu_animation ? animations[suproData.menu_animation] : 'fade';

        $('.primary-nav li, .menu-extra li.menu-item-account').on('mouseenter', function () {
            $(this).addClass('active').children('.dropdown-submenu').stop(true, true)[animation[0]]();
        }).on('mouseleave', function () {
            $(this).removeClass('active').children('.dropdown-submenu').stop(true, true)[animation[1]]();
        });
    };

    // Header CSS
    supro.headerCss = function () {
        if (!supro.$body.hasClass('topbar-enable')) {
            return;
        }

        if (supro.$body.hasClass('header-left-sidebar')) {
            return;
        }

        if (supro.$body.hasClass('page-template-template-coming-soon-page')) {
            return;
        }

        var $wpAdminBar = $('#wpadminbar'),
            $headerTransparent = $('.header-transparent .site-header'),
            $topbarTransparent = $('.header-transparent .topbar'),
            adminBarHeight = $wpAdminBar.outerHeight(true),
            top = 0;

        if ($wpAdminBar.length) {
            top += adminBarHeight;

            if (supro.$body.hasClass('page-template-template-home-boxed')) {
                top += 20;
            }
        }

        supro.$window.on('resize', function () {
            var topBar = $('.topbar').outerHeight(true);

            if (supro.$window.width() < 1200) {
                topBar = $('.topbar-mobile').outerHeight(true);
            }

            $topbarTransparent.css('top', top);
            $headerTransparent.css('top', top + topBar);

        }).trigger('resize');

        supro.$window.on('scroll', function () {
            var scrollTop,
                scroll = supro.$window.scrollTop(),
                hHeader = supro.$header.outerHeight(true);

            var topBar = $('.topbar').outerHeight(true);

            if (supro.$window.width() < 1200) {
                topBar = $('.topbar-mobile').outerHeight(true);
            }

            scrollTop = 20 + topBar + hHeader;

            if (scroll > scrollTop) {
                $headerTransparent.css('top', top);
            } else {
                $headerTransparent.css('top', top + topBar);
            }
        });
    };

    supro.stickyHeader = function () {
        if (!supro.$body.hasClass('header-sticky')) {
            return;
        }

        if (supro.$body.hasClass('header-left-sidebar')) {
            return;
        }

        if (supro.$body.hasClass('page-template-template-coming-soon-page')) {
            return;
        }

        supro.$window.on('scroll', function () {
            var scrollTop = 20,
                scroll = supro.$window.scrollTop(),
                topbar,
                hHeader = supro.$header.outerHeight(true);

            if (supro.$body.hasClass('topbar-enable')) {
                topbar = $('.topbar').outerHeight(true);

                if (supro.$window.width() < 1200) {
                    topbar = $('.topbar-mobile').outerHeight(true);
                }

                scrollTop = scrollTop + topbar + hHeader;
            }

            if (scroll > scrollTop) {
                supro.$header.addClass('minimized');
                $('#su-header-minimized').addClass('minimized');
            } else {
                supro.$header.removeClass('minimized');
                $('#su-header-minimized').removeClass('minimized');
            }
        });

        supro.$window.on('resize', function () {
            var hHeader = supro.$header.outerHeight(true),
                $h = $('#su-header-minimized');

            if (!supro.$body.hasClass('header-transparent')) {
                $h.height(hHeader);
            }
        }).trigger('resize');
    };

    /**
     * Open modal
     *
     * @param $modal
     */
    supro.openModal = function ($modal) {
        supro.$body.addClass('modal-open');
        $modal.fadeIn();
        $modal.addClass('open');
    };

    /**
     * Close modal
     */
    supro.closeModal = function () {
        supro.$body.removeClass('modal-open');
        $('.supro-modal').fadeOut(function () {
            supro.$body.find('.menu-extra-search, #menu-extra-login').removeClass('show');
            $('#supro-newsletter-icon').removeClass('hide');
            $(this).removeClass('open');
        });
    };

    // Blog isotope
    supro.blogLayout = function () {

        if (!supro.$body.hasClass('blog-masonry')) {
            return;
        }

        var $blogList = supro.$body.find('.supro-post-list');

        $blogList.imagesLoaded(function () {
            $blogList.isotope({
                itemSelector: '.blog-wrapper',
                percentPosition: true,
                masonry: {
                    columnWidth: '.blog-masonry-sizer',
                    gutter: '.blog-gutter-sizer'
                }
            });
        });
    };

    /**
     * Shop
     */
        // Show Filter widget
    supro.showFilterContent = function () {
        var $shopTopbar = $('#un-shop-topbar');

        supro.$window.on('resize', function () {
            $shopTopbar.find('.widget-title').next().removeAttr('style');
        }).trigger('resize');

        supro.$body.find('#supro-catalog-filter-mobile').on('click', 'a', function (e) {
            e.preventDefault();
            $(this).toggleClass('active');
            //	supro.$body.toggleClass( 'show-filters-content-mobile' );
            supro.$body.addClass('open-canvas-panel');

            if (supro.$body.hasClass('full-content')) {
                $('.shop-topbar').addClass('open');
            } else {
                $('.catalog-sidebar').addClass('open');
            }
        });

        supro.$body.find('#supro-catalog-filter').on('click', 'a', function (e) {
            e.preventDefault();
            $(this).toggleClass('active');
            $shopTopbar.slideToggle();
            supro.$body.toggleClass('show-filters-content');
        });

        supro.$body.find('.filters-bottom').on('click', 'a', function (e) {
            e.preventDefault();
            $(this).addClass('active');
            supro.$body.addClass('show-filters-content');
        });
    };

    // Filter Ajax
    supro.filterAjax = function () {

        if (!supro.$body.hasClass('catalog-ajax-filter')) {
            return;
        }

        supro.$body.on('price_slider_change', function (event, ui) {
            var form = $('.price_slider').closest('form').get(0),
                $form = $(form),
                url = $form.attr('action') + '?' + $form.serialize();

            $(document.body).trigger('supro_catelog_filter_ajax', url, $(this));
        });


        supro.$body.on('click', '#remove-filter-actived', function (e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $(document.body).trigger('supro_catelog_filter_ajax', url, $(this));
        });

        supro.$body.find('#supro-shop-toolbar').find('.woocommerce-ordering').on('click', 'a', function (e) {
            e.preventDefault();
            $(this).addClass('active');
            var url = $(this).attr('href');
            $(document.body).trigger('supro_catelog_filter_ajax', url, $(this));
        });

        supro.$body.find('#un-shop-topbar, .catalog-sidebar').on('click', 'a', function (e) {
            var $widget = $(this).closest('.widget');
            if ($widget.hasClass('widget_product_tag_cloud') ||
                $widget.hasClass('supro_widget_product_categories') ||
                $widget.hasClass('widget_product_categories') ||
                $widget.hasClass('widget_layered_nav_filters') ||
                $widget.hasClass('widget_layered_nav') ||
                $widget.hasClass('product-sort-by') ||
                $widget.hasClass('supro-price-filter-list')) {
                e.preventDefault();
                $(this).closest('li').addClass('chosen');
                var url = $(this).attr('href');
                $(document.body).trigger('supro_catelog_filter_ajax', url, $(this));
            }

            if ($widget.hasClass('widget_product_tag_cloud')) {
                $(this).addClass('selected');
            }

            if ($widget.hasClass('product-sort-by')) {
                $(this).addClass('active');
            }
        });

        supro.$body.find('#supro-catalog-taxs-list').on('click', 'a', function (e) {
            e.preventDefault();
            $(this).addClass('selected');
            $(this).closest('li').siblings('li').find('a').removeClass('selected');
            var url = $(this).attr('href');
            $(document.body).trigger('supro_catelog_filter_ajax', url, $(this));
        });

        supro.$body.on('supro_catelog_filter_ajax', function (e, url, element) {

            var $container = $('#supro-shop-content'),
                $container_nav = $('#primary-sidebar'),
                $shopTopbar = $('#un-shop-topbar'),
                $shopToolbar = $('#supro-shop-toolbar'),
                $ordering = $('.shop-toolbar .woocommerce-ordering'),
                $found = $('.shop-toolbar .product-found'),
                $pageHeader = $('.page-header-catalog'),
                $productHeader = $('.woocommerce-products-header');

            if ($shopToolbar.length > 0) {
                var position = $shopToolbar.offset().top - 200;
                $('html, body').stop().animate({
                        scrollTop: position
                    },
                    1200
                );
            }

            $('.supro-catalog-loading').addClass('show');

            if ('?' == url.slice(-1)) {
                url = url.slice(0, -1);
            }

            url = url.replace(/%2C/g, ',');

            history.pushState(null, null, url);

            $(document.body).trigger('supro_ajax_filter_before_send_request', [url, element]);

            if (supro.ajaxXHR) {
                supro.ajaxXHR.abort();
            }

            supro.ajaxXHR = $.get(url, function (res) {

                var $sContent = $(res).find('#supro-shop-content').length > 0 ? $(res).find('#supro-shop-content').html() : '';
                $container.html($sContent);
                $container_nav.html($(res).find('#primary-sidebar').html());
                $shopTopbar.html($(res).find('#un-shop-topbar').html());

                if ($(res).find('.shop-toolbar .woocommerce-ordering').length > 0) {
                    $ordering.html($(res).find('.shop-toolbar .woocommerce-ordering').html());
                }

                $found.html($(res).find('.shop-toolbar .product-found').html());
                $pageHeader.html($(res).find('#page-header-catalog').html());
                $productHeader.html($(res).find('.woocommerce-products-header').html());

                supro.priceSlider();
                supro.filterScroll();
                $('.supro-catalog-loading').removeClass('show');

                $(document.body).trigger('supro_ajax_filter_request_success', [res, url]);

                supro.toolTipIcon();
                supro.shopView();
                supro.showFilterContent();

            }, 'html');
        });
    };

    // Change product quantity

    supro.productQuantity = function () {
        supro.$body.on('click', '.quantity .increase, .quantity .decrease', function (e) {
            e.preventDefault();

            var $this = $(this),
                $qty = $this.siblings('.qty'),
                step = parseInt($qty.attr('step'), 10),
                current = parseInt($qty.val(), 10),
                min = parseInt($qty.attr('min'), 10),
                max = parseInt($qty.attr('max'), 10);

            min = min ? min : 1;
            max = max ? max : current + 1;

            if ($this.hasClass('decrease') && current > min) {
                $qty.val(current - step);
                $qty.trigger('change');
            }
            if ($this.hasClass('increase') && current < max) {
                $qty.val(current + step);
                $qty.trigger('change');
            }
        });
    };

    supro.catalogSorting = function () {
        var $sortingMobile = $('#supro-catalog-sorting-mobile');

        $('#supro-shop-toolbar').on('click', '#supro-woocommerce-ordering-mobile', function (e) {
            e.preventDefault();
            $sortingMobile.addClass('sort-by-active');
        });

        $sortingMobile.on('click', '.cancel-order', function (e) {
            e.preventDefault();
            $sortingMobile.removeClass('sort-by-active');
        });
    };

    // Shop View
    supro.shopView = function () {
        $('#supro-shop-view').on('click', 'a', function (e) {
            e.preventDefault();
            var $el = $(this),
                view = $el.data('view');

            if ($el.hasClass('current')) {
                return;
            }

            $el.addClass('current').siblings().removeClass('current');
            supro.$body.removeClass('shop-view-grid shop-view-list').addClass('shop-view-' + view);

            document.cookie = 'shop_view=' + view + ';domain=' + window.location.host + ';path=/';
        });

        if (supro.$body.hasClass('catalog-masonry') && supro.$body.hasClass('shop-view-list')) {
            supro.$body.removeClass('shop-view-list').addClass('shop-view-grid');
        }
    };

    // Loading Ajax
    supro.shopLoadingAjax = function () {

        // Shop Page
        supro.$body.on('click', '#supro-shop-infinite-loading a.next', function (e) {
            e.preventDefault();

            if ($(this).data('requestRunning')) {
                return;
            }

            $(this).data('requestRunning', true);

            var $products = $(this).closest('.woocommerce-pagination').prev('.products'),
                $pagination = $(this).closest('.woocommerce-pagination'),
                $parent = $(this).parents('#supro-shop-infinite-loading');

            $parent.addClass('loading');

            $.get(
                $(this).attr('href'),
                function (response) {
                    var content = $(response).find('ul.products').children('li.product'),
                        $pagination_html = $(response).find('.woocommerce-pagination').html();

                    $pagination.html($pagination_html);

                    for (var index = 0; index < content.length; index++) {
                        $(content[index]).css('animation-delay', index * 100 + 100 + 'ms');
                    }

                    content.addClass('suproFadeInUp suproAnimation');

                    $products.append(content);
                    $pagination.find('.page-numbers.next').data('requestRunning', false);
                    $parent.removeClass('loading');
                    supro.$body.trigger('supro_shop_ajax_loading_success');

                    if (!$pagination.find('li .page-numbers').hasClass('next')) {
                        $pagination.addClass('loaded');
                    }

                    supro.toolTipIcon();
                    supro.$body.trigger('yith_wcwl_init');
                }
            );
        });
    };

    supro.viewPort = function () {
        if ('infinite' !== suproData.shop_nav_type) {
            return;
        }

        supro.$window.on('scroll', function () {
            if (supro.$body.find('#supro-shop-infinite-loading').is(':in-viewport')) {
                supro.$body.find('#supro-shop-infinite-loading a.next').trigger('click');
            }
        }).trigger('scroll');
    };

    /**
     * Show photoSwipe lightbox for product images
     */
    supro.productImagesLightbox = function () {
        var $images = $('.woocommerce-product-gallery');

        if (!$images.length) {
            return;
        }

        if ('no' == suproData.product.lightbox) {
            $images.on('click', 'a.gallery-item-icon, a.video-item-icon', function () {
                return false;
            });
            return;
        }

        var $links = $images.find('.woocommerce-product-gallery__image');

        $images.on('click', 'a.gallery-item-icon', function (e) {
            e.preventDefault();

            var items = [];

            $links.each(function () {
                var $a = $(this).find('a');
                items.push({
                    src: $a.attr('href'),
                    w: $a.find('img').attr('data-large_image_width'),
                    h: $a.find('img').attr('data-large_image_height')
                });
            });

            var index = $images.find('.flex-active-slide').index();
            lightBox(index, items);
        });

        $images.on('click', 'a.video-item-icon', function (e) {
            e.preventDefault();

            var items = [],
                $el = $(this);

            items.push({
                html: $el.data('href')
            });

            var index = 0;
            lightBox(index, items);
        });

        $images.find('.woocommerce-product-gallery__image').on('click', 'a', function (e) {
            e.preventDefault();

            if (supro.$body.hasClass('single-product-layout-1') ||
                supro.$body.hasClass('single-product-layout-2')
            ) {
                return false;
            }

            var items = [];

            $links.each(function () {
                var $a = $(this).find('a');
                items.push({
                    src: $a.attr('href'),
                    w: $a.find('img').attr('data-large_image_width'),
                    h: $a.find('img').attr('data-large_image_height')
                });
            });

            var index = $links.index($(this).closest('.woocommerce-product-gallery__image'));
            lightBox(index, items);
        });


        function lightBox(index, items) {

            var options = {
                index: index,
                bgOpacity: 0.85,
                showHideOpacity: true,
                mainClass: 'pswp--minimal-dark',
                barsSize: {top: 0, bottom: 0},
                captionEl: false,
                fullscreenEl: false,
                shareEl: false,
                tapToClose: true,
                tapToToggleControls: false
            };

            var lightBox = new PhotoSwipe(document.getElementById('pswp'), window.PhotoSwipeUI_Default, items, options);
            lightBox.init();

            lightBox.listen('close', function () {
                $('#pswp .video-wrapper').find('iframe').each(function () {
                    $(this).attr('src', $(this).attr('src'));
                });

                $('#pswp .video-wrapper').find('video').each(function () {
                    $(this)[0].pause();
                });
            });
        }
    };

    /**
     * Make product summary be sticky when use product layout 1
     */
    supro.stickyProductSummary = function () {
        if ($.fn.stick_in_parent && supro.$window.width() > 991) {
            var $el = $('div.product .sticky-summary'),
                offSet = 30;

            if (supro.$body.hasClass('header-sticky')) {
                offSet = 130;
            }

            $el.stick_in_parent({
                parent: '.supro-single-product-detail',
                offset_top: offSet
            });
        }
    };

    /**
     * Change product quantity
     */
    supro.productThumbnail = function () {

        if (suproData.product.thumb_slider != '1') {
            return;
        }

        var $gallery = $('.woocommerce-product-gallery');
        var $thumbnail = $gallery.find('.flex-control-thumbs');
        $gallery.imagesLoaded(function () {
            setTimeout(function () {
                if ($thumbnail.length < 1) {
                    return;
                }
                var columns = $gallery.data('columns');
                var count = $thumbnail.find('li').length;
                if (count > columns) {
                    if (suproData.product.thumb_vertical == '1') {
                        var vertical = true;

                        if (supro.$window.width() <= 480) {
                            vertical = false
                        }

                        $thumbnail.not('.slick-initialized').slick({
                            rtl: suproData.isRTL === '1',
                            slidesToShow: columns,
                            focusOnSelect: true,
                            slidesToScroll: 1,
                            vertical: vertical,
                            infinite: false,
                            prevArrow: '<span class="icon-chevron-up slick-prev-arrow"></span>',
                            nextArrow: '<span class="icon-chevron-down slick-next-arrow"></span>',
                            responsive: [
                                {
                                    breakpoint: 768,
                                    settings: {
                                        slidesToShow: 4
                                    }
                                },
                                {
                                    breakpoint: 480,
                                    settings: {
                                        slidesToShow: 3
                                    }
                                }
                            ]
                        });

                        $thumbnail.find('li.slick-current').trigger('click');
                    } else {
                        $thumbnail.not('.slick-initialized').slick({
                            rtl: suproData.isRTL === '1',
                            slidesToShow: columns,
                            focusOnSelect: true,
                            slidesToScroll: 1,
                            infinite: false,
                            prevArrow: '<span class="icon-chevron-left slick-prev-arrow"></span>',
                            nextArrow: '<span class="icon-chevron-right slick-next-arrow"></span>'
                        });
                    }
                } else {
                    $thumbnail.addClass('no-slick');
                }

            }, 100);

        });

    };

    supro.productGalleryCarousel = function () {

        if (suproData.product.gallery_carousel != '1') {
            return;
        }

        var $product = $('.woocommerce div.product'),
            $gallery = $('div.images', $product),
            $sliders = $('.woocommerce-product-gallery__wrapper', $gallery);

        // Show it

        $gallery.imagesLoaded(function () {

            var slidersOptions = {
                rtl: suproData.isRTL === '1',
                slidesToShow: 1,
                slidesToScroll: 1,
                infinite: false,
                arrows: true,
                prevArrow: '<span class="icon-arrow-left slick-prev-arrow"></span>',
                nextArrow: '<span class="icon-arrow-right slick-next-arrow"></span>'
            };

            if ($product.hasClass('supro-product-layout-5')) {
                var start = $sliders.children().length > 2 ? 1 : 0;
                slidersOptions.centerMode = true;
                slidersOptions.initialSlide = start;
                slidersOptions.centerPadding = '26.8%';
                slidersOptions.appendArrows = $('.slick-arrow-wrapper');
                slidersOptions.responsive = [
                    {
                        breakpoint: 601,
                        settings: {
                            centerPadding: '0'
                        }
                    }
                ];
            }

            if ($product.hasClass('supro-product-layout-6')) {
                slidersOptions.prevArrow = '<span class="arrow_carrot-left slick-prev-arrow"></span>';
                slidersOptions.nextArrow = '<span class="arrow_carrot-right slick-next-arrow"></span>';
                slidersOptions.variableWidth = true;
                slidersOptions.slidesToShow = 2;
                slidersOptions.responsive = [
                    {
                        breakpoint: 1200,
                        settings: {
                            variableWidth: false
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 1
                        }
                    }
                ];
            }

            $sliders.not('.slick-initialized').slick(slidersOptions);

        });

    };

    supro.productvariation = function () {
        var $form = $('.variations_form');

        supro.$body.on('tawcvs_initialized', function () {
            $form.unbind('tawcvs_no_matching_variations');
            $form.on('tawcvs_no_matching_variations', function (event, $el) {
                event.preventDefault();

                $form.find('.woocommerce-variation.single_variation').show();
                if (typeof wc_add_to_cart_variation_params !== 'undefined') {
                    $('.variations_form').find('.single_variation').slideDown(200).html('<p>' + wc_add_to_cart_variation_params.i18n_no_matching_variations_text + '</p>');
                }
            });

        });

        $('.variations_form td.value').on('change', 'select', function () {
            var value = $(this).find('option:selected').text();
            $(this).closest('tr').find('td.label .supro-attr-value').html(value);
        });

        $form.find('td.value').each(function () {
            if (!$(this).find('.variation-selector').hasClass('hidden')) {
                $(this).addClass('show-select');
            } else {
                $(this).prev().addClass('show-label');
            }
        });

        if (typeof wc_add_to_cart_variation_params !== 'undefined') {
            $form.on('found_variation.wc-variation-form', function (event, variation) {
                var $sku = $('.supro-single-product-detail').find('.sku_wrapper').find('.sku');

                if (variation.sku) {
                    $sku.wc_set_content(variation.sku);
                } else {
                    $sku.wc_reset_content();
                }
            });

            $form.on('reset_data', function (event, variation) {
                $('.supro-single-product-detail').find('.sku_wrapper').find('.sku').wc_reset_content();
            });
        }
    };

    // Get price js slider
    supro.priceSlider = function () {
        // woocommerce_price_slider_params is required to continue, ensure the object exists
        if (typeof woocommerce_price_slider_params === 'undefined') {
            return false;
        }

        if ($('.catalog-sidebar').find('.widget_price_filter').length <= 0 && $('#un-shop-topbar').find('.widget_price_filter').length <= 0) {
            return false;
        }

        // Get markup ready for slider
        $('input#min_price, input#max_price').hide();
        $('.price_slider, .price_label').show();

        // Price slider uses jquery ui
        var min_price = $('.price_slider_amount #min_price').data('min'),
            max_price = $('.price_slider_amount #max_price').data('max'),
            current_min_price = parseInt(min_price, 10),
            current_max_price = parseInt(max_price, 10);

        if ($('.price_slider_amount #min_price').val() != '') {
            current_min_price = parseInt($('.price_slider_amount #min_price').val(), 10);
        }
        if ($('.price_slider_amount #max_price').val() != '') {
            current_max_price = parseInt($('.price_slider_amount #max_price').val(), 10);
        }

        $(document.body).on('price_slider_create price_slider_slide', function (event, min, max) {
            if (woocommerce_price_slider_params.currency_pos === 'left') {

                $('.price_slider_amount span.from').html(woocommerce_price_slider_params.currency_symbol + min);
                $('.price_slider_amount span.to').html(woocommerce_price_slider_params.currency_symbol + max);

            } else if (woocommerce_price_slider_params.currency_pos === 'left_space') {

                $('.price_slider_amount span.from').html(woocommerce_price_slider_params.currency_symbol + ' ' + min);
                $('.price_slider_amount span.to').html(woocommerce_price_slider_params.currency_symbol + ' ' + max);

            } else if (woocommerce_price_slider_params.currency_pos === 'right') {

                $('.price_slider_amount span.from').html(min + woocommerce_price_slider_params.currency_symbol);
                $('.price_slider_amount span.to').html(max + woocommerce_price_slider_params.currency_symbol);

            } else if (woocommerce_price_slider_params.currency_pos === 'right_space') {

                $('.price_slider_amount span.from').html(min + ' ' + woocommerce_price_slider_params.currency_symbol);
                $('.price_slider_amount span.to').html(max + ' ' + woocommerce_price_slider_params.currency_symbol);

            }

            $(document.body).trigger('price_slider_updated', [min, max]);
        });
        if (typeof $.fn.slider !== 'undefined') {
            $('.price_slider').slider({
                range: true,
                animate: true,
                min: min_price,
                max: max_price,
                values: [current_min_price, current_max_price],
                create: function () {

                    $('.price_slider_amount #min_price').val(current_min_price);
                    $('.price_slider_amount #max_price').val(current_max_price);

                    $(document.body).trigger('price_slider_create', [current_min_price, current_max_price]);
                },
                slide: function (event, ui) {

                    $('input#min_price').val(ui.values[0]);
                    $('input#max_price').val(ui.values[1]);

                    $(document.body).trigger('price_slider_slide', [ui.values[0], ui.values[1]]);
                },
                change: function (event, ui) {

                    $(document.body).trigger('price_slider_change', [ui.values[0], ui.values[1]]);
                }
            });
        }
    };

    //related & upsell slider

    supro.singleProductCarousel = function () {
        var $singleProduct = $('.single-product'),
            $upsells = $singleProduct.find('.up-sells'),
            $upsellsProduct = $upsells.find('ul.products'),
            upsellsProductColumns = $upsells.data('columns'),
            $related = $singleProduct.find('.related.products'),
            $relatedProduct = $related.find('ul.products'),
            relatedProductColumns = $related.data('columns');

        // Product thumnails and featured image slider
        $upsellsProduct.not('.slick-initialized').slick({
            rtl: suproData.isRTL === '1',
            infinite: false,
            slidesToShow: upsellsProductColumns,
            slidesToScroll: 1,
            lazyLoad: 'ondemand',
            arrows: false,
            dots: true,
            responsive: [
                {
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: parseInt(upsellsProductColumns) > 3 ? 3 : parseInt(upsellsProductColumns),
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: parseInt(upsellsProductColumns) > 2 ? 2 : parseInt(upsellsProductColumns),
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 481,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });

        $relatedProduct.not('.slick-initialized').slick({
            rtl: suproData.isRTL === '1',
            infinite: false,
            slidesToShow: relatedProductColumns,
            slidesToScroll: 1,
            arrows: false,
            lazyLoad: 'ondemand',
            dots: true,
            responsive: [
                {
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: parseInt(relatedProductColumns) > 3 ? 3 : parseInt(relatedProductColumns),
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: parseInt(relatedProductColumns) > 2 ? 2 : parseInt(relatedProductColumns),
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 481,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
    };

    // Recently Viewed Products
    supro.recentlyViewedProducts = function () {
        footerRecentlyProducts();

        function footerRecentlyProducts() {
            var $recently = $('#footer-recently-viewed');
            if ($recently.length <= 0) {
                return;
            }

            if (!$recently.hasClass('load-ajax')) {
                return;
            }

            if ($recently.hasClass('loaded')) {
                return;
            }

            $.ajax({
                url: suproData.ajax_url,
                dataType: 'json',
                method: 'post',
                data: {
                    action: 'supro_footer_recently_viewed',
                    nonce: suproData.nonce
                },
                error: function () {
                    $recently.addClass('no-products');
                },
                success: function (response) {
                    $recently.html(response.data);

                    if ($recently.find('.product-list').hasClass('no-products')) {
                        $recently.addClass('no-products');
                    }

                    recentlyCarousel($recently);
                    $recently.addClass('loaded');
                }
            });
        }

        function recentlyCarousel($recently) {
            if (!$recently.find('.product-list').hasClass('no-products')) {
                var columns = parseInt($recently.data('columns'));

                $recently.find('.product-list').not('.slick-initialized').slick({
                    rtl: (suproData.direction === 'true'),
                    slidesToShow: columns,
                    slidesToScroll: columns,
                    arrows: true,
                    dots: false,
                    infinite: false,
                    prevArrow: '<span class="ion-ios-arrow-left slick-prev-arrow"></span>',
                    nextArrow: '<span class="ion-ios-arrow-right slick-next-arrow"></span>',
                    responsive: [
                        {
                            breakpoint: 1200,
                            settings: {
                                slidesToShow: 4,
                                slidesToScroll: 4
                            }
                        },
                        {
                            breakpoint: 992,
                            settings: {
                                slidesToShow: 3,
                                slidesToScroll: 3
                            }
                        },
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 2,
                                slidesToScroll: 2
                            }
                        },
                        {
                            breakpoint: 481,
                            settings: {
                                slidesToShow: 2,
                                slidesToScroll: 2,
                                arrows: false,
                                dots: true
                            }
                        }
                    ]
                });
            }
        }
    };

    supro.loginTab = function () {
        var $tabs = $('.supro-tabs'),
            $el = $tabs.find('.tabs-nav a'),
            $panels = $tabs.find('.tabs-panel');
        $el.on('click', function (e) {
            e.preventDefault();

            var $tab = $(this),
                index = $tab.parent().index();

            if ($tab.hasClass('active')) {
                return;
            }

            $tabs.find('.tabs-nav a').removeClass('active');
            $tab.addClass('active');
            $panels.removeClass('active');
            $panels.filter(':eq(' + index + ')').addClass('active');
        });
    };

    /**
     * Toggle product quick view
     */
    supro.productQuickView = function () {
        supro.$body.on('click', '.supro-product-quick-view', function (e) {
            e.preventDefault();
            var $a = $(this);

            var url = $a.attr('href'),
                $modal = $('#quick-view-modal'),
                $product = $modal.find('.product'),
                $product_sumary = $modal.find('.product-summary'),
                $product_images = $modal.find('.product-images-wrapper'),
                $button = $modal.find('.modal-header .close-modal').first().clone();

            $product.removeClass().addClass('invisible');
            $product_sumary.html('');
            $product_images.html('');
            $modal.addClass('loading');
            supro.openModal($modal);

            $.get(url, function (response) {
                var $html = $(response),
                    $response_summary = $html.find('#content').find('.entry-summary'),
                    $response_images = $html.find('#content').find('.product-images-wrapper'),
                    $product_thumbnails = $response_images.find('#product-thumbnails'),
                    $variations = $response_summary.find('.variations_form'),
                    productClasses = $html.find('.product').attr('class');

                // Remove unused elements
                $product_thumbnails.remove();
                $product.addClass(productClasses);
                $product_sumary.html($response_summary);
                $response_images.find('.woocommerce-product-gallery').removeAttr('style');
                $product_images.html($response_images);

                if ($product.find('.close-modal').length < 1) {
                    $product.show().prepend($button);
                }

                var $carousel = $product_images.find('.woocommerce-product-gallery__wrapper');

                // Force height for images
                $carousel.find('img').css('height', $product.outerHeight());

                $modal.removeClass('loading');
                $product.removeClass('invisible');

                $carousel.not('.slick-initialized').slick({
                    rtl: suproData.isRTL === '1',
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    infinite: false,
                    prevArrow: '<span class="icon-chevron-left slick-prev-arrow"></span>',
                    nextArrow: '<span class="icon-chevron-right slick-next-arrow"></span>'
                });

                $carousel.imagesLoaded(function () {
                    //Force height for images
                    $carousel.addClass('loaded');
                });

                $carousel.find('.woocommerce-product-gallery__image').on('click', 'a', function (e) {
                    e.preventDefault();
                });

                if (typeof wc_add_to_cart_variation_params !== 'undefined') {
                    $variations.wc_variation_form();
                    $variations.find('.variations select').change();
                }

                if (typeof $.fn.tawcvs_variation_swatches_form !== 'undefined') {
                    $variations.tawcvs_variation_swatches_form();
                }

                supro.$body.on('tawcvs_initialized', function () {
                    $('.variations_form').unbind('tawcvs_no_matching_variations');
                    $('.variations_form').on('tawcvs_no_matching_variations', function (event, $el) {
                        event.preventDefault();
                        $el.addClass('selected');

                        $('.variations_form').find('.woocommerce-variation.single_variation').show();
                        if (typeof wc_add_to_cart_variation_params !== 'undefined') {
                            $('.variations_form').find('.single_variation').slideDown(200).html('<p>' + wc_add_to_cart_variation_params.i18n_no_matching_variations_text + '</p>');
                        }
                    });

                });

                $product.find('[data-rel=tooltip]').tooltip({
                    classes: {'ui-tooltip': 'supro-tooltip'},
                    tooltipClass: 'supro-tooltip qv-tooltip',
                    position: {my: 'center bottom', at: 'center top-13'},
                    create: function () {
                        $('.ui-helper-hidden-accessible').remove();
                    }
                });

                supro.productvariation();
                supro.$body.trigger('yith_wcwl_init');

            }, 'html');

        });

        $('#quick-view-modal').on('click', function (e) {
            var target = e.target;
            if ($(target).closest('div.product').length <= 0) {
                supro.closeModal();
            }
        });
    };

    // add wishlist
    supro.addWishlist = function () {
        //$(document.body).find('a.add_to_wishlist').on('click', function () {
        //    $(this).addClass('loading');
        //});

        supro.$body.on('adding_to_wishlist', function () {
            $('.yith-wcwl-add-to-wishlist .yith-wcwl-add-button a').addClass('loading');
        });

        var $current_proid = 0;

        $(document.body).on('added_to_wishlist', function (e, $el_wrap) {
            e.preventDefault();
            //$(document.body).find('a.add_to_wishlist').removeClass('loading');
            $('.yith-wcwl-add-to-wishlist .yith-wcwl-add-button a').removeClass('loading');

            var $new_proid = parseInt($el_wrap.data('product-id'));

            if ($new_proid == $current_proid) {
                return;
            }

            $current_proid = $new_proid;

            var content = $el_wrap.data('product-title');
            $el_wrap.removeClass('loading');
            supro.addedToWishlistNotice('', content, false, 'success');
        });
    };

    supro.showAddedToCartNotice = function () {
        $(document.body).on('added_to_cart', function (event, fragments, cart_hash, $thisbutton) {
            var product_title = $thisbutton.attr('data-title') + ' ' + suproData.l10n.notice_text,
                $message = '';

            if (suproData.add_to_cart_action === 'notice') {
                supro.addedToCartNotice($message, product_title, false, 'success');
            } else {
                $('#icon-cart-contents').trigger('click');
            }
        });
    };

    supro.addedToCartNotice = function ($message, $content, single, className) {
        if (suproData.l10n.added_to_cart_notice != '1' || !$.fn.notify) {
            return;
        }

        $message += '<a href="' + suproData.l10n.cart_link + '" class="btn-button">' + suproData.l10n.cart_text + '</a>';

        if (single) {
            $message = '<div class="message-box">' + $message + '</div>';
        }

        $.notify.addStyle('supro', {
            html: '<div><i class="icon-checkmark-circle message-icon"></i><span data-notify-text/>' + $message + '<span class="close icon-cross2"></span> </div>'
        });
        $.notify($content, {
            autoHideDelay: suproData.l10n.cart_notice_auto_hide,
            className: className,
            style: 'supro',
            showAnimation: 'fadeIn',
            hideAnimation: 'fadeOut'
        });
    };

    supro.addedToWishlistNotice = function ($message, $content, single, className, multiple) {
        if (typeof suproData.added_to_wishlist_notice === 'undefined' || !$.fn.notify) {
            return;
        }

        if (multiple) {
            $content += ' ' + suproData.added_to_wishlist_notice.added_to_wishlist_texts;
        } else {
            $content += ' ' + suproData.added_to_wishlist_notice.added_to_wishlist_text;
        }

        $message += '<a href="' + suproData.added_to_wishlist_notice.wishlist_view_link + '" class="btn-button">' + suproData.added_to_wishlist_notice.wishlist_view_text + '</a>';

        if (single) {
            $message = '<div class="message-box">' + $message + '</div>';
        }

        $.notify.addStyle('supro', {
            html: '<div><i class="icon-checkmark-circle message-icon"></i><span data-notify-text/>' + $message + '<span class="close icon-cross2"></span> </div>'
        });
        $.notify($content, {
            autoHideDelay: suproData.added_to_wishlist_notice.wishlist_notice_auto_hide,
            className: className,
            style: 'supro',
            showAnimation: 'fadeIn',
            hideAnimation: 'fadeOut'
        });
    };


    // Add to cart ajax
    supro.addToCartAjax = function () {

        if (suproData.add_to_cart_ajax == '0') {
            return;
        }

        var found = false;
        supro.$body.on('click', '.single_add_to_cart_button', function (e) {
            var $el = $(this),
                $cartForm = $el.closest('form.cart'),
                $productWrapper = $el.closest('div.product');

            if ($productWrapper.hasClass('product-type-external')) {
                return;
            }

            if ($el.hasClass('has-buy-now')) {
                return;
            }

            if ($cartForm.length > 0) {
                e.preventDefault();
            } else {
                return;
            }

            if ($el.hasClass('disabled')) {
                return;
            }

            $el.addClass('loading');
            if (found) {
                return;
            }
            found = true;

            var formdata = $cartForm.serializeArray(),
                currentURL = window.location.href;

            if ($el.val() != '') {
                formdata.push({name: $el.attr('name'), value: $el.val()});
            }
            $.ajax({
                url: window.location.href,
                method: 'post',
                data: formdata,
                error: function () {
                    window.location = currentURL;
                },
                success: function (response) {
                    if (!response) {
                        window.location = currentURL;
                    }

                    if (typeof wc_add_to_cart_params !== 'undefined') {
                        if (wc_add_to_cart_params.cart_redirect_after_add === 'yes') {
                            window.location = wc_add_to_cart_params.cart_url;
                            return;
                        }
                    }

                    $(document.body).trigger('updated_wc_div');

                    var $message = '',
                        className = 'success';
                    if ($(response).find('.woocommerce-message').length > 0) {
                        $message = $(response).find('.woocommerce-message').html();
                    }

                    if ($(response).find('.woocommerce-error').length > 0) {
                        $message = $(response).find('.woocommerce-error').html();
                        className = 'error';
                    }

                    if ($(response).find('.woocommerce-info').length > 0) {
                        $message = $(response).find('.woocommerce-info').html();
                    }

                    $el.removeClass('loading');

                    if (suproData.add_to_cart_action === 'notice') {
                        if ($message) {
                            supro.addedToCartNotice($message, ' ', true, className);
                        }
                    } else {
                        $(document.body).on('wc_fragments_refreshed', function () {
                            $('#icon-cart-contents').trigger('click');
                        });
                    }

                    found = false;
                }
            });

        });

    };

    supro.buyNow = function () {
        supro.$body.find('form.cart').on('click', '.buy_now_button', function (e) {
            e.preventDefault();
            var $form = $(this).closest('form.cart'),
                is_disabled = $(this).is(':disabled');

            if (is_disabled) {
                jQuery('html, body').animate({
                        scrollTop: $(this).offset().top - 200
                    }, 900
                );
            } else {
                $form.append('<input type="hidden" value="true" name="buy_now" />');
                $form.find('.single_add_to_cart_button').addClass('has-buy-now');
                $form.find('.single_add_to_cart_button').trigger('click');
            }
        });

        var $variations_form = $('.variations_form');
        $variations_form.on('hide_variation', function (event) {
            event.preventDefault();
            $variations_form.find('.buy_now_button').addClass('disabled wc-variation-selection-needed');
        });


        $variations_form.on('show_variation', function (event, variation, purchasable) {
            event.preventDefault();
            if (purchasable) {
                $variations_form.find('.buy_now_button').removeClass('disabled wc-variation-selection-needed');
            } else {
                $variations_form.find('.buy_now_button').addClass('disabled wc-variation-selection-needed');
            }
        });
    };

    // Product Attribute
    supro.productAttribute = function () {
        supro.$body.on('click', '.supro-swatch-item', function (e) {
            e.preventDefault();
            $(this).siblings('.supro-swatch-item').removeClass('selected');
            $(this).addClass('selected');
            var imgSrc = $(this).data('src'),
                imgSrcSet = $(this).data('src-set'),
                $mainImages = $(this).parents('li.product').find('.un-product-thumbnail > a'),
                $image = $mainImages.find('img').first(),
                imgWidth = $image.first().width(),
                imgHeight = $image.first().height();

            $mainImages.addClass('image-loading');
            $mainImages.css({
                width: imgWidth,
                height: imgHeight,
                display: 'block'
            });

            $image.attr('src', imgSrc);

            if (imgSrcSet) {
                $image.attr('srcset', imgSrcSet);
            }

            $image.load(function () {
                $mainImages.removeClass('image-loading');
                $mainImages.removeAttr('style');
            });
        });
    };

    /**
     * Portfolio Masonry
     */
    supro.portfolioMasonry = function () {
        if (!supro.$body.hasClass('portfolio-masonry')) {
            return;
        }

        supro.$body.imagesLoaded(function () {
            supro.$body.find('.list-portfolio').isotope({
                itemSelector: '.portfolio-wrapper',
                layoutMode: 'masonry'
            });

        });
    };

    /**
     * Portfolio Ajax
     */
    supro.portfolioLoadingAjax = function () {

        if (!supro.$body.hasClass('supro-portfolio-page')) {
            return;
        }

        if (supro.$body.hasClass('portfolio-carousel')) {
            return;
        }

        $('.supro-portfolio-page').find('.numeric-navigation').on('click', '.page-numbers.next', function (e) {
            e.preventDefault();

            if ($(this).data('requestRunning')) {
                return;
            }

            $(this).data('requestRunning', true);

            $(this).addClass('loading');

            var $project = $(this).parents('.numeric-navigation').prev('.list-portfolio'),
                $pagination = $(this).parents('.numeric-navigation');

            $.get(
                $(this).attr('href'),
                function (response) {
                    var content = $(response).find('.list-portfolio').html(),
                        $pagination_html = $(response).find('.numeric-navigation').html();
                    var $content = $(content);

                    for (var index = 0; index < $content.length; index++) {
                        $($content[index]).css('animation-delay', index * 100 + 100 + 'ms');
                    }

                    $content.addClass('suproFadeIn suproAnimation');

                    $pagination.html($pagination_html);

                    if (supro.$body.hasClass('portfolio-masonry')) {
                        $content.imagesLoaded(function () {
                            $project.append($content).isotope('insert', $content);

                            $pagination.find('.page-numbers.next').removeClass('loading');
                            $pagination.find('.page-numbers.next').data('requestRunning', false);
                        });

                    } else {
                        $project.append($content);

                        $pagination.find('.page-numbers.next').removeClass('loading');
                        $pagination.find('.page-numbers.next').data('requestRunning', false);
                    }

                    if (!$pagination.find('.page-numbers').hasClass('next')) {
                        $pagination.addClass('loaded');
                    }
                }
            );
        });
    };

    /**
     * Portfolio Carousel
     */
    supro.portfolioCarousel = function () {
        if (!supro.$body.hasClass('supro-portfolio-page')) {
            return;
        }

        if (!supro.$body.hasClass('portfolio-carousel')) {
            return;
        }

        var $container = $('.swiper-container');

        if (!$container.length) {
            return;
        }

        var num = $('.list-portfolio .swiper-slide').length;

        var options = {
            loop: false,
            speed: 500,
            initialSlide: num > 0 ? 1 : 0,
            centeredSlides: true,
            spaceBetween: 100,
            scrollbar: {
                el: '.swiper-scrollbar',
                hide: false,
                draggable: true
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev'
            },
            on: {
                init: function () {
                    $container.css('opacity', 1);
                }
            },
            breakpoints: {
                // when window width is <= 480px
                767: {
                    spaceBetween: 30
                },
                // when window width is <= 1199
                1199: {
                    spaceBetween: 60
                }
            }
        };

        var carousel = new Swiper($container, options);

        var xhr;

        carousel.on('reachEnd', function () {
            var $nav = $('.portfolio-carousel .paging-navigation');

            if (!$nav.length) {
                return;
            }

            if (xhr) {
                return;
            }

            var loadingHolder = document.createElement('div');

            $(loadingHolder)
                .addClass('swiper-slide loading-placeholder')
                .css({height: carousel.height - 121})
                .append('<span class="spinner icon_loading supro-spin su-icon"></span>');

            carousel.appendSlide(loadingHolder);
            carousel.update();

            xhr = $.get($nav.find('a').attr('href'), function (response) {
                var $content = $('.list-portfolio', response),
                    $portfolio = $content.children(),
                    $newNav = $('.portfolio-carousel .paging-navigation', $content);

                if ($newNav.length) {
                    $nav.find('a').replaceWith($('a', $newNav));
                } else {
                    $nav.fadeOut(function () {
                        $nav.remove();
                    });
                }

                $(loadingHolder).remove();
                $portfolio.css({opacity: 0});

                carousel.appendSlide($portfolio.addClass('swiper-slide').get());
                carousel.update();

                $portfolio.animate({opacity: 1});
                xhr = false;

                $(document.body).trigger('supro_portfolio_loaded', [$portfolio, true]);
            });
        });
    };

    supro.filterScroll = function () {
        var $sidebar = $('.catalog-sidebar'),
            $categories = $('.supro_widget_product_categories > ul', $sidebar),
            $filter = $('.supro_attributes_filter > ul', $sidebar);

        supro.filterElement($categories);
        supro.filterElement($filter);
    };

    supro.filterElement = function ($element) {
        var h = $element.outerHeight(true),
            dataHeight = $element.data('height');

        if (h > dataHeight) {
            $element.addClass('scroll-enable');
            $element.css('height', dataHeight);
        }
    };

    /**
     * Init tooltip
     */
        // tooltip icon
    supro.toolTipIcon = function () {
        showToolTip();

        $(document).on('yith_wcwl_fragments_loaded', function () {
            showToolTip();
        });

        supro.$body.on('removed_from_wishlist', function () {
            showToolTip();
        });

        supro.$body.on('added_to_wishlist', function () {
            showToolTip();
        });

        function showToolTip() {
            $('.un-product-thumbnail, .product-summary').find('[data-rel=tooltip]').tooltip({
                classes: {'ui-tooltip': 'supro-tooltip'},
                tooltipClass: 'supro-tooltip',
                position: {my: 'center bottom', at: 'center top-13'},
                create: function () {
                    $('.ui-helper-hidden-accessible').remove();
                }
            });
        }

        $(document.body).on('added_to_cart', function () {
            $('.un-product-thumbnail').find('.added_to_cart').tooltip({
                offsetTop: -15,
                content: function () {
                    return $(this).html();
                },
                classes: {'ui-tooltip': 'supro-tooltip'},
                tooltipClass: 'supro-tooltip',
                position: {my: 'center bottom', at: 'center top-13'},
                create: function () {
                    $('.ui-helper-hidden-accessible').remove();
                }
            });
        });
    };

    /**
     * Ajax login before refresh page
     */
    supro.loginModalAuthenticate = function () {
        $('#login-modal').on('submit', '.woocommerce-form-login', function (e) {
            var username = $('input[name=username]', this).val(),
                password = $('input[name=password]', this).val(),
                remember = $('input[name=rememberme]', this).is(':checked'),
                $button = $('[type=submit]', this),
                $form = $(this),
                $box = $form.next('.woocommerce-error');

            if (!username || !password) {
                $('input[name=username]', this).focus();

                return false;
            }

            e.preventDefault();
            $button.addClass('loading');

            if ($box.length) {
                $box.fadeOut();
            }

            $.post(
                suproData.ajax_url,
                {
                    action: 'supro_login_authenticate',
                    creds: {
                        user_login: username,
                        user_password: password,
                        remember: remember
                    }
                },
                function (response) {
                    if (!response.success) {
                        if (!$box.length) {
                            $box = $('<div class="woocommerce-error supro-message-box danger"/>');

                            $box.append('<div class="box-content"></div>')
                                .append('<a class="close" href="#"><span aria-hidden="true" class="icon-cross2"></span></a>');

                            $box.hide().insertAfter($form);
                        }

                        $box.find('.box-content').html(response.data);
                        $box.fadeIn();
                        $button.removeClass('loading');
                    } else {
                        window.location.reload();
                    }
                }
            );
        });
    };

    /**
     * Close message box
     */
    $(document.body).on('click', '.supro-message-box .close', function (e) {
        e.preventDefault();

        $(this).closest('.supro-message-box').fadeOut('slow');
    });

    /**
     * Document ready
     */
    $(function () {
        supro.init();
    });

})(jQuery);
