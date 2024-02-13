/** Clock script **/
var s, t; s = document.createElement("script"); s.type = "text/javascript";
s.src = "//cdn.dayspedia.com/js/dwidget.min.vc01b6c64.js";
t = document.getElementsByTagName('script')[ 0 ]; t.parentNode.insertBefore(s, t);
s.onload = function () {
    window.dwidget = new window.DigitClock();
    window.dwidget.init("dayspedia_widget_107595b471ab8913");
};
