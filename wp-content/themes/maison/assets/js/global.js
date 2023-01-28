(function ($) {
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
        var tag = 'scrollingTimer' + uniqueCntr++;
        this.on('mousewheel DOMMouseScroll scroll', onItemScroll.bind($(this), tag, fn));

        // this.on('mousewheel DOMMouseScroll scroll', function () {
        //     fn();
        // });
    };
})(jQuery);

(function ($) {
    function CollectionsSlider(slider) {
        var self = this;

        this.slider = slider;
        this.$slider = $(slider);

        this.currenSlideIndex = 0;
        this.slides = $.map(this.$slider.find('.collection-slide'), function (s) {
            return $(s);
        });

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

        this.$slider.find('.js-up-trigger').on('click', function (e) {
            e.preventDefault();
            self.up();
        });

        this.$slider.find('.js-down-trigger').on('click', function (e) {
            e.preventDefault();
            self.down();
        });

        window.setInterval(function () {
            self.down();
        }, 6000);

        this._selectCurrentPage();
    }

    CollectionsSlider.prototype._placeUp = function (slide, animate) {
        if (animate) {
            slide.addClass('collection-slide--animating');
        } else {
            slide.removeClass('collection-slide--animating');
        }
        slide.removeClass('collection-slide--down').addClass('collection-slide--up');
    };

    CollectionsSlider.prototype._placeDown = function (slide, animate) {
        if (animate) {
            slide.addClass('collection-slide--animating');
        } else {
            slide.removeClass('collection-slide--animating');
        }
        slide.removeClass('collection-slide--up').addClass('collection-slide--down');
    };

    CollectionsSlider.prototype._placeMiddle = function (slide, animate) {
        if (animate) {
            slide.addClass('collection-slide--animating');
        } else {
            slide.removeClass('collection-slide--animating');
        }
        slide.removeClass('collection-slide--up').removeClass('collection-slide--down');
    };

    CollectionsSlider.prototype._animateLeaveUp = function (slide) {
        this._placeMiddle(slide);
        var self = this;
        setTimeout(function () {
            self._placeUp(slide, true);
        }, 0);
    };

    CollectionsSlider.prototype._animateLeaveDown = function (slide) {
        this._placeMiddle(slide);
        var self = this;
        setTimeout(function () {
            self._placeDown(slide, true);
        }, 0);
    };

    CollectionsSlider.prototype._animateEnterDown = function (slide) {
        this._placeDown(slide);
        var self = this;
        setTimeout(function () {
            self._placeMiddle(slide, true);
        }, 0);
    };

    CollectionsSlider.prototype._animateEnterUp = function (slide) {
        this._placeUp(slide);
        var self = this;
        setTimeout(function () {
            self._placeMiddle(slide, true);
        }, 0);
    };

    CollectionsSlider.prototype.up = function () {
        var currentSlide = this.slides[this.currenSlideIndex];
        this.currenSlideIndex--;
        if (this.currenSlideIndex < 0) this.currenSlideIndex = this.slides.length - 1;
        var prevSlide = this.slides[this.currenSlideIndex];

        this._animateLeaveUp(currentSlide);
        this._animateEnterDown(prevSlide);

        this._selectCurrentPage();
    };

    CollectionsSlider.prototype.down = function () {
        var currentSlide = this.slides[this.currenSlideIndex];
        this.currenSlideIndex++;
        if (this.currenSlideIndex >= this.slides.length) this.currenSlideIndex = 0;
        var nextSlide = this.slides[this.currenSlideIndex];

        this._animateLeaveDown(currentSlide);
        this._animateEnterUp(nextSlide);

        this._selectCurrentPage();
    };

    CollectionsSlider.prototype.showAt = function (index) {
        if (this.currenSlideIndex === index) {
            return;
        }

        var currentSlide = this.slides[this.currenSlideIndex];
        var newSlide = this.slides[index];

        if (this.currenSlideIndex < index) {
            this._animateLeaveUp(currentSlide);
            this._animateEnterDown(newSlide);
        } else {
            this._animateLeaveDown(currentSlide);
            this._animateEnterUp(newSlide);
        }

        this.currenSlideIndex = index;

        this._selectCurrentPage();
    };

    CollectionsSlider.prototype._selectCurrentPage = function () {
        $(this.pages.removeClass('selected').get(this.currenSlideIndex)).addClass('selected');
    };

    $.fn.collectionsSlider = function () {
        return this.each(function () {
            new CollectionsSlider(this);
        });
    };
})(jQuery);

