<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0"
                   xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                   xmlns:umi="http://www.umi-cms.ru/TR/umi"
                   xmlns:xlink="http://www.w3.org/TR/xlink">

<xsl:template match="udata" mode="right-column-counter">

    <div class="infoblock counterBanner">
        <div class="title">
            <h2 umi:element-id="{category_id}" umi:field-name="name">
                <xsl:value-of select="//property[@name='h1']/value" />
            </h2>
        </div>
        <div class="body">
            <div class="in">
                <div><xsl:value-of select="//property[@name='anons']/value" disable-output-escaping="yes"/></div>
                <div class="eventLabel">Осталось <span id="timetoevent">00 дней 00:00:00</span></div>
                <xsl:variable name="fakeDate" select="'/(Y,m-1,d)/last%20day%20of%20next%20month'" />
                <xsl:variable name="trueDate" select="'/(Y,m-1,d)'"/>
                <script>
                    function timeToEvent(eventDate) {
                        var now = new Date(),
                            daystoED = Math.floor(Math.round(eventDate-now)/86400000),
                            hourstoED = 24 - now.getHours() - 1,
                            minutestoED = 60 - now.getMinutes() - 1,
                            secondstoED = 60 - now.getSeconds() - 1,
                            days = '';
                        daystoED = (daystoED &lt; 1) ? "0"+daystoED : daystoED;
                        hourstoED = (hourstoED &lt; 10) ? "0"+hourstoED : hourstoED;
                        minutestoED = (minutestoED &lt; 10) ? "0"+minutestoED : minutestoED;
                        secondstoED = (secondstoED &lt; 10) ? "0"+secondstoED : secondstoED;

                        if(daystoED % 10 == 1) {
                            days = ' день ';
                        } else if (daystoED % 10 > 1 &amp;&amp; daystoED % 10 &lt; 5) {
                            days = ' дня ';
                        } else {
                            days = ' дней ';
                        }

                        return daystoED + days + hourstoED+":"+minutestoED+":"+secondstoED+"";
                    }
<!--
    Обратите внимание! В шаблоне использовано демострационное значение даты окончания счетчика.
    Для использования даты из новости вам следует заменить переменную fakeDate на trueDate в вызове метода convertDate().
-->
                    var now = new Date(),
                        eventDate = new Date(<xsl:apply-templates select="document(concat('udata://system/convertDate/', //property[@name='end_time']/value/@unix-timestamp, $fakeDate))" />),
                        span = document.getElementById('timetoevent');
                    if(now &lt; eventDate) {
                        window.setInterval(function(){
                            span.innerHTML = timeToEvent(eventDate);
                        }, 1000);
                    }
                </script>
            </div>
        </div>
    </div>

</xsl:template>

</xsl:stylesheet>