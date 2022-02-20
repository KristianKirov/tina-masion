jQuery(function ($) {
    $(document.body).on( 'click', '.showcoupon', function () {
        $('html, body').animate({ scrollTop: 0 }, 300, 'swing');
    });

    $window = $(window);
    var prevScrollTop = 0;

    var $stickySidebar = $('.js-sticky-sidebar');
    var stickySidebarTranslateY = 0;

    var stickySidebarTopOffsetMargin = 94;
    var stickySidebarBottomOffsetMargin = 20;

    function updateStickySidebar() {
        var viewportWidth = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
        var viewportHeight = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
        var scrollTop = window.scrollY || window.pageYOffset;

        if (viewportWidth >= 768)
        {
            var stickySidebarOffset = $stickySidebar.offset();
            var scrollTopEnd = scrollTop + viewportHeight;

            var stickySidebarOffsetTop = stickySidebarOffset.top;
            var stickySidebarHeight = $stickySidebar.outerHeight();
            var stickySidebarOffsetTopEnd = stickySidebarOffsetTop + stickySidebarHeight;

            var topOffsetDiff = stickySidebarOffsetTop - scrollTop - stickySidebarTopOffsetMargin;
            var topOffsetEndDiff = stickySidebarOffsetTopEnd - scrollTopEnd + stickySidebarBottomOffsetMargin;
            var isSidebarHigherThanViewport = stickySidebarHeight > (viewportHeight - (stickySidebarTopOffsetMargin + stickySidebarBottomOffsetMargin));
            if (scrollTop > prevScrollTop) {
                if (isSidebarHigherThanViewport) {
                    if (topOffsetEndDiff < 0) {
                        stickySidebarTranslateY = stickySidebarTranslateY - topOffsetEndDiff;
                    }
                }
                else {
                    if (topOffsetDiff < 0) {
                        stickySidebarTranslateY = stickySidebarTranslateY - topOffsetDiff;
                    }
                }
            }
            else if (scrollTop < prevScrollTop) {
                if (isSidebarHigherThanViewport) {
                    if (topOffsetDiff > 0) {
                        stickySidebarTranslateY = stickySidebarTranslateY - topOffsetDiff;
                    }
                }
                else {
                    if (topOffsetEndDiff > 0) {
                        stickySidebarTranslateY = stickySidebarTranslateY - topOffsetEndDiff;
                    }
                }
            }
        }
        else {
            stickySidebarTranslateY = 0;
        }

        if (stickySidebarTranslateY < 0) stickySidebarTranslateY = 0;
        $stickySidebar.css('transform', 'translateY(' + stickySidebarTranslateY + 'px)')

        prevScrollTop = scrollTop;
    }

    if ($stickySidebar.length) {
        updateStickySidebar();
        $window.onScrolling(updateStickySidebar);
    }

    $(document.body).on('checkout_error', function () {
        var notices = $('.woocommerce-NoticeGroup-checkout');
        var errorNotices = notices.find('.woocommerce-error');

        errorNotices.remove();

        if (errorNotices.length) {
            var errorsToggle = $('<a href="#">' + maison_frontend_data.read_more + '</a>').on('click', function (e) {
                e.preventDefault();
                anchorToggle = $(this);
                anchorToggle
                    .text(anchorToggle.text() == maison_frontend_data.read_more ? maison_frontend_data.read_less : maison_frontend_data.read_more)
                    .parents('.errors-toggle').find('.errors-toggle-wrapper').toggle();
            });
            $('<div class="errors-toggle"></div>')
                .append(
                    $('<div class="errors-toggle-message">'+ maison_frontend_data.checkout_error + '</div>')
                        .append(' ')
                        .append(errorsToggle)
                )
                .append($('<div class="errors-toggle-wrapper"></div>').hide().append(errorNotices))
                .appendTo(notices);
        }
    });
});