(function(){function r(e,n,t){function o(i,f){if(!n[i]){if(!e[i]){var c="function"==typeof require&&require;if(!f&&c)return c(i,!0);if(u)return u(i,!0);var a=new Error("Cannot find module '"+i+"'");throw a.code="MODULE_NOT_FOUND",a}var p=n[i]={exports:{}};e[i][0].call(p.exports,function(r){var n=e[i][1][r];return o(n||r)},p,p.exports,r,e,n,t)}return n[i].exports}for(var u="function"==typeof require&&require,i=0;i<t.length;i++)o(t[i]);return o}return r})()({1:[function(require,module,exports){
/**
 * Клиент сервера отправки нотификационных сообщений
 */
var RackNotifyClient = /** @class */ (function () {
    /**
     * Конструктор
     */
    function RackNotifyClient() {
        /**
         * Имя Cookie с URL сервера XNS
         * @type {string}
         */
        this.URL = 'xns-url';
        /**
         * Имя Cookie с идентификатором сессии
         * @type {string}
         */
        this.SESSION_ID = 'xns-session-id';
        /**
         * Текст подтверждения получения сообщения
         * @type {string}
         */
        this.ACKNOWLEDGE_EVENT = 'OK';
        /**
         * Идентификатор нотификационного сообщения
         * @type {string}
         */
        this.NOTIFICATION_EVENT = '_NOTIFICATION';
        /**
         * Признак режима ошибки
         * @type {boolean}
         */
        this._errorState = false;
        var serviceURL = this.getCookie(this.URL);
        var sessionId = this.getCookie(this.SESSION_ID);
        var self = this;
        if (serviceURL !== null && sessionId !== null) {
            var socketURL = serviceURL + '?session=' + sessionId;
            var socketIO = io(socketURL, {
                autoConnect: false
            });
            socketIO.connect();
            socketIO.on('connect', function () {
                self.processConnect();
            });
            socketIO.on('error', function (event) {
                self.processError(event);
            });
            socketIO.on(this.NOTIFICATION_EVENT, function (event, callback) {
                if (!self._errorState) {
                    callback(self.ACKNOWLEDGE_EVENT);
                    self.processNotification(event);
                }
            });
        }
        else {
            console.log('You need a session to use notification service.');
        }
    }
    /**
     * Обработка успешного подключения
     */
    RackNotifyClient.prototype.processConnect = function () {
        console.log('Notification service connected!');
    };
    /**
     * Обработка ошибки подключения к серверу
     *
     * @param event Информация о событии
     */
    RackNotifyClient.prototype.processError = function (event) {
        this._errorState = true;
        console.error('Error while connecting notification service.', event);
    };
    /**
     * Обработка входящего нотификационного сообщения
     *
     * @param event Информация о событии
     */
    RackNotifyClient.prototype.processNotification = function (event) {
        console.log('notification: ', event);
    };
    /**
     * Возвращает значение cookie
     *
     * @param name Имя cookie
     */
    RackNotifyClient.prototype.getCookie = function (name) {
        var match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
        return (match) ? unescape(match[2]) : null;
    };
    /**
     * Статический метод инициализации
     */
    RackNotifyClient.init = function () {
        return new RackNotifyClient();
    };
    return RackNotifyClient;
}());
},{}]},{},[1])
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIm5vZGVfbW9kdWxlcy9icm93c2VyLXBhY2svX3ByZWx1ZGUuanMiLCJzcmMvdWkvcHVibGljL3JhY2stbm90aWZ5LWNsaWVudC50cyJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiQUFBQTtBQ0tBOztHQUVHO0FBQ0g7SUFnQ0k7O09BRUc7SUFDSDtRQWpDQTs7O1dBR0c7UUFDTSxRQUFHLEdBQUcsU0FBUyxDQUFDO1FBRXpCOzs7V0FHRztRQUNNLGVBQVUsR0FBRyxnQkFBZ0IsQ0FBQztRQUV2Qzs7O1dBR0c7UUFDTSxzQkFBaUIsR0FBRyxJQUFJLENBQUM7UUFFbEM7OztXQUdHO1FBQ00sdUJBQWtCLEdBQUcsZUFBZSxDQUFDO1FBRTlDOzs7V0FHRztRQUNLLGdCQUFXLEdBQVksS0FBSyxDQUFDO1FBT2pDLElBQU0sVUFBVSxHQUFHLElBQUksQ0FBQyxTQUFTLENBQUMsSUFBSSxDQUFDLEdBQUcsQ0FBQyxDQUFDO1FBQzVDLElBQU0sU0FBUyxHQUFJLElBQUksQ0FBQyxTQUFTLENBQUMsSUFBSSxDQUFDLFVBQVUsQ0FBQyxDQUFDO1FBRW5ELElBQUksSUFBSSxHQUFHLElBQUksQ0FBQztRQUNoQixJQUFJLFVBQVUsS0FBSyxJQUFJLElBQUksU0FBUyxLQUFLLElBQUksRUFBRTtZQUMzQyxJQUFNLFNBQVMsR0FBRyxVQUFVLEdBQUcsV0FBVyxHQUFHLFNBQVMsQ0FBQztZQUN2RCxJQUFNLFFBQVEsR0FBSSxFQUFFLENBQUMsU0FBUyxFQUFFO2dCQUM1QixXQUFXLEVBQUUsS0FBSzthQUNyQixDQUFDLENBQUM7WUFDSCxRQUFRLENBQUMsT0FBTyxFQUFFLENBQUM7WUFDbkIsUUFBUSxDQUFDLEVBQUUsQ0FBQyxTQUFTLEVBQUU7Z0JBQ25CLElBQUksQ0FBQyxjQUFjLEVBQUUsQ0FBQztZQUMxQixDQUFDLENBQUMsQ0FBQztZQUNILFFBQVEsQ0FBQyxFQUFFLENBQUMsT0FBTyxFQUFFLFVBQVUsS0FBVTtnQkFDckMsSUFBSSxDQUFDLFlBQVksQ0FBQyxLQUFLLENBQUMsQ0FBQztZQUM3QixDQUFDLENBQUMsQ0FBQztZQUNILFFBQVEsQ0FBQyxFQUFFLENBQUMsSUFBSSxDQUFDLGtCQUFrQixFQUFFLFVBQVUsS0FBVSxFQUFFLFFBQWE7Z0JBQ3BFLElBQUksQ0FBQyxJQUFJLENBQUMsV0FBVyxFQUFFO29CQUNuQixRQUFRLENBQUMsSUFBSSxDQUFDLGlCQUFpQixDQUFDLENBQUM7b0JBQ2pDLElBQUksQ0FBQyxtQkFBbUIsQ0FBQyxLQUFLLENBQUMsQ0FBQztpQkFDbkM7WUFDTCxDQUFDLENBQUMsQ0FBQztTQUNOO2FBQU07WUFDSCxPQUFPLENBQUMsR0FBRyxDQUFDLGlEQUFpRCxDQUFDLENBQUM7U0FDbEU7SUFDTCxDQUFDO0lBRUQ7O09BRUc7SUFDSSx5Q0FBYyxHQUFyQjtRQUNJLE9BQU8sQ0FBQyxHQUFHLENBQUMsaUNBQWlDLENBQUMsQ0FBQztJQUNuRCxDQUFDO0lBRUQ7Ozs7T0FJRztJQUNJLHVDQUFZLEdBQW5CLFVBQW9CLEtBQVU7UUFDMUIsSUFBSSxDQUFDLFdBQVcsR0FBRyxJQUFJLENBQUM7UUFDeEIsT0FBTyxDQUFDLEtBQUssQ0FBQyw4Q0FBOEMsRUFBRSxLQUFLLENBQUMsQ0FBQztJQUN6RSxDQUFDO0lBRUQ7Ozs7T0FJRztJQUNJLDhDQUFtQixHQUExQixVQUEyQixLQUFVO1FBQ2pDLE9BQU8sQ0FBQyxHQUFHLENBQUMsZ0JBQWdCLEVBQUUsS0FBSyxDQUFDLENBQUM7SUFDekMsQ0FBQztJQUVEOzs7O09BSUc7SUFDSSxvQ0FBUyxHQUFoQixVQUFpQixJQUFZO1FBQ3pCLElBQUksS0FBSyxHQUFHLFFBQVEsQ0FBQyxNQUFNLENBQUMsS0FBSyxDQUFDLElBQUksTUFBTSxDQUFDLE9BQU8sR0FBRyxJQUFJLEdBQUcsVUFBVSxDQUFDLENBQUMsQ0FBQztRQUMzRSxPQUFPLENBQUMsS0FBSyxDQUFDLENBQUMsQ0FBQyxDQUFDLFFBQVEsQ0FBQyxLQUFLLENBQUMsQ0FBQyxDQUFDLENBQUMsQ0FBQyxDQUFDLENBQUMsSUFBSSxDQUFDO0lBQy9DLENBQUM7SUFFRDs7T0FFRztJQUNXLHFCQUFJLEdBQWxCO1FBQ0ksT0FBTyxJQUFJLGdCQUFnQixFQUFFLENBQUM7SUFDbEMsQ0FBQztJQUNMLHVCQUFDO0FBQUQsQ0ExR0EsQUEwR0MsSUFBQSIsImZpbGUiOiJnZW5lcmF0ZWQuanMiLCJzb3VyY2VSb290IjoiIiwic291cmNlc0NvbnRlbnQiOlsiKGZ1bmN0aW9uKCl7ZnVuY3Rpb24gcihlLG4sdCl7ZnVuY3Rpb24gbyhpLGYpe2lmKCFuW2ldKXtpZighZVtpXSl7dmFyIGM9XCJmdW5jdGlvblwiPT10eXBlb2YgcmVxdWlyZSYmcmVxdWlyZTtpZighZiYmYylyZXR1cm4gYyhpLCEwKTtpZih1KXJldHVybiB1KGksITApO3ZhciBhPW5ldyBFcnJvcihcIkNhbm5vdCBmaW5kIG1vZHVsZSAnXCIraStcIidcIik7dGhyb3cgYS5jb2RlPVwiTU9EVUxFX05PVF9GT1VORFwiLGF9dmFyIHA9bltpXT17ZXhwb3J0czp7fX07ZVtpXVswXS5jYWxsKHAuZXhwb3J0cyxmdW5jdGlvbihyKXt2YXIgbj1lW2ldWzFdW3JdO3JldHVybiBvKG58fHIpfSxwLHAuZXhwb3J0cyxyLGUsbix0KX1yZXR1cm4gbltpXS5leHBvcnRzfWZvcih2YXIgdT1cImZ1bmN0aW9uXCI9PXR5cGVvZiByZXF1aXJlJiZyZXF1aXJlLGk9MDtpPHQubGVuZ3RoO2krKylvKHRbaV0pO3JldHVybiBvfXJldHVybiByfSkoKSIsIi8qKlxuICog0JLQvdC10YjQvdGP0Y8g0YHRgdGL0LvQutCwINC90LAgc29ja2V0LmlvLWNsaWVudFxuICovXG5kZWNsYXJlIHZhciBpbzogYW55O1xuXG4vKipcbiAqINCa0LvQuNC10L3RgiDRgdC10YDQstC10YDQsCDQvtGC0L/RgNCw0LLQutC4INC90L7RgtC40YTQuNC60LDRhtC40L7QvdC90YvRhSDRgdC+0L7QsdGJ0LXQvdC40LlcbiAqL1xuY2xhc3MgUmFja05vdGlmeUNsaWVudCB7XG5cbiAgICAvKipcbiAgICAgKiDQmNC80Y8gQ29va2llINGBIFVSTCDRgdC10YDQstC10YDQsCBYTlNcbiAgICAgKiBAdHlwZSB7c3RyaW5nfVxuICAgICAqL1xuICAgIHJlYWRvbmx5IFVSTCA9ICd4bnMtdXJsJztcblxuICAgIC8qKlxuICAgICAqINCY0LzRjyBDb29raWUg0YEg0LjQtNC10L3RgtC40YTQuNC60LDRgtC+0YDQvtC8INGB0LXRgdGB0LjQuFxuICAgICAqIEB0eXBlIHtzdHJpbmd9XG4gICAgICovXG4gICAgcmVhZG9ubHkgU0VTU0lPTl9JRCA9ICd4bnMtc2Vzc2lvbi1pZCc7XG5cbiAgICAvKipcbiAgICAgKiDQotC10LrRgdGCINC/0L7QtNGC0LLQtdGA0LbQtNC10L3QuNGPINC/0L7Qu9GD0YfQtdC90LjRjyDRgdC+0L7QsdGJ0LXQvdC40Y9cbiAgICAgKiBAdHlwZSB7c3RyaW5nfVxuICAgICAqL1xuICAgIHJlYWRvbmx5IEFDS05PV0xFREdFX0VWRU5UID0gJ09LJztcblxuICAgIC8qKlxuICAgICAqINCY0LTQtdC90YLQuNGE0LjQutCw0YLQvtGAINC90L7RgtC40YTQuNC60LDRhtC40L7QvdC90L7Qs9C+INGB0L7QvtCx0YnQtdC90LjRj1xuICAgICAqIEB0eXBlIHtzdHJpbmd9XG4gICAgICovXG4gICAgcmVhZG9ubHkgTk9USUZJQ0FUSU9OX0VWRU5UID0gJ19OT1RJRklDQVRJT04nO1xuXG4gICAgLyoqXG4gICAgICog0J/RgNC40LfQvdCw0Log0YDQtdC20LjQvNCwINC+0YjQuNCx0LrQuFxuICAgICAqIEB0eXBlIHtib29sZWFufVxuICAgICAqL1xuICAgIHByaXZhdGUgX2Vycm9yU3RhdGU6IGJvb2xlYW4gPSBmYWxzZTtcblxuICAgIC8qKlxuICAgICAqINCa0L7QvdGB0YLRgNGD0LrRgtC+0YBcbiAgICAgKi9cbiAgICBjb25zdHJ1Y3RvcigpIHtcblxuICAgICAgICBjb25zdCBzZXJ2aWNlVVJMID0gdGhpcy5nZXRDb29raWUodGhpcy5VUkwpO1xuICAgICAgICBjb25zdCBzZXNzaW9uSWQgID0gdGhpcy5nZXRDb29raWUodGhpcy5TRVNTSU9OX0lEKTtcblxuICAgICAgICBsZXQgc2VsZiA9IHRoaXM7XG4gICAgICAgIGlmIChzZXJ2aWNlVVJMICE9PSBudWxsICYmIHNlc3Npb25JZCAhPT0gbnVsbCkge1xuICAgICAgICAgICAgY29uc3Qgc29ja2V0VVJMID0gc2VydmljZVVSTCArICc/c2Vzc2lvbj0nICsgc2Vzc2lvbklkO1xuICAgICAgICAgICAgY29uc3Qgc29ja2V0SU8gID0gaW8oc29ja2V0VVJMLCB7XG4gICAgICAgICAgICAgICAgYXV0b0Nvbm5lY3Q6IGZhbHNlXG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIHNvY2tldElPLmNvbm5lY3QoKTtcbiAgICAgICAgICAgIHNvY2tldElPLm9uKCdjb25uZWN0JywgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIHNlbGYucHJvY2Vzc0Nvbm5lY3QoKTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgc29ja2V0SU8ub24oJ2Vycm9yJywgZnVuY3Rpb24gKGV2ZW50OiBhbnkpIHtcbiAgICAgICAgICAgICAgICBzZWxmLnByb2Nlc3NFcnJvcihldmVudCk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIHNvY2tldElPLm9uKHRoaXMuTk9USUZJQ0FUSU9OX0VWRU5ULCBmdW5jdGlvbiAoZXZlbnQ6IGFueSwgY2FsbGJhY2s6IGFueSkge1xuICAgICAgICAgICAgICAgIGlmICghc2VsZi5fZXJyb3JTdGF0ZSkge1xuICAgICAgICAgICAgICAgICAgICBjYWxsYmFjayhzZWxmLkFDS05PV0xFREdFX0VWRU5UKTtcbiAgICAgICAgICAgICAgICAgICAgc2VsZi5wcm9jZXNzTm90aWZpY2F0aW9uKGV2ZW50KTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgIGNvbnNvbGUubG9nKCdZb3UgbmVlZCBhIHNlc3Npb24gdG8gdXNlIG5vdGlmaWNhdGlvbiBzZXJ2aWNlLicpO1xuICAgICAgICB9XG4gICAgfVxuXG4gICAgLyoqXG4gICAgICog0J7QsdGA0LDQsdC+0YLQutCwINGD0YHQv9C10YjQvdC+0LPQviDQv9C+0LTQutC70Y7Rh9C10L3QuNGPXG4gICAgICovXG4gICAgcHVibGljIHByb2Nlc3NDb25uZWN0KCkge1xuICAgICAgICBjb25zb2xlLmxvZygnTm90aWZpY2F0aW9uIHNlcnZpY2UgY29ubmVjdGVkIScpO1xuICAgIH1cblxuICAgIC8qKlxuICAgICAqINCe0LHRgNCw0LHQvtGC0LrQsCDQvtGI0LjQsdC60Lgg0L/QvtC00LrQu9GO0YfQtdC90LjRjyDQuiDRgdC10YDQstC10YDRg1xuICAgICAqXG4gICAgICogQHBhcmFtIGV2ZW50INCY0L3RhNC+0YDQvNCw0YbQuNGPINC+INGB0L7QsdGL0YLQuNC4XG4gICAgICovXG4gICAgcHVibGljIHByb2Nlc3NFcnJvcihldmVudDogYW55KSB7XG4gICAgICAgIHRoaXMuX2Vycm9yU3RhdGUgPSB0cnVlO1xuICAgICAgICBjb25zb2xlLmVycm9yKCdFcnJvciB3aGlsZSBjb25uZWN0aW5nIG5vdGlmaWNhdGlvbiBzZXJ2aWNlLicsIGV2ZW50KTtcbiAgICB9XG5cbiAgICAvKipcbiAgICAgKiDQntCx0YDQsNCx0L7RgtC60LAg0LLRhdC+0LTRj9GJ0LXQs9C+INC90L7RgtC40YTQuNC60LDRhtC40L7QvdC90L7Qs9C+INGB0L7QvtCx0YnQtdC90LjRj1xuICAgICAqXG4gICAgICogQHBhcmFtIGV2ZW50INCY0L3RhNC+0YDQvNCw0YbQuNGPINC+INGB0L7QsdGL0YLQuNC4XG4gICAgICovXG4gICAgcHVibGljIHByb2Nlc3NOb3RpZmljYXRpb24oZXZlbnQ6IGFueSkge1xuICAgICAgICBjb25zb2xlLmxvZygnbm90aWZpY2F0aW9uOiAnLCBldmVudCk7XG4gICAgfVxuXG4gICAgLyoqXG4gICAgICog0JLQvtC30LLRgNCw0YnQsNC10YIg0LfQvdCw0YfQtdC90LjQtSBjb29raWVcbiAgICAgKlxuICAgICAqIEBwYXJhbSBuYW1lINCY0LzRjyBjb29raWVcbiAgICAgKi9cbiAgICBwdWJsaWMgZ2V0Q29va2llKG5hbWU6IHN0cmluZykge1xuICAgICAgICBsZXQgbWF0Y2ggPSBkb2N1bWVudC5jb29raWUubWF0Y2gobmV3IFJlZ0V4cCgnKF58ICknICsgbmFtZSArICc9KFteO10rKScpKTtcbiAgICAgICAgcmV0dXJuIChtYXRjaCkgPyB1bmVzY2FwZShtYXRjaFsyXSkgOiBudWxsO1xuICAgIH1cblxuICAgIC8qKlxuICAgICAqINCh0YLQsNGC0LjRh9C10YHQutC40Lkg0LzQtdGC0L7QtCDQuNC90LjRhtC40LDQu9C40LfQsNGG0LjQuFxuICAgICAqL1xuICAgIHB1YmxpYyBzdGF0aWMgaW5pdCgpOiBSYWNrTm90aWZ5Q2xpZW50IHtcbiAgICAgICAgcmV0dXJuIG5ldyBSYWNrTm90aWZ5Q2xpZW50KCk7XG4gICAgfVxufVxuIl19
