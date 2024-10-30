jQuery(document).ready(function ($) {
    $(".js-select2-multi").select2();
    $(".js-select2-multi").on("focus", () => {
        inputElement.parent().find(".select2-search__field").prop("disabled", true);
    });

    jQuery(function () {
        jQuery(".mcfw-color-field").wpColorPicker();
    });

    jQuery(".mcfw-select").val() == "" ? jQuery(".mcfw-select-err").fadeIn() : jQuery(".mcfw-select-err").fadeOut();

    jQuery(".mcfw-select").on("change", function () {
        var selectVal = jQuery(".mcfw-select").val();
        selectVal == "" ? jQuery(".mcfw-select-err").fadeIn() : jQuery(".mcfw-select-err").fadeOut();
    });

    jQuery(".mcfw-cart-options input[type=radio]").click(function () {
        jQuery(".mcfw-cart-options").removeClass("mcfw-current-cart-options");
        jQuery(this).parent().addClass("mcfw-current-cart-options");
    });

    jQuery(".mcfw-cart-btn-img input[type=radio]").click(function () {
        jQuery(".mcfw-cart-btn-img").removeClass("mcfw-current-cart-options");
        jQuery(this).parent().addClass("mcfw-current-cart-options");
    });

    jQuery("body").on("click", ".mcfw-menu-list", function () {
        jQuery(".mcfw-mini-cart-main").addClass("mcfw-menu-list-open");
    });

    Coloris({
        el: ".mcfw_coloris",
        themeMode: "auto",
        swatches: ["#264653", "#2a9d8f", "#e9c46a", "#f4a261"],
        clearButton: {
            show: true,
            label: "Clear",
        },
        formatToggle: true,
        selectInput: true,
    });

    jQuery("body").on("click", ".mcfw-design-setting input[type=reset]", function () {
        jQuery(".mcfw-design-setting").find("input[type=text]").val("");
        jQuery(".mcfw-design-setting").find(".clr-field").removeAttr("style");
        jQuery(".mcfw-design-setting .mcfw-sticky-cart-shape").val("mcfw_round_cart").change();
        jQuery(".mcfw-design-setting .mcfw-border-style-select").val("none").change();
        jQuery(".mcfw-design-setting .mcfw-currency-position-select").val("mcfw_currency_postion_left_withspace").change();
        jQuery(".mcfw-design-setting input[type=submit]").click();
    });

    jQuery("body").on("click", "#mcfw_copy_icon", function () {
        var copyText = document.getElementById("mcfw_shortcode_copy");

        var text = copyText.value;
        var sampleTextarea = document.createElement("textarea");
        document.body.appendChild(sampleTextarea);
        sampleTextarea.value = text; //save main text in it
        sampleTextarea.select(); //select textarea contenrs
        document.execCommand("copy");
        document.body.removeChild(sampleTextarea);

        var tooltip = document.getElementById("mcfw_shortcodeTooltip");
        tooltip.innerHTML = "Copied!";
    });

    jQuery("body #mcfw_shortcode_copy").mouseout(function () {
        var tooltip = document.getElementById("mcfw_shortcodeTooltip");
        tooltip.innerHTML = "Copy to clipboard";
    });

    jQuery("body").on("click", ".mcfw-product-option-ck", function () {
        if (jQuery(this).hasClass("mcfw-checkbox-disabled")) {
            jQuery(".mcfw-pro-product-option").css("display", "inline-block");
        } else {
            jQuery(".mcfw-pro-product-option").css("display", "none");
        }
    });
});
