/**
 * Клиент сервера отправки нотификационных сообщений
 */
class RackNotifyClient {

    /**
     * Имя Cookie с URL сервера XNS
     * @type {string}
     */
    readonly URL = 'xns-url';

    /**
     * Имя Cookie с идентификатором сессии
     * @type {string}
     */
    readonly SESSION_ID = 'xns-session-id';

    /**
     * Текст подтверждения получения сообщения
     * @type {string}
     */
    readonly ACKNOWLEDGE_EVENT = 'OK';

    /**
     * Идентификатор нотификационного сообщения
     * @type {string}
     */
    readonly NOTIFICATION_EVENT = '_NOTIFICATION';

    /**
     * Признак режима ошибки
     * @type {boolean}
     */
    private _errorState: boolean = false;

    /**
     * Конструктор
     */
    constructor() {

        const serviceURL = this.getCookie(this.URL);
        const sessionId  = this.getCookie(this.SESSION_ID);

        let self = this;
        if (serviceURL !== null && sessionId !== null) {
            const socketURL = serviceURL + '?session=' + sessionId;
            const socketIO  = io(socketURL, {
                autoConnect: false
            });
            socketIO.connect();
            socketIO.on('connect', function () {
                self.processConnect();
            });
            socketIO.on('error', function (event: any) {
                self.processError(event);
            });
            socketIO.on(this.NOTIFICATION_EVENT, function (event: any, callback: any) {
                if (!self._errorState) {
                    callback(self.ACKNOWLEDGE_EVENT);
                    self.processNotification(event);
                }
            });
        } else {
            console.log('You need a session to use notification service.');
        }
    }

    /**
     * Обработка успешного подключения
     */
    public processConnect() {
        console.log('Notification service connected!');
    }

    /**
     * Обработка ошибки подключения к серверу
     *
     * @param event Информация о событии
     */
    public processError(event: any) {
        this._errorState = true;
        console.error('Error while connecting notification service.', event);
    }

    /**
     * Обработка входящего нотификационного сообщения
     *
     * @param event Информация о событии
     */
    public processNotification(event: any) {
        console.log('notification: ', event);
    }

    /**
     * Возвращает значение cookie
     *
     * @param name Имя cookie
     */
    public getCookie(name: string) {
        let match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
        return (match) ? unescape(match[2]) : null;
    }
}

(new RackNotifyClient());