(function ($) {
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
                } else {
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
    };
})(jQuery);

(function ($) {
    var $window = $(window);
    var $document = $(document);
    var $topNav = $('.nav-top');
    var $mobileNavMenu = $('.nav-mobile-menu');
    var $mobileNavCurtain = $('.nav-mobile-menu-curtain');

    var topNavNormalClass = $topNav.data('normalClass');
    var topNavCondensedClass = $topNav.data('condensedClass');

    if (/ip(hone|od|ad)/i.test(window.navigator.userAgent)) $(document.body).addClass('mobile-apple-device');

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
                    .css({ opacity: 0, position: 'fixed' })
                    .removeClass(topNavNormalClass)
                    .addClass(topNavCondensedClass)
                    .animate({ opacity: 1 }, 300, 'swing');
            }
        } else {
            if (isNavSticked) {
                $topNav.data('sticked', false);
                $topNav.stop().animate({ opacity: 0 }, 300, 'swing', function () {
                    $topNav.removeClass(topNavCondensedClass).addClass(topNavNormalClass).css({ opacity: 1, position: 'absolute' });
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

    $('.js-nav-mobile-menu-toggle').click(function () {
        $mobileNavMenu.toggleClass('nav-mobile-menu--collapsed');
        $mobileNavCurtain.toggleClass('nav-mobile-menu-curtain--collapsed');
    });

    $('.scroll-home').click(function (e) {
        e.preventDefault();
        $('html, body').stop().animate({ scrollTop: 0 }, '1000', 'swing');
    });

    $('[data-smooth-scroll]').on('click', function (e) {
        //e.preventDefault();

        var $scrollToElement = $($.attr(this, 'href'));
        var scrollToElementOffsetTop = $scrollToElement.offset().top;
        $('html, body').animate({ scrollTop: scrollToElementOffsetTop }, 500, 'swing');
    });

    function getResponsiveHeight(width, orientation) {
        if (!orientation) {
            orientation = 'landscape';
        }

        var height = 0;
        if (orientation === 'portrait') {
            height = (width * 16) / 9;
        } else {
            height = (width * 9) / 16;
        }

        return height;
    }

    var youtubeIframeApiScript = document.createElement('script');
    youtubeIframeApiScript.src = 'https://www.youtube.com/iframe_api';
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(youtubeIframeApiScript, firstScriptTag);

    youtubePlayers = [];

    window.onYouTubeIframeAPIReady = function () {
        $('.js-youtubevideo').each(function (i, video) {
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
                    showinfo: 0,
                },
                events: {
                    onReady: function (e) {
                        youtubePlayers.push(e.target);

                        e.target.mute();
                        e.target.setLoop(true);
                    },
                    onStateChange: function (e) {
                        if (e.data === YT.PlayerState.ENDED) {
                            e.target.playVideo();
                        }
                    },
                },
            });
        });
    };

    $window.on('resize', function () {
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
            if (window.history && window.history.pushState) {
                window.history.pushState(null, null, hrefAttr);
            } else {
                window.location.hash = hrefAttr;
            }

            showProductQuickView(hrefAttr);
        }
    }

    var productHrefSequence = $('[data-quick-view]')
        .on('click', showProductModal)
        .map(function (index, link) {
            return link.getAttribute('href');
        })
        .toArray();

    function showProductQuickView(productHash) {
        var productSlug = productHash.substring(productHrefBeginning.length);

        $.ajax({
            url: maison_frontend_data.ajaxUrl,
            data: {
                action: 'maison_load_product_quick_view',
                product_slug: productSlug,
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

                if (typeof wc_add_to_cart_variation_params !== 'undefined') {
                    productModalElement.find('.variations_form').each(function () {
                        $(this).wc_variation_form();
                    });
                }

                ReadMore.init({
                    content: productModalElement.get(0).querySelectorAll('.js-read-more'),
                });
            },
        });
    }

    function getPrevProductHash(currentProductHash) {
        var currentProductIndex = productHrefSequence.indexOf(currentProductHash);
        if (currentProductIndex < 1) {
            return null;
        }

        return productHrefSequence[currentProductIndex - 1];
    }

    function getNextProductHash(currentProductHash) {
        var currentProductIndex = productHrefSequence.indexOf(currentProductHash);
        if (currentProductIndex === -1 || currentProductIndex >= productHrefSequence.length - 1) {
            return null;
        }

        return productHrefSequence[currentProductIndex + 1];
    }

    var pageHash = window.location.hash;
    if (pageHash && pageHash.indexOf(productHrefBeginning) === 0 && !$('.woocommerce-message, .woocommerce-error, .woocommerce-info').length) {
        showProductQuickView(pageHash);
    }

    $('.mc4wp-form').on('submit', function (e) {
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
            var modalTitle = isSubscriptionSuccessful ? maison_frontend_data.thank_you_for_subs : maison_frontend_data.error_on_subs;
            var modalMessage = subscriptionAlertBox.text();
            var subscriptionModalHtml =
                '<div class="subscription-message u-tac">' +
                '<h1 class="u-fs30 u-mb3">' +
                modalTitle +
                '</h1>' +
                '<p class="u-mb5">' +
                modalMessage +
                '</p>' +
                '<button type="button" class="btn btn-main" data-lity-close>' +
                maison_frontend_data.ok_thanks +
                '</button>' +
                '</div>';
            lity(subscriptionModalHtml);
        }).always(function () {
            submitButton.each(function () {
                this.disabled = false;
                $(this).removeClass('disabled');
            });
        });
    });

    $(document).on('change', '.shop_table .qty', function () {
        $('.shop_table input[name="update_cart"]').trigger('click');
    });

    var openMenuClass = 'menu-item-open-children';
    $(document.body).on('click', '.menu-item-has-children > a', function (e) {
        e.preventDefault();
        var $triggerElement = $(this);

        var $parentMenu = $triggerElement.parent();
        $parentMenu.toggleClass(openMenuClass);
    });

    /**
     *  Read More JS
     *  truncates text via specfied character length with more/less actions.
     *  Maintains original format of pre truncated text.
     *  @author stephen scaff
     *  @todo   Add destroy method for ajaxed content support.
     *

    */
    var ReadMore = (function () {
        var s;

        return {
            settings: function (is) {
                is = is || {};
                return {
                    content: is.content || document.querySelectorAll('.js-read-more'),
                    originalContentArr: [],
                    truncatedContentArr: [],
                    moreLink: maison_frontend_data.read_more,
                    lessLink: maison_frontend_data.read_less,
                };
            },

            init: function (is) {
                s = this.settings(is);
                if (!s.content || !s.content.length) return;
                this.bindEvents();
            },

            bindEvents: function () {
                ReadMore.truncateText();
            },

            /**
             * Count Words
             * Helper to handle word count.
             * @param {string} str - Target content string.
             */
            countWords: function (str) {
                return str.split(/\s+/).length;
            },

            /**
             * Ellpise Content
             * @param {string} str - content string.
             * @param {number} wordsNum - Number of words to show before truncation.
             */
            ellipseContent: function (str, wordsNum) {
                return str.split(/\s+/).slice(0, wordsNum).join(' ') + '...';
            },

            /**
             * Truncate Text
             * Truncate and ellipses contented content
             * based on specified word count.
             * Calls createLink() and handleClick() methods.
             *
             */
            truncateText: function () {
                for (var i = 0; i < s.content.length; i++) {
                    //console.log(s.content)
                    var originalContent = s.content[i].innerHTML;
                    var numberOfWords = s.content[i].dataset.rmWords;
                    var truncateContent = ReadMore.ellipseContent(originalContent, numberOfWords);
                    var originalContentWords = ReadMore.countWords(originalContent);

                    s.originalContentArr.push(originalContent);
                    s.truncatedContentArr.push(truncateContent);

                    if (numberOfWords < originalContentWords) {
                        s.content[i].classList.add('read-more');
                        s.content[i].innerHTML = s.truncatedContentArr[i];
                        var self = i;
                        ReadMore.createLink(self);
                    }
                }
                ReadMore.handleClick(s.content);
            },

            /**
             * Create Link
             * Creates and Inserts Read More Link
             * @param {number} index - index reference of looped item
             */
            createLink: function (index) {
                var linkWrap = document.createElement('span');

                linkWrap.className = 'read-more__link-wrap';

                linkWrap.innerHTML = '<a id="read-more_' + index + '" class="read-more__link" style="cursor:pointer;">' + s.moreLink + '</a>';

                // Inset created link
                s.content[index].parentNode.insertBefore(linkWrap, s.content[index].nextSibling);
            },

            /**
             * Handle Click
             * Toggle Click eve
             */
            handleClick: function (el) {
                var readMoreLink = document.querySelectorAll('.read-more__link');

                for (var j = 0, l = readMoreLink.length; j < l; j++) {
                    readMoreLink[j].addEventListener('click', function () {
                        var moreLinkID = this.getAttribute('id');
                        var index = moreLinkID.split('_')[1];

                        el[index].classList.toggle('is-expanded');

                        if (this.dataset.clicked !== 'true') {
                            el[index].innerHTML = s.originalContentArr[index];
                            this.innerHTML = s.lessLink;
                            this.dataset.clicked = true;
                        } else {
                            el[index].innerHTML = s.truncatedContentArr[index];
                            this.innerHTML = s.moreLink;
                            this.dataset.clicked = false;
                        }
                    });
                }
            },

            /**
             * Open All
             * Method to expand all instances on the page.
             */
            openAll: function () {
                el = document.querySelectorAll('.read-more__link');
                for (var i = 0; i < el.length; i++) {
                    content[i].innerHTML = s.truncatedContentArr[i];
                    el[i].innerHTML = s.moreLink;
                }
            },
        };
    })();

    ReadMore.init();

    $(function () {
        $('.lity-modal-notice').each(function () {
            lity(this.parentElement);
        });
    });

    var activeSliderClass = 'row-slider--active';
    (function (sliders) {
        var slidersData = [];

        function loadLazyImages(sliderData) {
            if (window.blazyInstance) {
                var notLoadedElements = sliderData.track.find('.b-lazy:not(.b-loaded)');
                if (notLoadedElements.length) window.blazyInstance.load(notLoadedElements, true);
            }
        }

        function scrollSliderLeft(sliderData) {
            var sliderTrackDomElement = sliderData.track[0];
            var clientWidth = sliderTrackDomElement.clientWidth;
            var scrollWidth = sliderTrackDomElement.scrollWidth;
            var maxTranslateXValue = 0;
            var newTranslateXValue = clientWidth - scrollWidth;
            if (sliderData.translateX < maxTranslateXValue) {
                newTranslateXValue = sliderData.translateX + clientWidth;
                if (newTranslateXValue > maxTranslateXValue) newTranslateXValue = maxTranslateXValue;
            }

            sliderData.track.css('transform', 'translateX(' + newTranslateXValue + 'px)');
            sliderData.translateX = newTranslateXValue;

            loadLazyImages(sliderData);
        }

        function scrollSliderRight(sliderData) {
            var sliderTrackDomElement = sliderData.track[0];
            var clientWidth = sliderTrackDomElement.clientWidth;
            var scrollWidth = sliderTrackDomElement.scrollWidth;
            var minTranslateXValue = clientWidth - scrollWidth;
            var newTranslateXValue = 0;
            if (sliderData.translateX > minTranslateXValue) {
                newTranslateXValue = sliderData.translateX - clientWidth;
                if (newTranslateXValue < minTranslateXValue) newTranslateXValue = minTranslateXValue;
            }

            sliderData.track.css('transform', 'translateX(' + newTranslateXValue + 'px)');
            sliderData.translateX = newTranslateXValue;

            loadLazyImages(sliderData);
        }

        function destroySlider(sliderData) {
            clearInterval(sliderData.timer);
            sliderData.slider.removeClass(activeSliderClass);
            sliderData.track.css('transform', '');
            sliderData.slider.find('.row-slider-arrow-left').off('click', sliderData.scrollLeft);
            sliderData.slider.find('.row-slider-arrow-right').off('click', sliderData.scrollRight);
            sliderData.slider.off('touchstart mousedown', sliderData.onPress);
            sliderData.slider.off('touchend mouseup touchcancel mouseleave', sliderData.onRelease);

            var sliderIndex = slidersData.indexOf(sliderData);
            if (sliderIndex != -1) slidersData.splice(sliderIndex, 1);
        }

        function destroySliders() {
            slidersData.forEach(function (sliderData) {
                destroySlider(sliderData);
            });
        }

        function getMouseXPositionFromEvent(e) {
            var mouseXPosition;
            if (e.originalEvent) {
                if (e.originalEvent.touches && e.originalEvent.touches.length) {
                    mouseXPosition = e.originalEvent.touches[0].pageX;
                }

                if (!mouseXPosition && e.originalEvent.changedTouches && e.originalEvent.changedTouches.length) {
                    mouseXPosition = e.originalEvent.changedTouches[0].pageX;
                }

                if (!mouseXPosition && e.originalEvent.targetTouches && e.originalEvent.targetTouches.length) {
                    mouseXPosition = e.originalEvent.targetTouches[0].pageX;
                }
            }

            if (!mouseXPosition && e.clientX) mouseXPosition = e.clientX;

            return mouseXPosition;
        }

        function initSlider(sliderDomElement) {
            var slider = $(sliderDomElement);
            var sliderTrack = slider.find('.row-slider-track');
            var sliderTrackDomElement = sliderTrack[0];
            if (sliderTrackDomElement.scrollWidth <= sliderTrackDomElement.clientWidth) return;

            var sliderData = {
                slider: slider,
                track: sliderTrack,
                translateX: 0,
            };

            sliderData.scrollRight = function (e) {
                e.preventDefault();
                scrollSliderRight(sliderData);
            };

            sliderData.scrollLeft = function (e) {
                e.preventDefault();
                scrollSliderLeft(sliderData);
            };

            slider.addClass(activeSliderClass);
            sliderData.timer = setInterval(function () {
                if (!sliderData.slider.is(':hover')) scrollSliderRight(sliderData);
            }, 5000);
            sliderData.onPress = function (e) {
                var mouseXPosition = getMouseXPositionFromEvent(e);
                if (!mouseXPosition) return;

                sliderData.swipeStartTime = new Date().getTime();
                sliderData.swipeStartXPosition = mouseXPosition;
            };

            sliderData.onRelease = function (e) {
                if (!sliderData.swipeStartTime || !sliderData.swipeStartXPosition) return;

                var mouseXPosition = getMouseXPositionFromEvent(e);
                if (!mouseXPosition) return;

                var currentTime = new Date().getTime();
                if (currentTime - sliderData.swipeStartTime <= 10000 && Math.abs(mouseXPosition - sliderData.swipeStartXPosition) >= 50) {
                    if (mouseXPosition < sliderData.swipeStartXPosition) scrollSliderRight(sliderData);
                    else scrollSliderLeft(sliderData);
                }
            };

            slider.find('.row-slider-arrow-left').on('click', sliderData.scrollLeft);
            slider.find('.row-slider-arrow-right').on('click', sliderData.scrollRight);
            slider.on('touchstart mousedown', sliderData.onPress);
            slider.on('touchend mouseup touchcancel mouseleave', sliderData.onRelease);

            return sliderData;
        }

        function initializeSliders() {
            sliders.each(function () {
                var sliderData = initSlider(this);
                if (sliderData) slidersData.push(sliderData);
            });
        }

        initializeSliders();

        var resizeTimeoutId;
        $window.on('resize', function () {
            function resetResizeHandler() {
                if (resizeTimeoutId) {
                    clearTimeout(resizeTimeoutId);
                    resizeTimeoutId = undefined;
                }
            }

            function resizeHandler() {
                resetResizeHandler();
                destroySliders();
                initializeSliders();
            }

            resetResizeHandler();
            resizeTimeoutId = setTimeout(resizeHandler, 300);
        });
    })($('.row-slider'));
})(jQuery);
