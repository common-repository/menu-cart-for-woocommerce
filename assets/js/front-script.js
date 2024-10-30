jQuery(document).ready(function ($) {
    if(typeof wc_add_to_cart_params !== "undefined") {
        setTimeout(refresh_cart_fragment, 1000);

        var $supports_html5_storage = true;

        var $fragment_refresh = {
            url: wc_add_to_cart_params.wc_ajax_url.toString().replace("%%endpoint%%", "get_refreshed_fragments"),
            type: "POST",
            data: {
                time: new Date().getTime(),
            },
            timeout: wc_add_to_cart_params.request_timeout,
            success: function (data) {
                if (data && data.fragments) {
                    $.each(data.fragments, function (key, value) {
                        $(key).replaceWith(value);
                    });

                    if ($supports_html5_storage) {
                        sessionStorage.setItem(wc_add_to_cart_params.fragment_name, JSON.stringify(data.fragments));
                    }

                    $(document.body).trigger("wc_fragments_refreshed");
                }
            },
        };

        /* Named callback for refreshing cart fragment */
        function refresh_cart_fragment() {
            $.ajax($fragment_refresh);
        }
    }

    if(typeof mcfwObj !== "undefined") {
        jQuery(document.body).on("wc_cart_emptied", empty_cart);
        function empty_cart(params) {
            if (mcfwObj.general_data["always_display"] != "on") {
                jQuery(".mcfw-menu:visible").hide();
            }
        }
    }
    
    jQuery("body").on("added_to_cart", function () {
        jQuery(".mcfw-menu").show();
    });

    /** Main Menu click to show falyout  */
    jQuery("body").on("click", ".mcfw-menu .mcfw-menu-list", function () {
        jQuery(this).closest(".mcfw-menu").toggleClass("mcfw-menu-show");
        const fullHeight = jQuery(document).scrollTop() + window.innerHeight - jQuery(".mcfw-mini-cart-main").height();
        const clickTop = jQuery(this).offset().top;
        const downUp = fullHeight - clickTop;

        if (jQuery(this).hasClass("mcfw-menu-show")) {
            if (downUp < 0) {
                jQuery(this).closest(".mcfw-menu").addClass("mcfw-falyout-open-top");
            } else {
                if (jQuery(".mcfw-menu").hasClass("mcfw-falyout-open-top")) {
                    jQuery(".mcfw-menu").removeClass("mcfw-falyout-open-top");
                }
            }
        }
    });

    jQuery("body").on("mouseover", ".mcfw-menu", function () {
        const fullHeight = jQuery(document).scrollTop() + window.innerHeight - jQuery(".mcfw-mini-cart-main").height();
        const clickTop = jQuery(this).offset().top;
        const downUp = fullHeight - clickTop;

        if (downUp < 0) {
            jQuery(this).closest(".mcfw-menu").addClass("mcfw-falyout-open-top");
        } else {
            if (jQuery(".mcfw-menu").hasClass("mcfw-falyout-open-top")) {
                jQuery(".mcfw-menu").removeClass("mcfw-falyout-open-top");
            }
        }
    });

    setTimeout(function () {
        var i = 1;
        jQuery(".mcfw-mini-cart-main").each(function () {
            const fullHeight = jQuery(document).scrollTop() + window.innerHeight - jQuery(".mcfw-mini-cart-main").height();
            const clickTop = jQuery(this).offset().top;
            const downUp = fullHeight - clickTop;

            if (downUp < 0) {
                jQuery(this).closest(".mcfw-menu").addClass("mcfw-falyout-open-top");
            }
        });
    }, 1000);
});

jQuery(document).click(function (event) {
    var container = jQuery(".mcfw-mini-cart-main");
    var container_menu = jQuery(".mcfw-menu");
    if (!container.is(event.target) && !container.has(event.target).length) {
        if (!container_menu.is(event.target) && !container_menu.has(event.target).length) {
            jQuery("body .mcfw-menu").removeClass("mcfw-menu-show");
        }
    }
});
