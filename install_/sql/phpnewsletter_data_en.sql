INSERT INTO `%prefix%category` (`id_cat`, `name`) VALUES (1, 'Category 1');
INSERT INTO `%prefix%category` (`id_cat`, `name`) VALUES (2, 'Category 2');
INSERT INTO `%prefix%category` (`id_cat`, `name`) VALUES (5, 'Category 3');
INSERT INTO `%prefix%charset` (`id_charset`, `charset`) VALUES (1, 'utf-8');
INSERT INTO `%prefix%charset` (`id_charset`, `charset`) VALUES (2, 'iso-8859-1');
INSERT INTO `%prefix%charset` (`id_charset`, `charset`) VALUES (3, 'iso-8859-2');
INSERT INTO `%prefix%charset` (`id_charset`, `charset`) VALUES (4, 'iso-8859-3');
INSERT INTO `%prefix%charset` (`id_charset`, `charset`) VALUES (5, 'iso-8859-4');
INSERT INTO `%prefix%charset` (`id_charset`, `charset`) VALUES (6, 'iso-8859-5');
INSERT INTO `%prefix%charset` (`id_charset`, `charset`) VALUES (7, 'iso-8859-6');
INSERT INTO `%prefix%charset` (`id_charset`, `charset`) VALUES (8, 'iso-8859-8');
INSERT INTO `%prefix%charset` (`id_charset`, `charset`) VALUES (9, 'iso-8859-7');
INSERT INTO `%prefix%charset` (`id_charset`, `charset`) VALUES (10, 'iso-8859-9');
INSERT INTO `%prefix%charset` (`id_charset`, `charset`) VALUES (11, 'iso-8859-10');
INSERT INTO `%prefix%charset` (`id_charset`, `charset`) VALUES (12, 'iso-8859-13');
INSERT INTO `%prefix%charset` (`id_charset`, `charset`) VALUES (13, 'iso-8859-14');
INSERT INTO `%prefix%charset` (`id_charset`, `charset`) VALUES (14, 'iso-8859-15');
INSERT INTO `%prefix%charset` (`id_charset`, `charset`) VALUES (15, 'iso-8859-16');
INSERT INTO `%prefix%charset` (`id_charset`, `charset`) VALUES (16, 'windows-1250');
INSERT INTO `%prefix%charset` (`id_charset`, `charset`) VALUES (17, 'windows-1251');
INSERT INTO `%prefix%charset` (`id_charset`, `charset`) VALUES (18, 'windows-1252');
INSERT INTO `%prefix%charset` (`id_charset`, `charset`) VALUES (19, 'windows-1253');
INSERT INTO `%prefix%charset` (`id_charset`, `charset`) VALUES (20, 'windows-1254');
INSERT INTO `%prefix%charset` (`id_charset`, `charset`) VALUES (21, 'windows-1255');
INSERT INTO `%prefix%charset` (`id_charset`, `charset`) VALUES (22, 'windows-1256');
INSERT INTO `%prefix%charset` (`id_charset`, `charset`) VALUES (23, 'windows-1257');
INSERT INTO `%prefix%charset` (`id_charset`, `charset`) VALUES (24, 'windows-1258');
INSERT INTO `%prefix%charset` (`id_charset`, `charset`) VALUES (25, 'gb2312');
INSERT INTO `%prefix%charset` (`id_charset`, `charset`) VALUES (26, 'big5');
INSERT INTO `%prefix%charset` (`id_charset`, `charset`) VALUES (27, 'iso-2022-jp');
INSERT INTO `%prefix%charset` (`id_charset`, `charset`) VALUES (28, 'ks_c_5601-1987');
INSERT INTO `%prefix%charset` (`id_charset`, `charset`) VALUES (29, 'euc-kr');
INSERT INTO `%prefix%charset` (`id_charset`, `charset`) VALUES (30, 'windows-874');
INSERT INTO `%prefix%charset` (`id_charset`, `charset`) VALUES (31, 'koi8-r');
INSERT INTO `%prefix%charset` (`id_charset`, `charset`) VALUES (32, 'koi8-u');
INSERT INTO `%prefix%process` (`process`) VALUES ('stop');
INSERT INTO `%prefix%settings` (`language`, `email`, `list_owner`, `email_name`, `show_email`, `organization`, `smtp_host`, `smtp_username`, `smtp_password`, `smtp_port`, `smtp_aut`, `smtp_secure`, `smtp_timeout`, `how_to_send`, `sendmail`, `id_charset`, `content_type`, `number_days`, `make_limit_send`, `re_send`, `delete_subs`, `newsubscribernotify`, `request_reply`, `email_reply`, `show_unsubscribe_link`, `subjecttextconfirm`, `textconfirmation`, `require_confirmation`, `unsublink`, `interval_type`, `interval_number`, `limit_number`, `precedence`, `return_path`, `sleep`, `random`, `add_dkim`, `dkim_domain`, `dkim_private`, `dkim_selector`, `dkim_passphrase`, `dkim_identity`) VALUES ('en', 'vasya-pupkin@my-domain.com', NULL, 'my-domain.com', 'yes', '', 'smtp.gmail.com', '', '', 25, 'no', 'no', 5, 1, '/usr/sbin/sendmail', 1, 2, 0, 'no', 'no', 'no', 'no', 'no', 'no', 'yes', 'Subscription to mailing', 'Hello, %NAME%\r\n\r\nGetting mail is possible after the completion of activation. To activate your subscription, click on the link below: %CONFIRM%\r\n\r\nIf you are not subscribing to this address, simply ignore the letter or go to: %UNSUB%\r\n\r\nSincerely, \r\nthe site administrator %SERVER_NAME%', 'yes', 'Unsubscribe from mailing: <a href=%UNSUB%>%UNSUB%</a>', 'no', 1, 100, 'bulk', NULL, 0, 'no', 'no', 'my-domain.com', 'keyprivate/.htkeyprivate', 'phpnewsletter', 'password', '');