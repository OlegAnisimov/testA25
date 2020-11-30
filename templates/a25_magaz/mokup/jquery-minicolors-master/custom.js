(function($,jQuery){
;(function($, window, undefined) {
    'use strict';
    window.ThemeSettings = {
        markup: "",
        defaults: {
            color: "red",
            background: "body-background2",
            textureColor: "#d1beab"
        },
        init: function () {
            $(".minicolors-append")
                    .append("<link  type='text/css' href='/templates/a25_magaz/mokup/jquery-minicolors-master/jquery.minicolors.css' rel='stylesheet'>")
                    .append("<script type='text/javascript' src='/templates/a25_magaz/mokup/jquery-minicolors-master/jquery.minicolors.min.js'></script>");

            this.buildMarkup();
            $("body").append(this.markup);

            $("#settings-texture-color").minicolors({
                defaultValue: this.defaults.textureColor,
                position: "bottom right",
                change: function (hex) {
                    ThemeSettings.setBodyColor(hex);
                }
            });

            this.setFromCookies();
        },
        buildMarkup: function () {
            this.markup = $(
                    '<div class="theme-settings light-gradient">' +
                        '<a id="settings-link" class="settings-link cream-gradient"></a>' +
                        '<div class="settings-colors">' +
                            '<div class="settings-title">Template color</div>' +
                            '<div class="settings-box" id="settings-colors"></div>' +
                        '</div>' +
                        '<div class="settings-backgrounds">' +
                            '<div class="settings-title">Background image</div>' +
                            '<div class="settings-box" id="settings-backgrounds"></div>' +
                        '</div>' +
                        '<div class="settings-textures">' +
                            '<div class="settings-title">Background texture</div>' +
                            '<div class="settings-box" id="settings-textures"></div>' +
                        '</div><br />' +
                        '<a id="settings-reset" class="button-small light-color middle-gradient dark-gradient-hover">Reset settings</a>' +
                    '</div>'
                    );

            var colors = {
                red: "#ee3124",
                yellow: "#fca400",
                blue: "#2985b0",
                green: "#719800",
                magenta: "#9400fc",
                pink: "#fc00ac"
            };

            $("#settings-link", this.markup).on("click", function () {
                $(this).parent().toggleClass("settings-active");
            });

            for (var i in colors) {
                if (colors.hasOwnProperty(i)) {
                    (function () {
                        var key = i;
                        var link = $("<a />").attr("rel", i)
                                .css("background-color", colors[i])
                                .on("click", function () {
                                    ThemeSettings.setColor(key);
                                });
                        $("#settings-colors", ThemeSettings.markup).append(link);
                    })();
                }
            }

            for (i = 0; i < 5; i++) {
                (function () {
                    var key = "body-background" + (i + 1);
                    var link = $("<a />").addClass(key).attr("rel", key)
                            .on("click", function () {
                                ThemeSettings.setBg(key);
                            });
                    $("#settings-backgrounds", ThemeSettings.markup).append(link);
                })();
            }

            $("#settings-textures", this.markup).append($("<span />").attr("id", "settings-texture-color"));
            for (i = 0; i < 4; i++) {
                (function () {
                    var key = "body-texture" + (i + 1);
                    var link = $("<a />").addClass(key).attr("rel", key)
                            .on("click", function () {
                                ThemeSettings.setTexture(key);
                            });
                    $("#settings-textures", ThemeSettings.markup).append(link);
                })();
            }

            $("#settings-reset", this.markup).on("click", this.resetSettings);
        },
        setColor: function (color) {
            $("link[href^='/templates/a25_magaz/mokup/css/colors']").attr("href", "/templates/a25_magaz/mokup/css/colors/" + color + ".css");

            var $colors = $("#settings-colors");
            $colors.find(".selected").removeClass("selected");
            $colors.find("a[rel=" + color + "]").addClass("selected");

            ThemeSettings.setCookie("settingsColor", color);
        },
        setBg: function (bg) {
            var $el = $("body");
            this.removePrefixClass($el, "body-texture");
            this.removePrefixClass($el, "body-background");
            $el.addClass(bg);

            $("#settings-textures").find(".selected").removeClass("selected");

            var $backgrounds = $("#settings-backgrounds");
            $backgrounds.find(".selected").removeClass("selected");
            $backgrounds.find("a[rel=" + bg + "]").addClass("selected");

            ThemeSettings.setCookie("settingsBackground", bg);
            ThemeSettings.deleteCookie("settingsTexture");
        },
        setTexture: function (texture) {
            var $el = $("body");
            this.removePrefixClass($el, "body-texture");
            this.removePrefixClass($el, "body-background");
            $el.addClass(texture);

            $("#settings-backgrounds").find(".selected").removeClass("selected");

            var $textures = $("#settings-textures");
            $textures.find(".selected").removeClass("selected");
            $textures.find("a[rel=" + texture + "]").addClass("selected");

            ThemeSettings.setCookie("settingsTexture", texture);
            ThemeSettings.deleteCookie("settingsBackground");
        },
        setBodyColor: function (color) {
            $("body").css("background-color", color);
            $("#settings-textures").find("a").css("background-color", color);

            ThemeSettings.setCookie("settingsBodyColor", color);
        },
        removePrefixClass: function (element, prefix) {
            var classes = element.attr("class").split(" ").filter(function (c) {
                return c.lastIndexOf(prefix, 0) !== 0;
            });
            element.attr("class", classes.join(" "));
        },
        resetSettings: function () {
            ThemeSettings.setBodyColor(ThemeSettings.defaults.textureColor);
            ThemeSettings.setBg(ThemeSettings.defaults.background);
            ThemeSettings.setColor(ThemeSettings.defaults.color);
            $("#settings-texture-color").minicolors("value", ThemeSettings.defaults.color);
        },
        setFromCookies: function () {
            var color = this.getCookie("settingsColor");
            var background = this.getCookie("settingsBackground");
            var texture = this.getCookie("settingsTexture");
            var bodyColor = this.getCookie("settingsBodyColor");

            if (color)
                this.setColor(color);
            if (background)
                this.setBg(background);
            if (texture)
                this.setTexture(texture);
            if (bodyColor) {
                $("#settings-texture-color").minicolors("value", bodyColor);
                this.setBodyColor(bodyColor);
            }
        },
        setCookie: function (cname, cvalue) {
            document.cookie = cname + "=" + cvalue + ";";
        },
        getCookie: function (cname) {
            var name = cname + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i].trim();
                if (c.indexOf(name) == 0)
                    return c.substring(name.length, c.length);
            }
            return "";
        },
        deleteCookie: function (cname) {
            document.cookie = cname + "=0; expires=Thu, 01 Jan 1970 00:00:01 GMT;";
        }

    };
})(jQuery, window);
})(lastJQ,lastJQ)
lastJQ(document).ready(function(){
    ThemeSettings.init();
});