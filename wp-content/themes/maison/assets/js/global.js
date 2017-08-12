(function($) {
    var uniqueCntr = 0;

    function onItemScrollTimeout(tag, fn) {
        this.removeData(tag);
        fn.call(this[0]);
    }

    function onItemScroll(tag, fn) {
        var timer = this.data(tag);
        if (!timer) {
            timer = setTimeout(onItemScrollTimeout.bind(this, tag, fn), 50);
            this.data(tag, timer);
        }

        onItemScrollTimeout.call(this, tag, fn);
    }

    $.fn.onScrolling = function (fn) {
        var tag = "scrollingTimer" + uniqueCntr++;
        this.on('mousewheel DOMMouseScroll scroll', onItemScroll.bind($(this), tag, fn));

        // this.on('mousewheel DOMMouseScroll scroll', function () {
        //     fn();
        // });
    }
})(jQuery);

(function($) {

    function CollectionsSlider(slider) {
        var self = this;
        
        this.slider = slider;
        this.$slider = $(slider);

        this.currenSlideIndex = 0;
        this.slides = $.map(this.$slider.find('.collection-slide'), function(s) { return $(s); });

        var paginPanel = this.$slider.find('.paging-panel');
        paginPanel.on('click', 'a', function (e) {
            e.preventDefault();

            var $clickedPage = $(this);
            var selectedCollectionIndex = $($clickedPage).index();

            self.showAt(selectedCollectionIndex);
        });
        this.pages = paginPanel.find('a');

        for (var i = 1; i < this.slides.length; ++i) {
            var slide = this.slides[i];
            this._placeDown(slide);
        }

        this.$slider.find('.js-up-trigger').on('click', function(e) {
            e.preventDefault();
            self.up();
        });

        this.$slider.find('.js-down-trigger').on('click', function(e) {
            e.preventDefault();
            self.down();
        });

        this._selectCurrentPage();
    }

    CollectionsSlider.prototype._placeUp = function(slide, animate) {
        if (animate) {
            slide.addClass('collection-slide--animating');
        }
        else {
            slide.removeClass('collection-slide--animating');
        }
        slide.removeClass('collection-slide--down').addClass('collection-slide--up');
    }

    CollectionsSlider.prototype._placeDown = function(slide, animate) {
        if (animate) {
            slide.addClass('collection-slide--animating');
        }
        else {
            slide.removeClass('collection-slide--animating');
        }
        slide.removeClass('collection-slide--up').addClass('collection-slide--down');
    }

    CollectionsSlider.prototype._placeMiddle = function(slide, animate) {
        if (animate) {
            slide.addClass('collection-slide--animating');
        }
        else {
            slide.removeClass('collection-slide--animating');
        }
        slide.removeClass('collection-slide--up').removeClass('collection-slide--down');
    }

    CollectionsSlider.prototype._animateLeaveUp = function(slide) {
        this._placeMiddle(slide);
        var self = this;
        setTimeout(function () {
            self._placeUp(slide, true);
        }, 0);
    }

    CollectionsSlider.prototype._animateLeaveDown = function(slide) {
        this._placeMiddle(slide);
        var self = this;
        setTimeout(function () {
            self._placeDown(slide, true);
        }, 0);
    }

    CollectionsSlider.prototype._animateEnterDown = function(slide) {
        this._placeDown(slide);
        var self = this;
        setTimeout(function () {
            self._placeMiddle(slide, true);
        }, 0);
    }

    CollectionsSlider.prototype._animateEnterUp = function(slide) {
        this._placeUp(slide);
        var self = this;
        setTimeout(function () {
            self._placeMiddle(slide, true);
        }, 0);
    }

    CollectionsSlider.prototype.up = function() {
        var currentSlide = this.slides[this.currenSlideIndex];
        this.currenSlideIndex--;
        if (this.currenSlideIndex < 0) this.currenSlideIndex = this.slides.length - 1;
        var prevSlide = this.slides[this.currenSlideIndex];
        
        this._animateLeaveUp(currentSlide);
        this._animateEnterDown(prevSlide);

        this._selectCurrentPage();
    }

    CollectionsSlider.prototype.down = function() {
        var currentSlide = this.slides[this.currenSlideIndex];
        this.currenSlideIndex++;
        if (this.currenSlideIndex >= this.slides.length) this.currenSlideIndex = 0;
        var nextSlide = this.slides[this.currenSlideIndex];

        this._animateLeaveDown(currentSlide);
        this._animateEnterUp(nextSlide);

        this._selectCurrentPage();
    }

    CollectionsSlider.prototype.showAt = function(index) {
        if (this.currenSlideIndex === index) {
            return;
        }

        var currentSlide = this.slides[this.currenSlideIndex];
        var newSlide = this.slides[index];

        if (this.currenSlideIndex < index) {
            this._animateLeaveUp(currentSlide);
            this._animateEnterDown(newSlide);
        }
        else {
            this._animateLeaveDown(currentSlide);
            this._animateEnterUp(newSlide);
        }

        this.currenSlideIndex = index;

        this._selectCurrentPage();
    }

    CollectionsSlider.prototype._selectCurrentPage = function() {
        $(this.pages.removeClass('selected').get(this.currenSlideIndex)).addClass('selected');
    }

    $.fn.collectionsSlider = function () {
        return this.each(function () {
            new CollectionsSlider(this);
        });
    }
})(jQuery);

