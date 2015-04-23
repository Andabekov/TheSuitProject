CREATE TABLE smsmessages
(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    text VARCHAR(500),
    variables VARCHAR(100),
    credits VARCHAR(100),
    sentdate DATETIME, /*d.m.Y H:i*/
    donedate DATETIME, /*d.m.Y H:i*/
    first_status  VARCHAR(100),
    status  VARCHAR(100),
    send_sms_xml VARCHAR(500)
);
CREATE UNIQUE INDEX unique_smsmessages_id ON smsmessages (id);

INSERT INTO pidzhak.smsmessages (id, text, variables, credits, sentdate, donedate, first_status, status, send_sms_xml)
VALUES (1, '798789798   ', '2121', '12155', '2015-04-23 15:26:33', '2015-04-23 15:26:36', '1', '0', '654654654');

/*first_status ***************************
AUTH_FAILED -1 Неправильный логин и/или пароль либо аккаунт заблокирован
XML_ERROR -2 Неправильный формат XML
NOT_ENOUGH_CREDITS -3 Недостаточно кредитов на аккаунте пользователя
NO_ROUTES -4 Нет корректных номеров получателей либо отправка по указанным маршрутам запрещена для Вашего аккаунта
NO_SENDER -5 Используемое имя отправителя не разрешено для Вашего аккаунта
NO_TEXT -5 Текст сообщения не указан
SEND_OK >  0 Сообщение успешно отправлено
*********************************/


/*status ***************************
PENDING Сообщение ожидает отправки
SENT Отправлено
NOT_DELIVERED Не доставлено
DELIVERED Доставлено
*********************************/