(function($) {
    function Tabs(tabsContainer) {
        var self = this;

        var $tabsContainer = $(tabsContainer);
        var selectedTabId = location.hash;
        var tabSelector = 'a[data-tab]';
        var selectedTabClass = 'tab--selected';
        var selectedTabTargetClass = 'tab-content--selected';

        this.allTabs = $tabsContainer.find(tabSelector);
        this.allTabTargets = $();
        this.tabTargetsMap = {};

        this.allTabs.each(function () {
            var $tabItem = $(this);
            var tabTargetSelector = $tabItem.attr('href');
            var tabItemId = tabTargetSelector.slice(1);
            var $tabTarget = $(tabTargetSelector);
            
            if (selectedTabId) {
                if (tabTargetSelector === selectedTabId) {
                    $tabItem.addClass(selectedTabClass);
                    $tabTarget.addClass(selectedTabTargetClass);
                }
                else {
                    $tabItem.removeClass(selectedTabClass);
                    $tabTarget.removeClass(selectedTabTargetClass);
                }
            }

            $tabTarget.removeAttr('id');
            self.allTabTargets = self.allTabTargets.add($tabTarget);
            self.tabTargetsMap[tabItemId] = $tabTarget;
        });

        $tabsContainer.on('click', tabSelector, function () {
            var $clickedTab = $(this);
            if ($clickedTab.hasClass(selectedTabClass)) {
                return;
            }

            var tabTargetSelector = $clickedTab.attr('href');
            var tabItemId = tabTargetSelector.slice(1);

            var $tabTarget = self.tabTargetsMap[tabItemId];

            self.allTabs.removeClass(selectedTabClass);
            self.allTabTargets.removeClass(selectedTabTargetClass);

            $clickedTab.addClass(selectedTabClass);
            $tabTarget.addClass(selectedTabTargetClass);
        });
    }

    $.fn.tabs = function () {
        return this.each(function () {
            new Tabs(this);
        });
    }
})(jQuery);

(function($) {
    var $window = $(window);
    var $document = $(document);
    var $topNav = $('.nav-top');
    var $mobileNavMenu = $('.nav-mobile-menu');

    var topNavNormalClass = $topNav.data('normalClass');
    var topNavCondensedClass = $topNav.data('condensedClass');

    function updateTopNav() {
        var scrollTop = window.scrollY || window.pageYOffset;
        var isNavSticked = $topNav.data('sticked');
        // if (scrollTop > 0) {
        //     $topNav.removeClass(topNavNormalClass).addClass(topNavCondensedClass);
        // }
        // else {
        //     $topNav.removeClass(topNavCondensedClass).addClass(topNavNormalClass);
        // }

        if (scrollTop > 300) {
            if (!isNavSticked) {
                $topNav.data('sticked', true);
                $topNav
                    .stop()
                    .css({ 'opacity': 0, 'position': 'fixed' })
                    .removeClass(topNavNormalClass).addClass(topNavCondensedClass)
                    .animate({ opacity: 1 }, 300, 'swing');
            }
        }
        else {
            if (isNavSticked) {
                $topNav.data('sticked', false);
                $topNav
                    .stop()
                    .animate({ opacity: 0 }, 300, 'swing',
                        function() {
                            $topNav
                                .removeClass(topNavCondensedClass).addClass(topNavNormalClass)
                                .css({ 'opacity': 1, 'position': 'absolute' });
                        });
            }
        }
    }

    updateTopNav();
    $window.onScrolling(updateTopNav);

    // $('.article figure img').mousemove(function(e) {
    //     var $this = $(this);
    //     var parent = $this.parent();
    //     var parentOffset = parent.offset();

    //     var relativeOffsetX = e.pageX - parentOffset.left;
    //     var relativeOffsetY = e.pageY - parentOffset.top;

    //     var parentWidth = parent.width();
    //     var parentHeight = parent.height();

    //     if (relativeOffsetX < 0) relativeOffsetX = 0;
    //     if (relativeOffsetY < 0) relativeOffsetY = 0;

    //     if (relativeOffsetX > parentWidth) relativeOffsetX = parentWidth;
    //     if (relativeOffsetY > parentHeight) relativeOffsetY = parentHeight;

    //     var offsetXInPercents = (relativeOffsetX * 100) / parentWidth;
    //     var offsetYInPercents = (relativeOffsetY * 100) / parentHeight;

    //     var transformOriginValue = offsetXInPercents + '% ' + offsetYInPercents + '%';
    //     $this.css('transform-origin', transformOriginValue);
    // });

    $('.js-nav-mobile-menu-toggle').click(function() {
        $mobileNavMenu.toggleClass('nav-mobile-menu--collapsed');
    });

    $('.scroll-home').click(function(e) {
        e.preventDefault();
        $('html, body').stop().animate({ scrollTop:0 }, '1000', 'swing');
    });

    $('[data-smooth-scroll]').on('click', function (e) {
        //e.preventDefault();

        var $scrollToElement = $($.attr(this, 'href'));
        var scrollToElementOffsetTop = $scrollToElement.offset().top
        $('html, body').animate({ scrollTop: scrollToElementOffsetTop }, 500, 'swing');
    });

    function getResponsiveHeight(width, orientation) {
        if (!orientation) {
            orientation = 'landscape';
        }

        var height = 0;
        if (orientation === 'portrait') {
            height = (width * 16) / 9;
        }
        else {
            height = (width * 9) / 16;
        }

        return height;
    }

    var youtubeIframeApiScript = document.createElement('script');
    youtubeIframeApiScript.src = "https://www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(youtubeIframeApiScript, firstScriptTag);

    youtubePlayers = [];

    window.onYouTubeIframeAPIReady = function() {
        $('.js-youtubevideo').each(function(i, video) {
            var $video = $(video);
            var youtubeId = $video.data('id');
            var orientation = $video.data('orientation');
            
            var videoWidth = $video.width();
            var videoHeight = getResponsiveHeight(videoWidth, orientation);
            
            new YT.Player(video, {
                videoId: youtubeId,
                width: videoWidth,
                height: videoHeight,
                playerVars: {
                    autoplay: 1,
                    controls: 0,
                    enablejsapi: 1,
                    modestbranding: 1,
                    rel: 0,
                    showinfo: 0
                },
                events: {
                    onReady: function(e) {
                        youtubePlayers.push(e.target);

                        e.target.mute();
                        e.target.setLoop(true);
                    },
                    onStateChange: function(e) {
                        if (e.data === YT.PlayerState.ENDED) {
                            e.target.playVideo();
                        }
                    }
                }
            });
        });
    };

    $window.on('resize', function() {
        if (youtubePlayers) {
            youtubePlayers.forEach(function (youtubePlayer) {
                var $videoFrame = $(youtubePlayer.getIframe());
                var $videoParent = $videoFrame.parent();

                var orientation = $videoFrame.data('orientation');
                var videoWidth = $videoParent.width();
                var videoHeight = getResponsiveHeight(videoWidth, orientation);

                youtubePlayer.setSize(videoWidth, videoHeight);
            });
        }
    });

    $('.collections-slider').collectionsSlider();

    $('.tabbed-control').tabs();

    var productHrefBeginning = '#product-';

    function showProductModal(e) {
        e.preventDefault();

        var hrefAttr = $.attr(this, 'href');
        if (hrefAttr && hrefAttr.length > productHrefBeginning.length && hrefAttr.indexOf(productHrefBeginning) === 0) {
            if(window.history && window.history.pushState) {
                window.history.pushState(null, null, hrefAttr);
            }
            else {
                window.location.hash = hrefAttr;
            }

            
            showProductQuickView(hrefAttr);
        }
    }

    var productHrefSequence = $('[data-quick-view]').on('click', showProductModal)
        .map(function (index, link) {
            return link.getAttribute('href');
        }).toArray();

    function showProductQuickView(productHash) {
        var productSlug = productHash.substring(productHrefBeginning.length);

        $.ajax({
            url: maison_frontend_data.ajaxUrl,
            data: {
                action: 'maison_load_product_quick_view',
                product_slug: productSlug
            },
            dataType: 'html',
            type: 'POST',
            success: function (data) {
                var prevProductHash = getPrevProductHash(productHash);
                var nextProductHash = getNextProductHash(productHash);

                var productModal = lity(data);
                var productModalElement = productModal.element();
                productModalElement.addClass('product');
                var prevLink = productModalElement.find('[data-prev-product]');
                var nextLink = productModalElement.find('[data-next-product]');

                prevProductHash ? prevLink.attr('href', prevProductHash) : prevLink.hide();
                nextProductHash ? nextLink.attr('href', nextProductHash) : nextLink.hide();

                var allNavigationLinks = prevLink.add(nextLink);
                allNavigationLinks.on('click', function (e) {
                    productModal.close();

                    showProductModal.call(this, e);
                });

                if ( typeof wc_add_to_cart_variation_params !== 'undefined' ) {
                    productModalElement.find('.variations_form').each( function() {
                        $(this).wc_variation_form();
                    });
                }
            }
        });
    }

    function getPrevProductHash(currentProductHash) {
        var currentProductIndex = productHrefSequence.indexOf(currentProductHash);
        if (currentProductIndex < 1) {
            return null;
        }

        return productHrefSequence[currentProductIndex - 1]
    }

    function getNextProductHash(currentProductHash) {
        var currentProductIndex = productHrefSequence.indexOf(currentProductHash);
        if (currentProductIndex === -1 || currentProductIndex >= productHrefSequence.length - 1) {
            return null;
        }

        return productHrefSequence[currentProductIndex + 1]
    }

    var pageHash = window.location.hash;
    if (pageHash && pageHash.indexOf(productHrefBeginning) === 0 && !$('.woocommerce-message, .woocommerce-error, .woocommerce-info').is(':visible')) {
        showProductQuickView(pageHash);
    }

    $('.mc4wp-form').on('submit', function(e) {
        e.preventDefault();
        var submittedFormId = this.id;
        var subscriptionForm = $(this);
        var subscriptionData = subscriptionForm.serialize();
        var submitButton = subscriptionForm.find('[type=submit]');
        submitButton.each(function () {
            this.disabled = true;
            $(this).addClass('disabled');
        });

        $.post('', subscriptionData, function (responseData) {
            var subscriptionAlertBox = $(responseData).find('#' + submittedFormId + ' .mc4wp-response .mc4wp-alert');
            var isSubscriptionSuccessful = subscriptionAlertBox.is('.mc4wp-success');
            var modalTitle = isSubscriptionSuccessful ? 'Thank you for subscribing!' : 'Error on subscribing!';
            var modalMessage = subscriptionAlertBox.text();
            var subscriptionModalHtml =
                '<div class="subscription-message u-tac">' +
                    '<h1 class="u-fs30 u-mb3">' + modalTitle + '</h1>' +
                    '<p class="u-mb5">' + modalMessage + '</p>' +
                    '<button type="button" class="btn btn-main" data-lity-close>OK, Thanks</button>' +
                '</div>'
            lity(subscriptionModalHtml);
        })
        .always(function() {
            submitButton.each(function () {
            this.disabled = false;
            $(this).removeClass('disabled');
        });
        });;
    });

    $(document).on('change', '.shop_table .qty',
        function () {
            $('.shop_table input[name="update_cart"]').trigger('click');
        });

})(jQuery);